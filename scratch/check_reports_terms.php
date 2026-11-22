<?php

$en = json_decode(file_get_contents('lang/en.json'), true) ?: [];
$keys = [
    'تقارير الأرباح' => 'Profit & Revenue Reports',
    'لوحة تقارير الفحص وتحليل الأرباح' => 'Inspection Reports & Profit Analysis Dashboard',
    'مؤشرات الأداء المالي، التدفقات النقدية، وحجم أعمال الفحص لجميع العملاء' => 'Financial performance indicators, cash flows, and inspection volume across all clients',
    'كل الفترات' => 'All Periods',
    'هذا الشهر' => 'This Month',
    'الشهر الماضي' => 'Last Month',
    'هذا العام' => 'This Year',
    'طباعة التقرير' => 'Print Report',
    'إجمالي الإيرادات المفوترة' => 'Total Invoiced Revenue',
    'إجمالي' => 'Total',
    'فاتورة' => 'Invoices',
    'المحصل نقداً / تحويلات (الأرباح المحققة)' => 'Cash / Transfers Collected (Realized Profit)',
    'نسبة التحصيل:' => 'Collection Rate:',
    'المتبقي لدى العملاء (الديون المستحقة)' => 'Receivables Balance (Outstanding Debt)',
    'مستحقات واجبة التحصيل' => 'Receivables Due for Collection',
    'إجمالي شهادات الفحص الصادرة' => 'Total Inspection Certificates Issued',
    'شهادة' => 'Certificates',
    'متوسط الفحص:' => 'Average Inspection:',
    'مؤشرات الجودة والسلامة الفنية (Safety & Compliance Ratio)' => 'Quality & Safety Compliance Indicators',
    'نسبة المعدات الآمنة للاستخدام مقابل المعدات التي تم رصد عيوب بها' => 'Ratio of safe equipment in service vs defective equipment detected',
    'إجمالي الفحوصات الميدانية:' => 'Total Field Inspections:',
    'معدات مطابقة وآمنة (Safe)' => 'Compliant & Safe Equipment',
    'معدات بها عيوب / مرفوضة (Unsafe)' => 'Defective / Rejected Equipment',
    'إجمالي الخصومات الممنوحة' => 'Total Discounts Granted',
    'خصم تجاري' => 'Commercial Discount',
    'حركة الإيرادات والتحصيل خلال آخر 6 أشهر' => 'Revenue & Collection Trend Over Last 6 Months',
    'مقارنة شهرية بين المبالغ المفوترة والمبالغ المحصلة فعلياً' => 'Monthly comparison between invoiced amounts and cash actually collected',
    'تم تحصيل:' => 'Collected:',
    'فحص' => 'Inspections',
    'سجل أرباح ومديونيات العملاء (Client Profitability)' => 'Client Profitability & Balance Log',
    'حجم الأعمال والمبالغ المسددة والمتبقية لكل عميل' => 'Business volume, amounts paid, and balance due per client',
    'عميل' => 'Clients',
    'العميل' => 'Client',
    'شهادات الفحص' => 'Inspection Certificates',
    'الفواتير' => 'Invoices',
    'إجمالي الأعمال المفوترة' => 'Total Invoiced Business',
    'المسدد (الأرباح المحصلة)' => 'Amount Paid (Realized Profit)',
    'المتبقي (ديون مستحقة)' => 'Balance Due (Receivables)',
    'حالة الحساب' => 'Account Status',
    'ملتزم بالسداد' => 'Fully Settled',
    'متبقي جزء' => 'Partially Paid',
    'مديونية معلقة' => 'Outstanding Balance',
    'لا توجد بيانات حسابات مسجلة' => 'No client financial data registered',
    'تقرير مالي رسمي لتحليل الأرباح وحجم الأعمال' => 'Official Financial Report - Profit & Volume Analysis',
    'تاريخ وتوقيت استخراج التقرير:' => 'Report Generation Timestamp:',
    'الفترة الزمنية:' => 'Time Period:',
];

$ar = json_decode(file_get_contents('lang/ar.json'), true) ?: [];

$added = 0;
foreach ($keys as $k => $v) {
    if (! isset($en[$k])) {
        $en[$k] = $v;
        $added++;
    }
    if (! isset($ar[$k])) {
        $ar[$k] = $k;
    }
}

file_put_contents('lang/en.json', json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents('lang/ar.json', json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Added {$added} report terms to dictionary. Total EN terms: ".count($en).PHP_EOL;
