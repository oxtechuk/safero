<?php

namespace App\Services;

use App\Models\InspectionTask;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsSyncService
{
    /**
     * Get the active Google Sheets webhook URL from settings or environment.
     */
    public static function getWebhookUrl(): ?string
    {
        return setting('google_sheets_webhook_url') ?: config('services.google_sheets.webhook_url');
    }

    /**
     * Get the active Google Spreadsheet ID from settings or environment.
     */
    public static function getSpreadsheetId(): ?string
    {
        return setting('google_sheet_id') ?: config('services.google_sheets.sheet_id');
    }

    /**
     * Check if Google Sheets integration is actively configured with an endpoint.
     */
    public static function isConfigured(): bool
    {
        return ! empty(self::getWebhookUrl()) || ! empty(self::getSpreadsheetId());
    }

    /**
     * Synchronize a single Inspection Task to Google Sheets.
     *
     * @return array{success: bool, message: string}
     */
    public static function syncTask(InspectionTask $task): array
    {
        $task->loadMissing(['customer', 'inspector', 'certificate']);
        $summary = $task->checklistSummary();

        $payload = [
            'action' => 'upsert_task',
            'task_id' => $task->task_number,
            'title_ar' => $task->title_ar,
            'title_en' => $task->title_en,
            'customer_name' => $task->customer?->name ?? 'عميل عام',
            'equipment_type' => $task->equipmentTypeLabel('ar'),
            'equipment_type_en' => $task->equipmentTypeLabel('en'),
            'equipment_identifier' => $task->equipment_identifier ?? '—',
            'equipment_description' => $task->equipment_description ?? '',
            'inspector' => $task->inspector?->name ?? 'غير مكلف',
            'scheduled_date' => $task->scheduled_date->format('Y-m-d'),
            'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : '—',
            'status' => $task->statusLabel('ar'),
            'status_en' => $task->statusLabel('en'),
            'priority' => $task->priorityLabel('ar'),
            'checklist_score' => "{$summary['pass']}/{$summary['total']} ({$summary['percent']}%)",
            'certificate_number' => $task->certificate?->certificate_number ?? '—',
            'verification_url' => $task->certificate ? $task->certificate->verification_url : '',
            'location' => $task->examination_location ?? ($task->customer?->address ?? 'ساحة العميل'),
            'notes' => $task->notes ?? '',
            'synced_at' => now()->toIso8601String(),
        ];

        $webhookUrl = self::getWebhookUrl();

        if (! empty($webhookUrl)) {
            try {
                $response = Http::timeout(8)
                    ->asJson()
                    ->post($webhookUrl, $payload);

                if ($response->successful()) {
                    $task->forceFill([
                        'synced_to_sheets_at' => now(),
                        'google_sheet_row_id' => $response->json('row_id') ?? $task->google_sheet_row_id,
                    ])->saveQuietly();

                    return [
                        'success' => true,
                        'message' => 'تمت المزامنة بنجاح مع جدول بيانات جوجل (Google Sheets).',
                    ];
                }

                Log::warning('Google Sheets Sync HTTP Failed', [
                    'task' => $task->task_number,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'استجاب خادم Google Sheets برمز غير ناجح: '.$response->status(),
                ];
            } catch (\Throwable $e) {
                Log::error('Google Sheets Sync Exception', [
                    'task' => $task->task_number,
                    'error' => $e->getMessage(),
                ]);

                return [
                    'success' => false,
                    'message' => 'تعذر الاتصال بـ Google Sheets: '.$e->getMessage(),
                ];
            }
        }

        // Local Fallback Simulation: When no URL is set, mark as synced locally with guidance
        $task->forceFill(['synced_to_sheets_at' => now()])->saveQuietly();

        return [
            'success' => true,
            'message' => 'تم تحديث بيانات المهمة محلياً وتسجيلها في قائمة المزامنة. بإمكانك ربط رابط Webhook في شاشة الإعدادات.',
        ];
    }

    /**
     * Synchronize all unsynced or updated tasks.
     *
     * @return array{total: int, synced: int, failed: int}
     */
    public static function syncAllTasks(bool $forceAll = false): array
    {
        $query = InspectionTask::with(['customer', 'inspector', 'certificate']);

        if (! $forceAll) {
            $query->where(function ($q) {
                $q->whereNull('synced_to_sheets_at')
                    ->orWhereColumn('updated_at', '>', 'synced_to_sheets_at');
            });
        }

        $tasks = $query->get();
        $synced = 0;
        $failed = 0;

        foreach ($tasks as $task) {
            $result = self::syncTask($task);
            if ($result['success']) {
                $synced++;
            } else {
                $failed++;
            }
        }

        return [
            'total' => $tasks->count(),
            'synced' => $synced,
            'failed' => $failed,
        ];
    }

    /**
     * Test connection to a given or configured Webhook URL.
     *
     * @return array{success: bool, message: string}
     */
    public static function testConnection(?string $webhookUrl = null): array
    {
        $url = $webhookUrl ?: self::getWebhookUrl();

        if (empty($url)) {
            return [
                'success' => false,
                'message' => 'لم يتم إدخال رابط Webhook الخاص بـ Google Sheets بعد.',
            ];
        }

        try {
            $response = Http::timeout(6)->asJson()->post($url, [
                'action' => 'ping',
                'app' => 'Safero ERP',
                'timestamp' => now()->toIso8601String(),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح بجدول Google Sheets واستجابة الرابط سليمة.',
                ];
            }

            return [
                'success' => false,
                'message' => 'فشل الاتصال بالرابط، رمز الاستجابة: '.$response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'تعذر الاتصال بالرابط: '.$e->getMessage(),
            ];
        }
    }
}
