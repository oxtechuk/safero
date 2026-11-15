<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_number',
        'title_ar',
        'title_en',
        'customer_id',
        'equipment_type',
        'equipment_description',
        'equipment_identifier',
        'assigned_inspector_id',
        'scheduled_date',
        'due_date',
        'status',
        'priority',
        'examination_location',
        'checklist_items',
        'certificate_id',
        'google_sheet_row_id',
        'synced_to_sheets_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'due_date' => 'date',
            'synced_to_sheets_at' => 'datetime',
            'checklist_items' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_inspector_id');
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public static function generateNextTaskNumber(): string
    {
        $year = date('Y');
        $prefix = "TSK-{$year}-";

        $lastTask = self::where('task_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($lastTask && preg_match('/TSK-\d{4}-(\d+)/', $lastTask->task_number, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    public static function defaultChecklist(string $equipmentType = 'crane'): array
    {
        if ($equipmentType === 'accessory') {
            return [
                ['id' => 1, 'item_ar' => 'هيكل الحبال السلكية وتآكل الأسلاك', 'item_en' => 'Wire rope body condition & broken wires', 'status' => 'pass', 'notes' => ''],
                ['id' => 2, 'item_ar' => 'سلامة العيون والمكابس (Thimbles & Ferrules)', 'item_en' => 'Thimbles and swaged ferrule terminals', 'status' => 'pass', 'notes' => ''],
                ['id' => 3, 'item_ar' => 'الشكالات وبنوز التثبيت وخلوها من الاعوجاج', 'item_en' => 'Shackles, pins, threads & distortion free', 'status' => 'pass', 'notes' => ''],
                ['id' => 4, 'item_ar' => 'سلاسل الرفع وحلقات الربط وخلوها من الاستطالة', 'item_en' => 'Chain sling links and elongation check', 'status' => 'pass', 'notes' => ''],
                ['id' => 5, 'item_ar' => 'أحزمة الرفع النسيجية وخلوها من القطوع والحرارة', 'item_en' => 'Webbing slings, stitching, cuts & burns', 'status' => 'pass', 'notes' => ''],
                ['id' => 6, 'item_ar' => 'وضوح علامات حمل التشغيل الآمن (SWL Tag)', 'item_en' => 'SWL identification tag legibility & certification', 'status' => 'pass', 'notes' => ''],
            ];
        }

        if ($equipmentType === 'general') {
            return [
                ['id' => 1, 'item_ar' => 'سلامة الهيكل الإنشائي واللحامات الرئيسية', 'item_en' => 'Main structural frame and weld integrity', 'status' => 'pass', 'notes' => ''],
                ['id' => 2, 'item_ar' => 'مجاري الشوكات ونقاط الرفع المحددة', 'item_en' => 'Forklift pockets and designated lifting lugs', 'status' => 'pass', 'notes' => ''],
                ['id' => 3, 'item_ar' => 'أبواب الإغلاق والأقفال وسقاطات الأمان', 'item_en' => 'Doors, safety catches and locking pins', 'status' => 'pass', 'notes' => ''],
                ['id' => 4, 'item_ar' => 'وضوح بيانات الحمولة الصافية والوزن الفارغ (Tare/Gross)', 'item_en' => 'Payload, Tare weight and Gross capacity markings', 'status' => 'pass', 'notes' => ''],
            ];
        }

        // Default: 28-point LOLER Crane Checklist
        return Certificate::defaultCraneChecklist();
    }

    public function checklistSummary(): array
    {
        $items = $this->checklist_items ?? [];
        if (empty($items)) {
            return ['total' => 0, 'pass' => 0, 'fail' => 0, 'na' => 0, 'percent' => 0];
        }

        $total = count($items);
        $pass = 0;
        $fail = 0;
        $na = 0;

        foreach ($items as $item) {
            $status = strtolower($item['status'] ?? 'pass');
            if ($status === 'pass' || $status === 'yes' || $status === 's') {
                $pass++;
            } elseif ($status === 'fail' || $status === 'no' || $status === 'u') {
                $fail++;
            } else {
                $na++;
            }
        }

        $effectiveTotal = max(1, $total - $na);
        $percent = (int) round(($pass / $effectiveTotal) * 100);

        return [
            'total' => $total,
            'pass' => $pass,
            'fail' => $fail,
            'na' => $na,
            'percent' => min(100, max(0, $percent)),
        ];
    }

    public function statusLabel(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        $labels = [
            'draft' => ['ar' => 'مسودة أمر شغل', 'en' => 'Draft'],
            'scheduled' => ['ar' => 'مجدولة بانتظار الفحص', 'en' => 'Scheduled'],
            'in_progress' => ['ar' => 'قيد الفحص الميداني', 'en' => 'In Progress'],
            'completed' => ['ar' => 'فحص مكتمل بانتظار الاعتماد', 'en' => 'Completed'],
            'certified' => ['ar' => 'تم إصدار الشهادة الرسمية', 'en' => 'Certified'],
            'cancelled' => ['ar' => 'ملغاة', 'en' => 'Cancelled'],
        ];

        return $labels[$this->status][$locale] ?? $this->status;
    }

    public function priorityLabel(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        $labels = [
            'low' => ['ar' => 'منخفضة', 'en' => 'Low'],
            'normal' => ['ar' => 'عادية', 'en' => 'Normal'],
            'high' => ['ar' => 'عالية ومستعجلة', 'en' => 'High'],
            'urgent' => ['ar' => 'طارئة وفورية', 'en' => 'Urgent'],
        ];

        return $labels[$this->priority][$locale] ?? $this->priority;
    }

    public function equipmentTypeLabel(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        $labels = [
            'crane' => ['ar' => 'أوناش وروافع هيدروليكية', 'en' => 'Cranes & Lifting Appliances'],
            'accessory' => ['ar' => 'مهمات رفع وحبال وسلاسل', 'en' => 'Lifting Accessories & Slings'],
            'general' => ['ar' => 'معدات عامة وباليتات شحن', 'en' => 'General Equipment & Pallets'],
            'environmental' => ['ar' => 'فحص بيئي وقياس انبعاثات', 'en' => 'Environmental Inspection'],
        ];

        return $labels[$this->equipment_type][$locale] ?? $this->equipment_type;
    }
}
