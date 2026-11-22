<?php

$en = json_decode(file_get_contents(__DIR__.'/../lang/en.json'), true);
$keys = ['الرئيسية', 'شهادات الفحص', 'شهادات التدريب', 'العملاء', 'الفواتير والحسابات', 'التقارير', 'التهيئة والإعدادات'];

foreach ($keys as $k) {
    echo "$k => ".($en[$k] ?? 'MISSING').PHP_EOL;
}
