<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Customer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_certificates' => Certificate::count(),
            'valid_certificates' => Certificate::where('status', 'issued')
                ->where('is_safe_to_operate', true)
                ->where('next_examination_due_date', '>=', now())
                ->count(),
            'due_soon' => Certificate::where('status', 'issued')
                ->whereBetween('next_examination_due_date', [now(), now()->addDays(30)])
                ->count(),
        ];

        $recentCertificates = Certificate::with('customer')
            ->latest('id')
            ->take(5)
            ->get();

        $modules = [
            [
                'id' => 'certificates',
                'name' => 'شهادات الفحص والمعايرة',
                'name_en' => 'Inspection Certificates',
                'description' => 'إصدار تقارير فحص معدات الرفع وشهادات ISO المعتمدة مع رمز QR',
                'icon' => 'document-check',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('certificates.index'),
                'count' => $stats['total_certificates'],
                'count_label' => 'شهادة',
                'active' => true,
            ],
            [
                'id' => 'customers',
                'name' => 'إدارة العملاء',
                'name_en' => 'Customers Directory',
                'description' => 'سجل الشركات والمصانع ومواقع الفحص والاتصال',
                'icon' => 'users',
                'color' => '#22A447',
                'bg_light' => '#E8F7EC',
                'route' => route('customers.index'),
                'count' => $stats['total_customers'],
                'count_label' => 'عميل',
                'active' => true,
            ],
            [
                'id' => 'new_certificate',
                'name' => 'إصدار شهادة جديدة',
                'name_en' => 'New Inspection',
                'description' => 'إدخال فحص جديد وتوليد الـ PDF والأختام فوراً',
                'icon' => 'plus-circle',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('certificates.create'),
                'count' => null,
                'count_label' => 'جديد',
                'active' => true,
            ],
            [
                'id' => 'verify',
                'name' => 'التحقق من الشهادات',
                'name_en' => 'Cert Verification',
                'description' => 'فحص صحة الشهادة والـ QR ومطابقة السجلات الرسمية',
                'icon' => 'shield-check',
                'color' => '#22A447',
                'bg_light' => '#E8F7EC',
                'route' => route('certificates.verify.lookup'),
                'count' => null,
                'count_label' => 'أداة فورية',
                'active' => true,
            ],
            [
                'id' => 'environmental',
                'name' => 'الدراسات البيئية والكربونية',
                'name_en' => 'Environmental & Carbon',
                'description' => 'حساب البصمة الكربونية وخدمات الحياد والاستدامة',
                'icon' => 'leaf',
                'color' => '#16a34a',
                'bg_light' => '#ecfdf5',
                'route' => '#',
                'count' => 'قريباً',
                'count_label' => '',
                'active' => false,
            ],
            [
                'id' => 'consultations',
                'name' => 'الاستشارات الهندسية والتدريب',
                'name_en' => 'Technical Consultations',
                'description' => 'تقييم السلامة وبناء القدرات المهنية المعتمدة',
                'icon' => 'academic-cap',
                'color' => '#d97706',
                'bg_light' => '#fffbeb',
                'route' => '#',
                'count' => 'قريباً',
                'count_label' => '',
                'active' => false,
            ],
        ];

        return view('dashboard', compact('stats', 'recentCertificates', 'modules'));
    }
}
