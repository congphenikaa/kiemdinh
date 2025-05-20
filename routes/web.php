<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Auth;

// Trang chủ cho người dùng chưa đăng nhập
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // Resource routes
    Route::resource('degrees', DegreeController::class);
    Route::resource('faculties', FacultyController::class);
    Route::resource('teachers', TeacherController::class);
    
    // Statistics route
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
