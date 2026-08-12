<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\PpdsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Rute untuk Dashboard PPDS
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk halaman profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rute untuk halaman Absensi
    Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/detail', [\App\Http\Controllers\AttendanceController::class, 'getDetail'])->name('attendance.detail');
});
Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('root');

// Rute untuk API internal (proxy)

Route::middleware('auth')->group(function () {
    Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index');
});
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () { return 'Superadmin Dashboard'; })->name('dashboard');
});
Route::middleware(['auth', 'role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::patch('/users/{user}/update-supervisor', [UserController::class, 'updateSupervisor'])->name('users.updateSupervisor');
    Route::patch('/users/{user}/update-attendance', [UserController::class, 'updateAttendance'])->name('users.updateAttendance');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Master Data Tindakan Medis
    Route::resource('medical-activities', \App\Http\Controllers\MedicalActivityController::class)->except(['show']);
});

    // Grup Rute untuk Manajemen PPDS 
    Route::prefix('ppds')->name('ppds.')->middleware('auth')->group(function () {
        // Rute untuk melihat data, hanya untuk admin & superadmin
        Route::middleware('role:superadmin,admin')->group(function () {
            Route::get('/', [PpdsController::class, 'index'])->name('index');
            Route::get('/create', [PpdsController::class, 'create'])->name('create');
            Route::post('/', [PpdsController::class, 'store'])->name('store');
            Route::get('/{ppds}/edit', [PpdsController::class, 'edit'])->name('edit');
            Route::put('/{ppds}', [PpdsController::class, 'update'])->name('update');
            Route::delete('/{ppds}', [PpdsController::class, 'destroy'])->name('destroy');
        });
        
        // Download dan SOAP logs dapat diakses semua user yang login
        Route::get('/{ppds}/download', [PpdsController::class, 'downloadBerkas'])->name('download');
        Route::resource('soap-logs', \App\Http\Controllers\SoapLogController::class)->parameters(['soap-logs' => 'log']);
    });
    
Route::resource('daily-activities', \App\Http\Controllers\DailyActivityController::class)->middleware('auth');
    
Route::get('/index', [DashboardController::class, 'index'])->middleware('auth')->name('index');

// Group Rute untuk Konsulen
Route::middleware(['auth', 'role:konsulen,superadmin,admin'])->prefix('konsulen')->name('konsulen.')->group(function () {
    Route::get('/mahasiswa', [App\Http\Controllers\KonsulenController::class, 'mahasiswa'])->name('mahasiswa');
});

// Group Rute untuk Konsulen Approval
Route::middleware(['auth', 'role:konsulen,superadmin,admin'])->prefix('approvals')->name('approvals.')->group(function () {
    Route::get('/', [App\Http\Controllers\ApprovalController::class, 'index'])->name('index');
    Route::patch('/daily-activity/{id}/approve', [App\Http\Controllers\ApprovalController::class, 'approveDailyActivity'])->name('daily-activity.approve');
    Route::patch('/daily-activity/{id}/reject', [App\Http\Controllers\ApprovalController::class, 'rejectDailyActivity'])->name('daily-activity.reject');
    Route::patch('/soap-log/{id}/approve', [App\Http\Controllers\ApprovalController::class, 'approveSoapLog'])->name('soap-log.approve');
    Route::patch('/soap-log/{id}/reject', [App\Http\Controllers\ApprovalController::class, 'rejectSoapLog'])->name('soap-log.reject');
});

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/debug-create', function() { return view('soap_logs.create'); });

