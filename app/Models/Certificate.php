<?php

namespace App\Models;

use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'certificate_number',
        'order_no',
        'customer_id',
        'examination_address',
        'thorough_examination_date',
        'next_examination_due_date',
        'reference_standard',
        'equipment_description',
        'equipment_type',
        'manufacturer',
        'model',
        'capacity',
        'chassis_no',
        'main_wire',
        'hook_block',
        'load_test_cert_no',
        'date_of_load_test',
        'last_examination_date',
        'test_point',
        'safety_factor',
        'length',
        'checklist_items',
        'equipment_id_range',
        'date_of_manufacture',
        'dimensions',
        'swl',
        'quantity',
        'is_first_examination_after_installation',
        'was_examination_within_6_months',
        'has_equipment_installed_correctly',
        'was_examination_within_12_months',
        'in_accordance_with_scheme',
        'after_exceptional_circumstances',
        'defect_identification',
        'is_defect_danger_to_process',
        'repair_particulars',
        'tests_carried_out',
        'is_safe_to_operate',
        'issuer_name',
        'authenticator_name',
        'status',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'thorough_examination_date' => 'date',
            'next_examination_due_date' => 'date',
            'date_of_load_test' => 'date',
            'last_examination_date' => 'date',
            'checklist_items' => 'array',
            'is_first_examination_after_installation' => 'boolean',
            'was_examination_within_6_months' => 'boolean',
            'has_equipment_installed_correctly' => 'boolean',
            'was_examination_within_12_months' => 'boolean',
            'in_accordance_with_scheme' => 'boolean',
            'after_exceptional_circumstances' => 'boolean',
            'is_defect_danger_to_process' => 'boolean',
            'is_safe_to_operate' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    public static function defaultCraneChecklist(): array
    {
        return [
            // CARRIER, CABIN & ENGINE
            ['no' => 1, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Outriggers', 'question' => 'Outriggers', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 2, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Leveling Indicator', 'question' => 'Leveling Indicator', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 3, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Carrier Including Engine', 'question' => 'Carrier Including Engine', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 4, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Steering, Brakes & tires / Crawler', 'question' => 'Steering, Brakes & tires / Crawler', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 5, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Cabin - Cranes Controls', 'question' => 'Cabin - Cranes Controls', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 6, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Power Unit', 'question' => 'Power Unit', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 7, 'category' => 'Carrier, Cabin & Controls', 'item' => 'Braking / Holding', 'question' => 'Braking / Holding', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],

            // HYDRAULIC & SLEWING SYSTEMS
            ['no' => 8, 'category' => 'Hydraulic & Slewing Systems', 'item' => 'Hydraulic Systems', 'question' => 'Hydraulic Systems', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 9, 'category' => 'Hydraulic & Slewing Systems', 'item' => 'Turntable Bearing', 'question' => 'Turntable Bearing', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 10, 'category' => 'Hydraulic & Slewing Systems', 'item' => 'Slewing System & Mechanism', 'question' => 'Slewing System & Mechanism', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 11, 'category' => 'Hydraulic & Slewing Systems', 'item' => 'Ballast Weight / Counterweight', 'question' => 'Ballast Weight / Counterweight', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],

            // BOOM, WINCHES, WIRE ROPES & HOOKS
            ['no' => 12, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Boom Structure, Sections', 'question' => 'Boom Structure, Sections', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 13, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Cathead Structure', 'question' => 'Cathead Structure', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 14, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Pendants', 'question' => 'Pendants', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 15, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Wire Rope – Main', 'question' => 'Wire Rope – Main', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 16, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Wire Rope - Auxiliary', 'question' => 'Wire Rope - Auxiliary', 'status' => 'N/A', 'check' => 'N/A', 'remarks' => 'Not Applicable', 'comment' => 'Not Applicable'],
            ['no' => 17, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Main Hook', 'question' => 'Main Hook', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 18, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Auxiliary Hook', 'question' => 'Auxiliary Hook', 'status' => 'N/A', 'check' => 'N/A', 'remarks' => 'Not Applicable', 'comment' => 'Not Applicable'],
            ['no' => 19, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Wire Rope Terminations', 'question' => 'Wire Rope Terminations', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 20, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Main Hoist Winch', 'question' => 'Main Hoist Winch', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 21, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Auxiliary Hoist Winch', 'question' => 'Auxiliary Hoist Winch', 'status' => 'N/A', 'check' => 'N/A', 'remarks' => 'Not Applicable', 'comment' => 'Not Applicable'],
            ['no' => 22, 'category' => 'Boom, Winches, Ropes & Hooks', 'item' => 'Luffing Hoist Machinery', 'question' => 'Luffing Hoist Machinery', 'status' => 'N/A', 'check' => 'N/A', 'remarks' => 'Not Applicable', 'comment' => 'Not Applicable'],

            // SAFETY & INSTRUMENTATION
            ['no' => 23, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Safe Load Indicator (Limiter)', 'question' => 'Safe Load Indicator (Limiter)', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 24, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Crane Load Chart', 'question' => 'Crane Load Chart', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 25, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Angel Indicator', 'question' => 'Angel Indicator', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 26, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Crane Limit Switches', 'question' => 'Crane Limit Switches', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 27, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Electric/ Spark Arrestor/ Fire Extinguisher/Reverse Alarm', 'question' => 'Electric/ Spark Arrestor/ Fire Extinguisher/Reverse Alarm', 'status' => 'Pass', 'check' => 'In Order', 'remarks' => 'Good condition', 'comment' => ''],
            ['no' => 28, 'category' => 'Safety, Alarms & Instrumentation', 'item' => 'Record of Major Repairs / Alterations / Replacements', 'question' => 'Record of Major Repairs / Alterations / Replacements', 'status' => 'N/A', 'check' => 'N/A', 'remarks' => 'Not Applicable', 'comment' => 'Not Applicable'],
        ];
    }

    public static function generateNextNumber(?string $date = null): string
    {
        $baseDate = $date ? Carbon::parse($date) : now();
        $datePrefix = $baseDate->format('Ymd');

        $existing = static::where('certificate_number', 'like', 'SAF/'.$datePrefix.'%')
            ->pluck('certificate_number')
            ->map(function ($num) use ($datePrefix) {
                $suffix = substr($num, strlen('SAF/'.$datePrefix));

                return is_numeric($suffix) ? (int) $suffix : 0;
            })
            ->filter();

        $maxSuffix = $existing->isNotEmpty() ? $existing->max() : 0;
        $nextSuffix = $maxSuffix + 1;

        $candidate = sprintf('SAF/%s%02d', $datePrefix, $nextSuffix);

        while (static::where('certificate_number', $candidate)->exists()) {
            $nextSuffix++;
            $candidate = sprintf('SAF/%s%02d', $datePrefix, $nextSuffix);
        }

        return $candidate;
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate): void {
            if (empty($certificate->uuid)) {
                $certificate->uuid = (string) Str::uuid();
            }

            if (empty($certificate->certificate_number)) {
                $certificate->certificate_number = static::generateNextNumber($certificate->thorough_examination_date ? $certificate->thorough_examination_date->toDateString() : null);
            }
        });
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getVerificationUrlAttribute(): string
    {
        $wpVerifyUrl = env('WORDPRESS_VERIFY_URL');
        if (! empty($wpVerifyUrl)) {
            $separator = str_contains($wpVerifyUrl, '?') ? '&' : '?';

            return rtrim($wpVerifyUrl, '/').$separator.'cert='.urlencode($this->certificate_number);
        }

        return route('certificates.verify', ['uuid' => $this->uuid]);
    }
}
