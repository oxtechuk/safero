<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateTemplateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InspectionTaskController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ReportController;
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

// Language Switcher Route (Arabic / English)
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Public verification routes (QR Code scan destination & manual lookup)
Route::get('/verify/{uuid}', [CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/embed/certificate/{uuid?}', [CertificateController::class, 'embed'])->where('uuid', '.*')->name('certificates.embed');
Route::get('/verify-lookup', [CertificateController::class, 'verifyLookup'])->name('certificates.verify.lookup');
Route::get('/certificates/{certificate}/pdf', [CertificateController::class, 'pdf'])->where('certificate', '.*')->name('certificates.pdf');

// Public Training Certificate routes (QR Code scan, Embed & PDF)
Route::get('/verify/training/{uuid}', [TrainingCertificateController::class, 'verify'])->name('training-certificates.verify');
Route::get('/embed/training-certificate/{uuid?}', [TrainingCertificateController::class, 'embed'])->where('uuid', '.*')->name('training-certificates.embed');
Route::get('/training-certificates/{trainingCertificate}/pdf', [TrainingCertificateController::class, 'pdf'])->where('trainingCertificate', '.*')->name('training-certificates.pdf');

// Protected ERP routes
Route::middleware('auth')->group(function () {
    // IsoOx-Style App Modules Launcher (/app)
    Route::get('/app', [DashboardController::class, 'apps'])->name('apps');
    Route::get('/apps', fn () => redirect()->route('apps'));

    // ERP Management Dashboard & Reports (/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers CRM
    Route::resource('customers', CustomerController::class);

    // Inspection & Certificates & Studio
    Route::get('/certificates/expiring', [CertificateController::class, 'expiring'])->name('certificates.expiring');
    Route::get('/certificates/batch-stickers', [CertificateController::class, 'batchStickers'])->name('certificates.batch_stickers');
    Route::get('/certificates/{certificate}/sticker', [CertificateController::class, 'sticker'])->name('certificates.sticker');
    Route::resource('certificates', CertificateController::class);

    // Certificate Studio (Templates & Equipment CMS Builder)
    Route::post('/certificate-templates/{certificateTemplate}/duplicate', [CertificateTemplateController::class, 'duplicate'])->name('certificate-templates.duplicate');
    Route::get('/certificate-templates/{certificateTemplate}/api', [CertificateTemplateController::class, 'apiData'])->name('certificate-templates.api');
    Route::resource('certificate-templates', CertificateTemplateController::class);

    // Trainees CRM
    Route::resource('trainees', TraineeController::class);

    // Training Certificates Module
    Route::resource('training-certificates', TrainingCertificateController::class);

    // Field Inspection Tasks & Google Sheets Work Orders (/tasks)
    Route::resource('tasks', InspectionTaskController::class);
    Route::post('/tasks/{task}/sync', [InspectionTaskController::class, 'syncNow'])->name('tasks.sync_now');
    Route::post('/tasks/sync-all', [InspectionTaskController::class, 'syncAll'])->name('tasks.sync_all');
    Route::get('/tasks/{task}/convert', [InspectionTaskController::class, 'convertToCertificate'])->name('tasks.convert');

    // Field Inspections & Mobile Checklist Flow (/inspections)
    Route::get('/inspections/start', [InspectionController::class, 'start'])->name('inspections.start');
    Route::get('/inspections/{inspection}/inspect', [InspectionController::class, 'inspect'])->name('inspections.inspect');
    Route::get('/inspections/{inspection}/sticker', [InspectionController::class, 'sticker'])->name('inspections.sticker');
    Route::post('/inspections/{inspection}/update-checklist', [InspectionController::class, 'updateChecklist'])->name('inspections.update_checklist');
    Route::post('/inspections/{inspection}/convert', [InspectionController::class, 'convertToCertificate'])->name('inspections.convert');
    Route::resource('inspections', InspectionController::class)->except(['create']);

    // Invoicing & Billing Module (/invoices)
    Route::get('/customers/{customer}/certificates-json', [InvoiceController::class, 'customerCertificates'])->name('customers.certificates_json');
    Route::resource('invoices', InvoiceController::class);

    // Business Reports & Profit Analytics (/reports)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // User & Permission Management (Odoo Enterprise RBAC)
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');
    Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);

    // Enterprise Platform Settings & Company Configuration (/settings)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/invite-user', [SettingController::class, 'inviteUser'])->name('settings.invite_user');
    Route::post('/settings/test-google-sheets', [SettingController::class, 'testGoogleSheets'])->name('settings.test_google_sheets');
});

// Progressive Web App (PWA) Web Manifest
Route::get('/manifest.webmanifest', function () {
    $appName = setting('company_name_en', config('app.name', 'Safero ERP'));
    $appShortName = setting('company_name_en', 'Safero');
    $appIcon = setting('app_icon_path') ?: setting('favicon_path') ?: setting('logo_path', '/branding/safero_logo.jpg');
    $fullIconUrl = asset(ltrim($appIcon, '/'));

    return response()->json([
        'name' => $appName.' - Lifting Inspection & Certification',
        'short_name' => $appShortName,
        'description' => 'نظام الفحص الهندسي وإصدار شهادات المعايرة المعتمدة',
        'start_url' => route('apps'),
        'display' => 'standalone',
        'orientation' => 'portrait',
        'background_color' => '#ffffff',
        'theme_color' => '#0E4DA4',
        'icons' => [
            [
                'src' => $fullIconUrl,
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src' => $fullIconUrl,
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
        ],
    ])->header('Content-Type', 'application/manifest+json');
})->name('pwa.manifest');
