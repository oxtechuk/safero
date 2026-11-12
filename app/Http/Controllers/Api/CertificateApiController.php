<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateApiController extends Controller
{
    /**
     * Verify a certificate by Certificate Number or UUID.
     * Used by WordPress Integration Plugin on safero-eg.com.
     */
    public function verify(Request $request, string $identifier): JsonResponse
    {
        $identifier = trim($identifier);

        $certificate = Certificate::with('customer')
            ->where('certificate_number', $identifier)
            ->orWhere('uuid', $identifier)
            ->first();

        if (! $certificate) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'لم يتم العثور على شهادة بهذا الرقم في سجلات شركة سافيرو (Safero). يرجى التأكد من كتابة الرقم بدقة.',
                'identifier' => $identifier,
            ], 404);
        }

        $isExpired = $certificate->next_examination_due_date->isPast();
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
            'status_code' => $statusCode,
            'status_label' => $statusLabel,
            'data' => [
                'certificate_number' => $certificate->certificate_number,
                'uuid' => $certificate->uuid,
                'order_no' => $certificate->order_no,
                'customer_name' => $certificate->customer?->name,
                'examination_address' => $certificate->examination_address,
                'thorough_examination_date' => $certificate->thorough_examination_date->format('Y-m-d'),
                'next_examination_due_date' => $certificate->next_examination_due_date->format('Y-m-d'),
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
}
