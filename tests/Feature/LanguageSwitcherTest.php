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

        // English session on Create Invoice
        $invoicesCreateEn = $this->withSession(['locale' => 'en'])->get(route('invoices.create'));
        $invoicesCreateEn->assertStatus(200);
        $invoicesCreateEn->assertSee('Create New Invoice &amp; Inspection Accounts', false);
        $invoicesCreateEn->assertSee('Payment Details &amp; Method', false);

        // Arabic session on Invoices
        $invoicesAr = $this->withSession(['locale' => 'ar'])->get(route('invoices.index'));
        $invoicesAr->assertStatus(200);
        $invoicesAr->assertSee('dir="rtl"', false);
        $invoicesAr->assertSee('فواتير وحسابات الفحص');

        // Arabic session on Create Invoice
        $invoicesCreateAr = $this->withSession(['locale' => 'ar'])->get(route('invoices.create'));
        $invoicesCreateAr->assertStatus(200);
        $invoicesCreateAr->assertSee('إنشاء فاتورة وحسابات فحص جديدة');
        $invoicesCreateAr->assertSee('تفاصيل السداد وطريقة الدفع');

        // English session on Reports
        $reportsEn = $this->withSession(['locale' => 'en'])->get(route('reports.index'));
        $reportsEn->assertStatus(200);
        $reportsEn->assertSee('Inspection &amp; Profit Analytics Dashboard', false);

        // English session on Customers
        $customersEn = $this->withSession(['locale' => 'en'])->get(route('customers.index'));
        $customersEn->assertStatus(200);
        $customersEn->assertSee('Customers &amp; Companies', false);

        // English session on Inspections
        $inspectionsEn = $this->withSession(['locale' => 'en'])->get(route('inspections.index'));
        $inspectionsEn->assertStatus(200);
        $inspectionsEn->assertSee('Field Inspection Sessions', false);

        // English session on Trainees
        $traineesEn = $this->withSession(['locale' => 'en'])->get(route('trainees.index'));
        $traineesEn->assertStatus(200);
        $traineesEn->assertSee('Trainees', false);

        // English session on Users
        $usersEn = $this->withSession(['locale' => 'en'])->get(route('users.index'));
        $usersEn->assertStatus(200);
        $usersEn->assertSee('Users &amp; Permissions', false);

        // English session on Dashboard
        $dashEn = $this->withSession(['locale' => 'en'])->get(route('dashboard'));
        $dashEn->assertStatus(200);
        $dashEn->assertSee('dir="ltr"', false);
        $dashEn->assertSee('Issued Training Certificates');
        $dashEn->assertSee('Equipment Inspection Certificates');

        // Arabic session on Dashboard
        $dashAr = $this->withSession(['locale' => 'ar'])->get(route('dashboard'));
        $dashAr->assertStatus(200);
        $dashAr->assertSee('dir="rtl"', false);
        $dashAr->assertSee('شهادات التدريب الصادرة');
        $dashAr->assertSee('شهادات فحص المعدات');
    }

    public function test_apps_launcher_page_renders_bilingual_strings(): void
    {
        $this->actingAs($this->user);

        // English session on Apps Launcher
        $appsEn = $this->withSession(['locale' => 'en'])->get(route('apps'));
        $appsEn->assertStatus(200);
        $appsEn->assertSee('dir="ltr"', false);
        $appsEn->assertSee('Inspection Certificates');
        $appsEn->assertSee('Invoices &amp; Accounts', false);
        $appsEn->assertSee('Profit Reports');
        $appsEn->assertSee('Customers CRM');
        $appsEn->assertSee('System Settings');

        // Arabic session on Apps Launcher
        $appsAr = $this->withSession(['locale' => 'ar'])->get(route('apps'));
        $appsAr->assertStatus(200);
        $appsAr->assertSee('dir="rtl"', false);
        $appsAr->assertSee('شهادات الفحص');
        $appsAr->assertSee('الفواتير والحسابات');
        $appsAr->assertSee('تقارير الأرباح');
        $appsAr->assertSee('إدارة العملاء');
        $appsAr->assertSee('إعدادات المنظومة');
    }

    public function test_certificate_create_and_types_render_bilingual_strings(): void
    {
        $this->actingAs($this->user);

        // Crane Type in English
        $craneEn = $this->withSession(['locale' => 'en'])->get(route('certificates.create', ['type' => 'crane']));
        $craneEn->assertStatus(200);
        $craneEn->assertSee('Lifting Appliances (Mobile Cranes)');
        $craneEn->assertSee('Chassis / Serial No');
        $craneEn->assertSee('Main Wire Diameter');

        // Crane Type in Arabic
        $craneAr = $this->withSession(['locale' => 'ar'])->get(route('certificates.create', ['type' => 'crane']));
        $craneAr->assertStatus(200);
        $craneAr->assertSee('فحص الأوناش والروافع المتنقلة');
        $craneAr->assertSee('رقم الشاسيه / السيريال');
        $craneAr->assertSee('قطر الواير الرئيسي');

        // Accessory Type in English
        $accEn = $this->withSession(['locale' => 'en'])->get(route('certificates.create', ['type' => 'accessory']));
        $accEn->assertStatus(200);
        $accEn->assertSee('Lifting Accessories (Slings, Shackles &amp; Clamps)', false);
        $accEn->assertSee('Safety Factor');

        // General Type in English
        $genEn = $this->withSession(['locale' => 'en'])->get(route('certificates.create', ['type' => 'general']));
        $genEn->assertStatus(200);
        $genEn->assertSee('General Cargo Units &amp; Pallet Equipment', false);
        $genEn->assertSee('Dimensions (L, W, H)');
    }

    public function test_top_navigation_bar_renders_bilingual_strings(): void
    {
        $this->actingAs($this->user);

        // English Navigation
        $navEn = $this->withSession(['locale' => 'en'])->get(route('certificates.index'));
        $navEn->assertStatus(200);
        $navEn->assertSee('Home');
        $navEn->assertSee('Inspection Certificates');
        $navEn->assertSee('Training Certificates');
        $navEn->assertSee('Customers');
        $navEn->assertSee('Invoices &amp; Accounts', false);
        $navEn->assertSee('Reports');
        $navEn->assertSee('Configuration &amp; Settings', false);

        // Arabic Navigation
        $navAr = $this->withSession(['locale' => 'ar'])->get(route('certificates.index'));
        $navAr->assertStatus(200);
        $navAr->assertSee('الرئيسية');
        $navAr->assertSee('شهادات الفحص');
        $navAr->assertSee('شهادات التدريب');
        $navAr->assertSee('العملاء');
        $navAr->assertSee('الفواتير والحسابات');
        $navAr->assertSee('التقارير');
        $navAr->assertSee('التهيئة والإعدادات');
    }
}
