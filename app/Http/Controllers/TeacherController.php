<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\RecurringCourse;
use App\Models\PaymentStatus;
use App\Models\Notification;
use App\Models\WaitingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Get teacher's students
        $studentsCount = Student::where('teacher_id', $teacher->id)->count();
        
        // Get teacher's courses/lessons
        $coursesQuery = Course::where('teacher_id', $teacher->id);
        
        // Get this month's stats
        $thisMonthCoursesQuery = clone $coursesQuery;
        $thisMonthCourses = $thisMonthCoursesQuery->whereMonth('course_date', now()->month)
                                                  ->whereYear('course_date', now()->year);
        
        $thisMonthHours = $thisMonthCourses->sum(DB::raw('duration_hours + (duration_minutes / 60.0)'));
        $thisMonthRevenue = $thisMonthCourses->sum('income');
        
        // Calculate teacher performance level
        // Based on attendance rate and student progress
        $totalCourses = Course::where('teacher_id', $teacher->id)->count();
        $presentCourses = Course::where('teacher_id', $teacher->id)
                               ->where('status', 'Present')
                               ->count();
        
        $attendanceRate = $totalCourses > 0 ? ($presentCourses / $totalCourses) * 100 : 0;
        
        // Calculate student progress (average n_value completion)
        $students = Student::where('teacher_id', $teacher->id)->get();
        $progressSum = 0;
        $progressCount = 0;
        
        foreach ($students as $student) {
            $lastCourse = Course::where('student_id', $student->id)
                               ->where('teacher_id', $teacher->id)
                               ->orderBy('n_value', 'desc')
                               ->first();
            
            if ($lastCourse && $student->package_number > 0) {
                $progress = min(100, ($lastCourse->n_value / $student->package_number) * 100);
                $progressSum += $progress;
                $progressCount++;
            }
        }
        
        $avgProgress = $progressCount > 0 ? ($progressSum / $progressCount) : 0;
        $teacherPerformanceLevel = ($attendanceRate * 0.5 + $avgProgress * 0.5);
        
        // Get weekly data for bar chart (last 4 weeks)
        $weeklyData = [];
        $maxWeekCourses = 0;
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            
            $weekCourses = Course::where('teacher_id', $teacher->id)
                                ->whereBetween('course_date', [$weekStart, $weekEnd])
                                ->count();
            
            $maxWeekCourses = max($maxWeekCourses, $weekCourses);
        }
        
        // Normalize to 0-100 scale
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            
            $weekCourses = Course::where('teacher_id', $teacher->id)
                                ->whereBetween('course_date', [$weekStart, $weekEnd])
                                ->count();
            
            $weeklyData[] = [
                'label' => $weekStart->format('d/m'),
                'value' => $maxWeekCourses > 0 ? ($weekCourses / $maxWeekCourses) * 100 : 0
            ];
        }
        
        // Course listing with filters and search - only show courses from current round and pending courses for each student
        $query = Course::where('teacher_id', $teacher->id)
                      ->with(['student', 'evaluation']);
        
        // Filter by current round for each student + pending courses
        // Get max round per student for this teacher (excluding round 0 which is for pending)
        $maxRounds = Course::where('teacher_id', $teacher->id)
            ->where('round', '>', 0)
            ->select('student_id', DB::raw('MAX(round) as max_round'))
            ->groupBy('student_id')
            ->pluck('max_round', 'student_id');
        
        // Build where clause for current round courses and pending courses
        // This ensures we only show courses from the highest round for each student
        $query->where(function($q) use ($maxRounds, $teacher) {
            // If we have students with rounds, include their current round courses
            if ($maxRounds->isNotEmpty()) {
                foreach ($maxRounds as $studentId => $maxRound) {
                    $q->orWhere(function($subQ) use ($studentId, $maxRound, $teacher) {
                        $subQ->where('student_id', $studentId)
                             ->where('round', $maxRound)
                             ->where('teacher_id', $teacher->id);
                    });
                }
            }
            // Always include pending courses (round = 0 or admin_status = 'pending') for this teacher
            // These are courses waiting for package activation
            $q->orWhere(function($subQ) use ($teacher) {
                $subQ->where('teacher_id', $teacher->id)
                     ->where(function($pendingQ) {
                         $pendingQ->where('round', 0)
                                  ->orWhere('admin_status', 'pending');
                     });
            });
        });
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Month/Year filter
        if ($request->filled('month_year')) {
            [$month, $year] = explode('-', $request->month_year);
            $query->whereMonth('course_date', $month)
                  ->whereYear('course_date', $year);
        }
        
        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('course_date', $request->date);
        }
        
        // Course type filter
        if ($request->filled('course_type')) {
            $query->where('course_type', $request->course_type);
        }
        
        $perPage = $request->get('per_page', 10);
        $courses = $query->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate($perPage);
        
        // Get students and subjects for filters
        $students = Student::where('teacher_id', $teacher->id)->get();
        $subjects = Subject::all();
        $evaluations = Evaluation::active()->ordered()->get();
        
        return view('teacher.dashboard', compact(
            'studentsCount', 
            'thisMonthHours', 
            'thisMonthRevenue',
            'teacherPerformanceLevel',
            'weeklyData',
            'courses',
            'students',
            'subjects',
            'evaluations'
        ));
    }

    public function students()
    {
        $teacher = Auth::guard('teacher')->user();
        $students = Student::where('teacher_id', $teacher->id)->with(['subject', 'paymentStatus'])->get();
        return view('teacher.students', compact('students'));
    }


    public function courses(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $query = Course::where('teacher_id', $teacher->id)
                      ->with(['student', 'evaluation']);
        
        // Filter by current round for each student + pending courses
        // Get max round per student for this teacher (excluding round 0 which is for pending)
        $maxRounds = Course::where('teacher_id', $teacher->id)
            ->where('round', '>', 0)
            ->select('student_id', DB::raw('MAX(round) as max_round'))
            ->groupBy('student_id')
            ->pluck('max_round', 'student_id');
        
        // Build where clause for current round courses and pending courses
        // This ensures we only show courses from the highest round for each student
        $query->where(function($q) use ($maxRounds, $teacher) {
            // If we have students with rounds, include their current round courses
            if ($maxRounds->isNotEmpty()) {
                foreach ($maxRounds as $studentId => $maxRound) {
                    $q->orWhere(function($subQ) use ($studentId, $maxRound, $teacher) {
                        $subQ->where('student_id', $studentId)
                             ->where('round', $maxRound)
                             ->where('teacher_id', $teacher->id);
                    });
                }
            }
            // Always include pending courses (round = 0 or admin_status = 'pending') for this teacher
            // These are courses waiting for package activation
            $q->orWhere(function($subQ) use ($teacher) {
                $subQ->where('teacher_id', $teacher->id)
                     ->where(function($pendingQ) {
                         $pendingQ->where('round', 0)
                                  ->orWhere('admin_status', 'pending');
                     });
            });
        });
        
        // Apply filters
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('course_type')) {
            $query->where('course_type', $request->course_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('course_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('course_date', '<=', $request->date_to);
        }
        
        $courses = $query->orderBy('created_at', 'asc')
                        ->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate(20);
        
        // Get students for filter dropdown
        $students = Student::where('teacher_id', $teacher->id)->get();
        
        // Get subjects for course type filter
        $subjects = Subject::all();
        
        return view('teacher.courses', compact('courses', 'students', 'subjects'));
    }

    public function createCourse()
    {
        $teacher = Auth::guard('teacher')->user();
        $students = Student::where('teacher_id', $teacher->id)->get();
        $subjects = Subject::all();
        $evaluations = Evaluation::active()->ordered()->get();
        
        return view('teacher.courses.create', compact('students', 'subjects', 'evaluations'));
    }

    public function storeCourse(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $validated = $request->validate([
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
        
        // Get student info
        $student = Student::findOrFail($validated['student_id']);
        
        // Handle image upload
        if ($request->hasFile('souvenir_image')) {
            $image = $request->file('souvenir_image');
            $imagePath = $image->store('souvenirs', 'public');
            $validated['souvenir_image'] = $imagePath;
        }
        
        // Calculate n_value based on package_number and previous lessons
        // Exclude unapproved absences (name = "0") and lessons beyond limit that haven't been paid (name = "0.0")
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('teacher_id', $teacher->id)
                               ->where('name', '!=', '0') // Exclude unapproved absences
                               ->where('name', '!=', '0.0') // Exclude unpaid lessons beyond limit
                               ->orderBy('n_value', 'desc')
                               ->first();
        
        $previousNValue = $previousLessons ? $previousLessons->n_value : 0;
        $currentDuration = $validated['duration_hours'] + ($validated['duration_minutes'] / 60.0);
        
        // Check if package limit has been reached
        $packageLimitReached = $previousNValue >= $student->package_number;
        
        // Check if there's a notification for package completion
        $hasPackageNotification = Notification::where('student_id', $student->id)
            ->where('type', 'progress_update')
            ->where('is_read', false)
            ->exists();
        
        // Set admin_status: pending for absences, approved for others
        if ($validated['status'] === 'Absent') {
            $validated['admin_status'] = 'pending';
            $validated['name'] = '0';
            $nValue = $previousNValue; // Don't increment n_value for absences until approved
            
            // Calculate income based on teacher's hourly rate
            $income = $currentDuration * $teacher->hourly_rate;
            
            // Get current round for this student
            $currentRound = Course::where('student_id', $student->id)
                ->where('teacher_id', $teacher->id)
                ->max('round') ?? 1;
            
            $validated['teacher_id'] = $teacher->id;
            $validated['student_name'] = $validated['student_name'] ?? $student->name;
            $validated['n_value'] = $nValue;
            $validated['total_hours'] = $currentDuration;
            $validated['income'] = $income;
            $validated['round'] = $currentRound;
            
            $course = Course::create($validated);
        } else {
            // Calculate income based on teacher's hourly rate
            $income = $currentDuration * $teacher->hourly_rate;
            
            // Check if lesson exceeds package limit
            $remainingInPackage = $student->package_number - $previousNValue;
            $exceedsPackage = $currentDuration > $remainingInPackage && $remainingInPackage > 0;
            
            if ($exceedsPackage) {
                // Split the lesson: one completes the package, one is pending from new package
                $hoursToComplete = $remainingInPackage;
                $hoursPending = $currentDuration - $remainingInPackage;
                
                // Convert hours to hours and minutes
                $completeHours = floor($hoursToComplete);
                $completeMinutes = round(($hoursToComplete - $completeHours) * 60);
                
                $pendingHours = floor($hoursPending);
                $pendingMinutes = round(($hoursPending - $pendingHours) * 60);
                
                // Get current round for this student
                $currentRound = Course::where('student_id', $student->id)
                    ->where('teacher_id', $teacher->id)
                    ->max('round') ?? 1;
                
                // First course: completes the package
                $nValueComplete = $previousNValue + $hoursToComplete;
                $incomeComplete = $hoursToComplete * $teacher->hourly_rate;
                
                $completeCourseData = $validated;
                $completeCourseData['teacher_id'] = $teacher->id;
                $completeCourseData['student_name'] = $validated['student_name'] ?? $student->name;
                $completeCourseData['duration_hours'] = $completeHours;
                $completeCourseData['duration_minutes'] = $completeMinutes;
                $completeCourseData['total_hours'] = $hoursToComplete;
                $completeCourseData['n_value'] = $nValueComplete;
                $completeCourseData['income'] = $incomeComplete;
                $completeCourseData['admin_status'] = 'approved';
                $completeCourseData['round'] = $currentRound;
                
                $course = Course::create($completeCourseData);
                
                // Second course: pending from new package (will get new round when activated)
                $pendingCourseData = $validated;
                $pendingCourseData['teacher_id'] = $teacher->id;
                $pendingCourseData['student_name'] = $validated['student_name'] ?? $student->name;
                $pendingCourseData['duration_hours'] = $pendingHours;
                $pendingCourseData['duration_minutes'] = $pendingMinutes;
                $pendingCourseData['total_hours'] = $hoursPending;
                $pendingCourseData['n_value'] = 0; // Will be recalculated when package is activated
                $pendingCourseData['income'] = $hoursPending * $teacher->hourly_rate;
                $pendingCourseData['admin_status'] = 'pending';
                $pendingCourseData['status'] = 'Present'; // Use Present status, admin_status='pending' indicates pending
                $pendingCourseData['round'] = 0; // Will be set to new round when package is activated
                
                Course::create($pendingCourseData);
                
                // Set payment status to "EN ATTENTE DE PAYEMENT"
                $waitingPaymentStatus = PaymentStatus::where('name', 'EN ATTENTE DE PAYEMENT')->first();
                if ($waitingPaymentStatus) {
                    $student->payment_status_id = $waitingPaymentStatus->id;
                    $student->save();
                }
                
                $nValue = $nValueComplete;
            } elseif ($packageLimitReached && $hasPackageNotification) {
                // Package limit reached and notification exists - create as pending
                $validated['teacher_id'] = $teacher->id;
                $validated['student_name'] = $validated['student_name'] ?? $student->name;
                $validated['n_value'] = 0; // Will be recalculated when package is activated
                $validated['total_hours'] = $currentDuration;
                $validated['income'] = $income;
                $validated['admin_status'] = 'pending';
                $validated['status'] = 'Present'; // Use Present status, admin_status='pending' indicates pending
                $validated['round'] = 0; // Will be set to new round when package is activated
                
                $course = Course::create($validated);
                
                // Set payment status to "EN ATTENTE DE PAYEMENT"
                $waitingPaymentStatus = PaymentStatus::where('name', 'EN ATTENTE DE PAYEMENT')->first();
                if ($waitingPaymentStatus) {
                    $student->payment_status_id = $waitingPaymentStatus->id;
                    $student->save();
                }
                
                $nValue = null; // Not set for pending courses
            } elseif ($packageLimitReached) {
                // Package limit reached but no notification - add to waiting list
                $waitingListData = [
                    'teacher_id' => $teacher->id,
                    'student_id' => $student->id,
                    'student_name' => $validated['student_name'] ?? $student->name,
                    'name' => $validated['name'],
                    'course_date' => $validated['course_date'],
                    'class_time' => $validated['class_time'],
                    'duration_hours' => $validated['duration_hours'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'total_hours' => $currentDuration,
                    'course_type' => $validated['course_type'],
                    'status' => $validated['status'],
                    'admin_status' => 'approved',
                    'homework' => $validated['homework'] ?? null,
                    'evaluation_id' => $validated['evaluation_id'] ?? null,
                    'content' => $validated['content'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'souvenir_image' => $validated['souvenir_image'] ?? null,
                    'income' => $income,
                    'is_recurring' => false,
                    'recurring_course_id' => null,
                ];
                
                WaitingList::create($waitingListData);
                
                // Set payment status to "EN ATTENTE DE PAYEMENT"
                $waitingPaymentStatus = PaymentStatus::where('name', 'EN ATTENTE DE PAYEMENT')->first();
                if ($waitingPaymentStatus) {
                    $student->payment_status_id = $waitingPaymentStatus->id;
                    $student->save();
                }
                
                return redirect()->route('teacher.dashboard')
                                ->with('success', 'Lesson added to waiting list. Package limit reached.');
            } else {
                // Normal lesson - calculate n_value and create course
                $nValue = $previousNValue + $currentDuration;
                
                $validated['teacher_id'] = $teacher->id;
                $validated['student_name'] = $validated['student_name'] ?? $student->name;
                $validated['n_value'] = $nValue;
                $validated['total_hours'] = $currentDuration;
                $validated['income'] = $income;
                $validated['admin_status'] = 'approved';
                
                $course = Course::create($validated);
            }
        }
        
        // Create notification for absence if status is Absent (so admin can decide to calculate or not)
        if (isset($course) && $validated['status'] === 'Absent') {
            $message = $student->name . ($validated['notes'] ? ' ' . $validated['notes'] : ' did not attend');
            Notification::create([
                'type' => 'absence_approval',
                'course_id' => $course->id,
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'message' => $message,
                'is_read' => false,
                'is_approved' => null,
            ]);
        }
        
        // Check if package is completed (n_value >= package_number) - only for non-absent lessons
        if (isset($course) && isset($nValue) && $nValue >= $student->package_number && !$packageLimitReached && $validated['status'] !== 'Absent') {
            $message = $student->name . ' has completed the course !!';
            Notification::create([
                'type' => 'progress_update',
                'course_id' => $course->id,
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'message' => $message,
                'is_read' => false,
                'is_approved' => null,
            ]);
        }
        
        // Check if generate report was requested
        if (isset($course) && $request->has('generate_report')) {
            return redirect()->route('teacher.courses')
                            ->with('success', 'Course created and report generated!');
        }
        
        if (isset($course)) {
            return redirect()->route('teacher.dashboard')
                            ->with('success', 'Course created successfully!');
        }
        
        // This should not be reached, but just in case
        return redirect()->route('teacher.dashboard')
                        ->with('success', 'Operation completed successfully!');
    }

    public function editCourse(Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $students = Student::where('teacher_id', $teacher->id)->get();
        $evaluations = Evaluation::active()->ordered()->get();
        $subjects = Subject::all();
        
        return view('teacher.courses.edit', compact('course', 'students', 'evaluations', 'subjects'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $validated = $request->validate([
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
        
        // Handle image upload
        if ($request->hasFile('souvenir_image')) {
            // Delete old image if exists
            if ($course->souvenir_image) {
                Storage::disk('public')->delete($course->souvenir_image);
            }
            
            $image = $request->file('souvenir_image');
            $imagePath = $image->store('souvenirs', 'public');
            $validated['souvenir_image'] = $imagePath;
        }
        
        // Get student info
        $student = Student::findOrFail($validated['student_id']);
        
        // Calculate n_value based on package_number and previous lessons (excluding current course)
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('teacher_id', $teacher->id)
                               ->where('id', '!=', $course->id) // Exclude current course
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
        
        $course->update($validated);
        
        // Recalculate all n_values for this student to ensure consistency
        $this->recalculateNValues($student->id, $teacher->id);
        
        // Check if generate report was requested
        if ($request->has('generate_report')) {
            return redirect()->route('teacher.courses.report', $course)
                            ->with('success', 'Course updated and report generated!');
        }
        
        return redirect()->route('teacher.dashboard')
                        ->with('success', 'Course updated successfully!');
    }

    public function destroyCourse(Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $studentId = $course->student_id;
        $course->delete();
        
        // Recalculate all n_values for this student to ensure consistency
        $this->recalculateNValues($studentId, $teacher->id);
        
        return redirect()->route('teacher.courses')
                        ->with('success', 'Course deleted successfully!');
    }

    /**
     * Recalculate n_values for all courses of a specific student
     * This method ensures the cumulative calculation is correct
     */
    public function recalculateNValues($studentId, $teacherId)
    {
        // Get all courses for this student and teacher, ordered by creation date
        $courses = Course::where('student_id', $studentId)
                        ->where('teacher_id', $teacherId)
                        ->orderBy('created_at', 'asc')
                        ->get();
        
        $cumulativeValue = 0;
        
        foreach ($courses as $course) {
            $duration = $course->duration_hours + ($course->duration_minutes / 60.0);
            $cumulativeValue += $duration;
            
            // Update the n_value
            $course->update(['n_value' => $cumulativeValue]);
        }
    }

    /**
     * Generate report for a course
     */
    public function generateReport(Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $course->load(['student', 'evaluation', 'teacher']);
        
        return view('teacher.courses.report', compact('course'));
    }

    /**
     * Display timetable page
     */
    public function timetable(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Get week parameter from query string, default to current week
        // Week starts on Saturday
        $weekStart = $request->get('week', Carbon::now()->format('Y-m-d'));
        $weekStart = Carbon::parse($weekStart);
        
        // Find the Saturday of the current week
        while ($weekStart->dayOfWeek !== Carbon::SATURDAY) {
            $weekStart->subDay();
        }
        $weekStart->startOfDay();
        
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();
        
        // Get students and subjects for forms
        $students = Student::where('teacher_id', $teacher->id)->get();
        $subjects = Subject::all();
        $evaluations = Evaluation::active()->ordered()->get();
        
        return view('teacher.timetable', compact('weekStart', 'weekEnd', 'students', 'subjects', 'evaluations'));
    }

    /**
     * Get timetable events for a date range (AJAX)
     */
    public function getTimetableEvents(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $startDate = $request->get('start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end', Carbon::now()->endOfWeek()->format('Y-m-d'));
        
        $courses = Course::where('teacher_id', $teacher->id)
            ->whereBetween('course_date', [$startDate, $endDate])
            ->with(['student', 'recurringCourse'])
            ->get();
        
        $events = [];
        foreach ($courses as $course) {
            $durationHours = $course->duration_hours + ($course->duration_minutes / 60.0);
            $events[] = [
                'id' => $course->id,
                'title' => $course->student_name . ($course->is_recurring ? ' (recurrent)' : ''),
                'student_name' => $course->student_name,
                'course_type' => $course->course_type,
                'date' => $course->course_date->format('Y-m-d'),
                'time' => Carbon::parse($course->class_time)->format('H:i'),
                'duration' => $course->duration_hours . 'h' . ($course->duration_minutes > 0 ? ' ' . $course->duration_minutes . 'm' : ''),
                'duration_hours' => $durationHours,
                'status' => $course->status,
                'is_recurring' => $course->is_recurring,
                'recurring_course_id' => $course->recurring_course_id,
            ];
        }
        
        return response()->json($events);
    }

    /**
     * Store a recurring course template
     */
    public function storeRecurringCourse(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'student_name' => 'nullable|string|max:255',
            'course_type' => 'required|string|max:255',
            'class_time' => 'required',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'duration_hours' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0|max:59',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'recurrence_type' => 'required|in:weekly,weeks_count,months_count,endless',
            'recurrence_value' => 'nullable|integer|min:1|required_if:recurrence_type,weeks_count,months_count',
        ]);
        
        // Ensure student belongs to teacher
        $student = Student::findOrFail($validated['student_id']);
        if ($student->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated['teacher_id'] = $teacher->id;
        $validated['student_name'] = $validated['student_name'] ?? $student->name;
        
        $recurringCourse = RecurringCourse::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Recurring course created successfully',
            'id' => $recurringCourse->id,
        ]);
    }

    /**
     * Generate Course instances from recurring course template
     */
    public function generateRecurringEvents(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $validated = $request->validate([
            'recurring_course_id' => 'required|exists:recurring_courses,id',
            'until_date' => 'nullable|date',
        ]);
        
        $recurringCourse = RecurringCourse::findOrFail($validated['recurring_course_id']);
        
        // Ensure recurring course belongs to teacher
        if ($recurringCourse->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $untilDate = $validated['until_date'] ?? null;
        $count = $recurringCourse->generateInstances($untilDate);
        
        return response()->json([
            'success' => true,
            'message' => "Generated {$count} course instances",
            'count' => $count,
        ]);
    }

    /**
     * Delete a recurring course template
     */
    public function destroyRecurringCourse($id)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $recurringCourse = RecurringCourse::findOrFail($id);
        
        // Ensure recurring course belongs to teacher
        if ($recurringCourse->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $recurringCourse->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Recurring course deleted successfully',
        ]);
    }

    public function getStudentRounds(Student $student)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure student belongs to this teacher
        if ($student->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to student.');
        }
        
        // Get all rounds for this student with their courses
        $rounds = Course::where('student_id', $student->id)
            ->where('teacher_id', $teacher->id)
            ->select('round', DB::raw('COUNT(*) as courses_count'), DB::raw('MIN(course_date) as start_date'), DB::raw('MAX(course_date) as end_date'))
            ->groupBy('round')
            ->orderBy('round', 'desc')
            ->get()
            ->map(function($round) use ($student, $teacher) {
                $courses = Course::where('student_id', $student->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('round', $round->round)
                    ->orderBy('course_date', 'asc')
                    ->orderBy('class_time', 'asc')
                    ->get();
                
                return [
                    'round' => $round->round,
                    'courses_count' => $round->courses_count,
                    'start_date' => $round->start_date,
                    'end_date' => $round->end_date,
                    'courses' => $courses->map(function($course) {
                        return [
                            'id' => $course->id,
                            'name' => $course->name,
                            'course_date' => $course->course_date->format('Y-m-d'),
                            'class_time' => $course->class_time ? \Carbon\Carbon::parse($course->class_time)->format('H:i') : null,
                            'course_type' => $course->course_type,
                            'duration_hours' => $course->duration_hours,
                            'duration_minutes' => $course->duration_minutes,
                            'total_hours' => $course->total_hours,
                            'status' => $course->status,
                            'n_value' => $course->n_value,
                        ];
                    })
                ];
            });
        
        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'package_number' => $student->package_number,
            ],
            'rounds' => $rounds
        ]);
    }
}
