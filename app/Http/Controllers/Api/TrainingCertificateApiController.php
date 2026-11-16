<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TrainingCertificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingCertificateApiController extends Controller
{
    /**
     * Verify a training certificate by Serial Number or UUID.
     * Also falls back to Inspection Certificate if needed.
     */
    public function verify(Request $request, ?string $identifier = null): JsonResponse
    {
        $raw = trim((string) ($identifier ?: $request->query('cert') ?: $request->query('serial') ?: $request->query('identifier') ?: $request->query('uuid')));
        if ($raw === '') {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'الرجاء إدخال رقم الشهادة بدقة.',
            ], 400);
        }

        $raw = trim(urldecode($raw));

        $variants = array_unique(array_filter([
            $raw,
            strtoupper($raw),
            str_replace('-', '/', $raw),
            str_replace('/', '-', $raw),
            str_starts_with(strtoupper($raw), 'SAF/') ? substr($raw, 4) : 'SAF/'.$raw,
            str_starts_with(strtoupper($raw), 'SAF-') ? substr($raw, 4) : 'SAF-'.$raw,
        ]));

        // 1. Check Training Certificates first
        $certificate = TrainingCertificate::with('trainee')
            ->whereIn('certificate_serial', $variants)
            ->orWhere('uuid', $raw)
            ->first();

        if ($certificate) {
            $isExpired = $certificate->expiry_date ? $certificate->expiry_date->isPast() : false;
            $isValid = ($certificate->status === 'issued') && ! $isExpired;

            $statusLabel = 'سارية ومعتمدة (Valid & Accredited)';
            $statusCode = 'valid';

            if ($certificate->status === 'revoked') {
                $statusLabel = 'شهادة ملغاة أو مسحوبة (Revoked)';
                $statusCode = 'revoked';
            } elseif ($isExpired) {
                $statusLabel = 'شهادة منتهية الصلاحية (Expired)';
                $statusCode = 'expired';
            } elseif ($certificate->status === 'draft') {
                $statusLabel = 'مسودة غير معتمدة (Draft)';
                $statusCode = 'draft';
            }

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'certificate_type' => 'training',
                'status_code' => $statusCode,
                'status_label' => $statusLabel,
                'data' => [
                    'certificate_type' => 'training',
                    'certificate_serial' => $certificate->certificate_serial,
                    'certificate_number' => $certificate->certificate_serial,
                    'uuid' => $certificate->uuid,
                    'trainee_name' => $certificate->trainee_name,
                    'customer_name' => $certificate->trainee_name,
                    'training_scope' => $certificate->training_scope,
                    'program_title' => $certificate->program_title,
                    'hours' => $certificate->hours,
                    'issue_date' => $certificate->issue_date->format('Y-m-d'),
                    'expiry_date' => $certificate->expiry_date?->format('Y-m-d'),
                    'instructor_name' => $certificate->instructor_name,
                    'training_manager_name' => $certificate->training_manager_name,
                    'accreditation_text' => $certificate->accreditation_text,
                    'embed_url' => route('training-certificates.embed', ['uuid' => $certificate->uuid]),
                    'verification_url' => route('training-certificates.verify', ['uuid' => $certificate->uuid]),
                    'pdf_url' => route('training-certificates.pdf', ['trainingCertificate' => $certificate->id]),
                    'pdf_download_url' => route('training-certificates.pdf', ['trainingCertificate' => $certificate->id]).'?download=1',
                    'pdf_view_url' => route('training-certificates.pdf', ['trainingCertificate' => $certificate->id]),
                ],
            ]);
        }

        // 2. Fallback to Inspection Certificate if searched here
        $inspectionCert = Certificate::with('customer')
            ->whereIn('certificate_number', $variants)
            ->orWhere('uuid', $raw)
            ->first();

        if ($inspectionCert) {
            return app(CertificateApiController::class)->verify($request, $raw);
        }

        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => 'لم يتم العثور على شهادة بهذا الرقم في سجلات شركة سافيرو (Safero). يرجى التأكد من كتابة الرقم بدقة.',
            'identifier' => $raw,
        ], 404);
    }
}
