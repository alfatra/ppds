<?php

use App\Http\Controllers\PpdsController;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// PENTING: Rute spesifik seperti ini harus didefinisikan SEBELUM rute catch-all '{any}'
Route::get('/ppds/form', [PpdsController::class, 'create'])->name('ppds.form')->middleware('auth');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
