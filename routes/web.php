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

// =================== DUMMY PREVIEW (OPSIONAL) ===================
// Preview sertifikat statis (contoh dummy untuk certmagangjogjacom)
Route::view('/dummycertificate','certificates.dummycertificate')
    ->name('dummycertificate');

// Preview dummy untuk versi certareakerjacom (payload sederhana)
Route::view(
    '/dummycertificate-areakerjacom',
    'certificates.certareakerjacom',
    [
        'name'          => 'Fida Royyanatus Syahr',
        'deptLabel'     => 'HR Departement',
        'directorLabel' => 'Direktur',
        'hrName'        => 'Ari Setia Husbana',
        'directorName'  => 'Pipit Damayanti',
        'roleDesc'      => 'bidang Human Resource di Area Kerja',
        'durationText'  => '1,5 bulan',
        'startDate'     => '21 April 2025',
        'endDate'       => '30 Mei 2025',
    ]
)->name('dummycertificate.areakerjacom');

// =================== GUEST / INTERNSHIP ===================
Route::get('/', [PublicRegController::class, 'create'])->name('internship.form');
Route::post('/internship/store', [PublicRegController::class, 'store'])->name('internship.store');

// Shortcut ke tabel admin
Route::get('/internship/table', function () {
    return redirect()->route('admin.interns.index');
})->name('internship.table');

// =================== ADMIN ===================
Route::prefix('admin')->group(function () {

    // ---- Auth (HANYA untuk tamu/guest) + no-cache
    Route::middleware(['guest', 'prevent-back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    // ---- Protected (admin login) + no-cache
    // PERHATIKAN: middleware dipisah item-nya, jangan digabung dalam satu string
    Route::middleware(['auth', 'role:admin', 'prevent-back'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Dashboard + default /admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/', fn () => redirect()->route('dashboard.index'))->name('admin.home');

        // ===== Pages (hanya view)
        Route::prefix('interns')->group(function () {
            Route::get('/',          [InternPageController::class, 'index'])->name('admin.interns.index');
            Route::get('/active',    [InternPageController::class, 'active'])->name('admin.interns.active');
            Route::get('/completed', [InternPageController::class, 'completed'])->name('admin.interns.completed');
            Route::get('/exited',    [InternPageController::class, 'exited'])->name('admin.interns.exited');
            Route::get('/pending',   [InternPageController::class, 'pending'])->name('admin.interns.pending');

            // ===== Update status: row & bulk
            Route::patch('/{intern}/status', [InternController::class, 'updateStatus'])
                ->name('admin.interns.status.update');

            Route::patch('/bulk/status', [InternController::class, 'bulkUpdateStatus'])
                ->name('admin.interns.status.bulk');
            
            Route::patch('/{intern}', [InternController::class, 'update'])
                ->name('admin.interns.update');


            // ===== Sertifikat default (yang sudah ada)
            Route::get('/{intern}/certificate', [InternController::class, 'certificate'])
                ->name('admin.interns.certificate');

            Route::get('/{intern}/certificate.pdf', [InternController::class, 'certificatePdf'])
                ->name('admin.interns.certificate.pdf');

            // ===== Sertifikat: AreaKerjaCom =====
            // PREVIEW HTML (opsional)
            Route::get('/{intern}/certificate/areakerjacom/preview',
                [InternController::class, 'certificateAreaKerjaCom'])
                ->name('admin.interns.certificate.areakerjacom.preview');

            // DEFAULT: LANGSUNG DOWNLOAD PDF (attachment)
            Route::get('/{intern}/certificate/areakerjacom',
                [InternController::class, 'certificateAreaKerjaComPdf'])
                ->name('admin.interns.certificate.areakerjacom');

            // Alternatif path .pdf (tetap download)
            Route::get('/{intern}/certificate/areakerjacom.pdf',
                [InternController::class, 'certificateAreaKerjaComPdf'])
                ->name('admin.interns.certificate.areakerjacom.pdf');
        });

        // ===== API JSON untuk tabel
        Route::get('/interns.json', [InternApiController::class, 'index'])->name('admin.interns.api');
    });
});
