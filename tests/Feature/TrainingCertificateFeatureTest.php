<?php

namespace Tests\Feature;

use App\Models\Trainee;
use App\Models\TrainingCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingCertificateFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_training_certificates_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('training-certificates.index'));

        $response->assertStatus(200);
        $response->assertSee('سجل شهادات التدريب المعتمدة');
    }

    public function test_can_create_training_certificate(): void
    {
        $user = User::factory()->create();
        $trainee = Trainee::factory()->create(['name' => 'Trainee Test']);

        $response = $this->actingAs($user)->post(route('training-certificates.store'), [
            'trainee_id' => $trainee->id,
            'trainee_name' => 'Trainee Test',
            'certificate_serial' => 'TEST_SERIAL_9999',
            'training_scope' => 'Safe Lifting & Rigging Operations',
            'program_title' => 'Defensive Driving',
            'hours' => 12,
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addYears(2)->toDateString(),
            'instructor_name' => 'Dr. Hany Nabil',
            'training_manager_name' => 'Gehan Mahmoud',
            'status' => 'issued',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('training_certificates', [
            'certificate_serial' => 'TEST_SERIAL_9999',
            'trainee_name' => 'Trainee Test',
        ]);
    }

    public function test_embed_endpoint_returns_correct_headers_and_data(): void
    {
        $cert = TrainingCertificate::factory()->create();

        $response = $this->get(route('training-certificates.embed', ['uuid' => $cert->uuid]));

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'ALLOWALL');
        $response->assertSee('CERTIFICATE');
        $response->assertSee($cert->trainee_name);
    }

    public function test_public_verify_endpoint_returns_200(): void
    {
        $cert = TrainingCertificate::factory()->create();

        $response = $this->get(route('training-certificates.verify', ['uuid' => $cert->uuid]));

        $response->assertStatus(200);
        $response->assertSee('شهادة تدريب معتمدة ومسجلة رسمياً');
        $response->assertSee($cert->certificate_serial);
    }

    public function test_api_v1_verify_returns_json(): void
    {
        $cert = TrainingCertificate::factory()->create();

        $response = $this->getJson(route('api.training-certificates.verify', ['identifier' => $cert->certificate_serial]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'valid' => true,
                'status_code' => 'valid',
                'data' => [
                    'certificate_serial' => $cert->certificate_serial,
                    'trainee_name' => $cert->trainee_name,
                ],
            ]);
    }
}
