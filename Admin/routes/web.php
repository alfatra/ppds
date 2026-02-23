<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PpdsController;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () { return 'Superadmin Dashboard'; })->name('dashboard');
});
Route::middleware(['auth', 'role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

// Grup Rute untuk Manajemen PPDS
Route::prefix('ppds')->name('ppds.')->middleware('auth')->group(function () {
    // Rute untuk melihat data, bisa diakses semua role yang sudah login
    Route::get('/', [PpdsController::class, 'index'])->name('index');
    
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
