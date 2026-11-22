<?php

$enPath = __DIR__.'/../lang/en.json';
$arPath = __DIR__.'/../lang/ar.json';

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];

$terms = [
    // Trainees
    'إضافة متدرب جديد' => 'Add New Trainee',
    'تسجيل بيانات المتدرب لسرعة استدعائه لاحقاً عند إصدار شهادات التدريب' => 'Register trainee data for quick retrieval when issuing training certificates',
    'عودة' => 'Back',
    'عودة للقائمة' => 'Back to List',
    'اسم المتدرب بالكامل (Certificate Holder)' => 'Full Trainee Name (Certificate Holder)',
    'الشركة / جهة العمل (Company)' => 'Company / Employer',
    'المسمى الوظيفي (Job Title)' => 'Job Title',
    'الرقم القومي / جواز السفر (National ID / Passport)' => 'National ID / Passport',
    'رقم الهاتف (Phone)' => 'Phone Number',
    'البريد الإلكتروني (Email)' => 'Email Address',
    'ملاحظات إضافية (Notes)' => 'Additional Notes',
    'حفظ المتدرب' => 'Save Trainee',
    'تعديل بيانات المتدرب' => 'Edit Trainee Details',
    'تحديث بيانات المتدرب' => 'Update Trainee Details',
    'تفاصيل المتدرب' => 'Trainee Details',
    'الشهادات الصادرة للمتدرب' => 'Certificates Issued to Trainee',

    // Training Certificates
    'إصدار شهادة تدريب معتمدة' => 'Issue Accredited Training Certificate',
    'إصدار شهادة تدريب معتمدة جديدة' => 'Issue New Accredited Training Certificate',
    'أدخل بيانات المتدرب والدورة لإصدار الشهادة الرسمية وتوليد رمز QR تلقائياً' => 'Enter trainee and course details to issue the official certificate and generate the QR code automatically',
    'بيانات المتدرب (Certificate Holder)' => 'Trainee Information (Certificate Holder)',
    'إضافة متدرب جديد في السجل' => 'Add new trainee in registry',
    'اختيار من سجل المتدربين المسجلين (اختياري)' => 'Select from registered trainees (optional)',
    '-- متدرب جديد / إدخال يدوي --' => '-- New Trainee / Manual Input --',
    'اسم المتدرب المعتمد في الشهادة (Certificate Holder Name)' => 'Accredited Trainee Name on Certificate',
    'الاسم الذي سيظهر بالخط العريض في منتصف الشهادة' => 'The name that will appear prominently in the center of the certificate',
    'تفاصيل البرنامج التدريبي (Training Details)' => 'Training Program Details',
    'رقم مسلسل الشهادة (Certificate Serial No)' => 'Certificate Serial No',
    'اسم الدورة / البرنامج التدريبي (Course Name)' => 'Course / Training Program Name',
    'تاريخ إتمام التدريب (Completion Date)' => 'Completion Date',
    'تاريخ الصلاحية / الانتهاء (Expiry Date)' => 'Expiry Date',
    'الدرجة / التقييم (Grade)' => 'Grade / Assessment',
    'اسم المدرب / المحاضر (Instructor)' => 'Instructor / Trainer Name',
    'مكان انعقاد التدريب (Location)' => 'Training Location',
    'حفظ وإصدار شهادة التدريب' => 'Save & Issue Training Certificate',
    'تعديل شهادة التدريب' => 'Edit Training Certificate',
    'عرض شهادة التدريب' => 'View Training Certificate',
    'تحميل الشهادة (PDF)' => 'Download Certificate (PDF)',
    'طباعة الشهادة' => 'Print Certificate',
    'التحقق من صحة شهادة التدريب' => 'Verify Training Certificate Authenticity',

    // Users & Roles
    'إضافة مستخدم جديد' => 'Add New User',
    'تعديل بيانات المستخدم' => 'Edit User',
    'الاسم الكامل' => 'Full Name',
    'البريد الإلكتروني' => 'Email Address',
    'كلمة المرور' => 'Password',
    'تأكيد كلمة المرور' => 'Confirm Password',
    'الدور الوظيفي' => 'Role',
    'الحالة' => 'Status',
    'نشط' => 'Active',
    'غير نشط' => 'Inactive',
    'حفظ المستخدم' => 'Save User',
    'تحديث المستخدم' => 'Update User',
    'أدوار ومصفوفة صلاحيات النظام' => 'Roles & System Permissions Matrix',
    'تعديل الدور والصلاحيات' => 'Edit Role & Permissions',
    'اسم الدور' => 'Role Name',
    'الوصف' => 'Description',
    'حفظ التعديلات' => 'Save Changes',

    // Tasks
    'إضافة أمر شغل جديد' => 'Add New Work Order',
    'تعديل أمر الشغل' => 'Edit Work Order',
    'عنوان المهمة / الفحص' => 'Task / Inspection Title',
    'تاريخ البدء' => 'Start Date',
    'تاريخ الانتهاء' => 'End Date',
    'الفني / المهندس المسؤول' => 'Assigned Engineer / Inspector',
    'الأولوية' => 'Priority',
    'عاجل' => 'Urgent',
    'عادي' => 'Normal',
    'منخفض' => 'Low',
    'تفاصيل المهمة والملاحظات' => 'Task Details & Notes',
    'حفظ أمر الشغل' => 'Save Work Order',
    'تحديث أمر الشغل' => 'Update Work Order',
];

foreach ($terms as $k => $v) {
    $en[$k] = $v;
    $ar[$k] = $k;
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Added remaining terms! Total keys: '.count($en)."\n";

// Update trainees/create.blade.php
$traineeCreate = __DIR__.'/../resources/views/trainees/create.blade.php';
if (file_exists($traineeCreate)) {
    $c = file_get_contents($traineeCreate);
    $c = str_replace("@section('title', 'إضافة متدرب جديد')", "@section('title', __('إضافة متدرب جديد'))", $c);
    $c = str_replace('<h1 class="text-2xl font-black text-slate-900">إضافة متدرب جديد</h1>', '<h1 class="text-2xl font-black text-slate-900">{{ __(\'إضافة متدرب جديد\') }}</h1>', $c);
    $c = str_replace('تسجيل بيانات المتدرب لسرعة استدعائه لاحقاً عند إصدار شهادات التدريب', '{{ __(\'تسجيل بيانات المتدرب لسرعة استدعائه لاحقاً عند إصدار شهادات التدريب\') }}', $c);
    $c = str_replace('>عودة<', '>{{ __(\'عودة\') }}<', $c);
    $c = str_replace('اسم المتدرب بالكامل (Certificate Holder) <span class="text-red-500">*</span>', '{{ __(\'اسم المتدرب بالكامل (Certificate Holder)\') }} <span class="text-red-500">*</span>', $c);
    $c = str_replace('الشركة / جهة العمل (Company)', '{{ __(\'الشركة / جهة العمل (Company)\') }}', $c);
    $c = str_replace('المسمى الوظيفي (Job Title)', '{{ __(\'المسمى الوظيفي (Job Title)\') }}', $c);
    $c = str_replace('الرقم القومي / جواز السفر (National ID / Passport)', '{{ __(\'الرقم القومي / جواز السفر (National ID / Passport)\') }}', $c);
    $c = str_replace('رقم الهاتف (Phone)', '{{ __(\'رقم الهاتف (Phone)\') }}', $c);
    $c = str_replace('البريد الإلكتروني (Email)', '{{ __(\'البريد الإلكتروني (Email)\') }}', $c);
    $c = str_replace('ملاحظات إضافية (Notes)', '{{ __(\'ملاحظات إضافية (Notes)\') }}', $c);
    $c = str_replace('>إلغاء<', '>{{ __(\'إلغاء\') }}<', $c);
    $c = str_replace('>حفظ المتدرب<', '>{{ __(\'حفظ المتدرب\') }}<', $c);
    $c = str_replace('يرجى تصحيح الأخطاء التالية:', '{{ __(\'يرجى تصحيح الأخطاء التالية:\') }}', $c);
    file_put_contents($traineeCreate, $c);
    echo "Updated trainees/create.blade.php\n";
}

// Update trainees/edit.blade.php
$traineeEdit = __DIR__.'/../resources/views/trainees/edit.blade.php';
if (file_exists($traineeEdit)) {
    $c = file_get_contents($traineeEdit);
    $c = str_replace("@section('title', 'تعديل بيانات المتدرب')", "@section('title', __('تعديل بيانات المتدرب'))", $c);
    $c = str_replace('تعديل بيانات المتدرب', '{{ __(\'تعديل بيانات المتدرب\') }}', $c);
    $c = str_replace('>عودة<', '>{{ __(\'عودة\') }}<', $c);
    $c = str_replace('اسم المتدرب بالكامل (Certificate Holder)', '{{ __(\'اسم المتدرب بالكامل (Certificate Holder)\') }}', $c);
    $c = str_replace('الشركة / جهة العمل (Company)', '{{ __(\'الشركة / جهة العمل (Company)\') }}', $c);
    $c = str_replace('المسمى الوظيفي (Job Title)', '{{ __(\'المسمى الوظيفي (Job Title)\') }}', $c);
    $c = str_replace('الرقم القومي / جواز السفر (National ID / Passport)', '{{ __(\'الرقم القومي / جواز السفر (National ID / Passport)\') }}', $c);
    $c = str_replace('رقم الهاتف (Phone)', '{{ __(\'رقم الهاتف (Phone)\') }}', $c);
    $c = str_replace('البريد الإلكتروني (Email)', '{{ __(\'البريد الإلكتروني (Email)\') }}', $c);
    $c = str_replace('ملاحظات إضافية (Notes)', '{{ __(\'ملاحظات إضافية (Notes)\') }}', $c);
    $c = str_replace('>إلغاء<', '>{{ __(\'إلغاء\') }}<', $c);
    $c = str_replace('>تحديث بيانات المتدرب<', '>{{ __(\'تحديث بيانات المتدرب\') }}<', $c);
    file_put_contents($traineeEdit, $c);
    echo "Updated trainees/edit.blade.php\n";
}

// Update training-certificates/create.blade.php
$tcCreate = __DIR__.'/../resources/views/training-certificates/create.blade.php';
if (file_exists($tcCreate)) {
    $c = file_get_contents($tcCreate);
    $c = str_replace("@section('title', 'إصدار شهادة تدريب معتمدة')", "@section('title', __('إصدار شهادة تدريب معتمدة'))", $c);
    $c = str_replace('<h1 class="text-2xl font-black text-slate-900">إصدار شهادة تدريب معتمدة جديدة</h1>', '<h1 class="text-2xl font-black text-slate-900">{{ __(\'إصدار شهادة تدريب معتمدة جديدة\') }}</h1>', $c);
    $c = str_replace('أدخل بيانات المتدرب والدورة لإصدار الشهادة الرسمية وتوليد رمز QR تلقائياً', '{{ __(\'أدخل بيانات المتدرب والدورة لإصدار الشهادة الرسمية وتوليد رمز QR تلقائياً\') }}', $c);
    $c = str_replace('>عودة للقائمة<', '>{{ __(\'عودة للقائمة\') }}<', $c);
    $c = str_replace('بيانات المتدرب (Certificate Holder)', '{{ __(\'بيانات المتدرب (Certificate Holder)\') }}', $c);
    $c = str_replace('<span>إضافة متدرب جديد في السجل</span>', '<span>{{ __(\'إضافة متدرب جديد في السجل\') }}</span>', $c);
    $c = str_replace('اختيار من سجل المتدربين المسجلين (اختياري)', '{{ __(\'اختيار من سجل المتدربين المسجلين (اختياري)\') }}', $c);
    $c = str_replace('-- متدرب جديد / إدخال يدوي --', '{{ __(\'-- متدرب جديد / إدخال يدوي --\') }}', $c);
    $c = str_replace('اسم المتدرب المعتمد في الشهادة (Certificate Holder Name) <span class="text-red-500">*</span>', '{{ __(\'اسم المتدرب المعتمد في الشهادة (Certificate Holder Name)\') }} <span class="text-red-500">*</span>', $c);
    $c = str_replace('الاسم الذي سيظهر بالخط العريض في منتصف الشهادة', '{{ __(\'الاسم الذي سيظهر بالخط العريض في منتصف الشهادة\') }}', $c);
    $c = str_replace('تفاصيل البرنامج التدريبي (Training Details)', '{{ __(\'تفاصيل البرنامج التدريبي (Training Details)\') }}', $c);
    $c = str_replace('رقم مسلسل الشهادة (Certificate Serial No) <span class="text-red-500">*</span>', '{{ __(\'رقم مسلسل الشهادة (Certificate Serial No)\') }} <span class="text-red-500">*</span>', $c);
    $c = str_replace('>حفظ وإصدار شهادة التدريب<', '>{{ __(\'حفظ وإصدار شهادة التدريب\') }}<', $c);
    file_put_contents($tcCreate, $c);
    echo "Updated training-certificates/create.blade.php\n";
}

echo "All additional views updated successfully!\n";
