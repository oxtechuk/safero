<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $rosiport = Customer::firstOrCreate(
            ['name' => 'Rosiport'],
            [
                'contact_person' => 'Eng. Technical Manager',
                'phone' => '+201000000001',
                'email' => 'info@rosiport.com',
                'address' => 'Dabaa NPP, Matrouh, Egypt',
            ]
        );

        $craneCo = Customer::firstOrCreate(
            ['name' => 'International Company for Crane'],
            [
                'contact_person' => 'Operations Manager',
                'phone' => '+201000000002',
                'email' => 'crane@international-crane.com',
                'address' => 'Owner Yard, Alexandria, Egypt',
            ]
        );

        $morgan = Customer::firstOrCreate(
            ['name' => 'Morgan international transport & marine'],
            [
                'contact_person' => 'Fleet Supervisor',
                'phone' => '+201000000003',
                'email' => 'info@morgan-marine.com',
                'address' => 'Om Zoghio - Alexandria, Egypt',
            ]
        );

        // 1. Mobile Telescopic Crane (Liebherr 90 Ton) - 2 Pages Report + Checklist
        Certificate::updateOrCreate(
            ['certificate_number' => 'SAF/RS/2026090101'],
            [
                'uuid' => (string) Str::uuid(),
                'order_no' => '2026-110',
                'customer_id' => $rosiport->id,
                'examination_address' => 'Dabaa NPP',
                'thorough_examination_date' => '2026-09-01',
                'next_examination_due_date' => '2027-03-01',
                'reference_standard' => 'BS 7121-3:2017+A1:2019',
                'equipment_type' => 'crane',
                'equipment_description' => 'Mobile Telescopic Crane',
                'manufacturer' => 'Liebherr',
                'model' => 'LTM1090-1',
                'capacity' => '90 Ton',
                'chassis_no' => 'W09464500SEL05033',
                'main_wire' => '21.0 MM',
                'hook_block' => 'Serial No 91373408E, SWL: 63.0 Ton',
                'load_test_cert_no' => '629984',
                'date_of_load_test' => '2026-09-01',
                'last_examination_date' => '2026-02-10',
                'swl' => '90.0 Tons',
                'equipment_id_range' => '0022489',
                'quantity' => 1,
                'is_first_examination_after_installation' => false,
                'was_examination_within_6_months' => true,
                'has_equipment_installed_correctly' => true,
                'was_examination_within_12_months' => false,
                'in_accordance_with_scheme' => false,
                'after_exceptional_circumstances' => false,
                'defect_identification' => 'NONE',
                'is_defect_danger_to_process' => false,
                'repair_particulars' => 'NONE',
                'tests_carried_out' => "Crane has been tested visually, functional and performance in accordance with the British Standard BS 7121-3:2017+A12019\nTest point:\nBoom length :41.4 m, Radius 10.0 m SWL: 14.2 Ton, Load test: 14.2 Ton",
                'test_point' => 'Boom length :41.4 m, Radius 10.0 m SWL: 14.2 Ton, Load test: 14.2 Ton',
                'checklist_items' => Certificate::defaultCraneChecklist(),
                'is_safe_to_operate' => true,
                'issuer_name' => 'Eng. Mohammed fathy',
                'authenticator_name' => 'Dr. Hany Nabil',
                'status' => 'issued',
            ]
        );

        // 2. Mobile Telescopic Crane (Grove 60 Ton)
        Certificate::updateOrCreate(
            ['certificate_number' => 'SAF/RS/2026101601'],
            [
                'uuid' => (string) Str::uuid(),
                'order_no' => '2026-110',
                'customer_id' => $craneCo->id,
                'examination_address' => 'Owner Yard',
                'thorough_examination_date' => '2026-10-16',
                'next_examination_due_date' => '2027-04-15',
                'reference_standard' => 'BS 7121-3:2017+A1:2019',
                'equipment_type' => 'crane',
                'equipment_description' => 'Mobile Telescopic Crane',
                'manufacturer' => 'Grove',
                'model' => 'TMS 760',
                'capacity' => '60 Ton',
                'main_wire' => '17.0 MM',
                'hook_block' => 'Serial No H01, SWL: 50.0 Ton',
                'load_test_cert_no' => '365809',
                'date_of_load_test' => '2022-01-12',
                'last_examination_date' => '2026-04-09',
                'swl' => '60.0 Tons',
                'equipment_id_range' => '8724',
                'quantity' => 1,
                'is_first_examination_after_installation' => false,
                'was_examination_within_6_months' => true,
                'has_equipment_installed_correctly' => true,
                'was_examination_within_12_months' => false,
                'in_accordance_with_scheme' => false,
                'after_exceptional_circumstances' => false,
                'defect_identification' => 'NONE',
                'is_defect_danger_to_process' => false,
                'repair_particulars' => 'NONE',
                'tests_carried_out' => "Crane has been tested visually, functional and performance in accordance with the British Standard BS 7121-3:2017+A12019\nTest point:\nBoom length :25.7 m, Radius 7.5 m SWL: 18 Ton, Load test: 18 Ton",
                'test_point' => 'Boom length :25.7 m, Radius 7.5 m SWL: 18 Ton, Load test: 18 Ton',
                'checklist_items' => Certificate::defaultCraneChecklist(),
                'is_safe_to_operate' => true,
                'issuer_name' => 'Eng. Mohammed fathy',
                'authenticator_name' => 'Dr. Hany Nabil',
                'status' => 'issued',
            ]
        );

        // 3. Lifting Accessories - Round Sling (BS EN 1492-2)
        Certificate::updateOrCreate(
            ['certificate_number' => 'SAF/RS/2026090105'],
            [
                'uuid' => (string) Str::uuid(),
                'customer_id' => $rosiport->id,
                'examination_address' => 'Dabaa NPP',
                'thorough_examination_date' => '2026-09-01',
                'next_examination_due_date' => '2027-03-01',
                'reference_standard' => 'BS EN 1492-2:2000+A1:2008',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Round Sling',
                'manufacturer' => 'Lifting safety',
                'safety_factor' => '7:1',
                'length' => '8.0 meters',
                'swl' => '30.0 Tons',
                'quantity' => 4,
                'equipment_id_range' => "R00772\nR00773\nR00774\nR00775",
                'load_test_cert_no' => 'NONE',
                'is_first_examination_after_installation' => false,
                'was_examination_within_6_months' => true,
                'has_equipment_installed_correctly' => true,
                'was_examination_within_12_months' => false,
                'in_accordance_with_scheme' => true,
                'after_exceptional_circumstances' => false,
                'defect_identification' => 'NONE',
                'is_defect_danger_to_process' => false,
                'repair_particulars' => 'NONE',
                'tests_carried_out' => 'The Flat Woven Webbing Sling / Round Sling has been inspected in accordance with the British Standard BS EN 1492-2:2000+A1:2008',
                'is_safe_to_operate' => true,
                'issuer_name' => 'Eng. Mohammed fathy',
                'authenticator_name' => 'Dr. Hany Nabil',
                'status' => 'issued',
            ]
        );

        // 4. Move Pallet (General Lifting Equipment - ASTM A6)
        Certificate::updateOrCreate(
            ['certificate_number' => 'SAF/2026110807'],
            [
                'uuid' => (string) Str::uuid(),
                'order_no' => '100000',
                'customer_id' => $morgan->id,
                'examination_address' => 'Om Zoghio -Alexandria',
                'thorough_examination_date' => '2026-11-08',
                'next_examination_due_date' => '2027-04-07',
                'reference_standard' => 'ASTM A6',
                'equipment_type' => 'general',
                'equipment_description' => 'Move Pallet',
                'dimensions' => 'Length: 0.48 m Width: 0.22 m Height: 0.19 m',
                'swl' => '70 Ton',
                'quantity' => 6,
                'equipment_id_range' => 'From 01 To 06',
                'date_of_manufacture' => '2009',
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
            ]
        );
    }
}
