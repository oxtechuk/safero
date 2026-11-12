<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SAFERO SERVICES');
        $response->assertSee('تسجيل الدخول للنظام');
    }

    public function test_users_can_authenticate(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@safero-eg.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@safero-eg.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_displays_odoo_style_modules_and_stats(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('مديولات النظام (Odoo-Style Apps)');
        $response->assertSee('شهادات الفحص والمعايرة');
        $response->assertSee('إدارة العملاء');
    }

    public function test_customer_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/customers', [
            'name' => 'Morgan international transport & marine',
            'contact_person' => 'Capt. Tarek',
            'phone' => '+201001234567',
            'email' => 'tarek@morgan.com',
            'tax_id' => '999-888',
            'address' => 'Om Zoghio - Alexandria',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Morgan international transport & marine',
        ]);

        $customer = Customer::first();
        $response->assertRedirect(route('customers.show', $customer));
    }

    public function test_certificate_can_be_created_and_viewed_with_qr(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'Morgan international transport & marine',
        ]);

        $postData = [
            'certificate_number' => 'SAF/2026110807',
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
            'is_first_examination_after_installation' => 0,
            'was_examination_within_6_months' => 1,
            'has_equipment_installed_correctly' => 1,
            'was_examination_within_12_months' => 0,
            'in_accordance_with_scheme' => 0,
            'after_exceptional_circumstances' => 0,
            'defect_identification' => 'NONE',
            'is_defect_danger_to_process' => 0,
            'repair_particulars' => 'NONE',
            'tests_carried_out' => 'Visual Inspection, Function Test Were Carried Out and ACCEPTED.',
            'is_safe_to_operate' => 1,
            'issuer_name' => 'Eng. Mohammed fathy',
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ];

        $response = $this->actingAs($user)->post('/certificates', $postData);
        $certificate = Certificate::first();

        $this->assertNotNull($certificate);
        $this->assertEquals('SAF/2026110807', $certificate->certificate_number);
        $this->assertNotEmpty($certificate->uuid);

        $response->assertRedirect(route('certificates.show', $certificate));

        // Test viewing certificate page
        $showResponse = $this->actingAs($user)->get(route('certificates.show', $certificate));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT');
        $showResponse->assertSee('Move Pallet');
        $showResponse->assertSee('70 Ton');
        $showResponse->assertSee('SCAN TO VERIFY');
    }

    public function test_certificate_pdf_can_be_streamed(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $certificate = Certificate::create([
            'certificate_number' => 'SAF/2026110807',
            'order_no' => '100000',
            'customer_id' => $customer->id,
            'examination_address' => 'Om Zoghio - Alexandria',
            'thorough_examination_date' => '2026-11-08',
            'next_examination_due_date' => '2027-04-07',
            'reference_standard' => 'ASTM A6',
            'equipment_description' => 'Move Pallet',
            'swl' => '70 Ton',
            'quantity' => 6,
            'tests_carried_out' => 'Visual inspection accepted.',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Mohammed fathy',
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ]);

        $response = $this->get(route('certificates.pdf', $certificate));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_public_qr_verification_endpoint_works(): void
    {
        $customer = Customer::factory()->create(['name' => 'Morgan Marine']);
        $certificate = Certificate::create([
            'certificate_number' => 'SAF/2026110807',
            'customer_id' => $customer->id,
            'examination_address' => 'Alexandria',
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'reference_standard' => 'ASTM A6',
            'equipment_description' => 'Overhead Crane',
            'swl' => '50 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Accepted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Mohammed fathy',
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ]);

        $response = $this->get(route('certificates.verify', $certificate->uuid));
        $response->assertStatus(200);
        $response->assertSee('شهادة فحص معتمدة وسارية');
        $response->assertSee('Morgan Marine');
        $response->assertSee('Overhead Crane');
    }

    public function test_embed_endpoint_for_wordpress_iframe(): void
    {
        $customer = Customer::factory()->create(['name' => 'Morgan Marine']);
        $certificate = Certificate::create([
            'certificate_number' => 'SAF/2026110807',
            'customer_id' => $customer->id,
            'examination_address' => 'Alexandria',
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'reference_standard' => 'ASTM A6',
            'equipment_description' => 'Move Pallet',
            'swl' => '70 Ton',
            'quantity' => 6,
            'tests_carried_out' => 'Accepted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Mohammed fathy',
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ]);

        $response = $this->get('/embed/certificate/'.$certificate->uuid);
        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'ALLOWALL');
        $response->assertSee('REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT');
        $response->assertSee('Move Pallet');
        $response->assertSee('70 Ton');
    }

    public function test_api_verification_endpoint_returns_json(): void
    {
        $customer = Customer::factory()->create(['name' => 'Morgan Marine']);
        $certificate = Certificate::create([
            'certificate_number' => 'SAF/2026110807',
            'customer_id' => $customer->id,
            'examination_address' => 'Alexandria',
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'reference_standard' => 'ASTM A6',
            'equipment_description' => 'Overhead Crane',
            'swl' => '50 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Accepted',
            'is_safe_to_operate' => true,
            'issuer_name' => 'Eng. Mohammed fathy',
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ]);

        $response = $this->getJson('/api/v1/certificates/verify/SAF/2026110807');
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'valid' => true,
                'status_code' => 'valid',
                'data' => [
                    'certificate_number' => 'SAF/2026110807',
                    'customer_name' => 'Morgan Marine',
                    'swl' => '50 Ton',
                ],
            ]);
    }
}
