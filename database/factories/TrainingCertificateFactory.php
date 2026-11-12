<?php

namespace Database\Factories;

use App\Models\Trainee;
use App\Models\TrainingCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TrainingCertificate>
 */
class TrainingCertificateFactory extends Factory
{
    protected $model = TrainingCertificate::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'certificate_serial' => fake()->unique()->numerify('2026######'),
            'trainee_id' => Trainee::factory(),
            'trainee_name' => fake()->name(),
            'training_scope' => 'Safe Lifting & Rigging Operations',
            'program_title' => 'Defensive Driving',
            'hours' => 12,
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addYears(2)->toDateString(),
            'instructor_name' => 'Dr. Hany Nabil',
            'training_manager_name' => 'Gehan Mahmoud',
            'accreditation_text' => 'National Quality Institute NQIT 347/686',
            'status' => 'issued',
        ];
    }
}
