<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
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

// Protected ERP routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers CRM
    Route::resource('customers', CustomerController::class);

    // Inspection & Certificates
    Route::resource('certificates', CertificateController::class);
});
