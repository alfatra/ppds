<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PpdsController;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

// PENTING: Rute spesifik seperti ini harus didefinisikan SEBELUM rute catch-all '{any}'
Route::get('/ppds/form', [PpdsController::class, 'create'])->name('ppds.form')->middleware('auth');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
