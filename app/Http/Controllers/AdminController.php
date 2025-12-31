<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Evaluation;
use App\Models\PaymentStatus;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Calculate metrics
        $totalStudents = Student::count();
        
        // Monthly Hours - sum of total_hours for current month
        $monthlyHours = Course::whereMonth('course_date', now()->month)
            ->whereYear('course_date', now()->year)
            ->sum('total_hours') ?? 0;
        
        // Monthly Revenue - sum of income for current month
        $monthlyRevenue = Course::whereMonth('course_date', now()->month)
            ->whereYear('course_date', now()->year)
            ->sum('income') ?? 0;
        
        // Get all teachers and students for filter dropdowns
        $teachers = Teacher::all();
        $students = Student::all();
        
        // Query courses with filters
        $query = Course::with(['student', 'teacher', 'evaluation']);
        
        // Filter by teacher_id
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        // Filter by student_id
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('course_date', $request->date);
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('course_type', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Pagination
        $perPage = $request->get('per_page', 20);
        $courses = $query->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate($perPage);
        
        return view('admin.dashboard', compact(
            'totalStudents',
            'monthlyHours',
            'monthlyRevenue',
            'teachers',
            'students',
            'courses'
        ));
    }

    // Teachers Management
    public function teachers()
    {
        $teachers = Teacher::all();
        return view('admin.teachers', compact('teachers'));
    }

    public function createTeacher()
    {
        return view('admin.teachers-create');
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'password' => 'required|string|min:8',
            'currency' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Teacher::create($validated);

        return redirect()->route('admin.teachers')->with('success', 'Teacher created successfully!');
    }

    public function editTeacher(Teacher $teacher)
    {
        return view('admin.teachers-edit', compact('teacher'));
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'currency' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $teacher->update($validated);

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully!');
    }

    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully!');
    }

    // Students Management
    public function students()
    {
        $students = Student::with(['teacher', 'subject'])->get();
        return view('admin.students', compact('students'));
    }

    public function createStudent()
    {
        $teachers = Teacher::all();
        $subjects = Subject::where('is_active', true)->get();
        $paymentStatuses = PaymentStatus::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.students-create', compact('teachers', 'subjects', 'paymentStatuses'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'password' => 'required|string|min:8',
            'section' => 'required|string|max:10',
            'package_number' => 'required|integer|min:1',
            'hour_rate' => 'required|numeric|min:0',
            'package_rate' => 'required|numeric|min:0',
            'payment_status_id' => 'required|exists:payment_statuses,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_rate' => 'nullable|numeric|min:0',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Student::create($validated);

        return redirect()->route('admin.students')->with('success', 'Student created successfully!');
    }

    public function editStudent(Student $student)
    {
        $teachers = Teacher::all();
        $subjects = Subject::where('is_active', true)->get();
        $paymentStatuses = PaymentStatus::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.students-edit', compact('student', 'teachers', 'subjects', 'paymentStatuses'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'section' => 'required|string|max:10',
            'package_number' => 'required|integer|min:1',
            'hour_rate' => 'required|numeric|min:0',
            'package_rate' => 'required|numeric|min:0',
            'payment_status_id' => 'required|exists:payment_statuses,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_rate' => 'nullable|numeric|min:0',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $student->update($validated);

        // Redirect back to management if coming from there, otherwise to students list
        if (request()->has('from_management') || str_contains(request()->header('referer', ''), '/management')) {
            return redirect()->route('admin.management')->with('success', 'Student updated successfully!');
        }

        return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
    }

    public function destroyStudent(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students')->with('success', 'Student deleted successfully!');
    }

    // Teacher Details API endpoint
    public function getTeacherDetails(Teacher $teacher)
    {
        $students = $teacher->students()->with(['courses', 'subject'])->get();
        $courses = $teacher->courses()->get();
        
        // Calculate stats
        $activeStudents = $students->count();
        $totalCourses = $courses->count();
        
        // Calculate hours this month
        $hoursThisMonth = $teacher->courses()
            ->whereMonth('course_date', now()->month)
            ->whereYear('course_date', now()->year)
            ->sum('total_hours') ?? 0;
        
        // Calculate total hours all time
        $totalHours = $teacher->courses()->sum('total_hours') ?? 0;
        
        // Calculate monthly income
        $monthlyIncome = $teacher->courses()
            ->whereMonth('course_date', now()->month)
            ->whereYear('course_date', now()->year)
            ->sum('income') ?? 0;
        
        // Calculate total income
        $totalIncome = $teacher->courses()->sum('income') ?? 0;

        return response()->json([
            'teacher' => $teacher,
            'students' => $students,
            'stats' => [
                'activeStudents' => $activeStudents,
                'totalCourses' => $totalCourses,
                'hoursThisMonth' => number_format($hoursThisMonth, 2),
                'totalHours' => number_format($totalHours, 2),
                'monthlyIncome' => number_format($monthlyIncome, 2),
                'totalIncome' => number_format($totalIncome, 2),
                'hourlyRate' => number_format($teacher->hourly_rate, 2),
            ]
        ]);
    }

    // Settings - Multiple Settings Management
    public function settings(Request $request)
    {
        $activeTab = $request->get('tab', 'subjects');
        
        $subjects = Subject::all();
        $evaluations = Evaluation::ordered()->get();
        $paymentStatuses = PaymentStatus::orderBy('sort_order')->get();
        
        return view('admin.settings', compact('subjects', 'evaluations', 'paymentStatuses', 'activeTab'));
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.settings')->with('success', 'Subject created successfully!');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.settings')->with('success', 'Subject updated successfully!');
    }

    public function destroySubject(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.settings')->with('success', 'Subject deleted successfully!');
    }

    // Evaluations Management
    public function storeEvaluation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:evaluations,name',
            'description' => 'nullable|string|max:255',
            'min_percentage' => 'nullable|integer|min:0|max:100',
            'max_percentage' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ], [
            'name.required' => 'The evaluation name is required.',
            'name.unique' => 'An evaluation with this name already exists.',
            'name.max' => 'The evaluation name cannot exceed 255 characters.',
            'min_percentage.integer' => 'Minimum percentage must be a whole number.',
            'min_percentage.min' => 'Minimum percentage cannot be less than 0.',
            'min_percentage.max' => 'Minimum percentage cannot exceed 100.',
            'max_percentage.integer' => 'Maximum percentage must be a whole number.',
            'max_percentage.min' => 'Maximum percentage cannot be less than 0.',
            'max_percentage.max' => 'Maximum percentage cannot exceed 100.',
            'icon.max' => 'Icon name cannot exceed 50 characters.',
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000).',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be less than 0.',
            'sort_order.max' => 'Sort order cannot exceed 999.',
        ]);

        // Ensure max_percentage is not less than min_percentage
        if ($validated['min_percentage'] && $validated['max_percentage'] && 
            $validated['min_percentage'] > $validated['max_percentage']) {
            return redirect()->back()
                ->withErrors(['max_percentage' => 'Maximum percentage must be greater than or equal to minimum percentage.'])
                ->withInput()
                ->with('open_evaluation_modal', true);
        }

        // Check for overlapping percentage ranges with existing evaluations
        $overlappingEvaluation = null;
        if ($validated['min_percentage'] !== null && $validated['max_percentage'] !== null) {
            $overlappingEvaluation = Evaluation::where('is_active', true)
                ->whereNotNull('min_percentage')
                ->whereNotNull('max_percentage')
                ->where(function($query) use ($validated) {
                    // Check if new range overlaps with existing ranges
                    // Two ranges overlap if: new_min <= existing_max AND new_max >= existing_min
                    $query->where('min_percentage', '<=', $validated['max_percentage'])
                          ->where('max_percentage', '>=', $validated['min_percentage']);
                })
                ->first();
        }

        if ($overlappingEvaluation) {
            return redirect()->back()
                ->withErrors(['min_percentage' => 'This percentage range overlaps with existing evaluation: ' . $overlappingEvaluation->name])
                ->withInput()
                ->with('open_evaluation_modal', true);
        }

        // Set default sort_order if not provided
        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = Evaluation::max('sort_order') + 1;
        }

        Evaluation::create($validated);

        return redirect()->route('admin.settings', ['tab' => 'evaluations'])->with('success', 'Evaluation created successfully!');
    }

    public function updateEvaluation(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:evaluations,name,' . $evaluation->id,
            'description' => 'nullable|string|max:255',
            'min_percentage' => 'nullable|integer|min:0|max:100',
            'max_percentage' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ], [
            'name.required' => 'The evaluation name is required.',
            'name.unique' => 'An evaluation with this name already exists.',
            'name.max' => 'The evaluation name cannot exceed 255 characters.',
            'min_percentage.integer' => 'Minimum percentage must be a whole number.',
            'min_percentage.min' => 'Minimum percentage cannot be less than 0.',
            'min_percentage.max' => 'Minimum percentage cannot exceed 100.',
            'max_percentage.integer' => 'Maximum percentage must be a whole number.',
            'max_percentage.min' => 'Maximum percentage cannot be less than 0.',
            'max_percentage.max' => 'Maximum percentage cannot exceed 100.',
            'icon.max' => 'Icon name cannot exceed 50 characters.',
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000).',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be less than 0.',
            'sort_order.max' => 'Sort order cannot exceed 999.',
        ]);

        // Ensure max_percentage is not less than min_percentage
        if ($validated['min_percentage'] && $validated['max_percentage'] && 
            $validated['min_percentage'] > $validated['max_percentage']) {
            return redirect()->back()
                ->withErrors(['max_percentage' => 'Maximum percentage must be greater than or equal to minimum percentage.'])
                ->withInput()
                ->with('open_evaluation_modal', true);
        }

        // Check for overlapping percentage ranges with existing evaluations (excluding current one)
        $overlappingEvaluation = null;
        if ($validated['min_percentage'] !== null && $validated['max_percentage'] !== null) {
            $overlappingEvaluation = Evaluation::where('id', '!=', $evaluation->id)
                ->where('is_active', true)
                ->whereNotNull('min_percentage')
                ->whereNotNull('max_percentage')
                ->where(function($query) use ($validated) {
                    // Check if new range overlaps with existing ranges
                    // Two ranges overlap if: new_min <= existing_max AND new_max >= existing_min
                    $query->where('min_percentage', '<=', $validated['max_percentage'])
                          ->where('max_percentage', '>=', $validated['min_percentage']);
                })
                ->first();
        }

        if ($overlappingEvaluation) {
            return redirect()->back()
                ->withErrors(['min_percentage' => 'This percentage range overlaps with existing evaluation: ' . $overlappingEvaluation->name])
                ->withInput()
                ->with('open_evaluation_modal', true);
        }

        // Set default sort_order if not provided
        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = $evaluation->sort_order ?? (Evaluation::max('sort_order') + 1);
        }

        $evaluation->update($validated);

        return redirect()->route('admin.settings', ['tab' => 'evaluations'])->with('success', 'Evaluation updated successfully!');
    }

    public function destroyEvaluation(Evaluation $evaluation)
    {
        // Check if evaluation is being used by any courses
        if ($evaluation->courses()->count() > 0) {
            return redirect()->route('admin.settings', ['tab' => 'evaluations'])
                ->with('error', 'Cannot delete evaluation that is being used by courses. Please reassign courses first.');
        }

        $evaluation->delete();

        return redirect()->route('admin.settings', ['tab' => 'evaluations'])->with('success', 'Evaluation deleted successfully!');
    }

    // Payment Statuses Management
    public function storePaymentStatus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_statuses,name',
            'display_name' => 'required|string|max:255',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        // Set default sort_order if not provided
        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = PaymentStatus::max('sort_order') + 1;
        }

        PaymentStatus::create($validated);

        return redirect()->route('admin.settings', ['tab' => 'payment-statuses'])->with('success', 'Payment status created successfully!');
    }

    public function updatePaymentStatus(Request $request, PaymentStatus $paymentStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_statuses,name,' . $paymentStatus->id,
            'display_name' => 'required|string|max:255',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        // Set default sort_order if not provided
        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = $paymentStatus->sort_order ?? (PaymentStatus::max('sort_order') + 1);
        }

        $paymentStatus->update($validated);

        return redirect()->route('admin.settings', ['tab' => 'payment-statuses'])->with('success', 'Payment status updated successfully!');
    }

    public function destroyPaymentStatus(PaymentStatus $paymentStatus)
    {
        // Check if payment status is being used by any students
        if ($paymentStatus->students()->count() > 0) {
            return redirect()->route('admin.settings', ['tab' => 'payment-statuses'])
                ->with('error', 'Cannot delete payment status that is being used by students. Please reassign students first.');
        }

        $paymentStatus->delete();

        return redirect()->route('admin.settings', ['tab' => 'payment-statuses'])->with('success', 'Payment status deleted successfully!');
    }

    // Courses Management
    public function courses(Request $request)
    {
        $query = Course::with(['student', 'teacher', 'evaluation']);
        
        // Apply filters similar to dashboard
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('course_date', $request->date);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $courses = $query->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate(20);
        
        $teachers = Teacher::all();
        $students = Student::all();
        
        return view('admin.courses', compact('courses', 'teachers', 'students'));
    }

    public function createCourse()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        $subjects = Subject::all();
        $evaluations = Evaluation::active()->ordered()->get();
        
        return view('admin.courses.create', compact('teachers', 'students', 'subjects', 'evaluations'));
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'student_id' => 'required|exists:students,id',
            'student_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'course_date' => 'required|date',
            'class_time' => 'required',
            'duration_hours' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0|max:59',
            'course_type' => 'required|string|max:255',
            'status' => 'required|in:Present,Absent,Late,Pending',
            'homework' => 'nullable|string',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
            'souvenir_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Get student and teacher info
        $student = Student::findOrFail($validated['student_id']);
        $teacher = Teacher::findOrFail($validated['teacher_id']);
        
        // Handle image upload
        if ($request->hasFile('souvenir_image')) {
            $image = $request->file('souvenir_image');
            $imagePath = $image->store('souvenirs', 'public');
            $validated['souvenir_image'] = $imagePath;
        }
        
        // Calculate n_value based on package_number and previous lessons
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('teacher_id', $teacher->id)
                               ->orderBy('n_value', 'desc')
                               ->first();
        
        $previousNValue = $previousLessons ? $previousLessons->n_value : 0;
        $currentDuration = $validated['duration_hours'] + ($validated['duration_minutes'] / 60.0);
        $nValue = $previousNValue + $currentDuration;
        
        // Calculate income based on teacher's hourly rate
        $income = $currentDuration * $teacher->hourly_rate;
        
        $validated['student_name'] = $validated['student_name'] ?? $student->name;
        $validated['n_value'] = $nValue;
        $validated['total_hours'] = $currentDuration;
        $validated['income'] = $income;
        
        Course::create($validated);
        
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Course created successfully!');
    }

    public function editCourse(Course $course)
    {
        $teachers = Teacher::all();
        $students = Student::all();
        $evaluations = Evaluation::active()->ordered()->get();
        $subjects = Subject::all();
        
        return view('admin.courses.edit', compact('course', 'teachers', 'students', 'evaluations', 'subjects'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'student_id' => 'required|exists:students,id',
            'student_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'course_date' => 'required|date',
            'class_time' => 'required',
            'duration_hours' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0|max:59',
            'course_type' => 'required|string|max:255',
            'status' => 'required|in:Present,Absent,Late,Pending',
            'homework' => 'nullable|string',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
            'souvenir_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Get student and teacher info
        $student = Student::findOrFail($validated['student_id']);
        $teacher = Teacher::findOrFail($validated['teacher_id']);
        
        // Handle image upload
        if ($request->hasFile('souvenir_image')) {
            $image = $request->file('souvenir_image');
            $imagePath = $image->store('souvenirs', 'public');
            $validated['souvenir_image'] = $imagePath;
        }
        
        // Recalculate n_value and income if teacher or duration changed
        if ($course->teacher_id != $validated['teacher_id'] || 
            $course->duration_hours != $validated['duration_hours'] || 
            $course->duration_minutes != $validated['duration_minutes']) {
            
            $previousLessons = Course::where('student_id', $student->id)
                                   ->where('teacher_id', $teacher->id)
                                   ->where('id', '!=', $course->id)
                                   ->orderBy('n_value', 'desc')
                                   ->first();
            
            $previousNValue = $previousLessons ? $previousLessons->n_value : 0;
            $currentDuration = $validated['duration_hours'] + ($validated['duration_minutes'] / 60.0);
            $nValue = $previousNValue + $currentDuration;
            $income = $currentDuration * $teacher->hourly_rate;
            
            $validated['n_value'] = $nValue;
            $validated['total_hours'] = $currentDuration;
            $validated['income'] = $income;
        }
        
        $validated['student_name'] = $validated['student_name'] ?? $student->name;
        
        $course->update($validated);
        
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Course updated successfully!');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Course deleted successfully!');
    }

    // Notifications Management
    public function notifications(Request $request)
    {
        $query = Notification::with(['student', 'teacher', 'course']);

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by date (from)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Search by student name
        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $teachers = Teacher::all();

        return view('admin.notifications', compact('notifications', 'teachers'));
    }

    public function approveAbsence(Notification $notification)
    {
        if ($notification->type !== 'absence_approval') {
            return redirect()->back()->with('error', 'Invalid notification type');
        }

        $course = $notification->course;
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        $student = $notification->student;
        
        // Find the last lesson before this one (excluding unapproved absences with name "0")
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('teacher_id', $course->teacher_id)
                               ->where('id', '!=', $course->id)
                               ->where('name', '!=', '0') // Exclude unapproved absences
                               ->orderBy('n_value', 'desc')
                               ->first();

        $previousNValue = $previousLessons ? $previousLessons->n_value : 0;
        $currentDuration = $course->total_hours;
        $nValue = $previousNValue + $currentDuration;

        // Get the last lesson number to determine the next number
        $lastLesson = Course::where('student_id', $student->id)
                          ->where('teacher_id', $course->teacher_id)
                          ->where('id', '!=', $course->id)
                          ->where('name', '!=', '0')
                          ->where('name', '!=', '0.0')
                          ->orderBy('course_date', 'desc')
                          ->orderBy('class_time', 'desc')
                          ->first();

        // Determine the lesson number
        if ($lastLesson && is_numeric($lastLesson->name)) {
            $lessonNumber = (float)$lastLesson->name + 1;
        } else {
            // If no previous lesson, start from 1
            $lessonNumber = 1;
        }

        // Update the course
        $course->name = (string)$lessonNumber;
        $course->n_value = $nValue;
        $course->admin_status = 'approved';
        $course->save();

        // Mark notification as approved
        $notification->is_approved = true;
        $notification->is_read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Absence approved and lesson calculated');
    }

    public function rejectAbsence(Notification $notification)
    {
        if ($notification->type !== 'absence_approval') {
            return redirect()->back()->with('error', 'Invalid notification type');
        }

        $course = $notification->course;
        if ($course) {
            // Keep the name as "0" and don't calculate n_value
            $course->name = '0';
            $course->admin_status = 'rejected';
            // Keep n_value at previous value (don't increment)
            $course->save();
        }

        // Mark notification as rejected (not approved)
        $notification->is_approved = false;
        $notification->is_read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Absence rejected - lesson will not be calculated');
    }

    public function markNotificationRead(Notification $notification)
    {
        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    public function dismissNotification(Notification $notification)
    {
        $notification->delete();

        return redirect()->back()->with('success', 'Notification dismissed');
    }

    // Payment Management
    public function payment(Request $request)
    {
        // Get students who have reached their package limit (have lessons with name "0.0")
        // These are students who need payment activation
        $query = Student::with(['teacher', 'paymentStatus', 'courses' => function($q) {
            $q->orderBy('course_date', 'desc')->orderBy('class_time', 'desc');
        }])
        ->whereHas('courses', function($q) {
            $q->where('name', '0.0');
        });

        // Filter by student search
        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by pack (package_number)
        if ($request->filled('pack') && $request->pack !== 'all') {
            $query->where('package_number', $request->pack);
        }

        $students = $query->get();

        // Calculate totals - only count courses with name "0.0" (unpaid lessons beyond limit)
        $totalPending = $students->count();
        $totalAmountDue = $students->sum(function($student) {
            // Calculate amount due based on courses with name "0.0" (reached package limit)
            $coursesBeyondLimit = $student->courses->filter(function($course) {
                return $course->name === '0.0';
            });
            
            return $coursesBeyondLimit->sum('income');
        });

        $teachers = Teacher::all();
        $paymentStatuses = PaymentStatus::all();
        
        // Get unique package numbers for filter
        $packs = Student::distinct()->pluck('package_number')->sort()->values();

        return view('admin.payment', compact('students', 'teachers', 'paymentStatuses', 'packs', 'totalPending', 'totalAmountDue'));
    }

    public function updateStudentPaymentStatus(Request $request, Student $student)
    {
        $validated = $request->validate([
            'payment_status_id' => 'required|exists:payment_statuses,id',
        ]);

        $oldStatus = $student->payment_status_id;
        $newStatus = PaymentStatus::findOrFail($validated['payment_status_id']);

        $student->payment_status_id = $validated['payment_status_id'];
        $student->save();

        // If changing to active/paid status, rename lessons
        if ($newStatus->name === 'PAYÉ' || $newStatus->name === 'Active') {
            $this->renameLessonsAfterPackageLimit($student);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }

    // Analytics
    public function analytics(Request $request)
    {
        // Get date range filters (default to last 12 months if not provided)
        $dateFrom = $request->filled('date_from') 
            ? Carbon::parse($request->date_from) 
            : Carbon::now()->subMonths(12)->startOfMonth();
        
        $dateTo = $request->filled('date_to') 
            ? Carbon::parse($request->date_to)->endOfDay() 
            : Carbon::now()->endOfDay();

        // Get all teachers
        $teachers = Teacher::all();

        // Base query for courses within date range
        $baseQuery = Course::whereBetween('course_date', [$dateFrom, $dateTo]);

        // Filter by teacher if selected
        $selectedTeacherId = $request->filled('teacher_id') ? $request->teacher_id : null;
        if ($selectedTeacherId) {
            $baseQuery->where('teacher_id', $selectedTeacherId);
        }

        // Calculate total teaching hours
        $totalHours = (clone $baseQuery)->sum('total_hours') ?? 0;

        // Get data for charts
        // 1. Total Hours Across Months (line chart) - database agnostic
        $coursesForMonths = (clone $baseQuery)->get();
        $hoursByMonth = $coursesForMonths->groupBy(function ($course) {
            return Carbon::parse($course->course_date)->format('Y-m');
        })->map(function ($monthCourses, $monthKey) {
            return $monthCourses->sum('total_hours');
        })->sortKeys()->mapWithKeys(function ($hours, $monthKey) {
            return [Carbon::parse($monthKey . '-01')->format('M Y') => (float)$hours];
        });

        // 2. Top 5 Teachers by Total Hours (bar chart)
        $topTeachersQuery = Course::whereBetween('course_date', [$dateFrom, $dateTo]);
        if ($selectedTeacherId) {
            $topTeachersQuery->where('teacher_id', $selectedTeacherId);
        }
        $topTeachersCourses = $topTeachersQuery->with('teacher')->get();
        $topTeachers = $topTeachersCourses->groupBy('teacher_id')
            ->map(function ($courses, $teacherId) {
                $teacher = $courses->first()->teacher;
                return [
                    'teacher_id' => $teacherId,
                    'name' => $teacher->name ?? 'Unknown',
                    'hours' => (float)$courses->sum('total_hours')
                ];
            })
            ->sortByDesc('hours')
            ->take(5)
            ->values()
            ->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'hours' => $item['hours']
                ];
            });

        // 3. Best Days by Number of Courses (donut chart) - database agnostic
        $coursesForDays = (clone $baseQuery)->get();
        $dayOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $coursesByDay = $coursesForDays->groupBy(function ($course) {
            return Carbon::parse($course->course_date)->format('l'); // Full day name
        })->map(function ($dayCourses, $dayName) {
            return [
                'day' => $dayName,
                'count' => $dayCourses->count()
            ];
        })->sortBy(function ($item) use ($dayOrder) {
            $index = array_search($item['day'], $dayOrder);
            return $index !== false ? $index : 999;
        })->values();

        // Calculate financial statistics per teacher
        $financialStats = [];
        foreach ($teachers as $teacher) {
            $teacherCoursesQuery = Course::where('teacher_id', $teacher->id)
                ->whereBetween('course_date', [$dateFrom, $dateTo]);
            
            if ($selectedTeacherId && $selectedTeacherId != $teacher->id) {
                continue; // Skip if filtering by specific teacher
            }

            $totalIncome = $teacherCoursesQuery->sum('income') ?? 0;
            $totalHoursForTeacher = $teacherCoursesQuery->sum('total_hours') ?? 0;
            
            // Only include teachers who have courses in the date range
            if ($totalHoursForTeacher > 0 || $totalIncome > 0) {
                $teacherRateCost = $totalHoursForTeacher * $teacher->hourly_rate;

                $financialStats[] = [
                    'teacher_id' => $teacher->id,
                    'name' => $teacher->name,
                    'income' => (float)$totalIncome,
                    'hours' => (float)$totalHoursForTeacher,
                    'hourly_rate' => (float)$teacher->hourly_rate,
                    'teacher_rate_cost' => (float)$teacherRateCost,
                ];
            }
        }

        return view('admin.analytics', compact(
            'teachers',
            'selectedTeacherId',
            'dateFrom',
            'dateTo',
            'totalHours',
            'hoursByMonth',
            'topTeachers',
            'coursesByDay',
            'financialStats'
        ));
    }

    /**
     * Rename lessons with name "0.0" sequentially after package limit
     * When payment is activated, restart from 0 for the new package
     */
    private function renameLessonsAfterPackageLimit(Student $student)
    {
        // Get all courses for this student that have name "0.0" (lessons beyond package limit)
        // Exclude unapproved absences (name = "0")
        $coursesToRename = Course::where('student_id', $student->id)
            ->where('name', '0.0')
            ->where('name', '!=', '0')
            ->orderBy('course_date', 'asc')
            ->orderBy('class_time', 'asc')
            ->get();

        if ($coursesToRename->isEmpty()) {
            return;
        }

        // When payment is activated, restart from 0 for the new package
        // Start lesson numbering from 1 and n_value from 0
        $lessonNumber = 0;
        $baseNValue = 0;

        // Rename and recalculate n_value for each lesson
        foreach ($coursesToRename as $course) {
            $lessonNumber++;
            $course->name = (string)$lessonNumber;
            
            // Recalculate n_value starting from 0 for the new package
            $baseNValue += $course->total_hours;
            $course->n_value = $baseNValue;
            
            $course->save();
        }
    }

    // Student Management Page
    public function management(Request $request)
    {
        $query = Student::with(['teacher', 'subject', 'paymentStatus', 'courses']);

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by teacher
        if ($request->filled('teacher_id') && $request->teacher_id !== 'all') {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by course type (subject)
        if ($request->filled('course_type') && $request->course_type !== 'all') {
            $query->where('subject_id', $request->course_type);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            $query->where(function($q) use ($status) {
                if ($status === 'Archived') {
                    $q->whereHas('paymentStatus', function($ps) {
                        $ps->where('name', 'ARRÊTÉ');
                    });
                } else {
                    // For Active, Inactive, Suspended - we need to filter by last course date
                    $now = Carbon::now();
                    if ($status === 'Active') {
                        $q->whereHas('courses', function($c) use ($now) {
                            $c->where('status', 'Present')
                              ->whereNotIn('name', ['0', '0.0'])
                              ->where('course_date', '>=', $now->copy()->subDays(30));
                        });
                    } elseif ($status === 'Inactive') {
                        $q->whereHas('courses', function($c) use ($now) {
                            $c->where('status', 'Present')
                              ->whereNotIn('name', ['0', '0.0'])
                              ->whereBetween('course_date', [
                                  $now->copy()->subDays(60),
                                  $now->copy()->subDays(31)
                              ]);
                        });
                    } elseif ($status === 'Suspended') {
                        $q->where(function($sq) use ($now) {
                            $sq->whereDoesntHave('courses', function($c) {
                                $c->where('status', 'Present')
                                  ->whereNotIn('name', ['0', '0.0']);
                            })
                            ->orWhereHas('courses', function($c) use ($now) {
                                $c->where('status', 'Present')
                                  ->whereNotIn('name', ['0', '0.0'])
                                  ->where('course_date', '<', $now->copy()->subDays(60));
                            });
                        });
                    }
                }
            });
        }

        $students = $query->get();

        // Calculate summary statistics from ALL students (not just filtered)
        $allStudents = Student::with(['courses', 'paymentStatus'])->get();
        $totalStudents = $allStudents->count();
        $activeCount = 0;
        $inactiveCount = 0;
        $suspendedCount = 0;
        $archivedCount = 0;

        foreach ($allStudents as $student) {
            $status = $student->getActivityStatus();
            switch ($status) {
                case 'Active':
                    $activeCount++;
                    break;
                case 'Inactive':
                    $inactiveCount++;
                    break;
                case 'Suspended':
                    $suspendedCount++;
                    break;
                case 'Archived':
                    $archivedCount++;
                    break;
            }
        }

        // Get filter options
        $teachers = Teacher::all();
        $subjects = Subject::where('is_active', true)->get();
        $paymentStatuses = PaymentStatus::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.management', compact(
            'students',
            'totalStudents',
            'activeCount',
            'inactiveCount',
            'suspendedCount',
            'archivedCount',
            'teachers',
            'subjects',
            'paymentStatuses'
        ));
    }

    public function showStudentProfile(Student $student)
    {
        $teachers = Teacher::all();
        $subjects = Subject::where('is_active', true)->get();
        $paymentStatuses = PaymentStatus::where('is_active', true)->orderBy('sort_order')->get();
        
        return view('admin.student-profile', compact('student', 'teachers', 'subjects', 'paymentStatuses'));
    }

    public function quickUpdateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'package_number' => 'nullable|integer|min:1',
            'package_rate' => 'nullable|numeric|min:0',
            'payment_status_id' => 'nullable|exists:payment_statuses,id',
        ]);

        // Update only provided fields
        if ($request->has('teacher_id')) {
            $student->teacher_id = $validated['teacher_id'];
        }
        if ($request->has('package_number')) {
            $student->package_number = $validated['package_number'];
        }
        if ($request->has('package_rate')) {
            $student->package_rate = $validated['package_rate'];
        }
        if ($request->has('payment_status_id')) {
            $student->payment_status_id = $validated['payment_status_id'];
            
            // If changing to active/paid status, rename lessons
            $newStatus = PaymentStatus::findOrFail($validated['payment_status_id']);
            if ($newStatus->name === 'PAYÉ' || $newStatus->name === 'Active') {
                $this->renameLessonsAfterPackageLimit($student);
            }
        }

        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'student' => $student->load(['teacher', 'paymentStatus'])
        ]);
    }
}
