<?php

use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\TrainingCertificateApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public unified verification API (Supports both Inspection and Training Certificates)
Route::get('/v1/verify/{identifier?}', [CertificateApiController::class, 'verify'])
    ->where('identifier', '.*')
    ->name('api.verify');

// Public verification API for WordPress plugin / external validation (Inspection Certificates)
Route::get('/v1/certificates/verify/{identifier?}', [CertificateApiController::class, 'verify'])
    ->where('identifier', '.*')
    ->name('api.certificates.verify');

// Public verification API for WordPress plugin (Training Certificates)
Route::get('/v1/training-certificates/verify/{identifier?}', [TrainingCertificateApiController::class, 'verify'])
    ->where('identifier', '.*')
    ->name('api.training-certificates.verify');
