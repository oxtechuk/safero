<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Admin Safero',
            'email' => 'admin@safero-eg.com',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_settings(): void
    {
        $response = $this->get(route('settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertSee('الإعدادات (Settings)');
        $response->assertSee('General Settings');
        $response->assertSee('Menu Items');
        $response->assertSee('Branding');
    }

    public function test_authenticated_user_can_update_settings(): void
    {
        $response = $this->actingAs($this->user)->post(route('settings.update'), [
            '_tab' => 'company',
            'company_name_ar' => 'شركة فحص واختبار هندسي معتمدة',
            'company_name_en' => 'Accredited Inspection Corp',
            'company_phone' => '+201099998888',
            'company_address' => 'Alexandria Free Zone, Egypt',
            'accreditation_body' => 'EGAC ISO 17020',
            'certificate_prefix' => 'EXP',
            'certificate_validity_months' => 6,
            'app_locale' => 'ar',
            'saas_enabled' => 1,
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'company']));
        $response->assertSessionHas('success');

        $this->assertEquals('شركة فحص واختبار هندسي معتمدة', Setting::get('company_name_ar'));
        $this->assertEquals('Accredited Inspection Corp', Setting::get('company_name_en'));
        $this->assertEquals('EXP', Setting::get('certificate_prefix'));
        $this->assertEquals(6, Setting::get('certificate_validity_months'));
        $this->assertEquals(1, Setting::get('saas_enabled'));
    }

    public function test_authenticated_user_can_invite_new_user(): void
    {
        $response = $this->actingAs($this->user)->post(route('settings.invite_user'), [
            'email' => 'new.inspector@safero-eg.com',
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'users']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'new.inspector@safero-eg.com',
        ]);
    }
}
