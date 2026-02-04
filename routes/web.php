<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LanguageController;

// Language Switcher Route (accessible from both admin and teacher panels)
Route::post('language/switch', [LanguageController::class, 'switchLanguage'])->name('language.switch');

// Public routes
Route::get('/', function () {
    // Check if user is authenticated as admin
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    // Check if user is authenticated as teacher
    if (Auth::guard('teacher')->check()) {
        return redirect()->route('teacher.dashboard');
    }
    
    // If not authenticated, redirect to login page
    return redirect()->route('admin.login');
});

// Test PDF Report Route (Public - for testing)
Route::get('/test-pdf-report', [TeacherController::class, 'testPdfReport'])->name('test.pdf.report');

// Test Send WhatsApp Routes (Public - for testing)
Route::get('/test-send-whatsapp-text', [TeacherController::class, 'testSendWhatsAppText'])->name('test.send.whatsapp.text');
Route::get('/test-send-whatsapp', [TeacherController::class, 'testSendWhatsApp'])->name('test.send.whatsapp');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::middleware(['locale'])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Protected admin routes
    Route::middleware(['auth.admin', 'locale'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Teachers Management
        Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
        Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('admin.teachers.create');
        Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');
        Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('admin.teachers.edit');
        Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('admin.teachers.update');
        Route::delete('/teachers/{teacher}', [AdminController::class, 'destroyTeacher'])->name('admin.teachers.destroy');
        Route::get('/teachers/{teacher}/details', [AdminController::class, 'getTeacherDetails'])->name('admin.teachers.details');
        
        // Students Management
        Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('admin.students.create');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
        Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('admin.students.edit');
        Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
        Route::delete('/students/{student}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy');
        
        // Settings - Multiple Settings Management
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        
        // Subjects Management
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('admin.subjects.store');
        Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('admin.subjects.update');
        Route::delete('/subjects/{subject}', [AdminController::class, 'destroySubject'])->name('admin.subjects.destroy');
        
        // Evaluations Management
        Route::post('/evaluations', [AdminController::class, 'storeEvaluation'])->name('admin.evaluations.store');
        Route::put('/evaluations/{evaluation}', [AdminController::class, 'updateEvaluation'])->name('admin.evaluations.update');
        Route::delete('/evaluations/{evaluation}', [AdminController::class, 'destroyEvaluation'])->name('admin.evaluations.destroy');
        
        // Payment Statuses Management
        Route::post('/payment-statuses', [AdminController::class, 'storePaymentStatus'])->name('admin.payment-statuses.store');
        Route::put('/payment-statuses/{paymentStatus}', [AdminController::class, 'updatePaymentStatus'])->name('admin.payment-statuses.update');
        Route::delete('/payment-statuses/{paymentStatus}', [AdminController::class, 'destroyPaymentStatus'])->name('admin.payment-statuses.destroy');
        
        // Courses Management (Admin can view/manage all courses)
        Route::get('/courses', [AdminController::class, 'courses'])->name('admin.courses');
        Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('admin.courses.create');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
        Route::get('/courses/{course}/edit', [AdminController::class, 'editCourse'])->name('admin.courses.edit');
        Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
        Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy');
        
        // Notifications Management
        Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
        Route::post('/notifications/{notification}/approve', [AdminController::class, 'approveAbsence'])->name('admin.notifications.approve');
        Route::post('/notifications/{notification}/reject', [AdminController::class, 'rejectAbsence'])->name('admin.notifications.reject');
        Route::post('/notifications/{notification}/read', [AdminController::class, 'markNotificationRead'])->name('admin.notifications.read');
        Route::delete('/notifications/{notification}', [AdminController::class, 'dismissNotification'])->name('admin.notifications.dismiss');
        
        // Payment Management
        Route::get('/payment', [AdminController::class, 'payment'])->name('admin.payment');
        Route::put('/students/{student}/payment-status', [AdminController::class, 'updateStudentPaymentStatus'])->name('admin.students.payment-status');
        
        // Analytics
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
        
        // Student Management Page
        Route::get('/management', [AdminController::class, 'management'])->name('admin.management');
        Route::get('/students/{student}/profile', [AdminController::class, 'showStudentProfile'])->name('admin.students.profile');
        Route::put('/students/{student}/quick-update', [AdminController::class, 'quickUpdateStudent'])->name('admin.students.quick-update');
        
        // Student Rounds API
        Route::get('/students/{student}/rounds', [AdminController::class, 'getStudentRounds'])->name('admin.students.rounds');
    });
});

// Teacher Authentication Routes
Route::prefix('teacher')->group(function () {
    // Language switching route (accessible without auth for login page)
    Route::post('/language/switch', [LanguageController::class, 'switchLanguage'])->name('teacher.language.switch');
    
    // Apply locale middleware to login routes too
    Route::middleware(['locale'])->group(function () {
        Route::get('/login', [TeacherAuthController::class, 'showLoginForm'])->name('teacher.login');
        Route::post('/login', [TeacherAuthController::class, 'login']);
    });
    
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');
    
    // Protected teacher routes
    Route::middleware(['auth.teacher', 'locale'])->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
        Route::get('/students', [TeacherController::class, 'students'])->name('teacher.students');
        
        // Courses Management
        Route::get('/courses', [TeacherController::class, 'courses'])->name('teacher.courses');
        Route::get('/courses/create', [TeacherController::class, 'createCourse'])->name('teacher.courses.create');
        Route::post('/courses', [TeacherController::class, 'storeCourse'])->name('teacher.courses.store');
        Route::get('/courses/{course}/edit', [TeacherController::class, 'editCourse'])->name('teacher.courses.edit');
        Route::put('/courses/{course}', [TeacherController::class, 'updateCourse'])->name('teacher.courses.update');
        Route::delete('/courses/{course}', [TeacherController::class, 'destroyCourse'])->name('teacher.courses.destroy');
        Route::get('/courses/{course}/report', [TeacherController::class, 'generateReport'])->name('teacher.courses.report');
        
        // Timetable
        Route::get('/timetable', [TeacherController::class, 'timetable'])->name('teacher.timetable');
        Route::get('/timetable/events', [TeacherController::class, 'getTimetableEvents'])->name('teacher.timetable.events');
        
        // Recurring Courses
        Route::post('/recurring-courses', [TeacherController::class, 'storeRecurringCourse'])->name('teacher.recurring-courses.store');
        Route::post('/recurring-courses/generate', [TeacherController::class, 'generateRecurringEvents'])->name('teacher.recurring-courses.generate');
        Route::delete('/recurring-courses/{id}', [TeacherController::class, 'destroyRecurringCourse'])->name('teacher.recurring-courses.destroy');
        
        // Student Rounds API
        Route::get('/students/{student}/rounds', [TeacherController::class, 'getStudentRounds'])->name('teacher.students.rounds');
    });
});
