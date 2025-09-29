<?php

use Illuminate\Support\Facades\Route;

// Public / Guest
use App\Http\Controllers\InternshipRegistrationController as PublicRegController;

// Auth & Admin
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\Admin\InternPageController;
use App\Http\Controllers\Admin\InternApiController;
use App\Http\Controllers\Admin\CertificateGeneratorController;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =================== GUEST / INTERNSHIP ===================
Route::get('/', [PublicRegController::class, 'create'])->name('internship.form');
Route::post('/internship/store', [PublicRegController::class, 'store'])->name('internship.store');

Route::get('/internship/table', function () {
    return redirect()->route('admin.interns.index');
})->name('internship.table');

// =================== ADMIN ===================
Route::prefix('admin')->group(function () {

    // Auth (guest)
    Route::middleware(['guest', 'prevent-back'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    // Protected (admin login)
    Route::middleware(['auth', 'role:admin', 'prevent-back'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/', fn () => redirect()->route('dashboard.index'))->name('admin.home');

        // ===== Interns (pages + status + certificates bawaan)
        Route::prefix('interns')->group(function () {
            Route::get('/', [InternPageController::class, 'index'])->name('admin.interns.index');
            Route::get('/active', [InternPageController::class, 'active'])->name('admin.interns.active');
            Route::get('/completed', [InternPageController::class, 'completed'])->name('admin.interns.completed');
            Route::get('/exited', [InternPageController::class, 'exited'])->name('admin.interns.exited');
            Route::get('/pending', [InternPageController::class, 'pending'])->name('admin.interns.pending');

            // Update status
            Route::patch('/{intern}/status', [InternController::class, 'updateStatus'])->name('admin.interns.status.update');
            Route::patch('/bulk/status', [InternController::class, 'bulkUpdateStatus'])->name('admin.interns.status.bulk');
            Route::patch('/{intern}', [InternController::class, 'update'])->name('admin.interns.update');

            // Sertifikat default
            Route::get('/{intern}/certificate', [InternController::class, 'certificate'])->name('admin.interns.certificate');
            Route::get('/{intern}/certificate.pdf', [InternController::class, 'certificatePdf'])->name('admin.interns.certificate.pdf');

            // Sertifikat AreaKerjaCom
            Route::get('/{intern}/certificate/areakerjacom/preview', [InternController::class, 'certificateAreaKerjaCom'])->name('admin.interns.certificate.areakerjacom.preview');
            Route::get('/{intern}/certificate/areakerjacom', [InternController::class, 'certificateAreaKerjaComPdf'])->name('admin.interns.certificate.areakerjacom');
            Route::get('/{intern}/certificate/areakerjacom.pdf', [InternController::class, 'certificateAreaKerjaComPdf'])->name('admin.interns.certificate.areakerjacom.pdf');

            // Dynamic template
            Route::get('/{intern}/certificate/{template}.pdf', [InternController::class, 'certificatePdfDynamic'])
                ->name('admin.interns.certificate.dynamic')
                ->whereIn('template', ['certmagangjogjacom','certareakerjacom','certtipisinicom']);

            // Hapus intern
            Route::delete('/{intern}', [InternController::class, 'destroy'])->name('admin.interns.destroy');
        });

        // ===== Certificate Generator lama (opsional)
        Route::get('/certificate/form', [CertificateGeneratorController::class, 'showForm'])->name('certificate.form');
        Route::post('/certificate/preview', [CertificateGeneratorController::class, 'generatePreview'])->name('certificate.generatePreview');
        Route::get('/certificate/download/{id}', [CertificateGeneratorController::class, 'generatePDF'])->name('certificate.generatePDF');
        Route::post('/download-pdf', [CertificateGeneratorController::class, 'generatePDF'])->name('download.pdf');
        

        // ===== Resource Certificate (baru) =====
        Route::resource('certificate', CertificateController::class);
        
        Route::get('/certificate/{certificate}/pdf', [CertificateController::class, 'downloadPdf']) ->name('certificate.pdf');

        Route::middleware(['auth'])->group(function () {
            Route::post('/uploads/backgrounds', [CertificateController::class, 'uploadBackground'])
                ->name('uploads.backgrounds.store');
            Route::post('/uploads/logos', [CertificateController::class, 'uploadLogo'])
                ->name('uploads.logos.store');
            Route::post('/uploads/signatures', [CertificateController::class, 'uploadSignature'])
                ->name('uploads.signatures.store');
        });


        // ===== API JSON =====
        Route::get('/interns.json', [InternApiController::class, 'index'])->name('admin.interns.api');
    });
});
