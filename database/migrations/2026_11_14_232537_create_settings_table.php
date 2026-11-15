<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 190)->unique();
            $table->longText('value')->nullable();
            $table->string('group', 50)->default('general')->index();
            $table->string('type', 30)->default('string'); // string, text, boolean, integer, json, image
            $table->string('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        // Seed initial default company & system settings
        $defaults = [
            // Company Profile
            ['group' => 'company', 'key' => 'company_name_ar', 'value' => 'شركة سافيرو للخدمات والاستشارات الهندسية', 'type' => 'string', 'description' => 'اسم الشركة باللغة العربية'],
            ['group' => 'company', 'key' => 'company_name_en', 'value' => 'SAFERO Services & Consultations', 'type' => 'string', 'description' => 'اسم الشركة باللغة الإنجليزية'],
            ['group' => 'company', 'key' => 'company_tagline', 'value' => 'Accredited Inspection Body (ISO/IEC 17020) & Lifting Equipment Certification', 'type' => 'string', 'description' => 'شعار الشركة أو الوصف القانوني'],
            ['group' => 'company', 'key' => 'company_address', 'value' => '215- Sporting - Port Said Main Street - Sidi Gaber - Alexandria-Egypt', 'type' => 'string', 'description' => 'العنوان والفرع الرئيسي'],
            ['group' => 'company', 'key' => 'company_phone', 'value' => '+2-01508367666', 'type' => 'string', 'description' => 'رقم الهاتف / الواتساب الرسمي'],
            ['group' => 'company', 'key' => 'company_email', 'value' => 'info@safero-eg.com', 'type' => 'string', 'description' => 'البريد الإلكتروني للشركة'],
            ['group' => 'company', 'key' => 'company_website', 'value' => 'https://safero-eg.com', 'type' => 'string', 'description' => 'رابط الموقع الإلكتروني'],
            ['group' => 'company', 'key' => 'company_cr', 'value' => '145892', 'type' => 'string', 'description' => 'رقم السجل التجاري'],
            ['group' => 'company', 'key' => 'company_tax_id', 'value' => '741-982-310', 'type' => 'string', 'description' => 'رقم البطاقة الضريبية'],

            // Accreditation & Standards
            ['group' => 'certificates', 'key' => 'accreditation_body', 'value' => 'EGAC & ilac-MRA', 'type' => 'string', 'description' => 'جهة الاعتماد الدولية'],
            ['group' => 'certificates', 'key' => 'accreditation_number', 'value' => 'CAB # 0326001', 'type' => 'string', 'description' => 'رقم شهادة اعتماد جهاز التفتيش'],
            ['group' => 'certificates', 'key' => 'certificate_prefix', 'value' => 'SAF', 'type' => 'string', 'description' => 'بادئة أرقام الشهادات'],
            ['group' => 'certificates', 'key' => 'certificate_validity_months', 'value' => '12', 'type' => 'integer', 'description' => 'صلاحية فحص المعدات بالشهور'],
            ['group' => 'certificates', 'key' => 'default_inspector_name', 'value' => 'Eng. Mohamed Fathy', 'type' => 'string', 'description' => 'اسم مهندس الفحص الافتراضي'],
            ['group' => 'certificates', 'key' => 'default_authenticator_name', 'value' => 'Dr. Hany Taher', 'type' => 'string', 'description' => 'اسم المدير الفني / معتمد الشهادات'],

            // Branding Assets
            ['group' => 'branding', 'key' => 'logo_path', 'value' => '/branding/safero_logo.jpg', 'type' => 'image', 'description' => 'مسار شعار الشركة'],
            ['group' => 'branding', 'key' => 'stamp_path', 'value' => '/branding/stamp.svg', 'type' => 'image', 'description' => 'مسار الختم الرسمي للشركة'],
            ['group' => 'branding', 'key' => 'letterhead_bg_path', 'value' => '/branding/cert_inspect_bg.jpg', 'type' => 'image', 'description' => 'مسار ورقة القالب الرسمي لشهادات الفحص'],
            ['group' => 'branding', 'key' => 'engineer_signature_path', 'value' => '/branding/eng_signature.svg', 'type' => 'image', 'description' => 'مسار توقيع مهندس الفحص'],
            ['group' => 'branding', 'key' => 'director_signature_path', 'value' => '/branding/director_signature.svg', 'type' => 'image', 'description' => 'مسار توقيع معتمد الشهادة'],

            // Localization & System
            ['group' => 'localization', 'key' => 'app_locale', 'value' => 'ar', 'type' => 'string', 'description' => 'لغة واجهة النظام الافتراضية'],
            ['group' => 'localization', 'key' => 'app_direction', 'value' => 'rtl', 'type' => 'string', 'description' => 'اتجاه واجهة النظام'],
            ['group' => 'localization', 'key' => 'app_timezone', 'value' => 'Africa/Cairo', 'type' => 'string', 'description' => 'المنطقة الزمنية'],
            ['group' => 'localization', 'key' => 'app_currency', 'value' => 'EGP', 'type' => 'string', 'description' => 'العملة الافتراضية'],

            // SaaS & Scalability
            ['group' => 'saas', 'key' => 'saas_enabled', 'value' => '0', 'type' => 'boolean', 'description' => 'تفعيل وضع المنصة المتعددة للمستأجرين SaaS'],
            ['group' => 'saas', 'key' => 'saas_allow_registration', 'value' => '0', 'type' => 'boolean', 'description' => 'السماح للشركات بتسجيل حسابات جديدة'],
        ];

        $now = now();
        foreach ($defaults as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('settings')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
