<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipRegistrationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama form pendaftaran magang
Route::get('/', [InternshipRegistrationController::class, 'create'])->name('internship.form');

// Simpan data form ke database
Route::post('/internship/store', [InternshipRegistrationController::class, 'store'])->name('internship.store');

// Halaman untuk melihat semua data yang tersimpan
Route::get('/internship/table', [InternshipRegistrationController::class, 'index'])->name('internship.table');
