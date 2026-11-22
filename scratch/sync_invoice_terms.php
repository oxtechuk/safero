<?php

$keys = [
    'فاتورة ضريبية معتمدة: ' => 'Accredited Tax Invoice: ',
    'الرجوع لسجل الفواتير' => 'Back to Invoices Log',
    'معاينة وطباعة الفاتورة الضريبية المعتمدة' => 'Preview & Print Accredited Tax Invoice',
    'تعديل الفاتورة' => 'Edit Invoice',
    'طباعة الفاتورة (A4 Print / PDF)' => 'Print Invoice (A4 Print / PDF)',
    'شركة سافيرو للخدمات والاستشارات الهندسية والتفتيش' => 'Safero Services & Consultations - Inspection Body',
    'هيئة تفتيش هندسي معتمدة طبقاً للمواصفة ISO/IEC 17020:2012' => 'Accredited Engineering Inspection Body ISO/IEC 17020:2012',
    'ب.ض:' => 'Tax ID:',
    'س.ت:' => 'C.R. No:',
    'الترخيص:' => 'Licence:',
    'امسح للتحقق الرقمي' => 'Scan to Verify Digitally',
    'فاتورة ضريبية معتمدة' => 'Accredited Tax Invoice',
    'تاريخ الإصدار / Date:' => 'Issue Date:',
    'الاستحقاق / Due:' => 'Due Date:',
    'عند الاستلام' => 'Upon Receipt',
    'مدفوع بالكامل / PAID IN FULL' => 'PAID IN FULL',
    'سداد جزئي / PARTIALLY PAID' => 'PARTIALLY PAID',
    'مستحقة السداد / PAYMENT DUE' => 'PAYMENT DUE',
    'فاتورة إلى (BILLED TO / CLIENT)' => 'Billed To (Client)',
    'البطاقة الضريبية / Tax ID:' => 'Tax ID:',
    'العنوان / Address:' => 'Address:',
    'الهاتف / Phone:' => 'Phone:',
    'شروط السداد والتحصيل (PAYMENT TERMS)' => 'Payment & Settlement Terms',
    'مطالبة رسمية' => 'Official Claim',
    'طريقة السداد / Method:' => 'Payment Method:',
    'نقداً (Cash)' => 'Cash',
    'تحويل بنكي (Bank Transfer)' => 'Bank Transfer',
    'شيك مصرفي (Cheque)' => 'Bank Cheque',
    'العملة / Currency:' => 'Currency:',
    'مرجع الفحص / Inspection Ref:' => 'Inspection Reference:',
    'محرر الفاتورة / Issued By:' => 'Issued By:',
    'حالة المطالبة المالية:' => 'Financial Claim Status:',
    'بيان خدمة الفحص / الشهادة الهندسية المعتمدة (Inspection & Service Description)' => 'Inspection Service / Accredited Engineering Certificate',
    'الكمية (Qty)' => 'Qty',
    'سعر الوحدة (Unit)' => 'Unit Price',
    'الإجمالي (Total EGP)' => 'Total (EGP)',
    'لا توجد بنود مسجلة في هذه الفاتورة' => 'No items registered in this invoice',
    'المبلغ الإجمالي بالحروف والتفقيط المعتمد:' => 'Total Amount in Words (Official Certification):',
    'بيانات التحويل البنكي والحساب الرسمي (Bank Wire Details):' => 'Official Bank Account & Wire Details:',
    'البنك:' => 'Bank:',
    'كود السويفت:' => 'SWIFT Code:',
    'اسم الحساب:' => 'Account Name:',
    'رقم الآيبان (IBAN):' => 'IBAN Number:',
    'ملاحظات وشروط الفحص والاعتماد:' => 'Inspection & Certification Notes and Terms:',
    'شكراً لتعاملكم مع شركة سافيرو لخدمات الفحص والاعتماد الهندسي والمعايرة.' => 'Thank you for choosing Safero for engineering inspection and calibration services.',
    'تعتبر شهادات وتقارير الفحص سارية ونافذة فور استيفاء شروط السداد والاعتماد الفني، وصلاحية هذه الفاتورة 30 يوماً من تاريخ الإصدار.' => 'Inspection certificates and reports become valid upon full payment and technical endorsement. Validity is 30 days from date of issue.',
    'توقيع المستلم / العميل' => 'Customer / Recipient Signature',
    'الاستلام والموافقة على الفحص' => 'Received & Acknowledged',
    'إدارة الحسابات والاعتماد الفني' => 'Finance & Technical Certification Dept.',
    'معتمد - شركة سافيرو' => 'Approved - Safero Inspection Body',
    'المجموع الفرعي (Subtotal):' => 'Subtotal:',
    'الخصم الممنوح' => 'Discount Granted',
    'مبلغ ثابت' => 'Fixed Amount',
    'ضريبة القيمة المضافة' => 'Value Added Tax (VAT)',
    'صافي إجمالي الفاتورة:' => 'Grand Total (Net):',
    'المبلغ المدفوع (Paid):' => 'Paid Amount:',
    'المتبقي المستحق (Balance Due):' => 'Balance Due:',
    'هاتف / واتساب:' => 'Phone / WhatsApp:',
    'البريد الإلكتروني:' => 'Email:',
];

$enPath = 'lang/en.json';
$arPath = 'lang/ar.json';

$en = json_decode(file_get_contents($enPath), true) ?: [];
$ar = json_decode(file_get_contents($arPath), true) ?: [];

$added = 0;
foreach ($keys as $arKey => $enVal) {
    if (! isset($en[$arKey])) {
        $en[$arKey] = $enVal;
        $added++;
    }
    if (! isset($ar[$arKey])) {
        $ar[$arKey] = $arKey;
    }
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Added {$added} terms. Total in EN: ".count($en).PHP_EOL;
