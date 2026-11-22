<?php

$enPath = __DIR__.'/../lang/en.json';
$arPath = __DIR__.'/../lang/ar.json';

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];

$invoiceTerms = [
    'إنشاء فاتورة وحسابات فحص جديدة' => 'Create New Invoice & Inspection Accounts',
    'إضافة شهادات الفحص للعميل، تحديد الأسعار، احتساب الخصم (% أو ج.م)، وتسجيل المبالغ المدفوعة' => 'Add client inspection certificates, set prices, calculate discount (% or EGP), and register paid amounts',
    'شهادات الفحص المتاحة للعميل (اختر لإضافتها فوراً للفاتورة):' => 'Available inspection certificates for client (Click to add directly to invoice):',
    '+ إضافة جميع الشهادات المتاحة' => '+ Add All Available Certificates',
    'تمت الإضافة' => 'Added',
    '+ إضافة' => '+ Add',
    'بنود وشهادات الفحص المفوترة (Invoice Line Items)' => 'Invoiced Inspection Line Items & Certificates',
    '+ إضافة بند خدمة يدوي' => '+ Add Manual Service Item',
    'وصف الخدمة / شهادة الفحص' => 'Service Description / Certificate',
    'سعر الفحص (ج.م)' => 'Inspection Price (EGP)',
    'السعر (ج.م)' => 'Price (EGP)',
    'الإجمالي (ج.م)' => 'Total (EGP)',
    'وصف فحص المعدة والشهادة المعتمدة...' => 'Equipment inspection description & certified report...',
    'حذف البند' => 'Delete Item',
    'تفاصيل السداد وطريقة الدفع' => 'Payment Details & Method',
    'طريقة السداد' => 'Payment Method',
    'نقداً (كاش)' => 'Cash',
    'تحويل بنكي (Bank Transfer)' => 'Bank Transfer',
    'شيك بنكي (Cheque)' => 'Bank Cheque',
    'أخرى' => 'Other',
    'ملاحظات الفاتورة' => 'Invoice Notes',
    'أي شروط أو ملاحظات خاصة بالفاتورة...' => 'Any specific terms or invoice notes...',
    'شروط الدفع والاعتماد' => 'Payment & Accreditation Terms',
    'تعتبر الشهادات سارية فور استيفاء شروط السداد والاعتماد الفني.' => 'Certificates are valid immediately upon fulfillment of payment terms and technical approval.',
    'ملخص الحساب والخصومات' => 'Financial Summary & Discounts',
    'المجموع الفرعي (Subtotal):' => 'Subtotal:',
    'نوع الخصم (Discount):' => 'Discount Type:',
    'نسبة مئوية (%)' => 'Percentage (%)',
    'مبلغ ثابت (ج.م)' => 'Fixed Amount (EGP)',
    'مثال: 10%' => 'e.g. 10%',
    'مثال: 500 ج.م' => 'e.g. 500 EGP',
    'الإجمالي المطلوب (Total Amount):' => 'Total Amount Due:',
    'المبلغ المدفوع كاش / تحويل (دفع كم):' => 'Amount Paid (Cash / Transfer):',
    'المبلغ المتبقي (Balance Due):' => 'Remaining Balance Due:',
    'حالة الفاتورة:' => 'Invoice Status:',
    'حفظ وإصدار الفاتورة' => 'Save & Issue Invoice',
    'تعديل الفاتورة وبيانات السداد' => 'Edit Invoice & Payment Details',
    'بنود الفاتورة والشهادات' => 'Invoice Line Items & Certificates',
    '+ إضافة بند جديد' => '+ Add New Item',
    'مدفوع بالكامل' => 'Paid in Full',
    'مدفوعة بالكامل' => 'Paid in Full',
    'سداد جزئي' => 'Partially Paid',
    'مدفوعة جزئياً' => 'Partially Paid',
    'غير مدفوع (معلق)' => 'Unpaid (Pending)',
    'غير مدفوعة' => 'Unpaid',
    'تحديث وحفظ الفاتورة' => 'Update & Save Invoice',
    'ج.م' => 'EGP',
    'شهادة' => 'Certificate',
];

foreach ($invoiceTerms as $k => $v) {
    $en[$k] = $v;
    $ar[$k] = $k;
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Dictionaries updated with invoice terms! Total keys: '.count($en)."\n";

// Update invoices/create.blade.php
$createPath = __DIR__.'/../resources/views/invoices/create.blade.php';
$c = file_get_contents($createPath);

$replacements = [
    "@section('title', 'إنشاء فاتورة فحص جديدة - New Invoice')" => "@section('title', __('إنشاء فاتورة وحسابات فحص جديدة'))",
    '<h1 class="text-base sm:text-lg font-black text-slate-900">إنشاء فاتورة وحسابات فحص جديدة</h1>' => '<h1 class="text-base sm:text-lg font-black text-slate-900">{{ __(\'إنشاء فاتورة وحسابات فحص جديدة\') }}</h1>',
    '<p class="text-xs text-slate-500 mt-0.5">إضافة شهادات الفحص للعميل، تحديد الأسعار، احتساب الخصم (% أو ج.م)، وتسجيل المبالغ المدفوعة</p>' => '<p class="text-xs text-slate-500 mt-0.5">{{ __(\'إضافة شهادات الفحص للعميل، تحديد الأسعار، احتساب الخصم (% أو ج.م)، وتسجيل المبالغ المدفوعة\') }}</p>',
    '<a href="{{ route(\'invoices.index\') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">'."\n".'                    إلغاء' => '<a href="{{ route(\'invoices.index\') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">'."\n".'                    {{ __(\'إلغاء\') }}',
    'العميل (Customer) <span class="text-red-500">*</span>' => '{{ __(\'العميل\') }} <span class="text-red-500">*</span>',
    '-- اختر العميل --' => '{{ __(\'-- اختر العميل --\') }}',
    'تاريخ الفاتورة (Invoice Date) <span class="text-red-500">*</span>' => '{{ __(\'تاريخ الفاتورة\') }} <span class="text-red-500">*</span>',
    'تاريخ الاستحقاق (Due Date)' => '{{ __(\'تاريخ الاستحقاق\') }}',
    'شهادات الفحص المتاحة للعميل (اختر لإضافتها فوراً للفاتورة):' => '{{ __(\'شهادات الفحص المتاحة للعميل (اختر لإضافتها فوراً للفاتورة):\') }}',
    '+ إضافة جميع الشهادات المتاحة' => '{{ __(\'+ إضافة جميع الشهادات المتاحة\') }}',
    '<span x-text="isCertAdded(cert.id) ? \'تمت الإضافة\' : \'+ إضافة\'"></span>' => '<span x-text="isCertAdded(cert.id) ? \'{{ __(\'تمت الإضافة\') }}\' : \'{{ __(\'+ إضافة\') }}\'"></span>',
    'بنود وشهادات الفحص المفوترة (Invoice Line Items)' => '{{ __(\'بنود وشهادات الفحص المفوترة (Invoice Line Items)\') }}',
    '<span>+ إضافة بند خدمة يدوي</span>' => '<span>{{ __(\'+ إضافة بند خدمة يدوي\') }}</span>',
    '<th class="pb-2">وصف الخدمة / شهادة الفحص</th>' => '<th class="pb-2">{{ __(\'وصف الخدمة / شهادة الفحص\') }}</th>',
    '<th class="pb-2 w-24 text-center">الكمية</th>' => '<th class="pb-2 w-24 text-center">{{ __(\'الكمية\') }}</th>',
    '<th class="pb-2 w-32 text-center">سعر الفحص (ج.م)</th>' => '<th class="pb-2 w-32 text-center">{{ __(\'سعر الفحص (ج.م)\') }}</th>',
    '<th class="pb-2 w-32 text-center">الإجمالي (ج.م)</th>' => '<th class="pb-2 w-32 text-center">{{ __(\'الإجمالي (ج.م)\') }}</th>',
    'placeholder="وصف فحص المعدة والشهادة المعتمدة..."' => 'placeholder="{{ __(\'وصف فحص المعدة والشهادة المعتمدة...\') }}"',
    'title="حذف البند"' => 'title="{{ __(\'حذف البند\') }}"',
    '<h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">تفاصيل السداد وطريقة الدفع</h3>' => '<h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">{{ __(\'تفاصيل السداد وطريقة الدفع\') }}</h3>',
    '<label for="payment_method" class="block text-xs font-bold text-slate-700 mb-1">طريقة السداد</label>' => '<label for="payment_method" class="block text-xs font-bold text-slate-700 mb-1">{{ __(\'طريقة السداد\') }}</label>',
    '<option value="cash">نقداً (كاش)</option>' => '<option value="cash">{{ __(\'نقداً (كاش)\') }}</option>',
    '<option value="bank_transfer">تحويل بنكي (Bank Transfer)</option>' => '<option value="bank_transfer">{{ __(\'تحويل بنكي (Bank Transfer)\') }}</option>',
    '<option value="cheque">شيك بنكي (Cheque)</option>' => '<option value="cheque">{{ __(\'شيك بنكي (Cheque)\') }}</option>',
    '<option value="other">أخرى</option>' => '<option value="other">{{ __(\'أخرى\') }}</option>',
    '<label for="notes" class="block text-xs font-bold text-slate-700 mb-1">ملاحظات الفاتورة</label>' => '<label for="notes" class="block text-xs font-bold text-slate-700 mb-1">{{ __(\'ملاحظات الفاتورة\') }}</label>',
    'placeholder="أي شروط أو ملاحظات خاصة بالفاتورة..."' => 'placeholder="{{ __(\'أي شروط أو ملاحظات خاصة بالفاتورة...\') }}"',
    '<label for="terms" class="block text-xs font-bold text-slate-700 mb-1">شروط الدفع والاعتماد</label>' => '<label for="terms" class="block text-xs font-bold text-slate-700 mb-1">{{ __(\'شروط الدفع والاعتماد\') }}</label>',
    'value="تعتبر الشهادات سارية فور استيفاء شروط السداد والاعتماد الفني."' => 'value="{{ __(\'تعتبر الشهادات سارية فور استيفاء شروط السداد والاعتماد الفني.\') }}"',
    '<h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">ملخص الحساب والخصومات</h3>' => '<h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">{{ __(\'ملخص الحساب والخصومات\') }}</h3>',
    '<span>المجموع الفرعي (Subtotal):</span>' => '<span>{{ __(\'المجموع الفرعي (Subtotal):\') }}</span>',
    '<span class="text-xs font-bold text-slate-700">نوع الخصم (Discount):</span>' => '<span class="text-xs font-bold text-slate-700">{{ __(\'نوع الخصم (Discount):\') }}</span>',
    '<span>نسبة مئوية (%)</span>' => '<span>{{ __(\'نسبة مئوية (%)\') }}</span>',
    '<span>مبلغ ثابت (ج.م)</span>' => '<span>{{ __(\'مبلغ ثابت (ج.م)\') }}</span>',
    '<span>الإجمالي المطلوب (Total Amount):</span>' => '<span>{{ __(\'الإجمالي المطلوب (Total Amount):\') }}</span>',
    'المبلغ المدفوع كاش / تحويل (دفع كم):' => '{{ __(\'المبلغ المدفوع كاش / تحويل (دفع كم):\') }}',
    '<span class="text-xs font-bold text-slate-700 block">المبلغ المتبقي (Balance Due):</span>' => '<span class="text-xs font-bold text-slate-700 block">{{ __(\'المبلغ المتبقي (Balance Due):\') }}</span>',
    '<span class="text-[10px] font-bold text-slate-400 block mb-0.5">حالة الفاتورة:</span>' => '<span class="text-[10px] font-bold text-slate-400 block mb-0.5">{{ __(\'حالة الفاتورة:\') }}</span>',
    '<span>حفظ وإصدار الفاتورة</span>' => '<span>{{ __(\'حفظ وإصدار الفاتورة\') }}</span>',
    'x-text="subtotal.toFixed(2) + \' ج.م\'"' => 'x-text="subtotal.toFixed(2) + \' \' + currencySymbol"',
    'x-text="\'-\' + discountAmount.toFixed(2) + \' ج.م\'"' => 'x-text="\'- \' + discountAmount.toFixed(2) + \' \' + currencySymbol"',
    'x-text="totalAmount.toFixed(2) + \' ج.م\'"' => 'x-text="totalAmount.toFixed(2) + \' \' + currencySymbol"',
    'x-text="remainingBalance.toFixed(2) + \' ج.م\'"' => 'x-text="remainingBalance.toFixed(2) + \' \' + currencySymbol"',
    '<span class="absolute left-3 top-2.5 text-xs font-bold text-emerald-700">ج.م</span>' => '<span class="absolute left-3 top-2.5 text-xs font-bold text-emerald-700">{{ __(\'ج.م\') }}</span>',
];

foreach ($replacements as $s => $r) {
    $c = str_replace($s, $r, $c);
}

// Add currencySymbol and dynamic status label to alpine
$c = str_replace('remainingBalance: 0,', "remainingBalance: 0,\n        currencySymbol: '{{ __('ج.م') }}',", $c);
$c = str_replace("if (this.paymentStatus === 'paid') return 'مدفوع بالكامل';", "if (this.paymentStatus === 'paid') return '{{ __('مدفوع بالكامل') }}';", $c);
$c = str_replace("if (this.paymentStatus === 'partial') return 'سداد جزئي';", "if (this.paymentStatus === 'partial') return '{{ __('سداد جزئي') }}';", $c);
$c = str_replace("return 'غير مدفوع (معلق)';", "return '{{ __('غير مدفوع (معلق)') }}';", $c);

file_put_contents($createPath, $c);
echo "invoices/create.blade.php updated successfully!\n";

// Update invoices/edit.blade.php
$editPath = __DIR__.'/../resources/views/invoices/edit.blade.php';
$e = file_get_contents($editPath);

$editReplacements = [
    "@section('title', 'تعديل الفاتورة: '.\$invoice->invoice_number)" => "@section('title', __('تعديل الفاتورة:') . ' ' . \$invoice->invoice_number)",
    '<h1 class="text-base sm:text-lg font-black text-slate-900">تعديل الفاتورة وبيانات السداد</h1>' => '<h1 class="text-base sm:text-lg font-black text-slate-900">{{ __(\'تعديل الفاتورة وبيانات السداد\') }}</h1>',
    '<h2 class="text-sm font-extrabold text-slate-900">بنود الفاتورة والشهادات</h2>' => '<h2 class="text-sm font-extrabold text-slate-900">{{ __(\'بنود الفاتورة والشهادات\') }}</h2>',
    '+ إضافة بند جديد' => '{{ __(\'+ إضافة بند جديد\') }}',
    '<th class="pb-2">وصف الخدمة / شهادة الفحص</th>' => '<th class="pb-2">{{ __(\'وصف الخدمة / شهادة الفحص\') }}</th>',
    '<th class="pb-2 w-24 text-center">الكمية</th>' => '<th class="pb-2 w-24 text-center">{{ __(\'الكمية\') }}</th>',
    '<th class="pb-2 w-32 text-center">السعر (ج.م)</th>' => '<th class="pb-2 w-32 text-center">{{ __(\'السعر (ج.م)\') }}</th>',
    '<th class="pb-2 w-32 text-center">الإجمالي (ج.م)</th>' => '<th class="pb-2 w-32 text-center">{{ __(\'الإجمالي (ج.م)\') }}</th>',
    '<span>تحديث وحفظ الفاتورة</span>' => '<span>{{ __(\'تحديث وحفظ الفاتورة\') }}</span>',
    '<label for="invoice_date" class="block text-xs font-bold text-slate-700 mb-1.5">'."\n".'                        تاريخ الفاتورة <span class="text-red-500">*</span>' => '<label for="invoice_date" class="block text-xs font-bold text-slate-700 mb-1.5">'."\n".'                        {{ __(\'تاريخ الفاتورة\') }} <span class="text-red-500">*</span>',
    '<label for="due_date" class="block text-xs font-bold text-slate-700 mb-1.5">'."\n".'                        تاريخ الاستحقاق' => '<label for="due_date" class="block text-xs font-bold text-slate-700 mb-1.5">'."\n".'                        {{ __(\'تاريخ الاستحقاق\') }}',
    'العميل (Customer) <span class="text-red-500">*</span>' => '{{ __(\'العميل\') }} <span class="text-red-500">*</span>',
];

foreach ($editReplacements as $s => $r) {
    $e = str_replace($s, $r, $e);
}
file_put_contents($editPath, $e);
echo "invoices/edit.blade.php updated successfully!\n";
