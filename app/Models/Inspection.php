<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_number',
        'customer_id',
        'certificate_template_id',
        'equipment_type',
        'equipment_name',
        'reference_standard',
        'doc_number',
        'asset_tag',
        'location',
        'manufacturer',
        'model_no',
        'serial_no',
        'year_of_manufacture',
        'swl',
        'inspection_type',
        'inspection_date',
        'next_inspection_date',
        'inspector_name',
        'inspector_cert_ref',
        'inspector_id',
        'checklist_results',
        'overall_condition',
        'defects_identified',
        'corrective_actions',
        'repeat_examination_required',
        'status',
        'certificate_id',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'next_inspection_date' => 'date',
            'checklist_results' => 'array',
            'repeat_examination_required' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeCertified(Builder $query): Builder
    {
        return $query->where('status', 'certified');
    }

    public static function generateNextInspectionNumber(): string
    {
        $year = date('Y');
        $prefix = "INS-{$year}-";

        $last = self::where('inspection_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($last && preg_match('/INS-\d{4}-(\d+)/', $last->inspection_number, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return array{total: int, pass: int, fail: int, na: int, percent: int}
     */
    public function checklistSummary(): array
    {
        $items = $this->checklist_results ?? [];
        if (empty($items)) {
            return ['total' => 0, 'pass' => 0, 'fail' => 0, 'na' => 0, 'percent' => 0];
        }

        $total = count($items);
        $pass = 0;
        $fail = 0;
        $na = 0;

        foreach ($items as $item) {
            $status = strtolower($item['status'] ?? '');
            if ($status === 'pass' || $status === 'p') {
                $pass++;
            } elseif ($status === 'fail' || $status === 'f') {
                $fail++;
            } elseif ($status === 'na' || $status === 'n/a') {
                $na++;
            }
        }

        $completed = $pass + $fail + $na;
        $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'pass' => $pass,
            'fail' => $fail,
            'na' => $na,
            'percent' => min(100, max(0, $percent)),
        ];
    }
}
