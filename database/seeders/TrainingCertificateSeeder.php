<?php

namespace Database\Seeders;

use App\Models\Trainee;
use App\Models\TrainingCertificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TrainingCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'name' => 'Mohamed Mahmoud Mahran',
                'serial' => '2025112310',
                'scope' => 'Safe Lifting & Rigging Operations',
                'program' => 'Safe Lifting & Rigging Operations',
                'hours' => 12,
                'issue_date' => '2025-11-22',
                'expiry_date' => '2027-11-21',
                'instructor' => 'Dr. Hany Nabil',
                'manager' => 'Gehan Mahmoud',
            ],
            [
                'name' => 'Mohamed Shaban Ibrahim',
                'serial' => '2025112311',
                'scope' => 'Safe Lifting & Rigging Operations',
                'program' => 'Safe Lifting & Rigging Operations',
                'hours' => 12,
                'issue_date' => '2025-11-22',
                'expiry_date' => '2027-11-21',
                'instructor' => 'Dr. Hany Nabil',
                'manager' => 'Gehan Mahmoud',
            ],
            [
                'name' => 'Mostafa Mohamed Abd Ellatif',
                'serial' => '2025112312',
                'scope' => 'Safe Lifting & Rigging Operations',
                'program' => 'Safe Lifting & Rigging Operations',
                'hours' => 12,
                'issue_date' => '2025-11-22',
                'expiry_date' => '2027-11-21',
                'instructor' => 'Dr. Hany Nabil',
                'manager' => 'Gehan Mahmoud',
            ],
            [
                'name' => 'Mohammed Kamel Abu-al-qasem',
                'serial' => '2026101001',
                'scope' => 'Safe Lifting & Rigging Operations',
                'program' => 'Defensive Driving',
                'hours' => 16,
                'issue_date' => '2026-10-10',
                'expiry_date' => '2028-10-09',
                'instructor' => 'Hany Nabil',
                'manager' => 'Gehan Mahmoud',
            ],
        ];

        foreach ($samples as $data) {
            $trainee = Trainee::firstOrCreate(
                ['name' => $data['name']],
                ['company' => 'Morgan Marine', 'job_title' => 'Lifting & Rigging Specialist']
            );

            TrainingCertificate::updateOrCreate(
                ['certificate_serial' => $data['serial']],
                [
                    'uuid' => (string) Str::uuid(),
                    'trainee_id' => $trainee->id,
                    'trainee_name' => $data['name'],
                    'training_scope' => $data['scope'],
                    'program_title' => $data['program'],
                    'hours' => $data['hours'],
                    'issue_date' => Carbon::parse($data['issue_date']),
                    'expiry_date' => Carbon::parse($data['expiry_date']),
                    'instructor_name' => $data['instructor'],
                    'training_manager_name' => $data['manager'],
                    'accreditation_text' => "Safero services & consultation is accredited by\nMinistry of Industry\nEgyptian organization for standardization & quality\nNational quality Institute\nNQIT 347/686",
                    'status' => 'issued',
                ]
            );
        }
    }
}
