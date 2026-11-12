<?php

namespace App\Models;

use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate): void {
            if (empty($certificate->uuid)) {
                $certificate->uuid = (string) Str::uuid();
            }

            if (empty($certificate->certificate_number)) {
                $today = now()->format('Ymd');
                $countToday = static::whereDate('created_at', now()->toDateString())->count() + 1;
                $certificate->certificate_number = sprintf('SAF/%s%02d', $today, $countToday);
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
