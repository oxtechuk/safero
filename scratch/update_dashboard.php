<?php

$enPath = __DIR__.'/../lang/en.json';
$arPath = __DIR__.'/../lang/ar.json';

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];

$dashboardTerms = [
    'لوحة المديولات الرئيسية' => 'Main Modules Dashboard',
    'شهادات التدريب الصادرة' => 'Issued Training Certificates',
    'المتدربون المسجلون' => 'Registered Trainees',
    'شهادات فحص المعدات' => 'Equipment Inspection Certificates',
    'العملاء والشركات' => 'Clients & Companies',
    'أحدث شهادات التدريب المعتمدة الصادرة' => 'Latest Issued Training Certificates',
    'آخر شهادات الدورات التدريبية المعتمدة للمتدربين مع كود QR والـ PDF' => 'Recent certified training courses certificates with QR verification & PDF',
    'عرض جميع شهادات التدريب' => 'View All Training Certificates',
    'اسم المتدرب (Holder)' => 'Trainee Name (Holder)',
    'رقم المسلسل' => 'Serial No',
    'عنوان البرنامج التدريبي' => 'Training Program Title',
    'المجال' => 'Scope',
    'الساعات' => 'Hours',
    'تاريخ الشهادة' => 'Certificate Date',
    'معاينة' => 'Preview',
    'تحميل PDF' => 'Download PDF',
    'لا توجد شهادات تدريب مسجلة بعد.' => 'No training certificates registered yet.',
    'أحدث تقارير وشهادات الفحص الشامل' => 'Latest Comprehensive Inspection Certificates',
    'آخر تقارير الفحص الصادرة للمعدات' => 'Recent equipment inspection reports',
    'عرض جميع شهادات الفحص' => 'View All Inspection Certificates',
    'آمنة للتشغيل' => 'Safe to Operate',
    'غير آمنة' => 'Unsafe to Operate',
    'معاينة الشهادة والـ QR' => 'Preview Certificate & QR',
    'تحميل PDF رسمي' => 'Download Official PDF',
    'لا توجد شهادات مسجلة حتى الآن.' => 'No certificates recorded yet.',
];

foreach ($dashboardTerms as $k => $v) {
    $en[$k] = $v;
    $ar[$k] = $k;
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Dictionaries updated with dashboard terms! Total keys: '.count($en)."\n";

// Update dashboard.blade.php
$dashPath = __DIR__.'/../resources/views/dashboard.blade.php';
$d = file_get_contents($dashPath);

$replacements = [
    "@section('title', 'لوحة المديولات الرئيسية')" => "@section('title', __('لوحة المديولات الرئيسية'))",
    'شهادات التدريب الصادرة' => '{{ __(\'شهادات التدريب الصادرة\') }}',
    'المتدربون المسجلون' => '{{ __(\'المتدربون المسجلون\') }}',
    'شهادات فحص المعدات' => '{{ __(\'شهادات فحص المعدات\') }}',
    'العملاء والشركات' => '{{ __(\'العملاء والشركات\') }}',
    '<h3 class="font-bold text-slate-900 text-base flex items-center gap-2">'."\n".'                    <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>'."\n".'                    أحدث شهادات التدريب المعتمدة الصادرة' => '<h3 class="font-bold text-slate-900 text-base flex items-center gap-2">'."\n".'                    <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>'."\n".'                    {{ __(\'أحدث شهادات التدريب المعتمدة الصادرة\') }}',
    '<p class="text-xs text-slate-500">آخر شهادات الدورات التدريبية المعتمدة للمتدربين مع كود QR والـ PDF</p>' => '<p class="text-xs text-slate-500">{{ __(\'آخر شهادات الدورات التدريبية المعتمدة للمتدربين مع كود QR والـ PDF\') }}</p>',
    '<span>عرض جميع شهادات التدريب ({{ $stats[\'total_training_certificates\'] }})</span>' => '<span>{{ __(\'عرض جميع شهادات التدريب\') }} ({{ $stats[\'total_training_certificates\'] }})</span>',
    '<th class="p-3.5">اسم المتدرب (Holder)</th>' => '<th class="p-3.5">{{ __(\'اسم المتدرب (Holder)\') }}</th>',
    '<th class="p-3.5">رقم المسلسل</th>' => '<th class="p-3.5">{{ __(\'رقم المسلسل\') }}</th>',
    '<th class="p-3.5">عنوان البرنامج التدريبي</th>' => '<th class="p-3.5">{{ __(\'عنوان البرنامج التدريبي\') }}</th>',
    '<th class="p-3.5">المجال</th>' => '<th class="p-3.5">{{ __(\'المجال\') }}</th>',
    '<th class="p-3.5 text-center">الساعات</th>' => '<th class="p-3.5 text-center">{{ __(\'الساعات\') }}</th>',
    '<th class="p-3.5">تاريخ الشهادة</th>' => '<th class="p-3.5">{{ __(\'تاريخ الشهادة\') }}</th>',
    '<th class="p-3.5 text-center">الحالة</th>' => '<th class="p-3.5 text-center">{{ __(\'الحالة\') }}</th>',
    '<th class="p-3.5 text-center">الإجراءات</th>' => '<th class="p-3.5 text-center">{{ __(\'الإجراءات\') }}</th>',
    'title="معاينة"' => 'title="{{ __(\'معاينة\') }}"',
    'title="تحميل PDF"' => 'title="{{ __(\'تحميل PDF\') }}"',
    'لا توجد شهادات تدريب مسجلة بعد.' => '{{ __(\'لا توجد شهادات تدريب مسجلة بعد.\') }}',
    '<h3 class="font-bold text-slate-900 text-base flex items-center gap-2">'."\n".'                    <span class="w-2 h-5 bg-[#0E4DA4] rounded-full inline-block"></span>'."\n".'                    أحدث تقارير وشهادات الفحص الشامل' => '<h3 class="font-bold text-slate-900 text-base flex items-center gap-2">'."\n".'                    <span class="w-2 h-5 bg-[#0E4DA4] rounded-full inline-block"></span>'."\n".'                    {{ __(\'أحدث تقارير وشهادات الفحص الشامل\') }}',
    '<p class="text-xs text-slate-500">آخر تقارير الفحص الصادرة للمعدات</p>' => '<p class="text-xs text-slate-500">{{ __(\'آخر تقارير الفحص الصادرة للمعدات\') }}</p>',
    '<span>عرض جميع شهادات الفحص ({{ $stats[\'total_certificates\'] }})</span>' => '<span>{{ __(\'عرض جميع شهادات الفحص\') }} ({{ $stats[\'total_certificates\'] }})</span>',
    '<th class="p-3.5 font-bold">رقم الشهادة</th>' => '<th class="p-3.5 font-bold">{{ __(\'رقم الشهادة\') }}</th>',
    '<th class="p-3.5 font-bold">اسم العميل</th>' => '<th class="p-3.5 font-bold">{{ __(\'اسم العميل\') }}</th>',
    '<th class="p-3.5 font-bold">وصف المعدة</th>' => '<th class="p-3.5 font-bold">{{ __(\'وصف المعدة\') }}</th>',
    '<th class="p-3.5 font-bold">الحمل الآمن (SWL)</th>' => '<th class="p-3.5 font-bold">{{ __(\'الحمل الآمن (SWL)\') }}</th>',
    '<th class="p-3.5 font-bold">تاريخ الفحص</th>' => '<th class="p-3.5 font-bold">{{ __(\'تاريخ الفحص\') }}</th>',
    '<th class="p-3.5 font-bold">تاريخ الاستحقاق</th>' => '<th class="p-3.5 font-bold">{{ __(\'تاريخ الاستحقاق\') }}</th>',
    '<th class="p-3.5 font-bold">الحالة</th>' => '<th class="p-3.5 font-bold">{{ __(\'الحالة\') }}</th>',
    '<th class="p-3.5 font-bold text-center">الإجراءات</th>' => '<th class="p-3.5 font-bold text-center">{{ __(\'الإجراءات\') }}</th>',
    'آمنة للتشغيل' => '{{ __(\'آمنة للتشغيل\') }}',
    'غير آمنة' => '{{ __(\'غير آمنة\') }}',
    'title="معاينة الشهادة والـ QR"' => 'title="{{ __(\'معاينة الشهادة والـ QR\') }}"',
    'title="تحميل PDF رسمي"' => 'title="{{ __(\'تحميل PDF رسمي\') }}"',
    'لا توجد شهادات مسجلة حتى الآن.' => '{{ __(\'لا توجد شهادات مسجلة حتى الآن.\') }}',
    '>'."\n".'                                    معتمدة'."\n".'                                </span>' => '>'."\n".'                                    {{ __(\'معتمدة\') }}'."\n".'                                </span>',
];

foreach ($replacements as $s => $r) {
    $d = str_replace($s, $r, $d);
}

file_put_contents($dashPath, $d);
echo "dashboard.blade.php updated successfully!\n";
