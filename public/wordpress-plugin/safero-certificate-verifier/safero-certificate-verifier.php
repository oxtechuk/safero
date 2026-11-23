<?php
/**
 * Plugin Name: Safero Certificate Verifier (التحقق من شهادات الفحص والتدريب - سافيرو)
 * Plugin URI: https://safero-eg.com
 * Description: الإضافة الرسمية الموحدة لشركة سافيرو (Safero Services & Consultations) للتحقق الفوري من شهادات فحص معدات الرفع وشهادات التدريب المعتمدة، مع إمكانية عرض معاينة الـ PDF المباشرة وتحميل النسخة الرسمية عبر شورت كود واحد فقط [safero_certificate_verifier].
 * Version: 2.5.0
 * Author: Safero IT & Ox Tech
 * Author URI: https://safero-eg.com
 * License: GPL v2 or later
 * Text Domain: safero-verifier
 */
if (! defined('ABSPATH')) {
    exit;
}

class SaferoUnifiedCertificateVerifier
{
    private $api_base_url;

    public function __construct()
    {
        $saved_url = get_option('safero_erp_url');
        $this->api_base_url = ! empty($saved_url) ? rtrim($saved_url, '/') : 'http://localhost/saferoo/public';

        // 1. Single Universal Shortcode + Aliases for compatibility
        add_shortcode('safero_certificate_verifier', [$this, 'render_verifier_shortcode']);
        add_shortcode('safero_verify', [$this, 'render_verifier_shortcode']);
        add_shortcode('inspection_certificate', [$this, 'render_verifier_shortcode']);
        add_shortcode('inspection', [$this, 'render_verifier_shortcode']);
        add_shortcode('safero_training_certificate_verifier', [$this, 'render_verifier_shortcode']);
        add_shortcode('safero_certificate_frame', [$this, 'render_verifier_shortcode']);

        // 2. Ajax Handlers
        add_action('wp_ajax_safero_verify_cert', [$this, 'ajax_verify']);
        add_action('wp_ajax_nopriv_safero_verify_cert', [$this, 'ajax_verify']);

        // 3. Admin Menu & Settings
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_admin_menu()
    {
        add_options_page(
            'إعدادات شهادات Safero',
            'شهادات Safero ERP',
            'manage_options',
            'safero-erp-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings()
    {
        register_setting('safero_erp_settings_group', 'safero_erp_url');
        register_setting('safero_erp_settings_group', 'safero_pdf_height');
    }

    public function render_settings_page()
    {
        $erp_url = get_option('safero_erp_url', 'http://localhost/saferoo/public');
        $pdf_height = get_option('safero_pdf_height', '850');
        ?>
        <div class="wrap" dir="rtl" style="font-family: 'Cairo', Tahoma, sans-serif; max-width: 900px; margin-top: 20px;">
            <div style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-right: 6px solid #006837;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 18px; margin-bottom: 25px;">
                    <div>
                        <h1 style="color: #006837; font-size: 24px; font-weight: 900; margin: 0;">نظام التحقق الموحد من شهادات Safero ERP</h1>
                        <p style="color: #64748b; font-size: 13.5px; margin: 6px 0 0 0;">إضافة واحدة متكاملة للبحث عن شهادات فحص معدات الرفع وشهادات التدريب مع المعاينة والتحميل المباشر للـ PDF.</p>
                    </div>
                    <span style="background: #E8F7EC; color: #006837; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 12px;">الإصدار 2.5.0</span>
                </div>

                <form method="post" action="options.php">
                    <?php settings_fields('safero_erp_settings_group'); ?>
                    
                    <table class="form-table" style="font-size: 14px;">
                        <tr valign="top">
                            <th scope="row" style="font-weight: bold; color: #1e293b; width: 220px;">رابط نظام الـ ERP (Base URL):</th>
                            <td>
                                <input type="url" name="safero_erp_url" value="<?php echo esc_attr($erp_url); ?>" style="width: 100%; max-width: 500px; padding: 9px 14px; border-radius: 8px; direction: ltr; font-weight: bold;" placeholder="http://127.0.0.1:8000 أو https://safero.oxtech.uk" required />
                                <p class="description" style="color: #64748b; margin-top: 6px;">
                                    الرابط الذي يعمل عليه نظام لارفيل ERP بدون شرطة مائلة في النهاية (مثلاً: <code>http://127.0.0.1:8000</code> محلياً، أو <code>https://safero.oxtech.uk</code> على السيرفر).
                                </p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" style="font-weight: bold; color: #1e293b;">ارتفاع فريم معاينة الـ PDF:</th>
                            <td>
                                <input type="number" name="safero_pdf_height" value="<?php echo esc_attr($pdf_height); ?>" style="width: 130px; padding: 8px 12px; border-radius: 8px; direction: ltr;" min="500" max="1800" /> بكسل (px)
                                <p class="description" style="color: #64748b; margin-top: 6px;">الارتفاع المناسب لإطار معاينة الشهادة داخل صفحات موقعك (الافتراضي 850px).</p>
                            </td>
                        </tr>
                    </table>

                    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                        <?php submit_button('حفظ الإعدادات', 'primary', 'submit', false, ['style' => 'background: #006837; border-color: #00522c; padding: 10px 28px; font-size: 14px; border-radius: 8px; font-weight: bold;']); ?>
                    </div>
                </form>

                <div style="margin-top: 35px; background: #f8fafc; border-radius: 12px; padding: 22px; border: 1px solid #e2e8f0;">
                    <h3 style="color: #0f172a; margin-top: 0; font-size: 16px; font-weight: 800;">طريقة الاستخدام في موقعك (Shortcode):</h3>
                    <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">انسخ الشورت كود التالي وضعه في أي صفحة في ووردبريس أو داخل قالب Elementor:</p>
                    
                    <div style="background: #ffffff; border: 2px dashed #006837; border-radius: 10px; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between;">
                        <code style="font-size: 16px; font-weight: bold; color: #006837; direction: ltr;">[safero_certificate_verifier]</code>
                        <span style="font-size: 12px; color: #64748b;">(شورت كود موحد للبحث والمعاينة والتحميل)</span>
                    </div>

                    <div style="margin-top: 15px; font-size: 12.5px; color: #64748b; line-height: 1.6;">
                        <strong>مميزات الشورت كود:</strong>
                        <ul style="margin: 6px 20px 0 0; list-style-type: disc;">
                            <li>يبحث تلقائياً في شهادات فحص معدات الرفع والأوناش (SAF/...) وشهادات التدريب المعتمدة (2026...).</li>
                            <li>يعرض بطاقة التحقق الرسمية مع تفاصيل الشهادة الموثقة.</li>
                            <li>يعرض معاينة حية ومباشرة لملف الـ PDF للشهادة داخل الصفحة.</li>
                            <li>يوفر زراً فورياً ومباشراً لتحميل نسخة الـ PDF الرسمية وزراً لفتحها بملء الشاشة.</li>
                            <li>يدعم القراءة التلقائية لرقم الشهادة عند مسح رمز الـ QR عبر الرابط (مثال: <code>?cert=SAF-2026112202</code>).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Unified Shortcode: [safero_certificate_verifier]
     */
    public function render_verifier_shortcode($atts)
    {
        $a = shortcode_atts([
            'height' => get_option('safero_pdf_height', '850'),
            'placeholder' => 'أدخل رقم الشهادة (مثال: SAF/2026112202 أو 2026101001)',
        ], $atts);

        $ajax_url = admin_url('admin-ajax.php');
        $initial_cert = sanitize_text_field($_GET['cert'] ?? $_GET['serial'] ?? $_GET['number'] ?? $_GET['identifier'] ?? $_GET['certificate'] ?? '');
        $auto_run = ! empty($initial_cert);
        $frame_height = esc_attr($a['height']);

        ob_start();
        ?>
        <div id="safero-unified-verifier" style="max-width: 960px; margin: 30px auto; font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif; direction: rtl; text-align: right; box-sizing: border-box;">
            <style>
                #safero-search-card {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 30px 25px;
                    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
                }
                .safero-form-group {
                    display: flex;
                    gap: 10px;
                    margin-top: 18px;
                    flex-wrap: wrap;
                }
                .safero-search-input {
                    flex: 1;
                    min-width: 240px;
                    padding: 13px 20px;
                    border: 2px solid #cbd5e1;
                    border-radius: 12px;
                    font-size: 15px;
                    font-weight: bold;
                    outline: none;
                    transition: all 0.2s;
                    direction: ltr;
                    text-align: center;
                    background: #f8fafc;
                }
                .safero-search-input:focus {
                    border-color: #006837;
                    background: #ffffff;
                    box-shadow: 0 0 0 4px rgba(0, 104, 55, 0.1);
                }
                .safero-search-btn {
                    background-color: #006837;
                    color: #ffffff;
                    border: none;
                    padding: 13px 30px;
                    border-radius: 12px;
                    font-size: 15px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: all 0.2s;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    box-shadow: 0 4px 12px rgba(0, 104, 55, 0.2);
                }
                .safero-search-btn:hover {
                    background-color: #00522c;
                    transform: translateY(-1px);
                    box-shadow: 0 6px 16px rgba(0, 104, 55, 0.3);
                }
                .safero-status-box {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    padding: 22px;
                    margin-top: 25px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                }
                .safero-meta-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    gap: 12px 20px;
                    margin-top: 15px;
                    padding-top: 15px;
                    border-top: 1px solid #f1f5f9;
                }
                .safero-meta-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-size: 13.5px;
                    padding: 6px 0;
                    border-bottom: 1px dashed #f1f5f9;
                }
                .safero-meta-item span {
                    color: #64748b;
                }
                .safero-meta-item strong {
                    color: #0f172a;
                }
                .safero-pdf-preview-box {
                    margin-top: 25px;
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid #cbd5e1;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                }
                .safero-pdf-actions-bar {
                    background: #f8fafc;
                    padding: 14px 20px;
                    border-bottom: 1px solid #e2e8f0;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 12px;
                }
                .safero-download-btn {
                    background: #006837;
                    color: #ffffff !important;
                    text-decoration: none !important;
                    padding: 9px 20px;
                    border-radius: 10px;
                    font-weight: bold;
                    font-size: 13.5px;
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    box-shadow: 0 3px 10px rgba(0,104,55,0.25);
                    transition: all 0.2s;
                }
                .safero-download-btn:hover {
                    background: #00522c;
                    color: #ffffff !important;
                    transform: translateY(-1px);
                }
                .safero-expand-btn {
                    background: #e2e8f0;
                    color: #1e293b !important;
                    text-decoration: none !important;
                    padding: 9px 16px;
                    border-radius: 10px;
                    font-size: 13px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    transition: all 0.2s;
                }
                .safero-expand-btn:hover {
                    background: #cbd5e1;
                }
                .safero-alert-danger {
                    background: #fef2f2;
                    color: #991b1b;
                    border: 1px solid #fecaca;
                    border-radius: 14px;
                    padding: 20px;
                    margin-top: 25px;
                    text-align: center;
                }
                @keyframes saferoSpinner {
                    to { transform: rotate(360deg); }
                }
            </style>

            <div id="safero-search-card">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 15px; background: #E8F7EC; color: #006837; margin-bottom: 12px;">
                        <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h2 style="color: #0f172a; font-size: 22px; font-weight: 900; margin: 0 0 6px 0;">التحقق من صحة ومطابقة الشهادات</h2>
                    <p style="color: #64748b; font-size: 13.5px; margin: 0;">النظام الرسمي المعتمد لشركة Safero للتحقق من شهادات الفحص والمعايرة وشهادات التدريب المعتمدة</p>
                </div>

                <form id="safero-verify-form" onsubmit="saferoExecuteVerification(event)">
                    <div class="safero-form-group">
                        <input type="text" 
                               id="safero-cert-input" 
                               class="safero-search-input" 
                               value="<?php echo esc_attr($initial_cert); ?>" 
                               placeholder="<?php echo esc_attr($a['placeholder']); ?>" 
                               required />
                        <button type="submit" id="safero-verify-button" class="safero-search-btn">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>تحقق وعرض الشهادة</span>
                        </button>
                    </div>
                </form>

                <div id="safero-loading-indicator" style="display: none; text-align: center; margin-top: 25px; color: #006837; font-size: 14px; font-weight: bold;">
                    <div style="display: inline-block; width: 22px; height: 22px; border: 3px solid #E8F7EC; border-top-color: #006837; border-radius: 50%; animation: saferoSpinner 0.8s linear infinite; vertical-align: middle; margin-left: 8px;"></div>
                    جاري التحقق من السجلات الرسمية والاتصال بالنظام...
                </div>

                <div id="safero-results-wrapper"></div>
            </div>

            <script>
                function saferoExecuteVerification(e) {
                    if (e && e.preventDefault) e.preventDefault();
                    var certInput = document.getElementById('safero-cert-input');
                    var certNo = certInput ? certInput.value.trim() : '';
                    if (!certNo) return;

                    var loader = document.getElementById('safero-loading-indicator');
                    var results = document.getElementById('safero-results-wrapper');
                    var btn = document.getElementById('safero-verify-button');

                    loader.style.display = 'block';
                    results.innerHTML = '';
                    btn.disabled = true;

                    var fd = new FormData();
                    fd.append('action', 'safero_verify_cert');
                    fd.append('cert_number', certNo);

                    fetch('<?php echo esc_url($ajax_url); ?>', {
                        method: 'POST',
                        body: fd
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(res) {
                        loader.style.display = 'none';
                        btn.disabled = false;

                        if (res.success && res.data) {
                            var d = res.data.data || res.data;
                            var certType = d.certificate_type || res.data.certificate_type || 'inspection';
                            var isTraining = (certType === 'training');
                            var brandColor = isTraining ? '#006837' : '#0E4DA4';
                            var brandBg = isTraining ? '#E8F7EC' : '#E8F1FC';

                            var statusLabel = res.data.status_label || (res.data.valid ? 'شهادة معتمدة ومطابقة للمعايير' : 'حالة الشهادة مسجلة بالنظام');
                            var statusBadgeBg = res.data.valid ? brandBg : '#FEE2E2';
                            var statusBadgeColor = res.data.valid ? brandColor : '#B91C1C';

                            var html = '';

                            // 1. Verification Meta Card
                            html += '<div class="safero-status-box" style="border-right: 5px solid ' + brandColor + ';">';
                            html += '  <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">';
                            html += '    <div style="display: flex; align-items: center; gap: 10px;">';
                            html += '      <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: ' + statusBadgeBg + '; color: ' + statusBadgeColor + '; font-weight: 900; font-size: 16px;">✓</span>';
                            html += '      <div>';
                            html += '        <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: ' + statusBadgeColor + ';">' + statusLabel + '</h4>';
                            html += '        <span style="font-size: 12px; color: #64748b;">رقم الشهادة: <strong style="direction: ltr; font-family: monospace; color:' + brandColor + ';">' + (d.certificate_serial || d.certificate_number || certNo) + '</strong></span>';
                            html += '      </div>';
                            html += '    </div>';
                            html += '    <span style="background: ' + brandBg + '; color: ' + brandColor + '; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">' + (isTraining ? 'شهادة تدريب معتمدة' : 'شهادة فحص ومعايرة') + '</span>';
                            html += '  </div>';

                            // Grid Rows
                            html += '  <div class="safero-meta-grid">';
                            if (isTraining) {
                                html += '    <div class="safero-meta-item"><span>اسم المتدرب:</span><strong>' + (d.trainee_name || d.customer_name || '—') + '</strong></div>';
                                html += '    <div class="safero-meta-item"><span>الدورة التدريبية:</span><strong>' + (d.program_title || '—') + '</strong></div>';
                                if (d.training_scope) html += '    <div class="safero-meta-item"><span>المجال:</span><span>' + d.training_scope + '</span></div>';
                                if (d.hours) html += '    <div class="safero-meta-item"><span>عدد الساعات:</span><strong style="direction:ltr;">' + d.hours + ' hrs</strong></div>';
                                html += '    <div class="safero-meta-item"><span>تاريخ الإصدار:</span><strong style="direction:ltr;">' + (d.issue_date || '—') + '</strong></div>';
                                if (d.expiry_date) html += '    <div class="safero-meta-item"><span>تاريخ الانتهاء:</span><strong style="direction:ltr;">' + d.expiry_date + '</strong></div>';
                                if (d.instructor_name) html += '    <div class="safero-meta-item"><span>المدرب المعتمد:</span><strong>' + d.instructor_name + '</strong></div>';
                            } else {
                                html += '    <div class="safero-meta-item"><span>اسم العميل / الشركة:</span><strong>' + (d.customer_name || '—') + '</strong></div>';
                                html += '    <div class="safero-meta-item"><span>المعدة المفحوصة:</span><strong>' + (d.equipment_description || '—') + '</strong></div>';
                                if (d.swl) html += '    <div class="safero-meta-item"><span>الحمل الآمن (SWL):</span><strong style="direction:ltr;">' + d.swl + '</strong></div>';
                                if (d.thorough_examination_date) html += '    <div class="safero-meta-item"><span>تاريخ الفحص:</span><strong style="direction:ltr;">' + d.thorough_examination_date + '</strong></div>';
                                if (d.next_examination_due_date) html += '    <div class="safero-meta-item"><span>تاريخ الفحص القادم:</span><strong style="direction:ltr;">' + d.next_examination_due_date + '</strong></div>';
                                if (d.reference_standard) html += '    <div class="safero-meta-item"><span>المواصفة المرجعية:</span><strong style="direction:ltr;">' + d.reference_standard + '</strong></div>';
                                if (d.inspector_name) html += '    <div class="safero-meta-item"><span>مهندس الفحص:</span><strong>' + d.inspector_name + '</strong></div>';
                            }
                            html += '  </div>';
                            html += '</div>';

                            // 2. Direct PDF Preview & Download Block
                            var pdfViewUrl = d.pdf_view_url || d.pdf_url || '';
                            var pdfDownloadUrl = d.pdf_download_url || (pdfViewUrl ? (pdfViewUrl + (pdfViewUrl.indexOf('?') > -1 ? '&download=1' : '?download=1')) : '');

                            if (pdfViewUrl) {
                                html += '<div class="safero-pdf-preview-box">';
                                html += '  <div class="safero-pdf-actions-bar">';
                                html += '    <div style="display: flex; align-items: center; gap: 8px;">';
                                html += '      <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: ' + brandColor + ';"></span>';
                                html += '      <strong style="font-size: 14px; color: ' + brandColor + ';">معاينة وثيقة الشهادة الرسمية (Official PDF Document)</strong>';
                                html += '    </div>';
                                html += '    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">';
                                if (pdfDownloadUrl) {
                                    html += '      <a href="' + pdfDownloadUrl + '" download class="safero-download-btn" style="background: ' + brandColor + ';">';
                                    html += '        <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
                                    html += '        <span>تحميل الشهادة (PDF)</span>';
                                    html += '      </a>';
                                }
                                html += '      <a href="' + pdfViewUrl + '" target="_blank" class="safero-expand-btn">';
                                html += '        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
                                html += '        <span>فتح بملء الشاشة</span>';
                                html += '      </a>';
                                html += '    </div>';
                                html += '  </div>';
                                html += '  <iframe src="' + pdfViewUrl + '#toolbar=1&navpanes=0" width="100%" height="<?php echo $frame_height; ?>" frameborder="0" style="width: 100%; min-height: <?php echo $frame_height; ?>px; display: block; border: none; background: #525659;" allowfullscreen title="Certificate PDF"></iframe>';
                                html += '</div>';
                            }

                            results.innerHTML = html;
                        } else {
                            results.innerHTML = '<div class="safero-alert-danger">' +
                                '<div style="font-weight: 800; font-size: 16px; margin-bottom: 6px;">لم يتم العثور على الشهادة</div>' +
                                '<p style="margin: 0; font-size: 13px;">' + (res.data ? res.data.message : (res.message || 'يرجى مراجعة رقم الشهادة والتأكد من إدخاله بشكل سليم.')) + '</p>' +
                                '</div>';
                        }
                    })
                    .catch(function(err) {
                        loader.style.display = 'none';
                        btn.disabled = false;
                        results.innerHTML = '<div class="safero-alert-danger">' +
                            '<div style="font-weight: 800; font-size: 15px; margin-bottom: 4px;">تعذر الاتصال بسيرفر التحقق</div>' +
                            '<p style="margin: 0; font-size: 12.5px;">يرجى التحقق من اتصال الإنترنت ورابط نظام الـ ERP في إعدادات الإضافة.</p>' +
                            '</div>';
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    <?php if ($auto_run) { ?>
                        saferoExecuteVerification();
                    <?php } ?>
                });
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Ajax Verification Endpoint
     */
    public function ajax_verify()
    {
        $cert_number = sanitize_text_field($_POST['cert_number'] ?? '');
        if (empty($cert_number)) {
            wp_send_json_error(['message' => 'الرجاء إدخال رقم الشهادة بدقة.']);
        }

        $cert_number = trim($cert_number);
        $base_url = rtrim($this->api_base_url, '/');

        // 1. Call unified verification endpoint
        $endpoint = $base_url.'/api/v1/verify?identifier='.urlencode($cert_number);

        $response = wp_remote_get($endpoint, [
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        // Fallbacks if unified route 404s
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) === 404) {
            $fallback_training = $base_url.'/api/v1/training-certificates/verify?identifier='.urlencode($cert_number);
            $res_training = wp_remote_get($fallback_training, [
                'timeout' => 15,
                'headers' => ['Accept' => 'application/json'],
            ]);

            if (! is_wp_error($res_training) && wp_remote_retrieve_response_code($res_training) === 200) {
                $response = $res_training;
            } else {
                $fallback_insp = $base_url.'/api/v1/certificates/verify?identifier='.urlencode($cert_number);
                $res_insp = wp_remote_get($fallback_insp, [
                    'timeout' => 15,
                    'headers' => ['Accept' => 'application/json'],
                ]);
                if (! is_wp_error($res_insp) && wp_remote_retrieve_response_code($res_insp) === 200) {
                    $response = $res_insp;
                }
            }
        }

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'خطأ في الاتصال بنظام الـ ERP: '.$response->get_error_message()]);
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($code !== 200 || ! $data || empty($data['success'])) {
            wp_send_json_error([
                'message' => $data['message'] ?? 'لم يتم العثور على أي شهادة مسجلة بهذا الرقم في سجلات شركة سافيرو (Safero).',
            ]);
        }

        // Fix absolute PDF URLs to point to configured base_url if needed
        if (! empty($data['data']['pdf_url']) && str_starts_with($data['data']['pdf_url'], '/')) {
            $data['data']['pdf_url'] = $base_url.$data['data']['pdf_url'];
        }
        if (! empty($data['data']['pdf_download_url']) && str_starts_with($data['data']['pdf_download_url'], '/')) {
            $data['data']['pdf_download_url'] = $base_url.$data['data']['pdf_download_url'];
        }
        if (! empty($data['data']['pdf_view_url']) && str_starts_with($data['data']['pdf_view_url'], '/')) {
            $data['data']['pdf_view_url'] = $base_url.$data['data']['pdf_view_url'];
        }

        wp_send_json_success($data);
    }
}

new SaferoUnifiedCertificateVerifier;
