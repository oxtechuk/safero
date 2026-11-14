<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TrainingCertificate extends Model
{
    use HasFactory;

    public static function generateNextSerial(?string $date = null): string
    {
        $baseDate = $date ? Carbon::parse($date) : now();
        $datePrefix = $baseDate->format('Ymd');

        $serials = static::where('certificate_serial', 'like', $datePrefix.'%')
            ->pluck('certificate_serial')
            ->map(function ($serial) use ($datePrefix) {
                $suffix = substr($serial, strlen($datePrefix));

                return is_numeric($suffix) ? (int) $suffix : 0;
            })
            ->filter();

        $maxSuffix = $serials->isNotEmpty() ? $serials->max() : 0;
        $nextSuffix = $maxSuffix + 1;

        $candidate = sprintf('%s%02d', $datePrefix, $nextSuffix);

        while (static::where('certificate_serial', $candidate)->exists()) {
            $nextSuffix++;
            $candidate = sprintf('%s%02d', $datePrefix, $nextSuffix);
        }

        return $candidate;
    }

    protected $fillable = [
        'uuid',
        'certificate_serial',
        'trainee_id',
        'trainee_name',
        'training_scope',
        'program_title',
        'hours',
        'issue_date',
        'expiry_date',
        'instructor_name',
        'training_manager_name',
        'accreditation_text',
        'status',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'hours' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TrainingCertificate $cert): void {
            if (empty($cert->uuid)) {
                $cert->uuid = (string) Str::uuid();
            }

            if (empty($cert->certificate_serial)) {
                $cert->certificate_serial = static::generateNextSerial($cert->issue_date ? $cert->issue_date->toDateString() : null);
            }

            if (empty($cert->accreditation_text)) {
                $cert->accreditation_text = "Safero services & consultation is accredited by\nMinistry of Industry\nEgyptian organization for standardization & quality\nNational quality Institute\nNQIT 347/686";
            }
        });
    }

    /**
     * @return BelongsTo<Trainee, $this>
     */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
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
        $wpVerifyUrl = env('WORDPRESS_TRAINING_VERIFY_URL', env('WORDPRESS_VERIFY_URL'));
        if (! empty($wpVerifyUrl)) {
            $separator = str_contains($wpVerifyUrl, '?') ? '&' : '?';

            return rtrim($wpVerifyUrl, '/').$separator.'cert='.urlencode($this->certificate_serial);
        }

        return route('training-certificates.verify', ['uuid' => $this->uuid]);
    }

    public function getEmbedUrlAttribute(): string
    {
        return route('training-certificates.embed', ['uuid' => $this->uuid]);
    }
}
