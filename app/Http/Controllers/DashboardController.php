<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Inspection;
use App\Models\Invoice;
use App\Models\Trainee;
use App\Models\TrainingCertificate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * IsoOx-Style Main Apps Launcher (/app)
     */
    public function apps(): View
    {
        $stats = $this->getStats();
        $modules = $this->getModulesList($stats);

        return view('apps', compact('stats', 'modules'));
    }

    /**
     * ERP Management Dashboard & Reports (/dashboard)
     */
    public function index(): View
    {
        $stats = $this->getStats();
        $modules = $this->getModulesList($stats);

        $recentCertificates = Certificate::with('customer')
            ->latest('id')
            ->take(5)
            ->get();

        $recentTrainingCertificates = TrainingCertificate::with('trainee')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentCertificates', 'recentTrainingCertificates', 'modules'));
    }

    /**
     * @return array<string, int>
     */
    protected function getStats(): array
    {
        return [
            'total_customers' => Customer::count(),
            'total_certificates' => Certificate::count(),
            'total_inspections' => Inspection::count(),
            'total_invoices' => Invoice::count(),
            'total_revenue' => (float) Invoice::sum('total_amount'),
            'total_trainees' => Trainee::count(),
            'total_training_certificates' => TrainingCertificate::count(),
            'valid_certificates' => Certificate::where('status', 'issued')
                ->where('is_safe_to_operate', true)
                ->where('next_examination_due_date', '>=', now())
                ->count(),
            'due_soon' => Certificate::where('status', 'issued')
                ->whereBetween('next_examination_due_date', [now(), now()->addDays(30)])
                ->count(),
        ];
    }

    /**
     * @param  array<string, int>  $stats
     * @return array<int, array<string, mixed>>
     */
    protected function getModulesList(array $stats): array
    {
        return [
            [
                'id' => 'start_inspection',
                'name' => 'بدء فحص ميداني',
                'name_en' => 'Start Inspection',
                'description' => 'بدء فحص وتعبئة شيك ليست المعدات من الموبايل وإنشاء الشهادة تلقائياً',
                'icon' => 'clipboard-document-check',
                'color' => '#006837',
                'bg_light' => '#E8F7EC',
                'route' => route('inspections.start'),
                'count' => $stats['total_inspections'],
                'count_label' => 'فحص',
                'active' => true,
                'category' => 'actions',
            ],
            [
                'id' => 'invoices',
                'name' => 'الفواتير والحسابات',
                'name_en' => 'Invoices & Billing',
                'description' => 'إصدار فواتير الفحص للعملاء وتحديد الأسعار والخصومات والمدفوع والمتبقي',
                'icon' => 'currency-dollar',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('invoices.index'),
                'count' => $stats['total_invoices'],
                'count_label' => 'فاتورة',
                'active' => true,
                'category' => 'financial',
            ],
            [
                'id' => 'profit_reports',
                'name' => 'تقرير وفهم الأرباح',
                'name_en' => 'Profit Analytics',
                'description' => 'مؤشرات الأداء المالي، الإيرادات المحصلة، المستحقات المعلقة، وتحليلات الفحص',
                'icon' => 'chart-bar',
                'color' => '#16a34a',
                'bg_light' => '#ecfdf5',
                'route' => route('reports.index'),
                'count' => null,
                'count_label' => 'تقارير فورية',
                'active' => true,
                'category' => 'analytics',
            ],
            [
                'id' => 'training_certificates',
                'name' => 'شهادات التدريب',
                'name_en' => 'Training Certs',
                'description' => 'إصدار شهادات الدورات التدريبية المعتمدة للمتدربين مع كود QR مخصص',
                'icon' => 'academic-cap',
                'color' => '#006837',
                'bg_light' => '#E8F7EC',
                'route' => route('training-certificates.index'),
                'count' => $stats['total_training_certificates'],
                'count_label' => 'شهادة',
                'active' => true,
                'category' => 'training',
            ],
            [
                'id' => 'trainees',
                'name' => 'سجل المتدربين',
                'name_en' => 'Trainees CRM',
                'description' => 'قاعدة بيانات المتدربين والجهات التابعين لها وسجل الدورات',
                'icon' => 'user-group',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('trainees.index'),
                'count' => $stats['total_trainees'],
                'count_label' => 'متدرب',
                'active' => true,
                'category' => 'crm',
            ],
            [
                'id' => 'certificates',
                'name' => 'شهادات الفحص والمعايرة',
                'name_en' => 'Inspection Certs',
                'description' => 'تقارير فحص معدات الرفع ومطابقة معايير LOLER و ISO المعتمدة',
                'icon' => 'document-check',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('certificates.index'),
                'count' => $stats['total_certificates'],
                'count_label' => 'شهادة',
                'active' => true,
                'category' => 'inspection',
            ],
            [
                'id' => 'customers',
                'name' => 'إدارة العملاء',
                'name_en' => 'Customers CRM',
                'description' => 'سجل الشركات والمصانع والموانئ ومواقع الفحص والاتصال',
                'icon' => 'users',
                'color' => '#22A447',
                'bg_light' => '#E8F7EC',
                'route' => route('customers.index'),
                'count' => $stats['total_customers'],
                'count_label' => 'عميل',
                'active' => true,
                'category' => 'crm',
            ],
            [
                'id' => 'new_training',
                'name' => 'إصدار شهادة تدريب',
                'name_en' => 'New Training',
                'description' => 'إصدار شهادة إتمام دورة تدريبية جديدة فوراً',
                'icon' => 'plus-circle',
                'color' => '#006837',
                'bg_light' => '#E8F7EC',
                'route' => route('training-certificates.create'),
                'count' => null,
                'count_label' => 'جديد',
                'active' => true,
                'category' => 'actions',
            ],
            [
                'id' => 'new_certificate',
                'name' => 'إصدار شهادة فحص',
                'name_en' => 'New Inspection',
                'description' => 'إدخال فحص معدة جديد وتوليد الـ PDF والأختام فوراً',
                'icon' => 'plus-circle',
                'color' => '#0E4DA4',
                'bg_light' => '#E6F2FF',
                'route' => route('certificates.create'),
                'count' => null,
                'count_label' => 'جديد',
                'active' => true,
                'category' => 'actions',
            ],
            [
                'id' => 'verify',
                'name' => 'أداة التحقق',
                'name_en' => 'Verify Tool',
                'description' => 'بوابة فحص ومطابقة صحة الشهادات وأكواد QR الرسمية',
                'icon' => 'shield-check',
                'color' => '#22A447',
                'bg_light' => '#E8F7EC',
                'route' => route('certificates.verify.lookup'),
                'count' => null,
                'count_label' => 'أداة فورية',
                'active' => true,
                'category' => 'tools',
            ],
            [
                'id' => 'dashboard_stats',
                'name' => 'لوحة الإحصائيات',
                'name_en' => 'Analytics',
                'description' => 'مؤشرات الأداء التشغيلية ومواعيد انتهاء الشهادات',
                'icon' => 'chart-pie',
                'color' => '#d97706',
                'bg_light' => '#fffbeb',
                'route' => route('dashboard'),
                'count' => null,
                'count_label' => 'تقارير',
                'active' => true,
                'category' => 'tools',
            ],
            [
                'id' => 'environmental',
                'name' => 'الدراسات البيئية',
                'name_en' => 'Environmental',
                'description' => 'حساب البصمة الكربونية وخدمات الحياد والاستدامة',
                'icon' => 'leaf',
                'color' => '#16a34a',
                'bg_light' => '#ecfdf5',
                'route' => 'javascript:void(0)',
                'count' => 'قريباً',
                'count_label' => '',
                'active' => false,
                'category' => 'future',
            ],
            [
                'id' => 'consultations',
                'name' => 'الاستشارات الهندسية',
                'name_en' => 'Consultations',
                'description' => 'تقييم السلامة وبناء القدرات المهنية المعتمدة',
                'icon' => 'academic-cap',
                'color' => '#d97706',
                'bg_light' => '#fffbeb',
                'route' => 'javascript:void(0)',
                'count' => 'قريباً',
                'count_label' => '',
                'active' => false,
                'category' => 'future',
            ],
            [
                'id' => 'studio',
                'name' => 'استوديو الشهادات',
                'name_en' => 'Cert Studio',
                'description' => 'تخصيص قوالب الشهادات والشورت كود ورموز QR',
                'icon' => 'document-check',
                'color' => '#c026d3',
                'bg_light' => '#fdf4ff',
                'route' => 'javascript:void(0)',
                'count' => 'قريباً',
                'count_label' => '',
                'active' => false,
                'category' => 'future',
            ],
            [
                'id' => 'settings',
                'name' => 'إعدادات النظام',
                'name_en' => 'Settings',
                'description' => 'إعدادات السيرفر والربط بموقع الشركة وتراخيص النظام',
                'icon' => 'cog',
                'color' => '#475569',
                'bg_light' => '#f1f5f9',
                'route' => 'javascript:void(0)',
                'count' => 'الإعدادات',
                'count_label' => '',
                'active' => false,
                'category' => 'system',
            ],
        ];
    }
}
