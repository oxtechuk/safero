<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'title',
        'category',
        'amount',
        'expense_date',
        'payment_method',
        'paid_to',
        'reference_no',
        'notes',
        'receipt_attachment',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function categories(): array
    {
        return [
            'transportation' => [
                'ar' => 'مصاريف انتقال وسيارة فحص',
                'en' => 'Transportation & Field Vehicle',
                'color' => 'blue',
            ],
            'salaries' => [
                'ar' => 'رواتب وأتعاب مفتشين ومدربين',
                'en' => 'Inspector & Trainer Fees / Salaries',
                'color' => 'purple',
            ],
            'equipment' => [
                'ar' => 'صيانة وأجهزة فحص ومعايرة',
                'en' => 'Inspection Tools & Calibration Maintenance',
                'color' => 'amber',
            ],
            'office' => [
                'ar' => 'مصاريف إدارية ومكتبية وتأجير',
                'en' => 'Office & Administrative Expenses',
                'color' => 'slate',
            ],
            'certifications' => [
                'ar' => 'رسوم اعتمادات وهيئات دولية',
                'en' => 'Accreditation & Regulatory Fees',
                'color' => 'emerald',
            ],
            'hospitality' => [
                'ar' => 'ضيافة وبوفيه واستقبال',
                'en' => 'Hospitality & Refreshments',
                'color' => 'orange',
            ],
            'other' => [
                'ar' => 'نثريات ومصروفات أخرى',
                'en' => 'General & Miscellaneous',
                'color' => 'gray',
            ],
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        $cats = self::categories();
        $locale = app()->getLocale();

        return $cats[$this->category][$locale] ?? ($cats[$this->category]['ar'] ?? $this->category);
    }

    public static function generateNextNumber(?string $date = null): string
    {
        $year = $date ? Carbon::parse($date)->format('Y') : Carbon::now()->format('Y');
        $prefix = "EXP-{$year}-";

        $lastRecord = self::where('expense_number', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (! $lastRecord) {
            return "{$prefix}0001";
        }

        $lastNumber = (int) str_replace($prefix, '', $lastRecord->expense_number);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$nextNumber}";
    }
}
