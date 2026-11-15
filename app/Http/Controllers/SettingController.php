<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the Enterprise Settings Management Page.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'general');
        $groupedSettings = Setting::getAllGrouped();
        $users = User::orderBy('id')->get();
        $userCount = $users->count();

        // IsoOx 17/18 Menu Items definition (Image 1 Technical -> Menu Items)
        $menuItems = [
            [
                'id' => 1,
                'name_ar' => 'شاشة التطبيقات والمديولات',
                'name_en' => 'Apps Launcher',
                'parent' => 'Root',
                'route' => 'apps',
                'url' => route('apps'),
                'icon' => 'branding/IsoOx-icons/apps.svg',
                'sequence' => 10,
                'is_active' => true,
                'badge' => 'Core',
            ],
            [
                'id' => 2,
                'name_ar' => 'شهادات الفحص والمعايرة',
                'name_en' => 'Inspection Certificates',
                'parent' => 'Quality Control',
                'route' => 'certificates.index',
                'url' => route('certificates.index'),
                'icon' => 'branding/IsoOx-icons/quality_control.svg',
                'sequence' => 20,
                'is_active' => true,
                'badge' => 'Module',
            ],
            [
                'id' => 3,
                'name_ar' => 'فحص الأوناش والروافع (LOLER)',
                'name_en' => 'Lifting Appliances (Cranes)',
                'parent' => 'Inspection Certificates',
                'route' => 'certificates.create?type=crane',
                'url' => route('certificates.create', ['type' => 'crane']),
                'icon' => 'branding/IsoOx-icons/fleet.svg',
                'sequence' => 21,
                'is_active' => true,
                'badge' => 'Model Form',
            ],
            [
                'id' => 4,
                'name_ar' => 'مهمات الرفع والحبال',
                'name_en' => 'Lifting Accessories & Slings',
                'parent' => 'Inspection Certificates',
                'route' => 'certificates.create?type=accessory',
                'url' => route('certificates.create', ['type' => 'accessory']),
                'icon' => 'branding/IsoOx-icons/maintenance.svg',
                'sequence' => 22,
                'is_active' => true,
                'badge' => 'Model Form',
            ],
            [
                'id' => 5,
                'name_ar' => 'معدات عامة وباليتات',
                'name_en' => 'General Cargo Units & Pallets',
                'parent' => 'Inspection Certificates',
                'route' => 'certificates.create?type=general',
                'url' => route('certificates.create', ['type' => 'general']),
                'icon' => 'branding/IsoOx-icons/repair.svg',
                'sequence' => 23,
                'is_active' => true,
                'badge' => 'Model Form',
            ],
            [
                'id' => 6,
                'name_ar' => 'شهادات التدريب المعتمدة',
                'name_en' => 'Training Certificates',
                'parent' => 'Certification',
                'route' => 'training-certificates.index',
                'url' => route('training-certificates.index'),
                'icon' => 'branding/IsoOx-icons/industry_certification.svg',
                'sequence' => 30,
                'is_active' => true,
                'badge' => 'Module',
            ],
            [
                'id' => 7,
                'name_ar' => 'قاعدة بيانات المتدربين',
                'name_en' => 'Trainees CRM',
                'parent' => 'Training Certificates',
                'route' => 'trainees.index',
                'url' => route('trainees.index'),
                'icon' => 'branding/IsoOx-icons/contacts.svg',
                'sequence' => 31,
                'is_active' => true,
                'badge' => 'Model View',
            ],
            [
                'id' => 8,
                'name_ar' => 'دليل الشركات والعملاء',
                'name_en' => 'Customers CRM',
                'parent' => 'CRM',
                'route' => 'customers.index',
                'url' => route('customers.index'),
                'icon' => 'branding/IsoOx-icons/crm.svg',
                'sequence' => 40,
                'is_active' => true,
                'badge' => 'Module',
            ],
            [
                'id' => 9,
                'name_ar' => 'لوحة المؤشرات والتحليلات',
                'name_en' => 'Analytics & Dashboard',
                'parent' => 'Reporting',
                'route' => 'dashboard',
                'url' => route('dashboard'),
                'icon' => 'branding/IsoOx-icons/board.svg',
                'sequence' => 50,
                'is_active' => true,
                'badge' => 'Dashboard',
            ],
            [
                'id' => 10,
                'name_ar' => 'بوابة التحقق الذكي QR',
                'name_en' => 'Public QR Verifier',
                'parent' => 'Security & Compliance',
                'route' => 'certificates.verify.lookup',
                'url' => route('certificates.verify.lookup'),
                'icon' => 'branding/IsoOx-icons/approvals.svg',
                'sequence' => 60,
                'is_active' => true,
                'badge' => 'Portal',
            ],
            [
                'id' => 11,
                'name_ar' => 'إعدادات النظام والشركة',
                'name_en' => 'General Settings',
                'parent' => 'Configuration',
                'route' => 'settings.index',
                'url' => route('settings.index'),
                'icon' => 'branding/IsoOx-icons/settings.svg',
                'sequence' => 100,
                'is_active' => true,
                'badge' => 'System',
            ],
        ];

        return view('settings.index', compact('groupedSettings', 'activeTab', 'users', 'userCount', 'menuItems'));
    }

    /**
     * Save and update company & platform settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name_ar' => 'nullable|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'company_tagline' => 'nullable|string|max:500',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:100',
            'company_website' => 'nullable|url|max:255',
            'company_cr' => 'nullable|string|max:100',
            'company_tax_id' => 'nullable|string|max:100',
            'accreditation_body' => 'nullable|string|max:100',
            'accreditation_number' => 'nullable|string|max:100',
            'certificate_prefix' => 'nullable|string|max:20',
            'certificate_validity_months' => 'nullable|integer|min:1|max:60',
            'default_inspector_name' => 'nullable|string|max:150',
            'default_authenticator_name' => 'nullable|string|max:150',
            'app_locale' => 'nullable|in:ar,en',
            'app_direction' => 'nullable|in:rtl,ltr',
            'app_timezone' => 'nullable|string|max:100',
            'app_currency' => 'nullable|string|max:10',
            'saas_enabled' => 'nullable|boolean',
            'saas_allow_registration' => 'nullable|boolean',
            'logo_file' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp|max:5120',
            'stamp_file' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp|max:5120',
            'letterhead_bg_file' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:10240',
            'eng_sign_file' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp|max:5120',
            'dir_sign_file' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp|max:5120',
        ]);

        // 1. Text & Config Settings
        $fields = [
            'company' => ['company_name_ar', 'company_name_en', 'company_tagline', 'company_address', 'company_phone', 'company_email', 'company_website', 'company_cr', 'company_tax_id'],
            'certificates' => ['accreditation_body', 'accreditation_number', 'certificate_prefix', 'certificate_validity_months', 'default_inspector_name', 'default_authenticator_name'],
            'localization' => ['app_locale', 'app_direction', 'app_timezone', 'app_currency'],
            'saas' => ['saas_enabled', 'saas_allow_registration'],
        ];

        foreach ($fields as $group => $keys) {
            foreach ($keys as $key) {
                if ($request->has($key)) {
                    $value = $request->input($key);
                    $type = is_numeric($value) ? 'integer' : 'string';
                    Setting::set($key, $value, $group, $type);
                }
            }
        }

        // Checkbox Booleans (when unchecked)
        Setting::set('saas_enabled', $request->boolean('saas_enabled') ? '1' : '0', 'saas', 'boolean');
        Setting::set('saas_allow_registration', $request->boolean('saas_allow_registration') ? '1' : '0', 'saas', 'boolean');

        // Automatic direction based on locale if not explicitly passed
        if ($request->filled('app_locale')) {
            $dir = $request->input('app_locale') === 'ar' ? 'rtl' : 'ltr';
            Setting::set('app_direction', $dir, 'localization', 'string');
        }

        // 2. Handle File Uploads (Branding Assets)
        $brandingDir = public_path('branding');
        if (! file_exists($brandingDir)) {
            mkdir($brandingDir, 0755, true);
        }

        // Logo
        if ($request->hasFile('logo_file') && $request->file('logo_file')->isValid()) {
            $file = $request->file('logo_file');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $filename = 'company_logo_'.time().'.'.$ext;
            $file->move($brandingDir, $filename);
            Setting::set('logo_path', '/branding/'.$filename, 'branding', 'image');
        }

        // Official Stamp
        if ($request->hasFile('stamp_file') && $request->file('stamp_file')->isValid()) {
            $file = $request->file('stamp_file');
            $ext = $file->getClientOriginalExtension() ?: 'svg';
            $filename = 'company_stamp_'.time().'.'.$ext;
            $file->move($brandingDir, $filename);
            Setting::set('stamp_path', '/branding/'.$filename, 'branding', 'image');
        }

        // Certificate Letterhead Background
        if ($request->hasFile('letterhead_bg_file') && $request->file('letterhead_bg_file')->isValid()) {
            $file = $request->file('letterhead_bg_file');
            $filename = 'cert_inspect_bg.jpg';
            $file->move($brandingDir, $filename);
            Setting::set('letterhead_bg_path', '/branding/cert_inspect_bg.jpg', 'branding', 'image');
        }

        // Engineer Signature
        if ($request->hasFile('eng_sign_file') && $request->file('eng_sign_file')->isValid()) {
            $file = $request->file('eng_sign_file');
            $ext = $file->getClientOriginalExtension() ?: 'svg';
            $filename = 'eng_signature_'.time().'.'.$ext;
            $file->move($brandingDir, $filename);
            Setting::set('engineer_signature_path', '/branding/'.$filename, 'branding', 'image');
        }

        // Director Signature
        if ($request->hasFile('dir_sign_file') && $request->file('dir_sign_file')->isValid()) {
            $file = $request->file('dir_sign_file');
            $ext = $file->getClientOriginalExtension() ?: 'svg';
            $filename = 'director_signature_'.time().'.'.$ext;
            $file->move($brandingDir, $filename);
            Setting::set('director_signature_path', '/branding/'.$filename, 'branding', 'image');
        }

        Setting::clearCache();

        $activeTab = $request->input('_tab', 'general');

        return redirect()->route('settings.index', ['tab' => $activeTab])
            ->with('success', 'تم حفظ إعدادات الشركة والنظام وتحديث الهوية بنجاح.');
    }

    /**
     * Invite a new user to the platform (Matches Image 1 Users section).
     */
    public function inviteUser(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:150',
            'role' => 'nullable|string|in:inspector,admin,manager,viewer',
        ]);

        $email = $request->input('email');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            return redirect()->route('settings.index', ['tab' => 'users'])
                ->with('error', "المستخدم ذو البريد ({$email}) موجود بالفعل في النظام.");
        }

        // Create the invited user with a temporary secure random password
        $name = Str::before($email, '@');
        $name = ucwords(str_replace(['.', '_', '-'], ' ', $name));

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random(16)),
        ]);

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "تم إرسال دعوة الانضمام وإنشاء حساب للمستخدم ({$email}) بنجاح.");
    }
}
