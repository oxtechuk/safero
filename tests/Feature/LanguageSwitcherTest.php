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

    public function test_pages_render_correct_bilingual_strings(): void
    {
        $this->actingAs($this->user);

        // English session on Invoices
        $invoicesEn = $this->withSession(['locale' => 'en'])->get(route('invoices.index'));
        $invoicesEn->assertStatus(200);
        $invoicesEn->assertSee('dir="ltr"', false);
        $invoicesEn->assertSee('Invoices &amp; Billing Accounts', false);

        // Arabic session on Invoices
        $invoicesAr = $this->withSession(['locale' => 'ar'])->get(route('invoices.index'));
        $invoicesAr->assertStatus(200);
        $invoicesAr->assertSee('dir="rtl"', false);
        $invoicesAr->assertSee('فواتير وحسابات الفحص');

        // English session on Reports
        $reportsEn = $this->withSession(['locale' => 'en'])->get(route('reports.index'));
        $reportsEn->assertStatus(200);
        $reportsEn->assertSee('Inspection &amp; Profit Analytics Dashboard', false);

        // English session on Customers
        $customersEn = $this->withSession(['locale' => 'en'])->get(route('customers.index'));
        $customersEn->assertStatus(200);
        $customersEn->assertSee('Customers &amp; Companies', false);
    }
}
