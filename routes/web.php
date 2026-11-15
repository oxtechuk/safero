<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\TrainingCertificateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to /app (Apps Launcher) or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('apps') : redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public verification routes (QR Code scan destination & manual lookup)
Route::get('/verify/{uuid}', [CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/embed/certificate/{uuid}', [CertificateController::class, 'embed'])->where('uuid', '.*')->name('certificates.embed');
Route::get('/verify-lookup', [CertificateController::class, 'verifyLookup'])->name('certificates.verify.lookup');
Route::get('/certificates/{certificate}/pdf', [CertificateController::class, 'pdf'])->name('certificates.pdf');

// Public Training Certificate routes (QR Code scan, Embed & PDF)
Route::get('/verify/training/{uuid}', [TrainingCertificateController::class, 'verify'])->name('training-certificates.verify');
Route::get('/embed/training-certificate/{uuid}', [TrainingCertificateController::class, 'embed'])->where('uuid', '.*')->name('training-certificates.embed');
Route::get('/training-certificates/{trainingCertificate}/pdf', [TrainingCertificateController::class, 'pdf'])->name('training-certificates.pdf');

// Protected ERP routes
Route::middleware('auth')->group(function () {
    // IsoOx-Style App Modules Launcher (/app)
    Route::get('/app', [DashboardController::class, 'apps'])->name('apps');
    Route::get('/apps', fn () => redirect()->route('apps'));

    // ERP Management Dashboard & Reports (/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers CRM
    Route::resource('customers', CustomerController::class);

    // Inspection & Certificates
    Route::resource('certificates', CertificateController::class);

    // Trainees CRM
    Route::resource('trainees', TraineeController::class);

    // Training Certificates Module
    Route::resource('training-certificates', TrainingCertificateController::class);

    // User & Permission Management (Odoo Enterprise RBAC)
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');
    Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);

    // Enterprise Platform Settings & Company Configuration (/settings)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/invite-user', [SettingController::class, 'inviteUser'])->name('settings.invite_user');
});
