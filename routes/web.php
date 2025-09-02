<?php

use Illuminate\Support\Facades\Route;

// Public / Guest
use App\Http\Controllers\InternshipRegistrationController as PublicRegController;

// Auth & Admin
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InternController;        // update status + certificate
use App\Http\Controllers\Admin\InternPageController;    // return view
use App\Http\Controllers\Admin\InternApiController;     // return JSON

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/certificate', 'certificate');

// ============ Guest / Internship ============
Route::get('/', [PublicRegController::class, 'create'])->name('internship.form');
Route::post('/internship/store', [PublicRegController::class, 'store'])->name('internship.store');

// Shortcut ke tabel admin
Route::get('/internship/table', function () {
    return redirect()->route('admin.interns.index');
})->name('internship.table');


// ============ Admin ============
Route::prefix('admin')->group(function () {
    // ---- Auth (tanpa middleware)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    // ---- Protected
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Dashboard + default /admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/', fn () => redirect()->route('dashboard.index'))->name('admin.home');

        // ===== Pages (hanya view) =====
        Route::prefix('interns')->group(function () {
            Route::get('/',          [InternPageController::class, 'index'])->name('admin.interns.index');
            Route::get('/active',    [InternPageController::class, 'active'])->name('admin.interns.active');
            Route::get('/completed', [InternPageController::class, 'completed'])->name('admin.interns.completed');
            Route::get('/exited',    [InternPageController::class, 'exited'])->name('admin.interns.exited');
            Route::get('/pending',   [InternPageController::class, 'pending'])->name('admin.interns.pending');

            // Update status: row & bulk
            Route::patch('/{intern}/status', [InternController::class, 'updateStatus'])->name('admin.interns.status.update');
            Route::patch('/bulk/status',     [InternController::class, 'bulkUpdateStatus'])->name('admin.interns.status.bulk');

            // PDF Sertifikat (DomPDF - cepat, tanpa JS canvas)
            Route::get('/{intern}/certificate', [InternController::class, 'certificate'])
                ->name('admin.interns.certificate');

            // PDF Sertifikat (Identik - Headless Chrome/Browsershot, render JS/canvas)
            Route::get('/{intern}/certificate.pdf', [InternController::class, 'certificatePdf'])
                ->name('admin.interns.certificate.pdf');
        });

        // ===== API JSON untuk tabel =====
        // dipakai di Blade dengan route('admin.interns.api')
        Route::get('/interns.json', [InternApiController::class, 'index'])->name('admin.interns.api');
    });
});
