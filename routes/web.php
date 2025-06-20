<?php

use App\Http\Controllers\{
    HomeController,
    ProfileController,
    DegreeController,
    FacultyController,
    TeacherController,
    TeacherReportController,
    CourseController,
    AcademicYearController,
    SemesterController,
    ClassController,
    ScheduleController,
    ClassReportController,
    PaymentConfigController,
    ClassSizeCoefficientController,
    TeachingAssignmentController,
    PaymentCalculationController,
    PaymentBatchController,
    ReportController
};

use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // 1. Quản lý giáo viên
    Route::resource('degrees', DegreeController::class);
    Route::resource('faculties', FacultyController::class);
    Route::resource('teachers', TeacherController::class);
    Route::get('teacher-reports', [TeacherReportController::class, 'index'])->name('teacher-reports.index');

    // 2. Quản lý lớp học
    Route::resource('courses', CourseController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::patch('/{semester}/toggle-active', [SemesterController::class, 'toggleActive'])->name('semesters.toggleActive');
    Route::resource('classes', ClassController::class);
    Route::resource('teaching-assignments', TeachingAssignmentController::class);
    Route::get('/api/classes/{class}/teachers', [TeachingAssignmentController::class, 'getTeachersByClass']);

    Route::resource('schedules', ScheduleController::class);
    Route::prefix('class-reports')->group(function () {
            Route::get('/', [ClassReportController::class, 'index'])->name('class-reports.index');
            Route::get('/{class}', [ClassReportController::class, 'show'])->name('class-reports.show');
    });


   Route::resource('payment-configs', PaymentConfigController::class);
    
Route::resource('class-size-coefficients', ClassSizeCoefficientController::class);

Route::prefix('payment-calculations')->group(function () {
    Route::get('/', [PaymentCalculationController::class, 'index'])->name('payment-calculations.index');
    Route::get('/{semester}/calculate', [PaymentCalculationController::class, 'calculate'])->name('payment-calculations.calculate');
});

Route::resource('payment-batches', PaymentBatchController::class)->except(['create']);
Route::get('/payment-batches/{semester}/create', [PaymentBatchController::class, 'create'])->name('payment-batches.create');


    // 4. Báo cáo
    Route::prefix('reports')->group(function () {
        Route::get('/teacher-payments', [ReportController::class, 'teacherPayments'])->name('reports.teacher-payments');
        Route::get('/faculty-payments', [ReportController::class, 'facultyPayments'])->name('reports.faculty-payments');
        Route::get('/summary', [ReportController::class, 'summary'])->name('reports.summary');
    });
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';