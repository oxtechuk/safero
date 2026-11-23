=== Safero Certificate Verifier (إضافة التحقق من شهادات سافيرو) ===
Contributors: safero, oxtech
Tags: certificate, verification, safero, loler, inspection, training, qr-code, pdf
Requires at least: 5.4
Tested up to: 6.7
Stable tag: 2.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

الإضافة الرسمية الموحدة لشركة سافيرو (Safero Services & Consultations) للتحقق الفوري من شهادات فحص معدات الرفع وشهادات التدريب المعتمدة، مع المعاينة والتحميل المباشر للـ PDF عبر شورت كود واحد فقط.

== Description ==

تم تصميم وتطوير هذه الإضافة لربط موقع ووردبريس مباشرة بنظام Safero ERP:
* شورت كود واحد فقط `[safero_certificate_verifier]` يتيح البحث عن أي شهادة (شهادة فحص أو شهادة تدريب).
* التعرف التلقائي الذكي على نوع الشهادة وسجلاتها من قاعدة البيانات.
* عرض بطاقة التحقق الرسمية المعتمدة (العميل، المتدرب، المعدة، الفحص، المواصفات).
* عرض معاينة تفاعلية حية ومباشرة لملف الـ PDF الرسمي للشهادة داخل الصفحة (iFrame).
* زر فوري ومباشر لتحميل نسخة الـ PDF الرسمية (Download PDF).
* زر فتح بملء الشاشة في نافذة جديدة.
* دعم القراءة التلقائية الفورية عند مسح رمز الـ QR عبر الرابط (مثال: `?cert=SAF-2026112202`).
* لوحة تحكم كاملة في ووردبريس لضبط رابط نظام الـ ERP وارتفاع إطار المعاينة.

== Installation ==

1. قم بتحميل الملف المضغوط `safero-certificate-verifier.zip`.
2. في لوحة تحكم ووردبريس، اذهب إلى: **إضافات > أضف جديد > رفع إضافة (Plugins > Add New > Upload Plugin)**.
3. اختر الملف `safero-certificate-verifier.zip` واضغط **التنصيب الآن (Install Now)** ثم اضغط **تفعيل (Activate)**.
4. اذهب إلى **الإعدادات > شهادات Safero ERP**، وأدخل رابط نظام الـ ERP (مثال: `https://erp.safero-eg.com`).
5. أضف الشورت كود `[safero_certificate_verifier]` في أي صفحة تريدها.

== Shortcodes ==

* `[safero_certificate_verifier]` : الشورت كود الموحد الأساسي.
* بارامترات اختيارية: `[safero_certificate_verifier height="900" placeholder="اكتب رقم الشهادة هنا..."]`

== Changelog ==

= 2.5.0 =
* دمج وتوحيد الإضافات السابقة في إضافة واحدة متكاملة وشورت كود موحد.
* إضافة إمكانية عرض ومعاينة الـ PDF المباشرة وتحميله لشهادات الفحص وشهادات التدريب معاً.
* دعم الـ QR Code Auto-Run.
