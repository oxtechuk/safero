<?php

// 1. Update apps.blade.php
$appsPath = __DIR__.'/../resources/views/apps.blade.php';
$appsContent = file_get_contents($appsPath);

$appsReplacements = [
    'placeholder="بحث في المديولات..."' => 'placeholder="{{ __(\'بحث في المديولات...\') }}"',
    "{{ Auth::user()->name ?? 'مدير النظام' }}" => "{{ Auth::user()->name ?? __('مدير النظام') }}",
    'title="تسجيل الخروج"' => 'title="{{ __(\'تسجيل الخروج\') }}"',
    'منصة شركة سافيرو (Safero) الموحدة لإدارة الفحص الشامل، شهادات التدريب المعتمدة، وسجلات المتدربين والعملاء.' => "{{ __('منصة شركة سافيرو (Safero) الموحدة لإدارة الفحص الشامل، شهادات التدريب المعتمدة، وسجلات المتدربين والعملاء.') }}",
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-emerald-700 transition">'."\n".'                            بدء فحص ميداني' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-emerald-700 transition">'."\n".'                            {{ __(\'بدء فحص ميداني\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">'."\n".'                            شهادات الفحص' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">'."\n".'                            {{ __(\'شهادات الفحص\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#006837] transition">'."\n".'                            الفواتير والحسابات' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#006837] transition">'."\n".'                            {{ __(\'الفواتير والحسابات\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-amber-600 transition">'."\n".'                            تقارير الأرباح' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-amber-600 transition">'."\n".'                            {{ __(\'تقارير الأرباح\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-rose-600 transition">'."\n".'                            إدارة العملاء' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-rose-600 transition">'."\n".'                            {{ __(\'إدارة العملاء\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-emerald-700 transition">'."\n".'                            شهادات التدريب' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-emerald-700 transition">'."\n".'                            {{ __(\'شهادات التدريب\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">'."\n".'                            سجل المتدربين' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">'."\n".'                            {{ __(\'سجل المتدربين\') }}',
    '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#714B67] transition">'."\n".'                            إعدادات المنظومة' => '<span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#714B67] transition">'."\n".'                            {{ __(\'إعدادات المنظومة\') }}',
    '>'."\n".'                                أرباح'."\n".'                            </span>' => '>'."\n".'                                {{ __(\'أرباح\') }}'."\n".'                            </span>',
    '>'."\n".'                                جديد'."\n".'                            </span>' => '>'."\n".'                                {{ __(\'جديد\') }}'."\n".'                            </span>',
];

foreach ($appsReplacements as $search => $replace) {
    $appsContent = str_replace($search, $replace, $appsContent);
}
file_put_contents($appsPath, $appsContent);
echo "apps.blade.php updated successfully!\n";

// 2. Update certificates/create.blade.php
$createPath = __DIR__.'/../resources/views/certificates/create.blade.php';
$createContent = file_get_contents($createPath);

$createReplacements = [
    "@section('title', 'إصدار شهادة - ' . \$activeMeta['ar'])" => "@section('title', __('إصدار شهادة') . ' - ' . \$activeMeta['ar'])",
    "'ar' => 'فحص الأوناش والروافع المتنقلة'" => "'ar' => __('فحص الأوناش والروافع المتنقلة')",
    "'badge' => 'تقرير تفصيلي (صفحتين) + فحص 28 نقطة'" => "'badge' => __('تقرير تفصيلي (صفحتين) + فحص 28 نقطة')",
    "'ar' => 'فحص مهمات الرفع والحبال والشكالات'" => "'ar' => __('فحص مهمات الرفع والحبال والشكالات')",
    "'badge' => 'تقرير فحص معتمد (صفحة واحدة)'" => "'badge' => __('تقرير فحص معتمد (صفحة واحدة)')",
    "'ar' => 'فحص المعدات العامة ووحدات الشحن والباليتات'" => "'ar' => __('فحص المعدات العامة ووحدات الشحن والباليتات')",
    '← سجل الشهادات' => '← {{ __(\'سجل الشهادات\') }}',
    'نوع الفحص:' => '{{ __(\'نوع الفحص:\') }}',
    'أوناش (Crane)' => '{{ __(\'أوناش (Crane)\') }}',
    'مهمات رفع (Accessory)' => '{{ __(\'مهمات رفع (Accessory)\') }}',
    'عام وباليتات (General)' => '{{ __(\'عام وباليتات (General)\') }}',
    '<span>ستوديو قوالب الفحص</span>' => '<span>{{ __(\'ستوديو قوالب الفحص\') }}</span>',
    '<span>تحميل قالب فحص من الاستوديو:</span>' => '<span>{{ __(\'تحميل قالب فحص من الاستوديو:\') }}</span>',
    '-- اختر قالباً لتعبئة البيانات تلقائياً --' => '{{ __(\'-- اختر قالباً لتعبئة البيانات تلقائياً --\') }}',
    '<span>تم تفعيل القالب:' => '<span>{{ __(\'تم تفعيل القالب:\') }}',
    'إلغاء القالب' => '{{ __(\'إلغاء القالب\') }}',
    '<span>تجديد فحص دوري للشهادة:' => '<span>{{ __(\'تجديد فحص دوري للشهادة:\') }}',
    'اختر قالباً جاهزاً من الاستوديو لتعبئة المواصفات والأدوات تلقائياً' => '{{ __(\'اختر قالباً جاهزاً من الاستوديو لتعبئة المواصفات والأدوات تلقائياً\') }}',
    '<h2 class="font-bold text-slate-800 text-xs">بيانات الشهادة والعميل وموقع الفحص</h2>' => '<h2 class="font-bold text-slate-800 text-xs">{{ __(\'بيانات الشهادة والعميل وموقع الفحص\') }}</h2>',
    'رقم الشهادة <span class="text-red-500">*</span>' => '{{ __(\'رقم الشهادة\') }} <span class="text-red-500">*</span>',
    'رقم أمر الشغل (Order No)' => '{{ __(\'رقم أمر الشغل (Order No)\') }}',
    'اسم العميل <span class="text-red-500">*</span>' => '{{ __(\'اسم العميل\') }} <span class="text-red-500">*</span>',
    '-- اختر العميل --' => '{{ __(\'-- اختر العميل --\') }}',
    'موقع الفحص <span class="text-red-500">*</span>' => '{{ __(\'موقع الفحص\') }} <span class="text-red-500">*</span>',
    'تاريخ الفحص <span class="text-red-500">*</span>' => '{{ __(\'تاريخ الفحص\') }} <span class="text-red-500">*</span>',
    'تاريخ الاستحقاق القادم <span class="text-red-500">*</span>' => '{{ __(\'تاريخ الاستحقاق القادم\') }} <span class="text-red-500">*</span>',
    'المواصفة المرجعية <span class="text-red-500">*</span>' => '{{ __(\'المواصفة المرجعية\') }} <span class="text-red-500">*</span>',
    'الحمل الآمن (SWL) <span class="text-red-500">*</span>' => '{{ __(\'الحمل الآمن (SWL)\') }} <span class="text-red-500">*</span>',
    '<h2 class="font-bold text-slate-800 text-xs">مواصفات وبيانات المعدة المفحوصة</h2>' => '<h2 class="font-bold text-slate-800 text-xs">{{ __(\'مواصفات وبيانات المعدة المفحوصة\') }}</h2>',
    'وصف المعدة (Description) <span class="text-red-500">*</span>' => '{{ __(\'وصف المعدة (Description)\') }} <span class="text-red-500">*</span>',
    'الشركة المصنعة (Manufacturer)' => '{{ __(\'الشركة المصنعة (Manufacturer)\') }}',
    'الموديل (Model)' => '{{ __(\'الموديل (Model)\') }}',
    'السعة (Capacity)' => '{{ __(\'السعة (Capacity)\') }}',
    'رقم الشاسيه / السيريال' => '{{ __(\'رقم الشاسيه / السيريال\') }}',
    'سنة الصنع (Year)' => '{{ __(\'سنة الصنع (Year)\') }}',
    'قطر الواير الرئيسي' => '{{ __(\'قطر الواير الرئيسي\') }}',
    'سعة الهوك الرئيسي' => '{{ __(\'سعة الهوك الرئيسي\') }}',
    'رقم شهادة تجربة الحمل' => '{{ __(\'رقم شهادة تجربة الحمل\') }}',
    'تاريخ تجربة الحمل' => '{{ __(\'تاريخ تجربة الحمل\') }}',
    'مواصفات ونقطة تجربة الحمل (Test Point Particulars)' => '{{ __(\'مواصفات ونقطة تجربة الحمل (Test Point Particulars)\') }}',
    'وصف المهمات (Description) <span class="text-red-500">*</span>' => '{{ __(\'وصف المهمات (Description)\') }} <span class="text-red-500">*</span>',
    'جهة التصنيع (Manufacturer)' => '{{ __(\'جهة التصنيع (Manufacturer)\') }}',
    'معامل الأمان (Safety Factor)' => '{{ __(\'معامل الأمان (Safety Factor)\') }}',
    'الطول (Length)' => '{{ __(\'الطول (Length)\') }}',
    'الكمية (Quantity)' => '{{ __(\'الكمية (Quantity)\') }}',
    'سيريال / كود المهمات (ID Range)' => '{{ __(\'سيريال / كود المهمات (ID Range)\') }}',
    'سنة الصنع (Manufacture Date)' => '{{ __(\'سنة الصنع (Manufacture Date)\') }}',
    'الأبعاد (Dimensions: L, W, H)' => '{{ __(\'الأبعاد (Dimensions: L, W, H)\') }}',
    'ترقيم المعدات (Equipment ID)' => '{{ __(\'ترقيم المعدات (Equipment ID)\') }}',
    'قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)' => '{{ __(\'قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)\') }}',
    'تحديد الكل سليم (Pass All)' => '{{ __(\'تحديد الكل سليم (Pass All)\') }}',
    'أسئلة الفحص النظامية (LOLER 1998) والقرار الفني' => '{{ __(\'أسئلة الفحص النظامية (LOLER 1998) والقرار الفني\') }}',
    'أول فحص بعد التركيب؟' => '{{ __(\'أول فحص بعد التركيب؟\') }}',
    'الفحص خلال 6 أشهر؟' => '{{ __(\'الفحص خلال 6 أشهر؟\') }}',
    'تم التركيب بشكل سليم؟' => '{{ __(\'تم التركيب بشكل سليم؟\') }}',
    'الفحص خلال 12 شهر؟' => '{{ __(\'الفحص خلال 12 شهر؟\') }}',
    'وفق مخطط الفحص؟' => '{{ __(\'وفق مخطط الفحص؟\') }}',
    'بعد ظروف استثنائية؟' => '{{ __(\'بعد ظروف استثنائية؟\') }}',
    'الأجزاء المعيبة إن وجدت' => '{{ __(\'الأجزاء المعيبة إن وجدت\') }}',
    'الإصلاحات المطلوبة' => '{{ __(\'الإصلاحات المطلوبة\') }}',
    'هل العيب يشكل خطورة على العمليات؟' => '{{ __(\'هل العيب يشكل خطورة على العمليات؟\') }}',
    'تفاصيل الفحوصات والاختبارات المنفذة <span class="text-red-500">*</span>' => '{{ __(\'تفاصيل الفحوصات والاختبارات المنفذة\') }} <span class="text-red-500">*</span>',
    'هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)' => '{{ __(\'هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)\') }}',
    'يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة' => '{{ __(\'يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة\') }}',
    '<span>YES (آمنة)</span>' => '<span>{{ __(\'YES (آمنة)\') }}</span>',
    '<span>NO (غير آمنة)</span>' => '<span>{{ __(\'NO (غير آمنة)\') }}</span>',
    'مهندس الفحص (Issuer) <span class="text-red-500">*</span>' => '{{ __(\'مهندس الفحص (Issuer)\') }} <span class="text-red-500">*</span>',
    'معتمد الشهادة (Authenticator) <span class="text-red-500">*</span>' => '{{ __(\'معتمد الشهادة (Authenticator)\') }} <span class="text-red-500">*</span>',
    'حالة الشهادة <span class="text-red-500">*</span>' => '{{ __(\'حالة الشهادة\') }} <span class="text-red-500">*</span>',
    'معتمدة ومصدرة (Issued)' => '{{ __(\'معتمدة ومصدرة (Issued)\') }}',
    'مسودة للمراجعة (Draft)' => '{{ __(\'مسودة للمراجعة (Draft)\') }}',
    '<span>حفظ وإصدار</span>' => '<span>{{ __(\'حفظ وإصدار\') }}</span>',
    '>'."\n".'                        إلغاء'."\n".'                    </a>' => '>'."\n".'                        {{ __(\'إلغاء\') }}'."\n".'                    </a>',
];

foreach ($createReplacements as $search => $replace) {
    $createContent = str_replace($search, $replace, $createContent);
}
file_put_contents($createPath, $createContent);
echo "certificates/create.blade.php updated successfully!\n";

// 3. Update certificates/edit.blade.php
$editPath = __DIR__.'/../resources/views/certificates/edit.blade.php';
if (file_exists($editPath)) {
    $editContent = file_get_contents($editPath);
    $editReplacements = [
        "@section('title', 'تعديل شهادة: ' . \$certificate->certificate_number)" => "@section('title', __('تعديل شهادة:') . ' ' . \$certificate->certificate_number)",
        '<h2 class="font-bold text-slate-800 text-xs">معلومات الشهادة والعميل وموقع الفحص</h2>' => '<h2 class="font-bold text-slate-800 text-xs">{{ __(\'معلومات الشهادة والعميل وموقع الفحص\') }}</h2>',
        '<label for="certificate_number" class="block font-bold text-slate-700 mb-1">رقم الشهادة</label>' => '<label for="certificate_number" class="block font-bold text-slate-700 mb-1">{{ __(\'رقم الشهادة\') }}</label>',
        '<label for="order_no" class="block font-bold text-slate-700 mb-1">رقم أمر الشغل</label>' => '<label for="order_no" class="block font-bold text-slate-700 mb-1">{{ __(\'رقم أمر الشغل\') }}</label>',
        '<label for="customer_id" class="block font-bold text-slate-700 mb-1">اسم العميل</label>' => '<label for="customer_id" class="block font-bold text-slate-700 mb-1">{{ __(\'اسم العميل\') }}</label>',
        '<label for="examination_address" class="block font-bold text-slate-700 mb-1">موقع الفحص</label>' => '<label for="examination_address" class="block font-bold text-slate-700 mb-1">{{ __(\'موقع الفحص\') }}</label>',
        '<label for="thorough_examination_date" class="block font-bold text-slate-700 mb-1">تاريخ الفحص</label>' => '<label for="thorough_examination_date" class="block font-bold text-slate-700 mb-1">{{ __(\'تاريخ الفحص\') }}</label>',
        '<label for="next_examination_due_date" class="block font-bold text-slate-700 mb-1">تاريخ الاستحقاق القادم</label>' => '<label for="next_examination_due_date" class="block font-bold text-slate-700 mb-1">{{ __(\'تاريخ الاستحقاق القادم\') }}</label>',
        '<label for="reference_standard" class="block font-bold text-slate-700 mb-1">المواصفة المرجعية</label>' => '<label for="reference_standard" class="block font-bold text-slate-700 mb-1">{{ __(\'المواصفة المرجعية\') }}</label>',
        '<label for="swl" class="block font-bold text-slate-700 mb-1">الحمل الآمن (SWL)</label>' => '<label for="swl" class="block font-bold text-slate-700 mb-1">{{ __(\'الحمل الآمن (SWL)\') }}</label>',
        '<h2 class="font-bold text-slate-800 text-xs">مواصفات وبيانات المعدة المفحوصة</h2>' => '<h2 class="font-bold text-slate-800 text-xs">{{ __(\'مواصفات وبيانات المعدة المفحوصة\') }}</h2>',
        'وصف المعدة (Description)' => '{{ __(\'وصف المعدة (Description)\') }}',
        'الشركة المصنعة (Manufacturer)' => '{{ __(\'الشركة المصنعة (Manufacturer)\') }}',
        'الموديل (Model)' => '{{ __(\'الموديل (Model)\') }}',
        'السعة (Capacity)' => '{{ __(\'السعة (Capacity)\') }}',
        'رقم الشاسيه / السيريال' => '{{ __(\'رقم الشاسيه / السيريال\') }}',
        'سنة الصنع (Year)' => '{{ __(\'سنة الصنع (Year)\') }}',
        'قطر الواير الرئيسي' => '{{ __(\'قطر الواير الرئيسي\') }}',
        'سعة الهوك الرئيسي' => '{{ __(\'سعة الهوك الرئيسي\') }}',
        'رقم شهادة تجربة الحمل' => '{{ __(\'رقم شهادة تجربة الحمل\') }}',
        'تاريخ تجربة الحمل' => '{{ __(\'تاريخ تجربة الحمل\') }}',
        'مواصفات ونقطة تجربة الحمل (Test Point Particulars)' => '{{ __(\'مواصفات ونقطة تجربة الحمل (Test Point Particulars)\') }}',
        'وصف المهمات (Description)' => '{{ __(\'وصف المهمات (Description)\') }}',
        'جهة التصنيع (Manufacturer)' => '{{ __(\'جهة التصنيع (Manufacturer)\') }}',
        'معامل الأمان (Safety Factor)' => '{{ __(\'معامل الأمان (Safety Factor)\') }}',
        'الطول (Length)' => '{{ __(\'الطول (Length)\') }}',
        'الكمية (Quantity)' => '{{ __(\'الكمية (Quantity)\') }}',
        'سيريال / كود المهمات (ID Range)' => '{{ __(\'سيريال / كود المهمات (ID Range)\') }}',
        'سنة الصنع (Manufacture Date)' => '{{ __(\'سنة الصنع (Manufacture Date)\') }}',
        'الأبعاد (Dimensions: L, W, H)' => '{{ __(\'الأبعاد (Dimensions: L, W, H)\') }}',
        'ترقيم المعدات (Equipment ID)' => '{{ __(\'ترقيم المعدات (Equipment ID)\') }}',
        'قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)' => '{{ __(\'قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)\') }}',
        'تحديد الكل سليم (Pass All)' => '{{ __(\'تحديد الكل سليم (Pass All)\') }}',
        'أسئلة الفحص النظامية (LOLER 1998) والقرار الفني' => '{{ __(\'أسئلة الفحص النظامية (LOLER 1998) والقرار الفني\') }}',
        'أول فحص بعد التركيب؟' => '{{ __(\'أول فحص بعد التركيب؟\') }}',
        'الفحص خلال 6 أشهر؟' => '{{ __(\'الفحص خلال 6 أشهر؟\') }}',
        'تم التركيب بشكل سليم؟' => '{{ __(\'تم التركيب بشكل سليم؟\') }}',
        'الفحص خلال 12 شهر؟' => '{{ __(\'الفحص خلال 12 شهر؟\') }}',
        'وفق مخطط الفحص؟' => '{{ __(\'وفق مخطط الفحص؟\') }}',
        'بعد ظروف استثنائية؟' => '{{ __(\'بعد ظروف استثنائية؟\') }}',
        'الأجزاء المعيبة إن وجدت' => '{{ __(\'الأجزاء المعيبة إن وجدت\') }}',
        'الإصلاحات المطلوبة' => '{{ __(\'الإصلاحات المطلوبة\') }}',
        'هل العيب يشكل خطورة على العمليات؟' => '{{ __(\'هل العيب يشكل خطورة على العمليات؟\') }}',
        'تفاصيل الفحوصات والاختبارات المنفذة' => '{{ __(\'تفاصيل الفحوصات والاختبارات المنفذة\') }}',
        'هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)' => '{{ __(\'هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)\') }}',
        'يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة' => '{{ __(\'يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة\') }}',
        '<span>YES (آمنة)</span>' => '<span>{{ __(\'YES (آمنة)\') }}</span>',
        '<span>NO (غير آمنة)</span>' => '<span>{{ __(\'NO (غير آمنة)\') }}</span>',
        'مهندس الفحص (Issuer)' => '{{ __(\'مهندس الفحص (Issuer)\') }}',
        'معتمد الشهادة (Authenticator)' => '{{ __(\'معتمد الشهادة (Authenticator)\') }}',
        'حالة الشهادة' => '{{ __(\'حالة الشهادة\') }}',
        'معتمدة ومصدرة (Issued)' => '{{ __(\'معتمدة ومصدرة (Issued)\') }}',
        'مسودة للمراجعة (Draft)' => '{{ __(\'مسودة للمراجعة (Draft)\') }}',
        'تحديث الشهادة' => '{{ __(\'تحديث الشهادة\') }}',
        'إلغاء' => '{{ __(\'إلغاء\') }}',
    ];
    foreach ($editReplacements as $search => $replace) {
        $editContent = str_replace($search, $replace, $editContent);
    }
    file_put_contents($editPath, $editContent);
    echo "certificates/edit.blade.php updated successfully!\n";
}

// 4. Update certificates/expiring.blade.php
$expiringPath = __DIR__.'/../resources/views/certificates/expiring.blade.php';
if (file_exists($expiringPath)) {
    $expContent = file_get_contents($expiringPath);
    $expReplacements = [
        "@section('title', 'الشهادات المنتهية والقريبة من الانتهاء - Expiring Certificates')" => "@section('title', __('الشهادات المنتهية والقريبة من الانتهاء'))",
        'title="متابعة صلاحية وتجديد شهادات الفحص"' => 'title="{{ __(\'متابعة صلاحية وتجديد شهادات الفحص\') }}"',
        'new-text="إصدار شهادة جديدة"' => 'new-text="{{ __(\'إصدار شهادة جديدة\') }}"',
        'search-placeholder="البحث برقم الشهادة، العميل، اسم المعدة، أمر الشغل..."' => 'search-placeholder="{{ __(\'البحث برقم الشهادة، العميل، اسم المعدة، أمر الشغل...\') }}"',
        'نطاق الصلاحية (Expiry Timeframe)' => '{{ __(\'نطاق الصلاحية (Expiry Timeframe)\') }}',
        'منتهية بالفعل' => '{{ __(\'منتهية بالفعل\') }}',
        'خلال 7 أيام' => '{{ __(\'خلال 7 أيام\') }}',
        'خلال 15 يوماً' => '{{ __(\'خلال 15 يوماً\') }}',
        'خلال شهر (30 يوم)' => '{{ __(\'خلال شهر (30 يوم)\') }}',
        'خلال شهرين (60 يوم)' => '{{ __(\'خلال شهرين (60 يوم)\') }}',
        'الكل (منتهية وقريبة)' => '{{ __(\'الكل (منتهية وقريبة)\') }}',
        'إعادة ضبط الفلاتر' => '{{ __(\'إعادة ضبط الفلاتر\') }}',
        '<span>جميع الشهادات</span>' => '<span>{{ __(\'سجل الشهادات\') }}</span>',
        '<span>السارية المعتمدة</span>' => '<span>{{ __(\'الشهادات السارية المعتمدة\') }}</span>',
        '<span>المسودات</span>' => '<span>{{ __(\'المسودات قيد المراجعة\') }}</span>',
        '<span>الشهادات القريبة من الانتهاء</span>' => '<span>{{ __(\'الشهادات القريبة من الانتهاء\') }}</span>',
    ];
    foreach ($expReplacements as $search => $replace) {
        $expContent = str_replace($search, $replace, $expContent);
    }
    file_put_contents($expiringPath, $expContent);
    echo "certificates/expiring.blade.php updated successfully!\n";
}

// 5. Update certificates/verify-lookup.blade.php
$verifyPath = __DIR__.'/../resources/views/certificates/verify-lookup.blade.php';
if (file_exists($verifyPath)) {
    $verContent = file_get_contents($verifyPath);
    $verReplacements = [
        "@section('title', 'أداة التحقق من شهادات الفحص')" => "@section('title', __('أداة التحقق من شهادات الفحص'))",
        '<span>أداة الاستعلام الرقمي الفوري</span>' => '<span>{{ __(\'أداة الاستعلام الرقمي الفوري\') }}</span>',
        '<h1 class="text-3xl font-black text-slate-900">التحقق من صحة شهادات وتقارير الفحص</h1>' => '<h1 class="text-3xl font-black text-slate-900">{{ __(\'التحقق من صحة شهادات وتقارير الفحص\') }}</h1>',
        'أدخل رقم الشهادة المطبوع على تقرير فحص معدات الرفع أو رمز التحقق للتأكد من مطابقتها وسريانها الرسمي.' => '{{ __(\'أدخل رقم الشهادة المطبوع على تقرير فحص معدات الرفع أو رمز التحقق للتأكد من مطابقتها وسريانها الرسمي.\') }}',
        '<label for="number" class="block text-xs font-bold text-slate-700 mb-2">رقم الشهادة أو الرمز المرجعي</label>' => '<label for="number" class="block text-xs font-bold text-slate-700 mb-2">{{ __(\'رقم الشهادة أو الرمز المرجعي\') }}</label>',
        '<span>تحقق الآن</span>' => '<span>{{ __(\'تحقق الآن\') }}</span>',
        '<span>شهادة تجريبية للاختبار:</span>' => '<span>{{ __(\'شهادة تجريبية للاختبار:\') }}</span>',
        '<span>نتيجة الفحص الرسمي:</span>' => '<span>{{ __(\'نتيجة الفحص الرسمي:\') }}</span>',
        '<h3 class="text-lg font-black">شهادة معتمدة ومطابقة للسجلات</h3>' => '<h3 class="text-lg font-black">{{ __(\'شهادة معتمدة ومطابقة للسجلات\') }}</h3>',
        '>'."\n".'                        سارية'."\n".'                    </span>' => '>'."\n".'                        {{ __(\'سارية\') }}'."\n".'                    </span>',
    ];
    foreach ($verReplacements as $search => $replace) {
        $verContent = str_replace($search, $replace, $verContent);
    }
    file_put_contents($verifyPath, $verContent);
    echo "certificates/verify-lookup.blade.php updated successfully!\n";
}

echo "All view files translated successfully!\n";
