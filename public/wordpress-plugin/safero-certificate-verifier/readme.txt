=== Safero Certificate Verifier ===
Contributors: safero
Tags: certificates, verification, iso, inspection, qr code, erp
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later

إضافة رسمية لشركة سافيرو (Safero Services & Consultations) تتيح للزوار والعملاء التحقق الفوري من صحة شهادات فحص معدات الرفع ومطابقتها للمعايير الدولية و ISO مباشرة عبر موقع الشركة الأساسي.

== Description ==
تربط هذه الإضافة موقع شركة Safero الرئيسي بنظام الـ ERP المصغر عبر REST API آمن وفوري.

المميزات:
* تحقق فوري بدون إعادة تحميل الصفحة (AJAX)
* عرض حالة الشهادة: سارية ومعتمدة (Valid)، منتهية (Expired)، أو غير صالحة.
* عرض بيانات المعدة المفحوصة، الحمل الآمن للتشغيل (SWL)، وتواريخ الفحص.
* زر تحميل مباشر لملف الـ PDF المعتمد والمختوم.
* متوافقة بنسبة 100% مع الهوية البصرية لشركة سافيرو.

== Installation ==
1. قم بضغط المجلد في ملف zip أو تنزيل `safero-certificate-verifier.zip`.
2. من لوحة تحكم ووردبريس: إضافات (Plugins) -> أضف جديد (Add New) -> رفع إضافة (Upload Plugin).
3. فعّل الإضافة.
4. اذهب إلى الإعدادات -> "شهادات Safero ERP" واضبط رابط النظام الفرعي (مثلاً: https://erp.safero-eg.com).
5. أنشئ صفحة جديدة وضع فيها الكود المختصر:
   [safero_certificate_verifier]

