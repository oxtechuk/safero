<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Mobile Telescopic Crane 90T Template
        CertificateTemplate::updateOrCreate(
            ['code' => 'TMP-CRANE-90T'],
            [
                'name' => 'ونش تلسكوبي متنقل 90 طن (Mobile Crane - Liebherr)',
                'equipment_type' => 'crane',
                'equipment_description' => 'Mobile Telescopic Crane (Liebherr LTM 1090-4.1)',
                'reference_standard' => 'EN 13000 / ASME B30.5 / LOLER 1998',
                'swl' => '90 Ton',
                'manufacturer' => 'Liebherr',
                'model' => 'LTM 1090-4.1',
                'capacity' => '90 Ton @ 3.0 m radius',
                'dimensions' => 'Boom Length: 50 m + 19 m Fly Jib',
                'safety_factor' => '1.25',
                'length' => '50 m',
                'validity_months' => 6,
                'tests_carried_out' => 'Visual Inspection, Functional & Safety Devices Testing, Outriggers & Chassis NDT Examination Were Carried Out and ACCEPTED.',
                'defect_identification' => 'NONE',
                'repair_particulars' => 'NONE',
                'checklist_items' => Certificate::defaultCraneChecklist(),
                'custom_specifications' => [
                    ['name' => 'أداة قياس عزم التثبيت (Torque Wrench)', 'type' => 'tool', 'value' => 'Norbar 1000 Nm (Calibrated)'],
                    ['name' => 'جهاز فحص NDT بالموجات فوق الصوتية', 'type' => 'equipment', 'value' => 'Olympus Epoch 650 (Cert #NDT-2026/89)'],
                    ['name' => 'جهاز اختبار أجهزة الأمان (LMI Calibration)', 'type' => 'safety_system', 'value' => 'PAT Hirschmann SafeLoad System'],
                    ['name' => 'دورة الفحص القانونية الدورية', 'type' => 'compliance', 'value' => 'كل 6 أشهر طبقاً للوائح LOLER'],
                ],
                'notes' => 'قالب فحص معتمد مخصص لرافعات المشروعات الكبرى (المحطات النووية ومواقع البترول والغاز).',
                'is_active' => true,
                'is_default' => true,
            ]
        );

        // 2. Round Polyester Webbing Sling 30T Template
        CertificateTemplate::updateOrCreate(
            ['code' => 'TMP-SLING-30T'],
            [
                'name' => 'وايرات قماشية دائرية 30 طن (Round Polyester Sling)',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Endless Round Polyester Webbing Sling (Color: Orange)',
                'reference_standard' => 'BS EN 1492-2 / ASME B30.9',
                'swl' => '30 Ton (Straight Lift) / 24 Ton (Choked)',
                'manufacturer' => 'SpanSet / Gunnebo',
                'model' => 'Magnum-X 30T',
                'capacity' => '30 Ton',
                'dimensions' => 'Effective Working Length: 6.0 m',
                'safety_factor' => '7:1',
                'length' => '6.0 m',
                'validity_months' => 6,
                'tests_carried_out' => 'Thorough Visual Examination, Core Load-bearing Fibers Inspection & Dimensional Verification carried out and ACCEPTED.',
                'defect_identification' => 'NONE',
                'repair_particulars' => 'NONE',
                'checklist_items' => CertificateTemplate::defaultAccessoryChecklist(),
                'custom_specifications' => [
                    ['name' => 'مادة الصنع والألياف الأساسية', 'type' => 'specification', 'value' => '100% High Tenacity Polyester (PES)'],
                    ['name' => 'معامل الأمان الصناعي المعتمد', 'type' => 'standard', 'value' => '7:1 Design Safety Factor'],
                    ['name' => 'أداة القياس والتحقق البصري', 'type' => 'tool', 'value' => 'Calibrated Steel Tape & Thickness Gauge'],
                    ['name' => 'درجة حرارة التشغيل المسموحة', 'type' => 'limit', 'value' => '-40°C to +100°C'],
                ],
                'notes' => 'قالب مخصص لمهمات الرفع البحرية والمصانع الثقيلة وفق الكود البريطاني BS EN 1492-2.',
                'is_active' => true,
                'is_default' => true,
            ]
        );

        // 3. Safety Bow Shackles 55T Template
        CertificateTemplate::updateOrCreate(
            ['code' => 'TMP-SHACKLE-55T'],
            [
                'name' => 'شكالات رفع أمان فولاذية 55 طن (Safety Bow Shackles - Crosby)',
                'equipment_type' => 'accessory',
                'equipment_description' => 'Safety Bow Shackle with Bolt, Nut & Cotter Pin Grade 80',
                'reference_standard' => 'EN 13889 / US FED RR-C-271D / ASME B30.26',
                'swl' => '55 Ton',
                'manufacturer' => 'Crosby Group',
                'model' => 'G-2130 Bolt Type',
                'capacity' => '55 Ton',
                'dimensions' => 'Body Dia: 2.5 in | Pin Dia: 2.75 in',
                'safety_factor' => '6:1',
                'length' => 'Jaw Width: 105 mm',
                'validity_months' => 6,
                'tests_carried_out' => 'Visual Examination, Magnetic Particle Inspection (MPI) on Pin & Body, Dimension Checks ACCEPTED.',
                'defect_identification' => 'NONE',
                'repair_particulars' => 'NONE',
                'checklist_items' => CertificateTemplate::defaultAccessoryChecklist(),
                'custom_specifications' => [
                    ['name' => 'طريقة الفحص غير الهدام (NDT)', 'type' => 'equipment', 'value' => 'Magnetic Particle Inspection (MPI - ASNT Level II)'],
                    ['name' => 'مادة ومعدن التصنيع', 'type' => 'specification', 'value' => 'Quenched and Tempered Alloy Steel (Grade 8)'],
                    ['name' => 'نوع مسمار الأمان وقفل التثبيت', 'type' => 'safety', 'value' => 'Safety Bolt with Hex Nut and Split Pin'],
                ],
                'notes' => 'قالب مخصص للشكالات الثقيلة في عمليات الرفع البرية والبحرية.',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        // 4. Heavy Duty Cargo Move Pallet 70T Template
        CertificateTemplate::updateOrCreate(
            ['code' => 'TMP-PALLET-70T'],
            [
                'name' => 'باليتات ومعدات شحن بحري 70 طن (Cargo Move Pallet Unit)',
                'equipment_type' => 'general',
                'equipment_description' => 'Heavy Duty Move Pallet for Offshore & Marine Cargo Transport',
                'reference_standard' => 'ASTM A6 / DNV 2.7-1 / EN 12079',
                'swl' => '70 Ton',
                'manufacturer' => 'Safero Fabrication Services',
                'model' => 'MP-70 HD',
                'capacity' => 'Max Gross Weight: 85 Ton',
                'dimensions' => 'Length: 0.48 m | Width: 0.22 m | Height: 0.19 m',
                'safety_factor' => '2.5:1',
                'length' => '0.48 m',
                'validity_months' => 12,
                'tests_carried_out' => 'Visual Inspection, Structural Integrity Verification, Pad-eyes MPI Test Were Carried Out and ACCEPTED.',
                'defect_identification' => 'NONE',
                'repair_particulars' => 'NONE',
                'checklist_items' => CertificateTemplate::defaultGeneralChecklist(),
                'custom_specifications' => [
                    ['name' => 'نقاط الرفع المعلقة (Pad Eyes)', 'type' => 'equipment', 'value' => '4-Point Lift Lug Configuration with MPI Accepted'],
                    ['name' => 'حماية السطح والدهان البحري', 'type' => 'specification', 'value' => 'Hot Dip Galvanized + 3-Coat Marine Epoxy'],
                    ['name' => 'دورة الفحص المعتمدة', 'type' => 'compliance', 'value' => 'فحص شامل سنوي (كل 12 شهراً)'],
                ],
                'notes' => 'قالب مخصص لوحدات الشحن بالباليتات والمعدات العامة ومهمات النقل البحري والبري.',
                'is_active' => true,
                'is_default' => true,
            ]
        );
    }
}
