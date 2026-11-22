<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Customer;
use App\Models\Inspection;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\AllInspectionTemplatesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InspectionAndInvoiceFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AllInspectionTemplatesSeeder::class);

        $this->user = User::factory()->create([
            'email' => 'admin@safero.com',
            'role' => 'admin',
        ]);

        $this->customer = Customer::create([
            'name' => 'شركة النيل للخدمات البترولية',
            'contact_person' => 'م. حازم',
            'phone' => '01012345678',
            'address' => 'ميناء الإسكندرية',
        ]);
    }

    public function test_all_28_inspection_templates_exist(): void
    {
        $this->actingAs($this->user);

        // Check key templates from the 28 registered ISO 17020 templates
        $codes = ['SAF-INS-01', 'SAF-INS-02', 'SAF-INS-05', 'SAF-INS-10', 'SAF-INS-12', 'SAF-INS-18', 'SAF-INS-22', 'SAF-INS-28'];

        foreach ($codes as $code) {
            $template = CertificateTemplate::where('code', $code)->first();
            $this->assertNotNull($template, "Template {$code} should exist in database");
            $this->assertNotEmpty($template->checklist_items, "Template {$code} should have checklist items");
        }

        $this->assertTrue(CertificateTemplate::count() >= 28);
    }

    public function test_can_start_inspection_and_inspect_checklist(): void
    {
        $this->actingAs($this->user);

        $template = CertificateTemplate::where('code', 'SAF-INS-01')->first();

        // 1. Visit Start Inspection Page
        $response = $this->get(route('inspections.start', ['template_id' => $template->id]));
        $response->assertStatus(200);

        // 2. Store new inspection session
        $storeResponse = $this->post(route('inspections.store'), [
            'customer_id' => $this->customer->id,
            'certificate_template_id' => $template->id,
            'equipment_name' => 'Overhead Travelling Crane 30T',
            'equipment_type' => 'crane',
            'reference_standard' => 'BS 7121-1, BS EN 15011, LOLER 1998',
            'doc_number' => 'SAF-INS-01',
            'asset_tag' => 'TAG-TEST-01',
            'serial_no' => 'SN-CRANE-999',
            'swl' => '30 Ton',
            'inspection_type' => 'periodic',
            'inspection_date' => Carbon::today()->toDateString(),
            'inspector_name' => $this->user->name,
            'inspector_cert_ref' => 'LEEA-8841',
        ]);

        $storeResponse->assertSessionHasNoErrors();
        $storeResponse->assertStatus(302);

        $inspection = Inspection::where('asset_tag', 'TAG-TEST-01')->first();
        $this->assertNotNull($inspection);
        $storeResponse->assertRedirect(route('inspections.inspect', $inspection));

        // 3. Visit inspect screen
        $inspectPage = $this->get(route('inspections.inspect', $inspection));
        $inspectPage->assertStatus(200);

        // 4. Update checklist
        $items = $inspection->checklist_results;
        $this->assertNotEmpty($items);

        $updateResponse = $this->post(route('inspections.update_checklist', $inspection), [
            'checklist' => [
                1 => ['status' => 'pass', 'remarks' => 'Sound structural frame'],
                2 => ['status' => 'pass', 'remarks' => 'Torqued and verified'],
            ],
            'overall_condition' => 'safe',
            'defects_identified' => 'None observed.',
            'corrective_actions' => 'None required.',
            'status' => 'completed',
        ]);

        $updateResponse->assertRedirect(route('inspections.inspect', $inspection));

        $inspection->refresh();
        $this->assertEquals('completed', $inspection->status);
        $this->assertEquals('safe', $inspection->overall_condition);
    }

    public function test_can_convert_inspection_to_official_certificate(): void
    {
        $this->actingAs($this->user);

        $inspection = Inspection::create([
            'inspection_number' => 'INS-TEST-CERT',
            'customer_id' => $this->customer->id,
            'equipment_name' => 'Chain Hoist 5T',
            'equipment_type' => 'hoist',
            'reference_standard' => 'BS EN 13157',
            'doc_number' => 'SAF-INS-05',
            'asset_tag' => 'TAG-HOIST-01',
            'serial_no' => 'SN-HOIST-77',
            'swl' => '5 Ton',
            'inspection_type' => 'periodic',
            'inspection_date' => Carbon::today()->toDateString(),
            'next_inspection_date' => Carbon::today()->addMonths(6)->toDateString(),
            'inspector_name' => 'Eng. Technical Inspector',
            'checklist_results' => [
                ['id' => 1, 'no' => 1, 'item' => 'Load chain condition', 'status' => 'pass', 'remarks' => 'In Order'],
            ],
            'overall_condition' => 'safe',
            'status' => 'completed',
        ]);

        // Stage 2: Convert to Certificate
        $response = $this->post(route('inspections.convert', $inspection));

        $inspection->refresh();
        $this->assertEquals('certified', $inspection->status);
        $this->assertNotNull($inspection->certificate_id);

        $certificate = Certificate::find($inspection->certificate_id);
        $this->assertNotNull($certificate);
        $this->assertEquals($this->customer->id, $certificate->customer_id);
        $this->assertTrue($certificate->is_safe_to_operate);

        $response->assertRedirect(route('certificates.show', $certificate));
    }

    public function test_can_create_invoice_with_certificates_and_discounts(): void
    {
        $this->actingAs($this->user);

        // Ensure at least one certificate exists
        $cert = Certificate::first() ?? Certificate::create([
            'certificate_number' => 'SAF-2026-TEST',
            'customer_id' => $this->customer->id,
            'thorough_examination_date' => Carbon::today()->toDateString(),
            'next_examination_due_date' => Carbon::today()->addMonths(12)->toDateString(),
            'reference_standard' => 'BS 7121',
            'equipment_type' => 'crane',
            'equipment_description' => 'Liebherr 90T Crane',
            'swl' => '90 Ton',
            'quantity' => 1,
            'is_safe_to_operate' => true,
        ]);

        // Create Invoice with 10% discount and partial payment
        $response = $this->post(route('invoices.store'), [
            'customer_id' => $this->customer->id,
            'invoice_date' => Carbon::today()->toDateString(),
            'due_date' => Carbon::today()->addDays(15)->toDateString(),
            'discount_type' => 'percentage',
            'discount_value' => 10, // 10%
            'paid_amount' => 1800, // Partial payment
            'payment_method' => 'bank_transfer',
            'items' => [
                [
                    'description' => 'فحص ومعايرة رافعة تلسكوبية 90 طن',
                    'certificate_id' => $cert->id,
                    'quantity' => 1,
                    'unit_price' => 2000,
                ],
                [
                    'description' => 'مصاريف انتقال ومعايرة موقعية',
                    'certificate_id' => null,
                    'quantity' => 1,
                    'unit_price' => 500,
                ],
            ],
        ]);

        $invoice = Invoice::where('customer_id', $this->customer->id)->latest('id')->first();
        $this->assertNotNull($invoice);

        // Subtotal = 2000 + 500 = 2500
        // Discount 10% = 250
        // Total = 2250
        // Paid = 1800
        // Remaining = 450
        $this->assertEquals(2500, (float) $invoice->subtotal);
        $this->assertEquals(250, (float) $invoice->discount_amount);
        $this->assertEquals(2250, (float) $invoice->total_amount);
        $this->assertEquals(1800, (float) $invoice->paid_amount);
        $this->assertEquals(450, (float) $invoice->remaining_balance);
        $this->assertEquals('partial', $invoice->payment_status);

        $response->assertRedirect(route('invoices.show', $invoice));
    }

    public function test_profit_reports_page_loads_successfully(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('reports.index'));
        $response->assertStatus(200);
        $response->assertSee('لوحة تقارير الفحص وتحليل الأرباح');
    }

    public function test_pwa_web_manifest_returns_correct_json_and_headers(): void
    {
        $response = $this->get('/manifest.webmanifest');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/manifest+json');

        $data = $response->json();
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('short_name', $data);
        $this->assertArrayHasKey('start_url', $data);
        $this->assertArrayHasKey('display', $data);
        $this->assertEquals('standalone', $data['display']);
        $this->assertArrayHasKey('icons', $data);
        $this->assertNotEmpty($data['icons']);
    }

    public function test_inspection_inspect_screen_renders_safely_with_alpine_and_actions(): void
    {
        $this->actingAs($this->user);

        $inspection = Inspection::create([
            'inspection_number' => 'INS-RESP-TEST',
            'customer_id' => $this->customer->id,
            'equipment_name' => 'Mobile Crane 50T',
            'equipment_type' => 'crane',
            'reference_standard' => 'BS 7121',
            'inspection_type' => 'periodic',
            'inspection_date' => Carbon::today()->toDateString(),
            'inspector_name' => 'Eng. Mobile Inspector',
            'checklist_results' => [
                ['id' => 1, 'no' => 1, 'item' => 'Boom Structure Check', 'status' => 'pass', 'remarks' => 'Good'],
                ['id' => 2, 'no' => 2, 'item' => 'Hydraulic Cylinder Leaks', 'status' => 'fail', 'remarks' => 'Oil leak'],
            ],
            'overall_condition' => 'unsafe',
            'status' => 'draft',
        ]);

        $response = $this->get(route('inspections.inspect', $inspection));
        $response->assertStatus(200);
        $response->assertSee('Boom Structure Check');
        $response->assertSee('Hydraulic Cylinder Leaks');
        $response->assertSee('مقبول');
        $response->assertSee('مرفوض');
        $response->assertSee('المرحلة 2: إنشاء شهادة الفحص المعتمدة فوراً');
    }

    public function test_can_view_and_render_equipment_inspection_sticker_tag(): void
    {
        $this->actingAs($this->user);

        $inspection = Inspection::create([
            'inspection_number' => 'INS-STICKER-01',
            'doc_number' => 'SAF-TAG-99',
            'customer_id' => $this->customer->id,
            'equipment_name' => 'Forklift 7T Komatsu',
            'equipment_type' => 'Forklift',
            'reference_standard' => 'ISO 17020',
            'inspection_type' => 'periodic',
            'inspection_date' => '2026-11-22',
            'next_inspection_date' => '2027-11-21',
            'inspector_name' => 'Eng. Certified Inspector',
            'overall_condition' => 'safe',
            'status' => 'completed',
        ]);

        $response = $this->get(route('inspections.sticker', $inspection));
        $response->assertStatus(200);
        $response->assertSee('SAF-TAG-99');
        $response->assertSee('Forklift 7T Komatsu');
        $response->assertSee('PASS');
        $response->assertSee('EQUIPMENT INSPECTION');
        $response->assertSee('data:image/svg+xml;base64');

        // Test certificate sticker route as well
        $cert = Certificate::create([
            'certificate_number' => 'SAF/CERT/STICKER',
            'uuid' => (string) Str::uuid(),
            'customer_id' => $this->customer->id,
            'thorough_examination_date' => '2026-11-22',
            'next_examination_due_date' => '2027-11-21',
            'reference_standard' => 'BS 7121',
            'equipment_type' => 'crane',
            'equipment_description' => 'Overhead Crane 20T',
            'equipment_id_range' => 'CR-OVERHEAD-20',
            'swl' => '20 Ton',
            'quantity' => 1,
            'status' => 'issued',
            'is_safe_to_operate' => true,
        ]);

        $certResponse = $this->get(route('certificates.sticker', $cert));
        $certResponse->assertStatus(200);
        $certResponse->assertSee('CR-OVERHEAD-20');
        $certResponse->assertSee('PASS');
        $certResponse->assertSee('EQUIPMENT INSPECTION');

        // Test batch stickers (4 per A4 sheet)
        $batchResponse = $this->get(route('certificates.batch_stickers', ['ids' => $cert->id]));
        $batchResponse->assertStatus(200);
        $batchResponse->assertSee('CR-OVERHEAD-20');
        $batchResponse->assertSee('4 استيكرات في ورقة A4');
    }
}
