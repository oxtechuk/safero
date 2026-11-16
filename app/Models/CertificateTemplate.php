<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'equipment_type',
        'equipment_description',
        'reference_standard',
        'swl',
        'manufacturer',
        'model',
        'capacity',
        'dimensions',
        'safety_factor',
        'length',
        'validity_months',
        'tests_carried_out',
        'defect_identification',
        'repair_particulars',
        'checklist_items',
        'custom_specifications',
        'notes',
        'is_active',
        'is_default',
        'usage_count',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checklist_items' => 'array',
            'custom_specifications' => 'array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'validity_months' => 'integer',
            'usage_count' => 'integer',
        ];
    }

    public function getDefaultManufacturerAttribute(): ?string
    {
        return $this->manufacturer;
    }

    public function getDefaultModelAttribute(): ?string
    {
        return $this->model;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('equipment_type', $type);
    }

    public function getEquipmentTypeLabelAttribute(): string
    {
        return match ($this->equipment_type) {
            'crane' => 'أوناش وروافع (Cranes)',
            'accessory' => 'مهمات رفع وحبال (Accessories)',
            'general' => 'معدات عامة وباليتات (General Cargo)',
            default => 'معدات مخصصة ('.$this->equipment_type.')',
        };
    }

    public function getEquipmentBadgeClassAttribute(): string
    {
        return match ($this->equipment_type) {
            'crane' => 'bg-blue-50 text-[#0E4DA4] border-blue-200',
            'accessory' => 'bg-amber-50 text-amber-700 border-amber-200',
            'general' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-purple-50 text-purple-700 border-purple-200',
        };
    }

    public static function defaultAccessoryChecklist(): array
    {
        return [
            ['no' => 1, 'category' => 'Visual Examination', 'item' => 'ID Marking & Tagging', 'question' => 'ID Marking & SWL Tag Legibility', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Clear & Legible', 'comment' => ''],
            ['no' => 2, 'category' => 'Visual Examination', 'item' => 'Body & Structure', 'question' => 'Cuts, Tears, Burns or Abrasions', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'None Observed', 'comment' => ''],
            ['no' => 3, 'category' => 'Visual Examination', 'item' => 'Stitching & Seams', 'question' => 'Integrity of Load-bearing Seams', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Sound Condition', 'comment' => ''],
            ['no' => 4, 'category' => 'Fittings & Hardware', 'item' => 'Pins & Threads', 'question' => 'Bow Shackle Pins & Thread Condition', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Free from Cracks', 'comment' => ''],
            ['no' => 5, 'category' => 'Operational Test', 'item' => 'Dimensional & NDT', 'question' => 'Wear / Deformation within Limits (<5%)', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Accepted', 'comment' => ''],
        ];
    }

    public static function defaultGeneralChecklist(): array
    {
        return [
            ['no' => 1, 'category' => 'Structural Integrity', 'item' => 'Welding & Connections', 'question' => 'Main Structural Frame Welds', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Visual / NDT Accepted', 'comment' => ''],
            ['no' => 2, 'category' => 'Structural Integrity', 'item' => 'Pad Eyes & Lifting Lugs', 'question' => 'Lifting Lugs Alignment & Wear', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'No Deformation', 'comment' => ''],
            ['no' => 3, 'category' => 'Marking & SWL', 'item' => 'Identification Plate', 'question' => 'Tare, Payload & Gross Weight Marked', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Legible Plate', 'comment' => ''],
            ['no' => 4, 'category' => 'Safety & Operation', 'item' => 'Surface & Coating', 'question' => 'Corrosion Protection & Paint', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Accepted', 'comment' => ''],
        ];
    }
}
