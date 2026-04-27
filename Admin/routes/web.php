<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\PpdsController;
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

    // Rute untuk halaman profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rute untuk halaman Absensi
    Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// Rute untuk API internal (proxy)

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () { return 'Superadmin Dashboard'; })->name('dashboard');
});
Route::middleware(['auth', 'role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
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

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
