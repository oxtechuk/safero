<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Trainee;
use App\Models\TrainingCertificate;
use App\Models\User;
use App\Support\ArabicShaper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewPlatformFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Requirement 1: Inspector list when someone else inspected.
     */
    public function test_can_create_certificate_with_selected_or_custom_inspector(): void
    {
        $otherInspector = User::factory()->create(['name' => 'Eng. Ahmed Field Inspector']);
        $customer = Customer::create([
            'name' => 'Petroleum Engineering Co',
            'email' => 'petro@example.com',
            'phone' => '01012345678',
            'address' => 'Alexandria Port',
        ]);

        $response = $this->actingAs($this->user)->post(route('certificates.store'), [
            'certificate_number' => 'CRT-TEST-001',
            'customer_id' => $customer->id,
            'examination_address' => 'Yard 4, Alex Port',
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'reference_standard' => 'BS 7121 Part 2',
            'equipment_type' => 'crane',
            'equipment_description' => 'Liebherr Mobile Crane 50 Ton',
            'swl' => '50 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Visual, Functional & Proof Load Test',
            'is_safe_to_operate' => 1,
            'inspector_id' => $otherInspector->id,
            'issuer_name' => $otherInspector->name,
            'authenticator_name' => 'Dr. Hany Nabil',
            'status' => 'issued',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('certificates', [
            'certificate_number' => 'CRT-TEST-001',
            'inspector_id' => $otherInspector->id,
            'issuer_name' => 'Eng. Ahmed Field Inspector',
        ]);
    }

    /**
     * Requirement 2: Trainer list for signatures on training certificates.
     */
    public function test_can_create_training_certificate_with_certified_trainer_from_list(): void
    {
        $trainee = Trainee::create([
            'name' => 'Kareem Tarek',
            'company' => 'Egypt Logistics',
        ]);

        $response = $this->actingAs($this->user)->post(route('training-certificates.store'), [
            'mode' => 'single',
            'trainee_id' => $trainee->id,
            'trainee_name' => $trainee->name,
            'certificate_serial' => 'TC-TEST-0001',
            'training_scope' => 'Safe Lifting Operations',
            'program_title' => 'Rigger Level 1',
            'hours' => 16,
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addYears(2)->toDateString(),
            'instructor_name' => 'Dr. Hany Nabil',
            'training_manager_name' => 'Gehan Mahmoud',
            'status' => 'issued',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('training_certificates', [
            'certificate_serial' => 'TC-TEST-0001',
            'trainee_name' => 'Kareem Tarek',
            'instructor_name' => 'Dr. Hany Nabil',
        ]);
    }

    /**
     * Requirement 3: Batch creation of multiple training certificates at once.
     */
    public function test_can_batch_create_multiple_training_certificates_for_a_course(): void
    {
        $t1 = Trainee::create(['name' => 'Trainee Alpha', 'company' => 'Alpha Co']);
        $t2 = Trainee::create(['name' => 'Trainee Beta', 'company' => 'Beta Co']);

        $response = $this->actingAs($this->user)->post(route('training-certificates.store'), [
            'mode' => 'batch',
            'training_scope' => 'Defensive Driving & Heavy Vehicle Safety',
            'program_title' => 'Defensive Driving Course',
            'hours' => 24,
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addYears(2)->toDateString(),
            'instructor_name' => 'Eng. Ahmed Hassan',
            'training_manager_name' => 'Gehan Mahmoud',
            'status' => 'issued',
            'batch_trainee_ids' => [$t1->id, $t2->id],
            'raw_trainee_names' => "Trainee Gamma\nTrainee Delta",
        ]);

        $response->assertRedirect(route('training-certificates.index'));
        $response->assertSessionHas('success');

        // Total 4 certificates generated
        $this->assertDatabaseHas('training_certificates', [
            'trainee_name' => 'Trainee Alpha',
            'program_title' => 'Defensive Driving Course',
            'instructor_name' => 'Eng. Ahmed Hassan',
        ]);
        $this->assertDatabaseHas('training_certificates', [
            'trainee_name' => 'Trainee Beta',
            'program_title' => 'Defensive Driving Course',
        ]);
        $this->assertDatabaseHas('training_certificates', [
            'trainee_name' => 'Trainee Gamma',
            'program_title' => 'Defensive Driving Course',
        ]);
        $this->assertDatabaseHas('training_certificates', [
            'trainee_name' => 'Trainee Delta',
            'program_title' => 'Defensive Driving Course',
        ]);

        $this->assertEquals(4, TrainingCertificate::where('program_title', 'Defensive Driving Course')->count());
    }

    /**
     * Requirement 4: Operational Expenses tracking and Real Net Profit calculation in Reports.
     */
    public function test_expenses_management_and_real_net_profit_calculation(): void
    {
        // 1. Create customer and invoice with payment
        $customer = Customer::create(['name' => 'Apex Cranes', 'email' => 'apex@example.com']);
        Invoice::create([
            'invoice_number' => 'INV-TEST-01',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'subtotal' => 10000,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 10000,
            'paid_amount' => 8000,
            'remaining_balance' => 2000,
            'payment_status' => 'partial',
            'created_by' => $this->user->id,
        ]);

        // 2. Record expenses
        $expResponse = $this->actingAs($this->user)->post(route('expenses.store'), [
            'title' => 'Alexandria Inspection Fuel & Vehicle Allowance',
            'category' => 'transportation',
            'amount' => 1500.00,
            'expense_date' => now()->toDateString(),
            'payment_method' => 'cash',
            'paid_to' => 'Driver / Station',
        ]);
        $expResponse->assertRedirect(route('expenses.index'));

        Expense::create([
            'expense_number' => 'EXP-TEST-0002',
            'title' => 'Calibration Sensor Recalibration Fee',
            'category' => 'equipment',
            'amount' => 500.00,
            'expense_date' => now()->toDateString(),
            'payment_method' => 'bank_transfer',
            'created_by' => $this->user->id,
        ]);

        // Total expenses = 1500 + 500 = 2000
        // Collected cash = 8000
        // Real Net Profit = 8000 - 2000 = 6000
        $reportResponse = $this->actingAs($this->user)->get(route('reports.index', ['period' => 'all']));
        $reportResponse->assertOk();
        $reportResponse->assertViewHas('totalExpenses', 2000.0);
        $reportResponse->assertViewHas('realNetProfit', 6000.0);
        $reportResponse->assertSee('6,000.00');
        $reportResponse->assertSee('2,000.00');
    }

    /**
     * Requirement 5: Certificate PDF rendering with dynamic settings stamp, signature, and UTF-8 Arabic equipment name.
     */
    public function test_certificate_pdf_renders_with_dynamic_settings_and_arabic_characters(): void
    {
        Setting::set('stamp_path', '/branding/stamp.svg', 'branding', 'image');
        Setting::set('engineer_signature_path', '/branding/eng_signature.svg', 'branding', 'image');
        Setting::set('director_signature_path', '/branding/director_signature.svg', 'branding', 'image');

        $customer = Customer::create([
            'name' => 'International Company for Crane',
            'email' => 'crane@example.com',
            'phone' => '01099998888',
            'address' => 'Owner Yard, Alexandria, Egypt',
        ]);

        $certificate = Certificate::create([
            'certificate_number' => 'SAF-2026112202',
            'customer_id' => $customer->id,
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'order_no' => 'INS-2026-0002',
            'examination_address' => 'Owner Yard, Alexandria, Egypt',
            'reference_standard' => 'BS EN ISO 3266, EN 1677',
            'equipment_type' => 'accessory',
            'equipment_description' => 'Eyebolts (مسامير عين مقلوظة)',
            'swl' => '2 Ton',
            'quantity' => 1,
            'tests_carried_out' => 'Thorough Visual Examination, Operational & Safety Limit Verification',
            'is_safe_to_operate' => 1,
            'is_first_examination_after_installation' => 0,
            'has_equipment_installed_correctly' => 1,
            'in_accordance_with_scheme' => 1,
            'was_examination_within_6_months' => 1,
            'was_examination_within_12_months' => 0,
            'after_exceptional_circumstances' => 0,
            'issuer_name' => 'Admin Safero',
            'authenticator_name' => 'Eng. Technical Manager (ISO 17020)',
            'status' => 'issued',
        ]);

        $response = $this->actingAs($this->user)->get(route('certificates.pdf', $certificate));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Requirement 6: Crane certificate creation defaults quantity to 1 when omitted.
     */
    public function test_crane_certificate_creation_without_quantity_input_defaults_to_one(): void
    {
        $customer = Customer::create([
            'name' => 'Gulf Crane Rental',
            'email' => 'gulf@example.com',
            'phone' => '01011112222',
            'address' => 'Borg El Arab',
        ]);

        $response = $this->actingAs($this->user)->post(route('certificates.store'), [
            'certificate_number' => 'SAF-CRANE-999',
            'customer_id' => $customer->id,
            'examination_address' => 'Borg El Arab Yard',
            'thorough_examination_date' => now()->toDateString(),
            'next_examination_due_date' => now()->addMonths(6)->toDateString(),
            'reference_standard' => 'BS 7121',
            'equipment_type' => 'crane',
            'equipment_description' => 'Liebherr LTM 1090-4.1 (ونش تلسكوبي 90 طن)',
            'swl' => '90 Ton',
            // Notice: quantity is deliberately omitted as in the crane form
            'tests_carried_out' => 'Visual & Proof Load Test',
            'is_safe_to_operate' => 1,
            'issuer_name' => 'Admin Safero',
            'authenticator_name' => 'Technical Director',
            'status' => 'issued',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('certificates', [
            'certificate_number' => 'SAF-CRANE-999',
            'equipment_type' => 'crane',
            'quantity' => 1,
        ]);
    }

    /**
     * Requirement 7: Arabic shaper correctly shapes and connects Arabic letters.
     */
    public function test_arabic_shaper_connects_arabic_text_properly(): void
    {
        $input = 'Eyebolts (مسامير عين مقلوظة)';
        $shaped = ArabicShaper::shape($input);

        $this->assertNotEquals($input, $shaped);
        $this->assertStringContainsString('Eyebolts', $shaped);
        // Shaped should contain Presentation Forms-B characters (e.g. 0xFE80-0xFEFC)
        $this->assertTrue((bool) preg_match('/[\x{FE70}-\x{FEFF}]/u', $shaped));
    }
}
