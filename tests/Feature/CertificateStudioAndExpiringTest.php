<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CertificateStudioAndExpiringTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_certificate_templates_index(): void
    {
        $user = User::factory()->create();

        CertificateTemplate::create([
            'name' => 'Crane 90T Liebherr Template',
            'code' => 'TMPL-CR-01',
            'equipment_type' => 'crane',
            'swl' => '90 Ton',
            'reference_standard' => 'EN 13000',
            'validity_months' => 6,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('certificate-templates.index'));

        $response->assertStatus(200);
        $response->assertSee('ستوديو قوالب شهادات الفحص');
        $response->assertSee('Crane 90T Liebherr Template');
        $response->assertSee('TMPL-CR-01');
    }

    public function test_user_can_create_new_template_with_custom_specs_and_checklist(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Webbing Sling 30 Ton Heavy',
            'code' => 'TMPL-SLING-30T',
            'equipment_type' => 'accessory',
            'description' => 'Heavy duty round synthetic webbing sling 30T',
            'swl' => '30 Ton',
            'reference_standard' => 'BS EN 1492-2',
            'manufacturer' => 'Lifting Safety UK',
            'model' => 'RS-30T',
            'validity_months' => 6,
            'is_active' => 1,
            'custom_specifications' => [
                ['key' => 'safety_factor', 'label' => 'معامل الأمان', 'value' => '7:1'],
                ['key' => 'ndt_equipment', 'label' => 'جهاز الفحص البصري', 'value' => 'Calibrated Luxmeter & Microscope'],
            ],
            'checklist_items' => [
                ['item' => 'Visual check of stitching', 'category' => 'Sewing', 'default_status' => 'pass'],
                ['item' => 'Fibre elongation test', 'category' => 'Load', 'default_status' => 'pass'],
            ],
        ];

        $response = $this->actingAs($user)->post(route('certificate-templates.store'), $payload);

        $response->assertRedirect(route('certificate-templates.index'));
        $this->assertDatabaseHas('certificate_templates', [
            'name' => 'Webbing Sling 30 Ton Heavy',
            'code' => 'TMPL-SLING-30T',
            'equipment_type' => 'accessory',
            'swl' => '30 Ton',
        ]);
    }

    public function test_user_can_view_and_edit_template(): void
    {
        $user = User::factory()->create();

        $template = CertificateTemplate::create([
            'name' => 'Move Pallet 70T',
            'code' => 'TMPL-PALLET-70',
            'equipment_type' => 'general',
            'swl' => '70 Ton',
            'reference_standard' => 'ASTM A6',
            'validity_months' => 12,
            'is_active' => true,
            'custom_specifications' => [
                ['key' => 'ndt_flaw', 'label' => 'جهاز الموجات فوق الصوتية', 'value' => 'Olympus Epoch 650'],
            ],
        ]);

        $showResponse = $this->actingAs($user)->get(route('certificate-templates.show', $template));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Move Pallet 70T');
        $showResponse->assertSee('Olympus Epoch 650');

        $editResponse = $this->actingAs($user)->get(route('certificate-templates.edit', $template));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('تعديل قالب: Move Pallet 70T');

        $updateResponse = $this->actingAs($user)->put(route('certificate-templates.update', $template), [
            'name' => 'Move Pallet 75T Upgraded',
            'code' => 'TMPL-PALLET-75',
            'equipment_type' => 'general',
            'swl' => '75 Ton',
            'reference_standard' => 'ASTM A6 / ISO 9001',
            'validity_months' => 12,
            'is_active' => 1,
        ]);

        $updateResponse->assertRedirect(route('certificate-templates.index'));
        $this->assertDatabaseHas('certificate_templates', [
            'id' => $template->id,
            'name' => 'Move Pallet 75T Upgraded',
            'swl' => '75 Ton',
        ]);
    }

    public function test_user_can_duplicate_template(): void
    {
        $user = User::factory()->create();

        $template = CertificateTemplate::create([
            'name' => 'Bow Shackle 55T Template',
            'code' => 'TMPL-SHACKLE-55',
            'equipment_type' => 'accessory',
            'swl' => '55 Ton',
            'reference_standard' => 'US Fed. Spec. RR-C-271',
            'validity_months' => 6,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('certificate-templates.duplicate', $template));

        $response->assertRedirect();
        $this->assertDatabaseHas('certificate_templates', [
            'name' => 'Bow Shackle 55T Template (نسخة مطابقة)',
            'swl' => '55 Ton',
        ]);
    }

    public function test_user_can_fetch_template_api_data(): void
    {
        $user = User::factory()->create();

        $template = CertificateTemplate::create([
            'name' => 'API Test Template',
            'code' => 'TMPL-API-01',
            'equipment_type' => 'crane',
            'swl' => '100 Ton',
            'reference_standard' => 'EN 13000',
            'validity_months' => 6,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('certificate-templates.api', $template));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'template' => [
                'id' => $template->id,
                'name' => 'API Test Template',
                'swl' => '100 Ton',
            ],
        ]);
    }

    public function test_user_can_delete_template(): void
    {
        $user = User::factory()->create();

        $template = CertificateTemplate::create([
            'name' => 'Delete Me Template',
            'code' => 'TMPL-DEL-01',
            'equipment_type' => 'general',
            'swl' => '10 Ton',
            'reference_standard' => 'ISO',
            'validity_months' => 6,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('certificate-templates.destroy', $template));

        $response->assertRedirect(route('certificate-templates.index'));
        $this->assertDatabaseMissing('certificate_templates', [
            'id' => $template->id,
        ]);
    }

    public function test_user_can_view_expiring_certificates_page_with_timeframe_filters(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Petrojet Petroleum Services',
            'contact_person' => 'Eng. Sameh',
            'phone' => '+201099999999',
            'email' => 'sameh@petrojet.com.eg',
            'address' => 'Petrojet Yard, Alexandria',
        ]);

        $today = Carbon::today();

        // Expiring in 5 days
        $certExpiringSoon = Certificate::create([
            'uuid' => (string) Str::uuid(),
            'certificate_number' => 'SAF/EXP/2026110001',
            'customer_id' => $customer->id,
            'examination_address' => 'Alexandria Port',
            'thorough_examination_date' => $today->copy()->subMonths(6)->toDateString(),
            'next_examination_due_date' => $today->copy()->addDays(5)->toDateString(),
            'reference_standard' => 'EN 13000',
            'equipment_type' => 'crane',
            'equipment_description' => 'Liebherr 70T Mobile Crane',
            'swl' => '70 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Visual and Functional Thorough Examination Conducted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Test',
            'authenticator_name' => 'Eng. Auth',
            'status' => 'issued',
            'created_by' => $user->id,
        ]);

        // Already expired 10 days ago
        $certExpired = Certificate::create([
            'uuid' => (string) Str::uuid(),
            'certificate_number' => 'SAF/EXP/2026110002',
            'customer_id' => $customer->id,
            'examination_address' => 'Alexandria Port',
            'thorough_examination_date' => $today->copy()->subMonths(7)->toDateString(),
            'next_examination_due_date' => $today->copy()->subDays(10)->toDateString(),
            'reference_standard' => 'BS EN 1492-2',
            'equipment_type' => 'accessory',
            'equipment_description' => 'Webbing Sling 20T',
            'swl' => '20 Ton',
            'quantity' => 2,
            'tests_carried_out' => 'Visual inspection conducted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Test',
            'authenticator_name' => 'Eng. Auth',
            'status' => 'issued',
            'created_by' => $user->id,
        ]);

        // Test filter 7 days
        $res7 = $this->actingAs($user)->get(route('certificates.expiring', ['timeframe' => '7']));
        $res7->assertStatus(200);
        $res7->assertSee('SAF/EXP/2026110001');
        $res7->assertDontSee('SAF/EXP/2026110002');

        // Test filter expired
        $resExpired = $this->actingAs($user)->get(route('certificates.expiring', ['timeframe' => 'expired']));
        $resExpired->assertStatus(200);
        $resExpired->assertSee('SAF/EXP/2026110002');
        $resExpired->assertDontSee('SAF/EXP/2026110001');
    }

    public function test_certificates_create_with_template_prefills_data(): void
    {
        $user = User::factory()->create();

        $template = CertificateTemplate::create([
            'name' => 'Crane Liebherr 90T Template',
            'code' => 'TMPL-CR-90',
            'equipment_type' => 'crane',
            'swl' => '90 Ton',
            'reference_standard' => 'EN 13000',
            'validity_months' => 6,
            'manufacturer' => 'Liebherr Werk Ehingen',
            'model' => 'LTM 1090-4.1',
            'is_active' => true,
            'custom_specifications' => [
                ['key' => 'ndt_load_cell', 'label' => 'خلية قياس الحمل', 'value' => 'Straightpoint 100T Radio Link'],
            ],
        ]);

        $response = $this->actingAs($user)->get(route('certificates.create', ['template_id' => $template->id]));

        $response->assertStatus(200);
        $response->assertSee('Crane Liebherr 90T Template');
        $response->assertSee('Liebherr Werk Ehingen');
        $response->assertSee('Straightpoint 100T Radio Link');
    }

    public function test_certificates_create_with_renew_from_prefills_data(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'El Sewedy Electric Cables',
            'contact_person' => 'Eng. Ahmed',
            'phone' => '+201088888888',
            'email' => 'ahmed@elsewedy.com',
            'address' => '10th of Ramadan City',
        ]);

        $cert = Certificate::create([
            'uuid' => (string) Str::uuid(),
            'certificate_number' => 'SAF/RNW/2026110099',
            'order_no' => 'PO-9988',
            'customer_id' => $customer->id,
            'examination_address' => '10th of Ramadan Factory 2',
            'thorough_examination_date' => Carbon::today()->subMonths(6)->toDateString(),
            'next_examination_due_date' => Carbon::today()->toDateString(),
            'reference_standard' => 'BS EN 1492-2',
            'equipment_type' => 'accessory',
            'equipment_description' => 'Round Sling 50T Heavy Duty',
            'swl' => '50 Ton',
            'quantity' => 4,
            'tests_carried_out' => 'Visual Examination Conducted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Renewal',
            'authenticator_name' => 'Eng. Auth',
            'status' => 'issued',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('certificates.create', ['renew_from' => $cert->id]));

        $response->assertStatus(200);
        $response->assertSee('SAF/RNW/2026110099');
        $response->assertSee('Round Sling 50T Heavy Duty');
        $response->assertSee('10th of Ramadan Factory 2');
    }

    public function test_creating_certificate_with_template_increments_usage_count(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Suez Canal Authority',
            'contact_person' => 'Eng. Mahmoud',
            'phone' => '+201077777777',
            'email' => 'sca@suezcanal.gov.eg',
            'address' => 'Port Said Yard',
        ]);

        $template = CertificateTemplate::create([
            'name' => 'Accessory Standard Template',
            'code' => 'TMPL-ACC-01',
            'equipment_type' => 'accessory',
            'swl' => '25 Ton',
            'reference_standard' => 'BS EN 1492-2',
            'validity_months' => 6,
            'usage_count' => 0,
            'is_active' => true,
        ]);

        $this->assertEquals(0, $template->usage_count);

        $response = $this->actingAs($user)->post(route('certificates.store'), [
            'template_id' => $template->id,
            'certificate_number' => 'SAF/TEST/2026110500',
            'customer_id' => $customer->id,
            'examination_address' => 'Port Said Yard',
            'thorough_examination_date' => Carbon::today()->toDateString(),
            'next_examination_due_date' => Carbon::today()->addMonths(6)->toDateString(),
            'reference_standard' => 'BS EN 1492-2',
            'equipment_type' => 'accessory',
            'equipment_description' => 'Test Shackle 25T',
            'swl' => '25 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Tested',
            'is_safe_to_operate' => 1,
            'issuer_name' => 'Eng. Test',
            'authenticator_name' => 'Eng. Auth',
            'status' => 'issued',
        ]);

        $response->assertSessionHasNoErrors();
        $template->refresh();
        $this->assertEquals(1, $template->usage_count);
    }
}
