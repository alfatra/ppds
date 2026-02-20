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

// Grup Rute untuk Manajemen PPDS
Route::middleware('auth')->prefix('ppds')->name('ppds.')->group(function () {
    Route::get('/', [PpdsController::class, 'index'])->name('index'); // Halaman daftar data (tabel)
    Route::get('/create', [PpdsController::class, 'create'])->name('create'); // Halaman form tambah data
    Route::post('/', [PpdsController::class, 'store'])->name('store'); // Rute untuk memproses simpan data
    // Rute untuk menampilkan form edit
    Route::get('/{ppds}/edit', [PpdsController::class, 'edit'])->name('edit');
    // Rute untuk memproses update data
    Route::put('/{ppds}', [PpdsController::class, 'update'])->name('update');
    // Rute untuk menghapus data
    Route::delete('/{ppds}', [PpdsController::class, 'destroy'])->name('destroy');

});

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
