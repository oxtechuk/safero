<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TrainingCertificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateApiController extends Controller
{
    /**
     * Unified verification API for certificates (Inspection & Training).
     * Used by WordPress Integration Plugin on safero-eg.com / external validation.
     */
    public function verify(Request $request, ?string $identifier = null): JsonResponse
    {
        $raw = trim((string) ($identifier ?: $request->query('cert') ?: $request->query('number') ?: $request->query('identifier') ?: $request->query('uuid')));
        if ($raw === '') {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'الرجاء إدخال رقم الشهادة بدقة.',
            ], 400);
        }

        $raw = trim(urldecode($raw));

        // Generate normalization variants (e.g. SAF/2026110807, 2026110807, SAF-2026110807)
        $variants = array_unique(array_filter([
            $raw,
            strtoupper($raw),
            str_replace('-', '/', $raw),
            str_replace('/', '-', $raw),
            str_starts_with(strtoupper($raw), 'SAF/') ? substr($raw, 4) : 'SAF/'.$raw,
            str_starts_with(strtoupper($raw), 'SAF-') ? substr($raw, 4) : 'SAF-'.$raw,
        ]));

        // 1. First, search Inspection Certificates
        $certificate = Certificate::with('customer')
            ->whereIn('certificate_number', $variants)
            ->orWhere('uuid', $raw)
            ->first();

        if ($certificate) {
            return $this->formatInspectionResponse($certificate);
        }

        // 2. Second, search Training Certificates (Academy)
        $trainingCertificate = TrainingCertificate::with('trainee')
            ->whereIn('certificate_serial', $variants)
            ->orWhere('uuid', $raw)
            ->first();

        if ($trainingCertificate) {
            return $this->formatTrainingResponse($trainingCertificate);
        }

        // 3. Not found in either
        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => 'لم يتم العثور على أي شهادة (فحص أو تدريب) مسجلة بهذا الرقم في سجلات شركة سافيرو (Safero). يرجى التأكد من كتابة الرقم بدقة.',
            'identifier' => $raw,
        ], 404);
    }

    /**
     * Format response for inspection certificates.
     */
    protected function formatInspectionResponse(Certificate $certificate): JsonResponse
    {
        $isExpired = $certificate->next_examination_due_date ? $certificate->next_examination_due_date->isPast() : false;
        $isValid = ($certificate->status === 'issued') && $certificate->is_safe_to_operate && ! $isExpired;

        $statusLabel = 'سارية ومطابقة للمعايير (Valid & Approved)';
        $statusCode = 'valid';

        if ($certificate->status === 'revoked') {
            $statusLabel = 'شهادة ملغاة أو مسحوبة (Revoked)';
            $statusCode = 'revoked';
        } elseif ($isExpired) {
            $statusLabel = 'شهادة منتهية - مستحقة لإعادة الفحص (Expired - Due for Re-examination)';
            $statusCode = 'expired';
        } elseif (! $certificate->is_safe_to_operate) {
            $statusLabel = 'المعدة غير آمنة للتشغيل (Not Safe to Operate)';
            $statusCode = 'unsafe';
        }

        return response()->json([
            'success' => true,
            'valid' => $isValid,
            'certificate_type' => 'inspection',
            'status_code' => $statusCode,
            'status_label' => $statusLabel,
            'data' => [
                'certificate_type' => 'inspection',
                'certificate_number' => $certificate->certificate_number,
                'certificate_serial' => $certificate->certificate_number,
                'uuid' => $certificate->uuid,
                'order_no' => $certificate->order_no,
                'customer_name' => $certificate->customer?->name,
                'examination_address' => $certificate->examination_address,
                'thorough_examination_date' => $certificate->thorough_examination_date?->format('Y-m-d'),
                'next_examination_due_date' => $certificate->next_examination_due_date?->format('Y-m-d'),
                'reference_standard' => $certificate->reference_standard,
                'equipment_description' => $certificate->equipment_description,
                'equipment_id_range' => $certificate->equipment_id_range,
                'date_of_manufacture' => $certificate->date_of_manufacture,
                'dimensions' => $certificate->dimensions,
                'swl' => $certificate->swl,
                'quantity' => $certificate->quantity,
                'is_safe_to_operate' => $certificate->is_safe_to_operate,
                'tests_carried_out' => $certificate->tests_carried_out,
                'inspector_name' => $certificate->issuer_name,
                'authenticator_name' => $certificate->authenticator_name,
                'verification_url' => route('certificates.verify', ['uuid' => $certificate->uuid]),
                'pdf_url' => route('certificates.pdf', ['certificate' => $certificate->id]),
            ],
        ]);
    }

    /**
     * Format response for training certificates.
     */
    protected function formatTrainingResponse(TrainingCertificate $trainingCertificate): JsonResponse
    {
        $isExpired = $trainingCertificate->expiry_date ? $trainingCertificate->expiry_date->isPast() : false;
        $isValid = ($trainingCertificate->status === 'issued') && ! $isExpired;

        $statusLabel = 'سارية ومعتمدة (Valid & Accredited)';
        $statusCode = 'valid';

        if ($trainingCertificate->status === 'revoked') {
            $statusLabel = 'شهادة ملغاة أو مسحوبة (Revoked)';
            $statusCode = 'revoked';
        } elseif ($isExpired) {
            $statusLabel = 'شهادة منتهية الصلاحية (Expired)';
            $statusCode = 'expired';
        } elseif ($trainingCertificate->status === 'draft') {
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
                'certificate_serial' => $trainingCertificate->certificate_serial,
                'certificate_number' => $trainingCertificate->certificate_serial,
                'uuid' => $trainingCertificate->uuid,
                'trainee_name' => $trainingCertificate->trainee_name,
                'customer_name' => $trainingCertificate->trainee_name,
                'training_scope' => $trainingCertificate->training_scope,
                'program_title' => $trainingCertificate->program_title,
                'hours' => $trainingCertificate->hours,
                'issue_date' => $trainingCertificate->issue_date->format('Y-m-d'),
                'expiry_date' => $trainingCertificate->expiry_date?->format('Y-m-d'),
                'instructor_name' => $trainingCertificate->instructor_name,
                'training_manager_name' => $trainingCertificate->training_manager_name,
                'accreditation_text' => $trainingCertificate->accreditation_text,
                'embed_url' => route('training-certificates.embed', ['uuid' => $trainingCertificate->uuid]),
                'verification_url' => route('training-certificates.verify', ['uuid' => $trainingCertificate->uuid]),
                'pdf_url' => route('training-certificates.pdf', ['trainingCertificate' => $trainingCertificate->id]),
            ],
        ]);
    }
}
