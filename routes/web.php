<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipRegistrationController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// Guest / Internship
// ==============================

// Halaman utama form pendaftaran magang
Route::get('/', [InternshipRegistrationController::class, 'create'])
    ->name('internship.form');

// Simpan data form ke database
Route::post('/internship/store', [InternshipRegistrationController::class, 'store'])
    ->name('internship.store');

// Halaman untuk melihat semua data yang tersimpan
Route::get('/internship/table', [InternshipRegistrationController::class, 'index'])
    ->name('internship.table')
    ->middleware(['auth','role:admin']);


// ==============================
// Admin Authentication & Dashboard
// ==============================
Route::prefix('admin')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('admin.login.submit');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');

    // Dashboard (hanya untuk role admin)
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard.index')->middleware(['auth', 'role:admin']);
});


