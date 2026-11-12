<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TrainingCertificate extends Model
{
    use HasFactory;

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
                $today = now()->format('Ymd');
                $countToday = static::whereDate('created_at', now()->toDateString())->count() + 1;
                $cert->certificate_serial = sprintf('%s%02d', $today, $countToday);
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
