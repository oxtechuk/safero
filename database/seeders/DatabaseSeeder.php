<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@safero-eg.com'],
            [
                'name' => 'Admin Safero',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'title' => 'System Administrator',
            ]
        );

        $engineer = User::firstOrCreate(
            ['email' => 'eng.fathy@safero-eg.com'],
            [
                'name' => 'Eng. Mohammed fathy',
                'password' => Hash::make('password'),
                'role' => 'engineer',
                'title' => 'Senior Inspection Engineer',
            ]
        );

        $director = User::firstOrCreate(
            ['email' => 'dr.hany@safero-eg.com'],
            [
                'name' => 'Dr. Hany Nabil',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'title' => 'Technical Managing Director',
            ]
        );

        $customer = Customer::firstOrCreate(
            ['name' => 'Morgan international transport & marine'],
            [
                'contact_person' => 'Captain Tarek Morgan',
                'phone' => '+20 100 123 4567',
                'email' => 'operations@morgan-marine.com',
                'tax_id' => '654-987-321',
                'address' => 'Om Zoghio - Alexandria, Egypt',
                'notes' => 'Major marine transport client in Alexandria port zone.',
            ]
        );

        Customer::firstOrCreate(
            ['name' => 'Alexandria Port Handling Co.'],
            [
                'contact_person' => 'Eng. Sherif Adel',
                'phone' => '+20 122 987 6543',
                'email' => 'tech@alex-port.com',
                'tax_id' => '112-334-556',
                'address' => 'Gate 27, Dekheila Port, Alexandria',
                'notes' => 'Container handling and heavy lifting equipment.',
            ]
        );

        Certificate::firstOrCreate(
            ['certificate_number' => 'SAF/2026110807'],
            [
                'uuid' => (string) Str::uuid(),
                'order_no' => '100000',
                'customer_id' => $customer->id,
                'examination_address' => 'Om Zoghio - Alexandria',
                'thorough_examination_date' => '2026-11-08',
                'next_examination_due_date' => '2027-04-07',
                'reference_standard' => 'ASTM A6',
                'equipment_description' => 'Move Pallet',
                'equipment_id_range' => 'From 01 To 06',
                'date_of_manufacture' => '2009',
                'dimensions' => 'Length: 0.48 m Width: 0.22 m Height: 0.19 m',
                'swl' => '70 Ton',
                'quantity' => 6,
                'is_first_examination_after_installation' => false,
                'was_examination_within_6_months' => true,
                'has_equipment_installed_correctly' => true,
                'was_examination_within_12_months' => false,
                'in_accordance_with_scheme' => false,
                'after_exceptional_circumstances' => false,
                'defect_identification' => 'NONE',
                'is_defect_danger_to_process' => false,
                'repair_particulars' => 'NONE',
                'tests_carried_out' => 'Visual Inspection, Function Test Were Carried Out and ACCEPTED.',
                'is_safe_to_operate' => true,
                'issuer_name' => 'Eng. Mohammed fathy',
                'authenticator_name' => 'Dr. Hany Nabil',
                'status' => 'issued',
                'created_by' => $engineer->id,
            ]
        );
    }
}
