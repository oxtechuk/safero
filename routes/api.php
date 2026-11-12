<?php

use App\Http\Controllers\Api\CertificateApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public verification API for WordPress plugin / external validation
Route::get('/v1/certificates/verify/{identifier}', [CertificateApiController::class, 'verify'])
    ->where('identifier', '.*')
    ->name('api.certificates.verify');
