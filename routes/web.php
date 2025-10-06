<?php

use Illuminate\Support\Facades\Route;

// Public / Guest
use App\Http\Controllers\InternshipRegistrationController as PublicRegController;

// Auth & Admin
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\Admin\InternPageController;
use App\Http\Controllers\Admin\InternApiController;
use App\Http\Controllers\Admin\CertificateGeneratorController;

// App
use App\Http\Controllers\UserController;  // UserController untuk pengguna biasa
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| - Root -> login (auth/admin-login.blade.php)
| - User login -> user.dashboard, Admin login -> admin/dashboard (atur di AuthController)
| - Form internship dilindungi auth + submitted page
| - Admin area: prefix URL 'admin' + prefix nama 'admin.' agar rapi & tidak bentrok
|--------------------------------------------------------------------------
*/

// Demo (opsional)
Route::view('/zombie-survival', 'cobacoba')->name('zombie.survival');

/* =================== ROOT -> LOGIN VIEW =================== */
Route::get('/', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

/* =================== AUTH (GUEST) =================== */
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('user.login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('user.login.submit');

Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('user.register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->name('user.register.submit');

// Logout (user & admin)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('user.logout');

/* =================== USER ROUTES =================== */
// HANYA SATU route bernama user.dashboard (hindari duplikasi!)
Route::get('/user/dashboard', [UserController::class, 'index'])
    ->name('user.dashboard')
    ->middleware('auth');

// Edit profil user
Route::get('/user/edit-profile', [PublicRegController::class, 'editProfile'])
    ->name('user.editProfile')
    ->middleware('auth');

Route::post('/user/update-profile', [PublicRegController::class, 'updateProfile'])
    ->name('user.updateProfile')
    ->middleware('auth');

/* =================== INTERNSHIP (USER) =================== */
// Form & store harus login
Route::get('/internship', [PublicRegController::class, 'create'])
    ->name('internship.form')
    ->middleware('auth');

Route::post('/internship/store', [PublicRegController::class, 'store'])
    ->name('internship.store')
    ->middleware('auth');

// Submitted page (butuh login)
Route::view('/internship/submitted', 'pages.internship.submitted-page')
    ->name('internship.submitted')
    ->middleware('auth');

// Shortcut ke tabel internship (halaman admin)
Route::get('/internship/table', fn () => redirect()->route('admin.interns.index'))
    ->name('internship.table')
    ->middleware('auth');

/* ===== Admin Login (untuk middleware RoleMiddleware) ===== */
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
    ->name('admin.login')
    ->middleware('guest');

/* =================== ADMIN ROUTES =================== */
// Semua nama route akan diawali 'admin.' (mis: 'admin.dashboard.index')
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'prevent-back'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/', fn () => redirect()->route('admin.dashboard.index'))->name('home');

    // Users CRUD -> admin.users.*
    Route::resource('users', AdminUserController::class);

    // Interns (pages + status + certificates) -> admin.interns.*
    Route::prefix('interns')->name('interns.')->group(function () {
        Route::get('/', [InternPageController::class, 'index'])->name('index');
        Route::get('/active', [InternPageController::class, 'active'])->name('active');
        Route::get('/completed', [InternPageController::class, 'completed'])->name('completed');
        Route::get('/exited', [InternPageController::class, 'exited'])->name('exited');
        Route::get('/pending', [InternPageController::class, 'pending'])->name('pending');
        Route::get('accepted', [InternPageController::class, 'accepted'])->name('accepted');
        Route::get('rejected', [InternPageController::class, 'rejected'])->name('rejected');

        // Update status & data
        Route::patch('/{intern}/status', [InternController::class, 'updateStatus'])->name('status.update');
        Route::patch('/bulk/status', [InternController::class, 'bulkUpdateStatus'])->name('status.bulk');
        Route::patch('/{intern}', [InternController::class, 'update'])->name('update');

        // Sertifikat default
        Route::get('/{intern}/certificate', [InternController::class, 'certificate'])->name('certificate');
        Route::get('/{intern}/certificate.pdf', [InternController::class, 'certificatePdf'])->name('certificate.pdf');

        // Sertifikat AreaKerjaCom
        Route::get('/{intern}/certificate/areakerjacom/preview', [InternController::class, 'certificateAreaKerjaCom'])->name('certificate.areakerjacom.preview');
        Route::get('/{intern}/certificate/areakerjacom', [InternController::class, 'certificateAreaKerjaComPdf'])->name('certificate.areakerjacom');
        Route::get('/{intern}/certificate/areakerjacom.pdf', [InternController::class, 'certificateAreaKerjaComPdf'])->name('certificate.areakerjacom.pdf');

        // Template dinamis
        Route::get('/{intern}/certificate/{template}.pdf', [InternController::class, 'certificatePdfDynamic'])
            ->name('certificate.dynamic')
            ->whereIn('template', ['certmagangjogjacom', 'certareakerjacom', 'certtipisinicom']);

        // Hapus intern
        Route::delete('/{intern}', [InternController::class, 'destroy'])->name('destroy');
    });

    // API Select interns & JSON -> admin.interns.search, admin.interns.api
    Route::get('/interns/search', [InternApiController::class, 'search'])->name('interns.search');
    Route::get('/interns.json',   [InternApiController::class, 'index'])->name('interns.api');

    // Certificate generator lama (opsional)
    Route::get('/certificate/form',          [CertificateGeneratorController::class, 'showForm'])->name('certificate.form');
    Route::post('/certificate/preview',      [CertificateGeneratorController::class, 'generatePreview'])->name('certificate.generatePreview');
    Route::get('/certificate/download/{id}', [CertificateGeneratorController::class, 'generatePDF'])->name('certificate.generatePDF');
    Route::post('/download-pdf',             [CertificateGeneratorController::class, 'generatePDF'])->name('download.pdf');

    // Bulk certificate custom -> admin.certificate.bulk.*
    Route::prefix('certificate/bulk')->name('certificate.bulk.')->group(function () {
        Route::get('/interns',  [CertificateController::class, 'bulkCreateInterns'])->name('interns.create');
        Route::post('/interns', [CertificateController::class, 'bulkStoreInterns'])->name('interns.store');

        Route::get('/external',  [CertificateController::class, 'bulkCreateExternal'])->name('external.create');
        Route::post('/external', [CertificateController::class, 'bulkStoreExternal'])->name('external.store');
    });

    // External create/store -> admin.certificate.external.*
    Route::get('/certificate/external/create', [CertificateController::class, 'createExternal'])->name('certificate.external.create');
    Route::post('/certificate/external',        [CertificateController::class, 'storeExternal'])->name('certificate.external.store');

    // Download PDF satu sertifikat -> admin.certificate.pdf
    Route::get('/certificate/{certificate}/pdf', [CertificateController::class, 'downloadPdf'])->name('certificate.pdf');

    // Bulk ZIP -> admin.certificate.external.bulk
    Route::post('/certificate/external/bulk-zip', [CertificateController::class, 'externalBulkZip'])->name('certificate.external.bulk');

    // Bulk download tanpa simpan DB -> admin.certificate.external.bulkDownload
    Route::post('/certificate/external/bulk-download', [CertificateController::class, 'externalBulkDownloadFromForm'])
        ->name('certificate.external.bulkDownload');

    // Resource Certificate -> admin.certificate.*
    Route::resource('certificate', CertificateController::class);

    // Upload assets (bg/logo/ttd) -> admin.uploads.*
    Route::post('/uploads/backgrounds', [CertificateController::class, 'uploadBackground'])->name('uploads.backgrounds.store');
    Route::post('/uploads/logos',       [CertificateController::class, 'uploadLogo'])->name('uploads.logos.store');
    Route::post('/uploads/signatures',  [CertificateController::class, 'uploadSignature'])->name('uploads.signatures.store');

    // (Opsional) Dashboard khusus di area admin -> admin.user.dashboard
    Route::get('/user/dashboard', [AdminUserController::class, 'index'])->name('user.dashboard');
});
