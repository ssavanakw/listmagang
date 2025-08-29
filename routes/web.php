<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipRegistrationController as PublicRegController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InternController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * ============ Guest / Internship ============
 * Halaman form pendaftaran & submit
 */
Route::get('/', [PublicRegController::class, 'create'])
    ->name('internship.form');

Route::post('/internship/store', [PublicRegController::class, 'store'])
    ->name('internship.store');

/**
 * Link lama "Tabel Pendaftar" → arahkan ke daftar pemagang (admin).
 */
Route::get('/internship/table', function () {
    return redirect()->route('admin.interns.index');
})->name('internship.table');


/**
 * ============ Admin ============
 */
Route::prefix('admin')->group(function () {
    // ---- Auth (tanpa middleware) ----
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    // ---- Protected (harus admin) ----
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // /admin → /admin/dashboard
        Route::get('/', fn () => redirect()->route('dashboard.index'))->name('admin.home');

        // Daftar Pemagang (5 menu/filter)
        Route::prefix('interns')->group(function () {
            Route::get('/',          [InternController::class, 'index'])     ->name('admin.interns.index');     // semua
            Route::get('/active',    [InternController::class, 'active'])    ->name('admin.interns.active');    // aktif
            Route::get('/completed', [InternController::class, 'completed']) ->name('admin.interns.completed'); // selesai
            Route::get('/exited',    [InternController::class, 'exited'])    ->name('admin.interns.exited');    // keluar
            Route::get('/pending',   [InternController::class, 'pending'])   ->name('admin.interns.pending');   // pending

            // >>> IMPORTANT: bulk route harus di atas parameter {intern}
            Route::patch('/bulk/status', [InternController::class, 'bulkUpdateStatus'])
                ->name('admin.interns.status.bulk');

            Route::patch('/{intern}/status', [InternController::class, 'updateStatus'])
                ->whereNumber('intern') // cegah "bulk" tertangkap sebagai {intern}
                ->name('admin.interns.status.update');
        });
    });
});
