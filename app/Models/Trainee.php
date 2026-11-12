<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'national_id',
        'company',
        'job_title',
        'phone',
        'email',
        'notes',
    ];

    /**
     * @return HasMany<TrainingCertificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(TrainingCertificate::class);
    }
}
