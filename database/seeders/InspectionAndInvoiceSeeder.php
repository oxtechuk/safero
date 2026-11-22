<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Customer;
use App\Models\Inspection;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InspectionAndInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            return;
        }

        $user = User::first();
        $templates = CertificateTemplate::all();

        // 1. Create a sample completed Inspection
        $cust1 = $customers->first();
        $tpl1 = $templates->where('code', 'SAF-INS-01')->first() ?: $templates->first();

        $checklist = $tpl1->checklist_items ?? [];
        foreach ($checklist as &$item) {
            $item['status'] = 'pass';
            $item['remarks'] = 'Passed inspection criteria.';
        }

        $inspection1 = Inspection::updateOrCreate(
            ['inspection_number' => 'INS-2026-0001'],
            [
                'customer_id' => $cust1->id,
                'certificate_template_id' => $tpl1?->id,
                'equipment_type' => $tpl1?->equipment_type ?? 'crane',
                'equipment_name' => $tpl1?->name ?? 'Overhead Travelling Crane 50T',
                'reference_standard' => $tpl1?->reference_standard ?? 'BS 7121-1, BS EN 15011, LOLER 1998',
                'doc_number' => $tpl1?->code ?? 'SAF-INS-01',
                'asset_tag' => 'TAG-CR-01',
                'location' => 'ورشة الصيانة - قطاع أ',
                'manufacturer' => 'Demag',
                'model_no' => 'EKKE 50T',
                'serial_no' => 'SN-DEMAG-98214',
                'year_of_manufacture' => '2021',
                'swl' => '50 Ton',
                'inspection_type' => 'periodic',
                'inspection_date' => Carbon::now()->subDays(5)->toDateString(),
                'next_inspection_date' => Carbon::now()->addMonths(12)->toDateString(),
                'inspector_name' => $user?->name ?? 'Eng. Ahmed Mostafa',
                'inspector_cert_ref' => 'LEEA-CERT-8841',
                'inspector_id' => $user?->id,
                'checklist_results' => $checklist,
                'overall_condition' => 'safe',
                'defects_identified' => 'No critical defects found. All safety limit switches operative.',
                'corrective_actions' => 'Routine monthly lubrication of wire rope recommended.',
                'repeat_examination_required' => false,
                'status' => 'completed',
            ]
        );

        // 2. Create sample Invoices with certificates and discounts
        $certificates = Certificate::all();

        // Invoice 1: Customer 1 with 2 certificates + 10% discount, paid in full
        $inv1 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-0001'],
            [
                'customer_id' => $cust1->id,
                'invoice_date' => Carbon::now()->subDays(10)->toDateString(),
                'due_date' => Carbon::now()->addDays(20)->toDateString(),
                'subtotal' => 6000,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'discount_amount' => 600,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total_amount' => 5400,
                'paid_amount' => 5400,
                'remaining_balance' => 0,
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'notes' => 'شامل خدمات المعايرة والفحص الميداني وفق المواصفات القياسية.',
                'terms' => 'تم استلام المبلغ بموجب إشعار تحويل بنكي.',
                'created_by' => $user?->id,
            ]
        );

        InvoiceItem::updateOrCreate(
            ['invoice_id' => $inv1->id, 'description' => 'فحص ومعايرة أوناش علوية وشهادات الاعتماد الهندسي'],
            [
                'certificate_id' => $certificates->first()?->id,
                'quantity' => 2,
                'unit_price' => 3000,
                'total_price' => 6000,
            ]
        );

        // Invoice 2: Customer 2 with fixed discount (500 EGP), partial payment
        if ($customers->count() > 1) {
            $cust2 = $customers[1];
            $inv2 = Invoice::updateOrCreate(
                ['invoice_number' => 'INV-2026-0002'],
                [
                    'customer_id' => $cust2->id,
                    'invoice_date' => Carbon::now()->subDays(3)->toDateString(),
                    'due_date' => Carbon::now()->addDays(12)->toDateString(),
                    'subtotal' => 4500,
                    'discount_type' => 'fixed',
                    'discount_value' => 500,
                    'discount_amount' => 500,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'total_amount' => 4000,
                    'paid_amount' => 2500,
                    'remaining_balance' => 1500,
                    'payment_status' => 'partial',
                    'payment_method' => 'cash',
                    'notes' => 'دفعة مقدمة 2,500 ج.م والمتبقي 1,500 ج.م يستحق بعد 15 يوم.',
                    'terms' => 'شروط سداد خاصة بالمشروع.',
                    'created_by' => $user?->id,
                ]
            );

            InvoiceItem::updateOrCreate(
                ['invoice_id' => $inv2->id, 'description' => 'فحص واختبار مهمات رفع وحبال سلكية ومطابقة LOLER'],
                [
                    'certificate_id' => $certificates->count() > 1 ? $certificates[1]->id : null,
                    'quantity' => 3,
                    'unit_price' => 1500,
                    'total_price' => 4500,
                ]
            );
        }
    }
}
