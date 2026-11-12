<?php

namespace Database\Factories;

use App\Models\Trainee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trainee>
 */
class TraineeFactory extends Factory
{
    protected $model = Trainee::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'national_id' => fake()->numerify('##############'),
            'company' => fake()->company(),
            'job_title' => 'Lifting & Rigging Specialist',
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'notes' => fake()->sentence(),
        ];
    }
}
