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
    TeacherPaymentController,
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
    Route::resource('classes', ClassController::class);
    Route::resource('teaching-assignments', TeachingAssignmentController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::get('class-reports', [ClassReportController::class, 'index'])->name('class-reports.index');


    // 3. Tính tiền dạy
    Route::resource('payment-configs', ScheduleController::class);
    // Route::prefix('payment-configs')->group(function () {
    //     Route::get('/', [PaymentConfigController::class, 'index'])->name('payment-configs.index');
    //     Route::post('/', [PaymentConfigController::class, 'store'])->name('payment-configs.store');
    //     Route::put('/', [PaymentConfigController::class, 'update'])->name('payment-configs.update');
    // });
    
    Route::resource('class-size-coefficients', ClassSizeCoefficientController::class);
    
    
    Route::prefix('teacher-payments')->group(function () {
        Route::get('/', [TeacherPaymentController::class, 'index'])->name('teacher-payments.index');
        Route::get('/calculate/{semester}', [TeacherPaymentController::class, 'calculate'])->name('teacher-payments.calculate');
        Route::post('/store', [TeacherPaymentController::class, 'store'])->name('teacher-payments.store');
        Route::get('/{payment}/edit', [TeacherPaymentController::class, 'edit'])->name('teacher-payments.edit');
        Route::put('/{payment}', [TeacherPaymentController::class, 'update'])->name('teacher-payments.update');
    });
    
    Route::resource('payment-batches', PaymentBatchController::class);

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