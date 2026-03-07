<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PpdsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

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
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

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
    // Rute untuk melihat data, bisa diakses semua role yang sudah login
    Route::get('/', [PpdsController::class, 'index'])->name('index');
    Route::get('/{ppds}/download', [PpdsController::class, 'downloadBerkas'])->name('download');

    // Logbook SOAP medical records
    Route::resource('soap-logs', \App\Http\Controllers\SoapLogController::class);
    
    // Rute untuk manajemen (create, edit, delete), hanya untuk admin & superadmin
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/create', [PpdsController::class, 'create'])->name('create');
        Route::post('/', [PpdsController::class, 'store'])->name('store');
        Route::get('/{ppds}/edit', [PpdsController::class, 'edit'])->name('edit');
        Route::put('/{ppds}', [PpdsController::class, 'update'])->name('update');
        Route::delete('/{ppds}', [PpdsController::class, 'destroy'])->name('destroy');
    });
});

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
