<?php

$enPath = __DIR__.'/../lang/en.json';
$arPath = __DIR__.'/../lang/ar.json';

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];

$templateTerms = [
    'أوناش وروافع' => 'Cranes & Lifting Appliances',
    'مهمات رفع وحبال' => 'Lifting Gear & Slings',
    'بيانات وهوية القالب (Template Identity)' => 'Template Identity & Properties',
    'نوع فئة القالب (Equipment Category)' => 'Template Category & Equipment Type',
    'اسم القالب في الاستوديو (Template Name)' => 'Template Name in Studio',
    'كود القالب الاختياري (Template Code)' => 'Optional Template Code',
    'دورة الصلاحية الافتراضية (الفحص القادم)' => 'Default Inspection Interval (Next Due)',
    'كل 6 أشهر (نصف سنوي - مهمات الرفع والأوناش)' => 'Every 6 Months (Semi-annual: Cranes & Slings)',
    'كل 12 شهراً (سنوي - المعدات العامة)' => 'Every 12 Months (Annual: General Cargo Units)',
    'كل 3 أشهر (فحص ربع سنوي شاق)' => 'Every 3 Months (Quarterly: Heavy Duty)',
    'كل 24 شهراً (سنتين)' => 'Every 24 Months (2 Years)',
    'تفعيل القالب في الاستوديو' => 'Enable Template in Studio',
    'تعيين كقالب معتمد افتراضي للفئة' => 'Set as Default Approved Template for Category',
    'المواصفات الفنية الافتراضية للمعدة' => 'Default Technical Specifications',
    'وصف المعدة (Equipment Description)' => 'Equipment Description',
    'الحمل الآمن للتشغيل (SWL)' => 'Safe Working Load (SWL)',
    'السعة / أقصى قدرة' => 'Capacity / Max Rating',
    'الشركة المصنعة' => 'Manufacturer',
    'الأبعاد / الطول والقطر' => 'Dimensions / Length & Diameter',
    'الطول الفعال (Length)' => 'Effective Length (Length)',
    '+ إضافة أداة / مواصفة' => '+ Add Tool / Specification',
    'تعديل القالب في الاستوديو' => 'Edit Studio Template',
    'حفظ التعديلات وتحديث القالب' => 'Save Changes & Update Template',
];

foreach ($templateTerms as $k => $v) {
    $en[$k] = $v;
    $ar[$k] = $k;
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Added studio template terms! Total keys: '.count($en)."\n";

// Now update certificate-templates/create.blade.php & edit.blade.php
$filesToUpdate = [
    __DIR__.'/../resources/views/certificate-templates/create.blade.php',
    __DIR__.'/../resources/views/certificate-templates/edit.blade.php',
];

$replacements = [
    '<strong class="text-xs font-bold block text-slate-800">أوناش وروافع</strong>' => '<strong class="text-xs font-bold block text-slate-800">{{ __(\'أوناش وروافع\') }}</strong>',
    '<strong class="text-xs font-bold block text-slate-800">مهمات رفع وحبال</strong>' => '<strong class="text-xs font-bold block text-slate-800">{{ __(\'مهمات رفع وحبال\') }}</strong>',
    '<strong class="text-xs font-bold block text-slate-800">معدات عامة وباليتات</strong>' => '<strong class="text-xs font-bold block text-slate-800">{{ __(\'معدات عامة وباليتات\') }}</strong>',
    'بيانات وهوية القالب (Template Identity)' => '{{ __(\'بيانات وهوية القالب (Template Identity)\') }}',
    'نوع فئة القالب (Equipment Category)' => '{{ __(\'نوع فئة القالب (Equipment Category)\') }}',
    'اسم القالب في الاستوديو (Template Name)' => '{{ __(\'اسم القالب في الاستوديو (Template Name)\') }}',
    'كود القالب الاختياري (Template Code)' => '{{ __(\'كود القالب الاختياري (Template Code)\') }}',
    'دورة الصلاحية الافتراضية (الفحص القادم)' => '{{ __(\'دورة الصلاحية الافتراضية (الفحص القادم)\') }}',
    'كل 6 أشهر (نصف سنوي - مهمات الرفع والأوناش)' => '{{ __(\'كل 6 أشهر (نصف سنوي - مهمات الرفع والأوناش)\') }}',
    'كل 12 شهراً (سنوي - المعدات العامة)' => '{{ __(\'كل 12 شهراً (سنوي - المعدات العامة)\') }}',
    'كل 3 أشهر (فحص ربع سنوي شاق)' => '{{ __(\'كل 3 أشهر (فحص ربع سنوي شاق)\') }}',
    'كل 24 شهراً (سنتين)' => '{{ __(\'كل 24 شهراً (سنتين)\') }}',
    'تفعيل القالب في الاستوديو' => '{{ __(\'تفعيل القالب في الاستوديو\') }}',
    'تعيين كقالب معتمد افتراضي للفئة' => '{{ __(\'تعيين كقالب معتمد افتراضي للفئة\') }}',
    'المواصفات الفنية الافتراضية للمعدة' => '{{ __(\'المواصفات الفنية الافتراضية للمعدة\') }}',
    'وصف المعدة (Equipment Description)' => '{{ __(\'وصف المعدة (Equipment Description)\') }}',
    'الحمل الآمن للتشغيل (SWL)' => '{{ __(\'الحمل الآمن للتشغيل (SWL)\') }}',
    'السعة / أقصى قدرة' => '{{ __(\'السعة / أقصى قدرة\') }}',
    'الأبعاد / الطول والقطر' => '{{ __(\'الأبعاد / الطول والقطر\') }}',
    'الطول الفعال (Length)' => '{{ __(\'الطول الفعال (Length)\') }}',
    '<span>+ إضافة أداة / مواصفة</span>' => '<span>{{ __(\'+ إضافة أداة / مواصفة\') }}</span>',
    'يرجى تصحيح الأخطاء التالية:' => '{{ __(\'يرجى تصحيح الأخطاء التالية:\') }}',
];

foreach ($filesToUpdate as $file) {
    if (file_exists($file)) {
        $c = file_get_contents($file);
        foreach ($replacements as $s => $r) {
            $c = str_replace($s, $r, $c);
        }
        file_put_contents($file, $c);
        echo 'Updated '.basename($file)."\n";
    }
}
