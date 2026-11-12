<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingCertificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingCertificateApiController extends Controller
{
    /**
     * Verify a training certificate by Serial Number or UUID.
     * Used by WordPress Integration Plugin on safero-eg.com.
     */
    public function verify(Request $request, string $identifier): JsonResponse
    {
        $identifier = trim($identifier);

        $certificate = TrainingCertificate::with('trainee')
            ->where('certificate_serial', $identifier)
            ->orWhere('uuid', $identifier)
            ->first();

        if (! $certificate) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'لم يتم العثور على شهادة تدريب بهذا الرقم في سجلات شركة سافيرو (Safero). يرجى التأكد من كتابة الرقم بدقة.',
                'identifier' => $identifier,
            ], 404);
        }

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
            'status_code' => $statusCode,
            'status_label' => $statusLabel,
            'data' => [
                'certificate_serial' => $certificate->certificate_serial,
                'uuid' => $certificate->uuid,
                'trainee_name' => $certificate->trainee_name,
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
            ],
        ]);
    }
}
