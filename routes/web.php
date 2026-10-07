<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Intern\AttendanceController;
use App\Http\Controllers\Intern\DashboardController as InternDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [GalleryController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/interns', [InternController::class, 'index'])->name('interns');
    Route::post('/interns', [InternController::class, 'store'])->name('interns.store');
    Route::post('/interns/{id}', [InternController::class, 'update'])->name('interns.update');
    Route::delete('/interns/{id}', [InternController::class, 'destroy'])->name('interns.destroy');
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests');
    Route::post('/leave-requests/{id}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations');

    // Admin Laporan Aktivitas
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities');
    Route::post('/activities/{id}/comment', [ActivityController::class, 'addComment'])->name('activities.comment');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// Intern Routes
Route::middleware(['auth', 'role:intern'])->prefix('intern')->name('intern.')->group(function () {
    Route::get('/dashboard', [InternDashboard::class, 'index'])->name('dashboard');
    Route::post('/check-in', [InternDashboard::class, 'checkIn'])->name('check-in');
    Route::get('/dashboard', [InternDashboard::class, 'index'])->name('dashboard');
    Route::post('/check-in', [InternDashboard::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [InternDashboard::class, 'checkOut'])->name('check-out');
    Route::post('/avatar', [InternDashboard::class, 'updateAvatar'])->name('avatar.update');

    // Activities
    Route::get('/activities', [App\Http\Controllers\Intern\ActivityController::class, 'index'])->name('activities');
    Route::post('/activities', [App\Http\Controllers\Intern\ActivityController::class, 'store'])->name('activities.store');
    Route::put('/activities/{id}', [App\Http\Controllers\Intern\ActivityController::class, 'update'])->name('activities.update');
    Route::get('/attendance-history', [AttendanceController::class, 'history'])->name('attendance-history');

    // Leave Requests
    Route::get('/leave-requests', [App\Http\Controllers\Intern\LeaveRequestController::class, 'index'])->name('leave-requests');
    Route::post('/leave-requests', [App\Http\Controllers\Intern\LeaveRequestController::class, 'store'])->name('leave-requests.store');
});
