<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_can_switch_language_to_english_and_change_direction_to_ltr(): void
    {
        $this->actingAs($this->user);

        // Switch to English
        $response = $this->get(route('lang.switch', 'en'));
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');

        // Visit app with session locale
        $page = $this->withSession(['locale' => 'en'])->get(route('inspections.index'));
        $page->assertStatus(200);
        $page->assertSee('dir="ltr"', false);
        $page->assertSee('lang="en"', false);
    }

    public function test_can_switch_language_to_arabic_and_change_direction_to_rtl(): void
    {
        $this->actingAs($this->user);

        // Switch to Arabic
        $response = $this->get(route('lang.switch', 'ar'));
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'ar');

        // Visit app with session locale
        $page = $this->withSession(['locale' => 'ar'])->get(route('inspections.index'));
        $page->assertStatus(200);
        $page->assertSee('dir="rtl"', false);
        $page->assertSee('lang="ar"', false);
    }

    public function test_invalid_locale_falls_back_safely_to_arabic(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('lang.switch', 'invalid_lang'));
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'ar');
    }
}
