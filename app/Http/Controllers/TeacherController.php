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
use Spatie\LaravelPdf\Facades\Pdf;
use App\Services\WhatsAppService;
use Spatie\Browsershot\Browsershot;

class TeacherController extends Controller
{
    /**
     * Configure a Browsershot instance with proper Node.js, npm, and Chrome paths.
     * Centralizes all path configuration to avoid "npm: command not found" errors.
     */
    private function configureBrowsershot(Browsershot $browsershot): Browsershot
    {
        // Node.js binary
        $nodeBinary = config('laravel-pdf.browsershot.node_binary') ?: env('LARAVEL_PDF_NODE_BINARY');
        if (!$nodeBinary || !file_exists($nodeBinary)) {
            $possiblePaths = [
                getenv('HOME') . '/nodejs/bin/node',
                '/opt/homebrew/bin/node',
                '/usr/local/bin/node',
                '/usr/bin/node',
            ];
            foreach ($possiblePaths as $path) {
                if (file_exists($path) && is_executable($path)) {
                    $nodeBinary = $path;
                    break;
                }
            }
        }
        if ($nodeBinary && file_exists($nodeBinary)) {
            $browsershot->setNodeBinary($nodeBinary);
        }

        // npm binary
        $npmBinary = config('laravel-pdf.browsershot.npm_binary') ?: env('LARAVEL_PDF_NPM_BINARY');
        if (!$npmBinary || !file_exists($npmBinary)) {
            $possiblePaths = [
                getenv('HOME') . '/nodejs/bin/npm',
                '/opt/homebrew/bin/npm',
                '/usr/local/bin/npm',
                '/usr/bin/npm',
            ];
            foreach ($possiblePaths as $path) {
                if (file_exists($path) && is_executable($path)) {
                    $npmBinary = $path;
                    break;
                }
            }
        }
        if ($npmBinary && file_exists($npmBinary)) {
            $browsershot->setNpmBinary($npmBinary);
        }

        // Node module path — setting this bypasses the `npm root -g` shell call entirely
        $nodeModulePath = config('laravel-pdf.browsershot.node_modules_path') ?: base_path('node_modules');
        if (is_dir($nodeModulePath)) {
            $browsershot->setNodeModulePath($nodeModulePath);
        }

        // Chrome executable path (prefer chrome-headless-shell for server environments)
        $chromePath = config('laravel-pdf.browsershot.chrome_path') ?: env('LARAVEL_PDF_CHROME_PATH');
        if (!$chromePath || !file_exists($chromePath)) {
            // Try to find Chrome/chrome-headless-shell in common locations
            $possibleChromePaths = [
                '/var/www/.cache/puppeteer/chrome-headless-shell/linux-145.0.7632.46/chrome-headless-shell-linux64/chrome-headless-shell',
                '/var/www/.cache/puppeteer/chrome/linux-145.0.7632.46/chrome-linux64/chrome',
                '/root/.cache/puppeteer/chrome-headless-shell/linux-145.0.7632.46/chrome-headless-shell-linux64/chrome-headless-shell',
                '/root/.cache/puppeteer/chrome/linux-145.0.7632.46/chrome-linux64/chrome',
                '/usr/bin/google-chrome',
                '/usr/bin/chromium-browser',
                '/usr/bin/chromium',
            ];
            foreach ($possibleChromePaths as $path) {
                if (file_exists($path) && is_executable($path)) {
                    $chromePath = $path;
                    break;
                }
            }
        }
        if ($chromePath && file_exists($chromePath)) {
            $browsershot->setChromePath($chromePath);
        }

        // Set Puppeteer cache directory environment variable
        $puppeteerCacheDir = env('PUPPETEER_CACHE_DIR', '/var/www/.cache/puppeteer');
        if (is_dir($puppeteerCacheDir)) {
            putenv('PUPPETEER_CACHE_DIR=' . $puppeteerCacheDir);
        }

        // Include nodejs/bin in the shell PATH so any sub-processes can find node/npm
        $nodeBinDir = dirname($nodeBinary ?: '/usr/bin/node');
        $browsershot->setIncludePath('$PATH:/usr/local/bin:/opt/homebrew/bin:' . $nodeBinDir);

        // Sandbox-safe Chrome args
        $browsershot->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox']);

        return $browsershot;
    }

    public function dashboard(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Build filtered query (same filters as table)
        $query = Course::where('teacher_id', $teacher->id)
                      ->with(['student', 'evaluation', 'student.paymentStatus']);
        
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
        
        // Date filter (single date)
        if ($request->filled('date')) {
            $query->whereDate('course_date', $request->date);
        }
        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('course_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('course_date', '<=', $request->date_to);
        }
        
        // Course type filter
        if ($request->filled('course_type')) {
            $query->where('course_type', $request->course_type);
        }

        // Check if any specific date filter is active
    $hasDateFilter = $request->filled('month_year') || $request->filled('date') || $request->filled('date_from') || $request->filled('date_to');

    // Top stat cards: reflect current search/filters, defaulting to current month if no date filter
    $filteredCourses = (clone $query)->get();
    
    if (!$hasDateFilter) {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $statsCourses = $filteredCourses->filter(function($c) use ($currentMonth, $currentYear) {
            $date = \Carbon\Carbon::parse($c->course_date);
            return $date->month == $currentMonth && $date->year == $currentYear;
        });
    } else {
        $statsCourses = $filteredCourses;
    }

    $studentsCount = $statsCourses->pluck('student_id')->unique()->filter()->count();
    $thisMonthHours = round($statsCourses->sum(function ($c) {
        return $c->duration_hours + ($c->duration_minutes / 60.0);
    }), 1);
    $thisMonthRevenue = $statsCourses->sum('income');

    $totalFiltered = $statsCourses->count();
    $presentFiltered = $statsCourses->where('status', 'Present')->count();
    $attendanceRate = $totalFiltered > 0 ? ($presentFiltered / $totalFiltered) * 100 : 0;
    $progressSum = 0;
    $progressCount = 0;
    foreach ($statsCourses->groupBy('student_id') as $studentId => $studentCourses) {
            $student = Student::find($studentId);
            if (!$student || $student->package_number <= 0) {
                continue;
            }
            $maxN = $studentCourses->max('n_value');
            $progressSum += min(100, ($maxN / $student->package_number) * 100);
            $progressCount++;
        }
        $avgProgress = $progressCount > 0 ? ($progressSum / $progressCount) : 0;
        $teacherPerformanceLevel = round($attendanceRate * 0.5 + $avgProgress * 0.5, 2);

        $weeklyData = [];
        $maxWeekCourses = 0;
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $weekCourses = $filteredCourses->filter(function ($c) use ($weekStart, $weekEnd) {
                $d = \Carbon\Carbon::parse($c->course_date);
                return $d->between($weekStart, $weekEnd);
            })->count();
            $maxWeekCourses = max($maxWeekCourses, $weekCourses);
        }
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $weekCourses = $filteredCourses->filter(function ($c) use ($weekStart, $weekEnd) {
                $d = \Carbon\Carbon::parse($c->course_date);
                return $d->between($weekStart, $weekEnd);
            })->count();
            $weeklyData[] = [
                'label' => $weekStart->format('d/m'),
                'value' => $maxWeekCourses > 0 ? ($weekCourses / $maxWeekCourses) * 100 : 0
            ];
        }

        $perPage = $request->get('per_page', 100);
        if ($perPage === 'all') {
            $perPage = 999999;
        } else {
            $perPage = (int) $perPage ?: 100;
        }
        $courses = $query->orderByRaw('CASE WHEN round = 0 THEN 999999 ELSE round END ASC')
                        
                        ->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate($perPage);
        
        // Get students and subjects for filters
        $students = Student::where('teacher_id', $teacher->id)->get();
        
        // Ensure all students have colors assigned
        foreach ($students as $student) {
            $student->ensureColor();
        }
        
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
                      ->with(['student', 'evaluation', 'student.paymentStatus']);
        
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
        
        // Set default month to current month if NO date filters are requested
    $hasAnyDateFilter = $request->filled('date_from') || $request->filled('date_to') || $request->has('month_year');
    if (!$hasAnyDateFilter) {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $request->merge(['month_year' => $currentMonth . '-' . $currentYear]);
    }

    if ($request->filled('month_year')) {
        [$month, $year] = explode('-', $request->month_year);
        $query->whereMonth('course_date', $month)->whereYear('course_date', $year);
    }
    
    if ($request->filled('date_from')) {
        $query->whereDate('course_date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('course_date', '<=', $request->date_to);
    }

    // Calculate overall statistics for the bottom section before paginating
    $clonedQuery = clone $query;
    $allFilteredCourses = $clonedQuery->get();
    
    $totalCoursesCount = $allFilteredCourses->count();
    $totalCoursesHours = $allFilteredCourses->sum(function($c) {
        return $c->total_hours ?: ($c->duration_hours + ($c->duration_minutes / 60));
    });
    $totalCoursesIncome = $allFilteredCourses->sum('income');

        $perPage = $request->get('per_page', 100);
        if ($perPage === 'all') {
            $perPage = 999999;
        } else {
            $perPage = (int) $perPage ?: 100;
        }
        $courses = $query->orderByRaw('CASE WHEN round = 0 THEN 999999 ELSE round END ASC')
                        
                        ->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->paginate($perPage);

        // Get students for filter dropdown
        $students = Student::where('teacher_id', $teacher->id)->get();
        
        // Ensure all students have colors assigned
        foreach ($students as $student) {
            $student->ensureColor();
        }
        
        // Get subjects for course type filter
        $subjects = Subject::all();
        $evaluations = Evaluation::active()->ordered()->get();
        
        return view('teacher.courses', compact(
            'courses', 
            'students', 
            'subjects', 
            'evaluations',
            'totalCoursesCount',
            'totalCoursesHours',
            'totalCoursesIncome'
        ));
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
      try {
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
            // Only allow Present, Absent, or Free from the UI
            'status' => 'required|in:Present,Absent,Free',
            'homework' => 'nullable|string',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
            'souvenir_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:65536',
        ]);
        
        // Get student info
        $student = Student::findOrFail($validated['student_id']);
        
        // Handle image upload
        if ($request->hasFile('souvenir_image')) {
            $image = $request->file('souvenir_image');
            $imagePath = $image->store('souvenirs', 'public');
            $validated['souvenir_image'] = $imagePath;
        }
        
        // Get current active round for this student (student-scoped, not teacher-scoped)
        $currentRound = Course::where('student_id', $student->id)
            ->where('round', '>', 0)
            ->max('round') ?? 1;
        
        // Calculate n_value based on package_number and previous lessons IN THE CURRENT ROUND
        // Student-scoped: counts hours across ALL teachers, not just the current one
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('round', $currentRound)
                               ->where('name', '!=', '0')
                               ->where('name', '!=', '0.0')
                               ->where('admin_status', '!=', 'pending')
                               
                               ->first();
        
        $previousNValue = $previousLessons ? $previousLessons->n_value : 0;
        $currentDuration = $validated['duration_hours'] + ($validated['duration_minutes'] / 60.0);
        
        $currentDuration = $validated['duration_hours'] + ($validated['duration_minutes'] / 60.0);
        
        // Income is 0 for Free, otherwise based on duration & rate
        $income = ($validated['status'] === 'Free') ? 0 : ($currentDuration * $teacher->hourly_rate);
        
        // Auto-approve pending Absent courses if a new non-Absent, non-Free (so "Present") course is added
        if ($validated['status'] !== 'Absent' && $validated['status'] !== 'Free') {
            // Student-scoped: auto-approve absences regardless of which teacher submitted them
            $pendingAbsents = Course::where('student_id', $student->id)
                ->where('admin_status', 'pending')
                ->where('status', 'Absent')
                ->orderBy('course_date', 'asc')
                ->orderBy('class_time', 'asc')
                ->get();
            
            if ($pendingAbsents->isNotEmpty()) {
                foreach ($pendingAbsents as $pendingAbsent) {
                    // Auto-approve using the shared package-aware logic
                    AdminController::processAbsenceApproval($pendingAbsent);
                    
                    // Mark the absence notification as auto-approved
                    $absenceNotification = Notification::where('course_id', $pendingAbsent->id)
                        ->where('type', 'absence_approval')
                        ->where('is_read', false)
                        ->first();
                    
                    if ($absenceNotification) {
                        $absenceNotification->is_approved = true;
                        $absenceNotification->is_read = true;
                        $absenceNotification->save();
                    }
                    
                    // Create auto-approval notification for admin
                    $autoApproveDate = $pendingAbsent->course_date ? $pendingAbsent->course_date->format('Y-m-d') : 'N/A';
                    $autoApproveTime = $pendingAbsent->class_time ? \Carbon\Carbon::parse($pendingAbsent->class_time)->format('H:i') : 'N/A';
                    $autoMessage = 'System auto-approved absent class on ' . $autoApproveDate 
                        . ' at ' . $autoApproveTime 
                        . ' for ' . $student->name 
                        . ' (Teacher: ' . $teacher->name . ')'
                        . ' because a new Present course was added.';
                    
                    Notification::create([
                        'type' => 'auto_approval',
                        'course_id' => $pendingAbsent->id,
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'message' => $autoMessage,
                        'is_read' => false,
                        'is_approved' => true,
                    ]);
                }
                
                // Recalculate currentRound after auto-approvals might have pushed limits
                $currentRound = Course::where('student_id', $student->id)
                    ->where('round', '>', 0)
                    ->max('round') ?? 1;
            }
        }
        
        // Prepare base course data
        $validated['teacher_id'] = $teacher->id;
        $validated['student_name'] = $validated['student_name'] ?? $student->name;
        $validated['total_hours'] = $currentDuration;
        $validated['round'] = $currentRound;
        $validated['n_value'] = 0; // Temp, recalculated later
        $validated['name'] = 'TBD'; // Temp, recalculated later
        $validated['package_limit'] = $student->package_number;
        $validated['income'] = $income;
        
        // Present and Free courses are auto-approved. Absent courses remain pending for admin approval.
        $validated['admin_status'] = ($validated['status'] === 'Absent') ? 'pending' : 'approved';
        
        // For Absent courses, we still want to log the zero name temporarily until recalculation
        if ($validated['status'] === 'Absent') {
            $validated['name'] = '0';
        }
        
        // Save the generic course
        $course = Course::create($validated);
        
        // Recalculate chronologically (this assigns n_value, name, handles limit splits, and overflow)
        app(\App\Http\Controllers\AdminController::class)->recalculateSequenceAndLimits($student->id, $teacher->id, $currentRound);
        
        // Refresh the course to get its true final state
        $course = $course->fresh();
        
        // Create notification for absence if status is Absent (so admin can approve)
        if ($validated['status'] === 'Absent') {
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
        
        // If the entire course was pushed to overflow (round 0)
        if ($course->round == 0 && $course->admin_status == 'pending' && $validated['status'] !== 'Absent') {
            $msg = 'Lesson added to waiting list. Package limit reached.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('teacher.dashboard')->with('success', $msg);
        }
        
        // Generate and send report via WhatsApp for any course
        if ($request->input('send_whatsapp') && $course->admin_status !== 'pending') {
            $this->generateAndSendReport($course);
        }
        
        $successMsg = $request->input('send_whatsapp') 
            ? 'Course created successfully! Report image has been sent to student\'s WhatsApp.'
            : 'Course created successfully!';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }
        
        return redirect()->back()->with('success', $successMsg);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Operation completed successfully!']);
        }
        return redirect()->route('teacher.dashboard')
                        ->with('success', 'Operation completed successfully!');
      } catch (\Illuminate\Validation\ValidationException $e) {
          if ($request->ajax() || $request->wantsJson() || $request->has('send_whatsapp')) {
              return response()->json(['errors' => $e->errors()], 422);
          }
          throw $e;
      } catch (\Exception $e) {
          if ($request->ajax() || $request->wantsJson() || $request->has('send_whatsapp')) {
              return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
          }
          throw $e;
      }
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

    public function getCourseData(Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $course->load(['student', 'evaluation']);
        return response()->json([
            'success' => true,
            'course' => [
                'id' => $course->id,
                'student_id' => $course->student_id,
                'student_name' => $course->student->name ?? $course->student_name,
                'name' => $course->name,
                'course_date' => $course->course_date->format('Y-m-d'),
                'class_time' => \Carbon\Carbon::parse($course->class_time)->format('H:i'),
                'duration_hours' => $course->duration_hours,
                'duration_minutes' => $course->duration_minutes,
                'course_type' => $course->course_type,
                'status' => $course->status,
                'homework' => $course->homework,
                'evaluation_id' => $course->evaluation_id,
                'content' => $course->content,
                'notes' => $course->notes,
                'souvenir_image' => $course->souvenir_image,
            ],
        ]);
    }

    public function updateCourse(Request $request, Course $course)
    {
      try {
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
            // Only allow Present, Absent, or Free from the UI
            'status' => 'required|in:Present,Absent,Free',
            'homework' => 'nullable|string',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
            'souvenir_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:65536',
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
        
        // Calculate n_value based on package_number and previous lessons IN THE SAME ROUND
        // Student-scoped: counts across ALL teachers in the same round
        $previousLessons = Course::where('student_id', $student->id)
                               ->where('round', $course->round)
                               ->where('id', '!=', $course->id)
                               ->where('name', '!=', '0')
                               ->where('name', '!=', '0.0')
                               ->where('admin_status', '!=', 'pending')
                               
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
        
        // If course status transitioned to Absent, set it to pending so admin can approve
        $becameAbsent = ($course->status !== 'Absent' && $validated['status'] === 'Absent');
        if ($becameAbsent) {
            $validated['admin_status'] = 'pending';
            $validated['name'] = '0';
            $validated['n_value'] = 0;
            $validated['income'] = 0;
        } elseif ($course->status === 'Absent' && $validated['status'] !== 'Absent') {
            // Transitioned FROM absent TO present/free
            $validated['admin_status'] = 'approved';
        }
        
        $course->update($validated);
        
        if ($becameAbsent) {
            $message = $student->name . (!empty($validated['notes']) ? ' ' . $validated['notes'] : ' did not attend');
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
        
        // Recalculate sequence and limits to ensure consistency across the whole sequence
        app(\App\Http\Controllers\AdminController::class)->recalculateSequenceAndLimits($student->id, $teacher->id, $course->round);
        
        // Conditionally generate and send report via WhatsApp
        if ($request->input('send_whatsapp')) {
            $this->generateAndSendReport($course);
        }
        
        $successMsg = $request->input('send_whatsapp') 
            ? 'Course updated successfully! Report image has been sent to student\'s WhatsApp.'
            : 'Course updated successfully!';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }
        
        return redirect()->back()
                        ->with('success', $successMsg);
      } catch (\Illuminate\Validation\ValidationException $e) {
          if ($request->ajax() || $request->wantsJson() || $request->has('send_whatsapp')) {
              return response()->json(['errors' => $e->errors()], 422);
          }
          throw $e;
      } catch (\Exception $e) {
          if ($request->ajax() || $request->wantsJson() || $request->has('send_whatsapp')) {
              return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
          }
          throw $e;
      }
    }

    public function destroyCourse(Course $course)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $studentId = $course->student_id;
        
        $course->update([
            'deleted_by_type' => get_class($teacher),
            'deleted_by_id' => $teacher->id
        ]);
        
        $course->delete();
        
        // Recalculate all n_values for this student to ensure consistency
        $this->recalculateNValues($studentId);
        
        return redirect()->back()
                        ->with('success', 'Course deleted successfully!');
    }

    /**
     * Recalculate n_values for all courses of a specific student
     * Student-scoped: correct per round across ALL teachers
     */
    public function recalculateNValues($studentId)
    {
        // Get all courses for this student ordered by round, date, time, created_at
        $courses = Course::where('student_id', $studentId)
                        ->orderBy('round', 'asc')
                        ->orderBy('course_date', 'asc')
                        ->orderBy('class_time', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->get();
        
        // Group courses by round and recalculate per round
        $coursesByRound = $courses->groupBy('round');
        
        foreach ($coursesByRound as $round => $roundCourses) {
            if ($round == 0) {
                // Round 0 = pending overflow courses, keep n_value as 0
                foreach ($roundCourses as $course) {
                    if ($course->n_value != 0) {
                        $course->update(['n_value' => 0]);
                    }
                }
                continue;
            }
            
            $cumulativeValue = 0;
            $lessonNumber = 0;
            
            foreach ($roundCourses as $course) {
                $duration = $course->duration_hours + ($course->duration_minutes / 60.0);
                
                if ($course->admin_status === 'pending' || $course->admin_status === 'rejected') {
                    // Unapproved/rejected absences: keep n_value at current cumulative (don't add duration)
                    $course->update(['n_value' => $cumulativeValue]);
                } elseif ($course->status === 'Free') {
                    // Free lessons never consume package hours
                    $course->update(['n_value' => $cumulativeValue]);
                } elseif ($course->name === '0.0') {
                    // Unpaid beyond-limit lessons: don't contribute to cumulative n_value
                    $course->update(['n_value' => $cumulativeValue]);
                } else {
                    // Normal/approved courses (including split courses with name='0' or 'Course'):
                    // add duration to cumulative and assign proper sequential lesson name
                    $cumulativeValue += $duration;
                    $lessonNumber++;
                    
                    $updateData = ['n_value' => $cumulativeValue];
                    
                    // Always assign the correct sequential lesson name
                    if ($course->name !== (string)$lessonNumber) {
                        $updateData['name'] = (string)$lessonNumber;
                    }
                    
                    $course->update($updateData);
                }
            }
        }
    }

    /**
     * Generate report for a course
     */
    public function generateReport(Course $course, Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Ensure the course belongs to this teacher
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to course.');
        }
        
        $course->load(['student.subject', 'evaluation', 'teacher']);
        
        // Generate PDF
        $pdf = Pdf::view('teacher.courses.report-pdf', ['course' => $course])
            ->format('a4');
        
        // If preview is requested, show in browser, otherwise download
        if ($request->has('preview')) {
            return $pdf->inline('rapport-cours-' . $course->id . '.pdf');
        }
        
        return $pdf->download('rapport-cours-' . $course->id . '.pdf');
    }

    /**
     * Download report as image for a course
     * This generates a high-quality PNG image of the report
     */
    public function downloadReportImage(Course $course)
    {
        // Check if user is admin or teacher
        $admin = Auth::guard('admin')->user();
        $teacher = Auth::guard('teacher')->user();
        
        // If admin, allow access to any course
        // If teacher, ensure the course belongs to this teacher
        if ($admin) {
            // Admin can access any course - no restriction
        } elseif ($teacher) {
            // Teacher can only access their own courses
            if ($course->teacher_id !== $teacher->id) {
                abort(403, 'Unauthorized access to course.');
            }
        } else {
            // Not authenticated as admin or teacher
            abort(403, 'Unauthorized access.');
        }
        
        $course->load(['student.subject', 'evaluation', 'teacher']);
        
        try {
            // Load HTML content with course data
            $html = view('teacher.courses.report-pdf', ['course' => $course])->render();
            
            // Use Browsershot with centralized configuration
            $browsershot = $this->configureBrowsershot(Browsershot::html($html));
            
            // Generate high-quality image (A4 size at 150dpi = 1240x1754)
            $imageContent = $browsershot->windowSize(1240, 1754)
                ->waitUntilNetworkIdle()
                ->delay(1000)
                ->dismissDialogs()
                ->setOption('captureBeyondViewport', true)
                ->screenshot();
            
            if (empty($imageContent)) {
                throw new \Exception('Image generation failed');
            }
            
            // Create filename with student name and date
            $studentName = str_replace(' ', '-', $course->student_name ?? $course->student->name ?? 'student');
            $date = $course->course_date->format('Y-m-d');
            $fileName = "rapport-{$studentName}-{$date}.png";
            
            // Return as download
            return response($imageContent)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Content-Length', strlen($imageContent));
                
        } catch (\Exception $e) {
            \Log::error('Report image generation failed', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to generate report image: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF report and send via WhatsApp
     * Public method so it can be called from AdminController as well
     */
    public function generateAndSendReport(Course $course)
    {
        try {
            // Load relationships if not already loaded
            if (!$course->relationLoaded('student')) {
                $course->load(['student.subject', 'evaluation', 'teacher']);
            }
            
            // Check if student exists and has phone number
            if (!$course->student) {
                \Log::warning('Cannot send report: Student not found', [
                    'course_id' => $course->id,
                    'student_id' => $course->student_id
                ]);
                return false;
            }
            
            if (!$course->student->phone) {
                \Log::warning('Cannot send report: Student has no phone number', [
                    'course_id' => $course->id,
                    'student_id' => $course->student_id,
                    'student_name' => $course->student->name
                ]);
                return false;
            }
            
            // Generate image directly using Browsershot (real browser rendering - perfect CSS support)
            try {
                // Load HTML content
                $html = view('teacher.courses.report-pdf', ['course' => $course])->render();
                
                // Use Browsershot with centralized configuration
                $browsershot = $this->configureBrowsershot(Browsershot::html($html));
                
                // Generate high-quality image (A4 at 150dpi = 1240x1754)
                $imageContent = $browsershot->windowSize(1240, 1754)
                    ->waitUntilNetworkIdle()
                    ->delay(1000)
                    ->dismissDialogs()
                    ->setOption('captureBeyondViewport', true)
                    ->screenshot();
                
                if (empty($imageContent)) {
                    throw new \Exception('Image generation failed - empty content');
                }
                
                // Create filename
                $fileName = 'rapport-cours-' . $course->id . '-' . time() . '.png';
                
                // Save to public storage directory (optional, for debugging)
                $savePath = storage_path('app/public/reports/' . $fileName);
                
                // Ensure reports directory exists
                if (!file_exists(storage_path('app/public/reports'))) {
                    mkdir(storage_path('app/public/reports'), 0755, true);
                }
                
                // Save image (optional, for debugging)
                file_put_contents($savePath, $imageContent);
                
            } catch (\Exception $error) {
                \Log::error('PDF/Image generation failed', [
                    'course_id' => $course->id,
                    'error' => $error->getMessage(),
                    'trace' => $error->getTraceAsString()
                ]);
                throw new \Exception('Failed to generate image: ' . $error->getMessage());
            }
            
            // Initialize WhatsApp service
            $whatsappService = new WhatsAppService();
            
            // Create caption message
            $caption = "📚 Rapport de cours - " . ($course->student_name ?? $course->student->name) . "\n";
            $caption .= "Date: " . $course->course_date->format('d/m/Y') . "\n";
            if ($course->evaluation) {
                $caption .= "Évaluation: " . $course->evaluation->name;
            }
            
            // Send image via WhatsApp (better UX - shows inline preview)
            $result = $whatsappService->sendImage(
                $course->student->phone,
                $imageContent,
                $fileName,
                $caption
            );
            
            if ($result['success']) {
                \Log::info('Report sent successfully via WhatsApp', [
                    'course_id' => $course->id,
                    'student_phone' => $course->student->phone
                ]);
                return true;
            } else {
                \Log::error('Failed to send report via WhatsApp', [
                    'course_id' => $course->id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Exception while generating and sending report', [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Test sending text message to WhatsApp - Public route for testing
     */
    public function testSendWhatsAppText(Request $request)
    {
        try {
            $whatsappService = new WhatsAppService();
            
            $phoneNumber = '+201207220414';
            $testMessage = "🧪 Test message from Azhary Academy System\n\nThis is a test to verify WhatsApp integration is working correctly.";
            
            $result = $whatsappService->sendText($phoneNumber, $testMessage);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Text message sent successfully to ' . $phoneNumber,
                    'details' => 'Please check your WhatsApp to confirm receipt',
                    'response' => $result['data'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send text message',
                    'error' => $result['error'] ?? 'Unknown error',
                    'full_response' => $result
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception occurred',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test sending PDF to WhatsApp - Public route for testing
     */
    public function testSendWhatsApp(Request $request)
    {
        // Create a mock course object with sample data
        $mockCourse = new Course();
        $mockCourse->id = 999;
        $mockCourse->student_name = 'Khadidiatou DRAME';
        $mockCourse->course_date = Carbon::parse('2026-01-31');
        $mockCourse->duration_hours = 0;
        $mockCourse->duration_minutes = 30;
        $mockCourse->status = 'Present';
        $mockCourse->content = 'Lire les lettres arabes avec la Prolongation Alif';
        $mockCourse->notes = 'Cora';
        $mockCourse->homework = 'Fait';
        $mockCourse->souvenir_image = null;
        
        // Create mock student with subject and phone
        $mockStudent = new Student();
        $mockStudent->id = 1;
        $mockStudent->name = 'Khadidiatou DRAME';
        $mockStudent->phone = '+201207220414'; // Test phone number
        
        $mockSubject = new Subject();
        $mockSubject->id = 1;
        $mockSubject->name = 'Arabe';
        $mockStudent->setRelation('subject', $mockSubject);
        
        // Set the student relation on the course
        $mockCourse->setRelation('student', $mockStudent);
        $mockCourse->student_id = 1;
        
        // Create mock evaluation
        $mockEvaluation = new Evaluation();
        $mockEvaluation->name = 'MashAllah';
        $mockEvaluation->description = '100%';
        $mockCourse->setRelation('evaluation', $mockEvaluation);
        
        // Generate and send report
        try {
            $result = $this->generateAndSendReport($mockCourse);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image report sent successfully to +201207220414',
                    'details' => 'Please check your WhatsApp to confirm receipt. The report should appear as an image.',
                    'note' => 'Check storage/logs/laravel.log for detailed WhatsApp API response'
                ]);
            } else {
                // Get last error from logs
                $logFile = storage_path('logs/laravel.log');
                $lastError = 'Unknown error';
                if (file_exists($logFile)) {
                    $lines = file($logFile);
                    $lastLines = array_slice($lines, -10);
                    foreach (array_reverse($lastLines) as $line) {
                        if (stripos($line, 'WhatsApp') !== false || stripos($line, 'error') !== false) {
                            $lastError = trim($line);
                            break;
                        }
                    }
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send PDF report',
                    'error' => $lastError,
                    'note' => 'Check storage/logs/laravel.log for full details'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception occurred',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test Image Generation - Public route for local testing
     * Generates and displays the image directly in browser
     */
    public function testGenerateImage(Request $request)
    {
        // Create a mock course object with sample data
        $mockCourse = new Course();
        $mockCourse->id = 999;
        $mockCourse->student_name = 'Khadidiatou DRAME';
        $mockCourse->course_date = Carbon::parse('2026-01-31');
        $mockCourse->duration_hours = 0;
        $mockCourse->duration_minutes = 30;
        $mockCourse->status = 'Present';
        $mockCourse->content = 'Lire les lettres arabes avec la Prolongation Alif';
        $mockCourse->notes = 'Cora';
        $mockCourse->homework = 'Fait';
        $mockCourse->souvenir_image = null;
        
        // Create mock student with subject
        $mockStudent = new Student();
        $mockStudent->name = 'Khadidiatou DRAME';
        
        $mockSubject = new Subject();
        $mockSubject->name = 'Arabe';
        $mockStudent->setRelation('subject', $mockSubject);
        
        $mockCourse->setRelation('student', $mockStudent);
        
        // Create mock evaluation
        $mockEvaluation = new Evaluation();
        $mockEvaluation->name = 'MashAllah';
        $mockEvaluation->description = '100%';
        $mockCourse->setRelation('evaluation', $mockEvaluation);
        
        try {
            // Load HTML content
            $html = view('teacher.courses.report-pdf', ['course' => $mockCourse])->render();
            
            // Use Browsershot with centralized configuration
            $browsershot = $this->configureBrowsershot(Browsershot::html($html));
            
            // Generate high-quality image (A4 at 150dpi = 1240x1754)
            $imageContent = $browsershot->windowSize(1240, 1754)
                ->waitUntilNetworkIdle()
                ->delay(1000)
                ->dismissDialogs()
                ->setOption('captureBeyondViewport', true)
                ->screenshot();
            
            if (empty($imageContent)) {
                return response('Image generation failed - empty content', 500);
            }
            
            // Display image directly in browser
            return response($imageContent, 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline; filename="test-report.png"');
                
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 500);
        }
    }

    /**
     * Test PDF Only - Public route to view PDF before image conversion
     */
    public function testGeneratePdfOnly(Request $request)
    {
        // Create a mock course object with sample data
        $mockCourse = new Course();
        $mockCourse->id = 999;
        $mockCourse->student_name = 'Khadidiatou DRAME';
        $mockCourse->course_date = Carbon::parse('2026-01-31');
        $mockCourse->duration_hours = 0;
        $mockCourse->duration_minutes = 30;
        $mockCourse->status = 'Present';
        $mockCourse->content = 'Lire les lettres arabes avec la Prolongation Alif';
        $mockCourse->notes = 'Cora';
        $mockCourse->homework = 'Fait';
        $mockCourse->souvenir_image = null;
        
        // Create mock student with subject
        $mockStudent = new Student();
        $mockStudent->name = 'Khadidiatou DRAME';
        
        $mockSubject = new Subject();
        $mockSubject->name = 'Arabe';
        $mockStudent->setRelation('subject', $mockSubject);
        
        $mockCourse->setRelation('student', $mockStudent);
        
        // Create mock evaluation
        $mockEvaluation = new Evaluation();
        $mockEvaluation->name = 'MashAllah';
        $mockEvaluation->description = '100%';
        $mockCourse->setRelation('evaluation', $mockEvaluation);
        
        try {
            // Load HTML content
            $html = view('teacher.courses.report-pdf', ['course' => $mockCourse])->render();
            
            // Use Browsershot with centralized configuration
            $browsershot = $this->configureBrowsershot(Browsershot::html($html));
            
            // Generate PDF with high quality
            $pdfContent = $browsershot->format('A4')
                ->margins(0, 0, 0, 0)
                ->waitUntilNetworkIdle()
                ->dismissDialogs()
                ->pdf();
            
            if (empty($pdfContent)) {
                return response('PDF content is empty', 500);
            }
            
            // Display PDF directly in browser
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="test-report.pdf"');
                
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 500);
        }
    }

    /**
     * Test PDF Report - Public route for testing
     * Creates a mock course object with sample data
     */
    public function testPdfReport(Request $request)
    {
        // Create a mock course object with sample data
        $mockCourse = new Course();
        $mockCourse->id = 999;
        $mockCourse->student_name = 'Khadidiatou DRAME';
        $mockCourse->course_date = Carbon::parse('2026-01-31');
        $mockCourse->duration_hours = 0;
        $mockCourse->duration_minutes = 30;
        $mockCourse->status = 'Present';
        $mockCourse->content = 'Lire les lettres arabes avec la Prolongation Alif';
        $mockCourse->notes = 'Cora';
        $mockCourse->homework = 'Fait';
        $mockCourse->souvenir_image = null; // Can be set to a test image path if needed
        
        // Create mock student with subject
        $mockStudent = new Student();
        $mockStudent->name = 'Khadidiatou DRAME';
        
        $mockSubject = new Subject();
        $mockSubject->name = 'Arabe';
        $mockStudent->setRelation('subject', $mockSubject);
        
        $mockCourse->setRelation('student', $mockStudent);
        
        // Create mock evaluation
        $mockEvaluation = new Evaluation();
        $mockEvaluation->name = 'MashAllah';
        $mockEvaluation->description = '100%';
        $mockCourse->setRelation('evaluation', $mockEvaluation);
        
        // Generate PDF with cache busting
        $pdf = Pdf::view('teacher.courses.report-pdf', ['course' => $mockCourse])
            ->format('a4');
        
        // If preview is requested, show in browser, otherwise download
        if ($request->has('preview')) {
            return $pdf->inline('test-rapport-cours-' . time() . '.pdf');
        }
        
        return $pdf->download('test-rapport-cours-' . time() . '.pdf');
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
