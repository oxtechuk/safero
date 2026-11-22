<?php

$enPath = __DIR__.'/../lang/en.json';
$arPath = __DIR__.'/../lang/ar.json';

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];

$menuTranslations = [
    'الرئيسية' => 'Home',
    'الخيارات التقنية' => 'Technical Settings',
    'الخيارات التقنية (Technical)' => 'Technical Settings',
    'واجهة المستخدم' => 'User Interface',
    'واجهة المستخدم (User Interface)' => 'User Interface',
    'عناصر القوائم' => 'Menu Items',
    'عناصر القوائم (Menu Items)' => 'Menu Items',
    'قوالب الشهادات والشعار' => 'Certificate Templates & Branding',
    'قوالب الشهادات والشعار (Branding & Assets)' => 'Certificate Templates & Branding',
    'البنية ونظام SaaS' => 'Architecture & SaaS System',
    'البنية ونظام SaaS (Architecture)' => 'Architecture & SaaS System',
    'منصة SaaS والمديولات مفتوحة المصدر' => 'SaaS Platform & Open Modules',
    'معايير الاعتماد الهندسي (ISO 17020)' => 'Accreditation Standards (ISO 17020)',
    'الفحص الميداني والشيك ليست' => 'Field Inspection & Checklist',
    'جديد' => 'New',
    'بدء فحص ميداني جديد (شيك ليست)' => 'Start Field Inspection (Checklist)',
    'سجل الفحوصات الميدانية' => 'Field Inspections Registry',
    'نماذج الفحص المعتمدة' => 'Approved Inspection Forms',
    'فحص الأوناش والروافع' => 'Lifting Appliances (Cranes)',
    'مهمات الرفع والحبال' => 'Lifting Accessories & Slings',
    'معدات عامة وباليتات' => 'General Equipment & Cargo',
    'ستوديو شهادات الفحص (Studio)' => 'Inspection Template Studio',
    '+ تصميم وتخصيص قالب جديد' => '+ Design & Customize Template',
    'سجل الشهادات والعمليات' => 'Certificates & Operations Registry',
    'سجل الشهادات (جميع الشهادات)' => 'Certificates Log (All)',
    'الشهادات السارية المعتمدة' => 'Valid Certified Certificates',
    'الشهادات القريبة من الانتهاء' => 'Expiring Soon Certificates',
    'المسودات قيد المراجعة' => 'Drafts Under Review',
    'التحقق والامتثال' => 'Verification & Compliance',
    'فحص رمز الاستجابة QR Code' => 'Verify QR Code',
    'شهادات المتدربين' => 'Trainee Certificates',
    'إصدار شهادة تدريب جديدة' => 'Issue New Training Certificate',
    'سجل شهادات التدريب المعتمدة' => 'Certified Training Registry',
    'إدارة المتدربين' => 'Trainee Management',
    'قاعدة بيانات المتدربين' => 'Trainees Database',
    'تسجيل متدرب جديد' => 'Register New Trainee',
    'دليل الشركات والمصانع' => 'Companies & Plants Directory',
    'إضافة عميل أو شركة جديدة' => 'Add New Client / Company',
    'فواتير الفحص والمعايرة' => 'Inspection & Calibration Invoices',
    '+ إنشاء فاتورة جديدة للعميل' => '+ Create New Client Invoice',
    'سجل الفواتير الصادرة' => 'Issued Invoices Log',
    'الفواتير غير المدفوعة / المعلقة' => 'Unpaid / Pending Invoices',
    'تقرير الأرباح والتحصيل السريع' => 'Revenue & Collections Report',
    'التحليلات والمؤشرات والأرباح' => 'Analytics, KPIs & Revenue',
    'تقرير الفحص' => 'Inspection Report',
    'لوحة المؤشرات الشاملة' => 'Comprehensive Dashboard',
    'تقرير فحص الأوناش والمعدات' => 'Cranes & Equipment Report',
    'تقرير شهادات التدريب' => 'Training Certificates Report',
    'التهيئة والإعدادات' => 'Configuration & Settings',
    'والإعدادات' => 'Settings',
    'إعدادات النظام والشركة' => 'System & Company Settings',
    'الإعدادات العامة (General Settings)' => 'General Settings',
    'المستخدمون والشركات (Users & Companies)' => 'Users & Companies',
    'الشعار والأختام والقوالب (Branding)' => 'Logos, Stamps & Templates',
    'منصة SaaS والمديولات (Architecture)' => 'SaaS Platform & Architecture',
    'المستخدمون والشركات' => 'Users & Companies',
    'الشعار والأختام والقوالب' => 'Logos, Stamps & Templates',
    'منصة SaaS والمديولات' => 'SaaS Platform & Modules',
    'أدوات المنظومة' => 'Platform Utilities',
    'شاشة التطبيقات (Apps Launcher)' => 'Apps Launcher',
    'أداة التحقق من الشهادات' => 'Certificate Verification Tool',
    'سجل المستخدمين' => 'Users Registry',
    'إضافة مستخدم جديد' => 'Add New User',
    'أدوار النظام ومصفوفة الصلاحيات' => 'System Roles & Permissions Matrix',
    'إعدادات الشركات والولوج' => 'Companies & Access Settings',
    'أوامر الشغل ومهام الفحص' => 'Work Orders & Inspection Tasks',
    '+ إضافة أمر شغل جديد' => '+ Add New Work Order',
    'قيد الفحص الميداني' => 'In Field Inspection',
    'مكتملة الفحص' => 'Completed Inspection',
    'مزامنة Google Sheets' => 'Sync Google Sheets',
    'دليل العملاء والشركات' => 'Clients & Companies Directory',
    'إضافة عميل جديد' => 'Add New Client',
    'لوحة المؤشرات والتقارير' => 'Dashboard & Reports',
    'مؤشرات شهادات الفحص' => 'Inspection Metrics',
    'مؤشرات شهادات التدريب' => 'Training Metrics',
    'الرجوع إلى شاشة التطبيقات الرئيسية (Apps Launcher)' => 'Back to Apps Launcher',
    'الرسائل والملاحظات (Activities & Messages)' => 'Activities & Messages',
    'الأنشطة المجدولة وجداول الفحص (Activities)' => 'Scheduled Inspection Activities',
    'إعدادات الربط' => 'Integration Settings',
    'مزامنة فورية مع Google Sheets' => 'Instant Google Sheets Sync',
];

foreach ($menuTranslations as $k => $v) {
    $en[$k] = $v;
    $ar[$k] = $k;
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Successfully updated dictionaries. Total keys in en: '.count($en).PHP_EOL;
