<?php
/**
 * Plugin Name: Safero Certificate Verifier (إضافة التحقق من شهادات سافيرو)
 * Plugin URI: https://safero-eg.com
 * Description: إضافة رسمية متكاملة لربط موقع شركة سافيرو (Safero Services & Consultations) مع نظام الـ ERP للتحقق الفوري من شهادات التدريب المعتمدة وشهادات فحص معدات الرفع وشهادات ISO المعتمدة مع دعم كامل للفريم المباشر (Iframe) والتضمين التلقائي عند مسح رمز الـ QR.
 * Version: 2.0.0
 * Author: Safero IT & Antigravity
 * Author URI: https://safero-eg.com
 * Text Domain: safero-verifier
 */
if (! defined('ABSPATH')) {
    exit;
}

class SaferoCertificateVerifier
{
    private $api_base_url;

    public function __construct()
    {
        // ERP Base URL (يمكن تعديلها من إعدادات الإضافة في ووردبريس)
        $saved_url = get_option('safero_erp_url');
        $this->api_base_url = ! empty($saved_url) ? $saved_url : 'http://localhost/saferoo/public';

        // Shortcodes
        add_shortcode('safero_certificate_verifier', [$this, 'render_universal_verifier']);
        add_shortcode('safero_training_certificate_verifier', [$this, 'render_training_verifier']);
        add_shortcode('safero_certificate_frame', [$this, 'render_certificate_frame']);

        // Ajax Handlers
        add_action('wp_ajax_safero_verify_cert', [$this, 'ajax_verify']);
        add_action('wp_ajax_nopriv_safero_verify_cert', [$this, 'ajax_verify']);

        // Admin Settings
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_menu()
    {
        add_options_page(
            'إعدادات شهادات Safero',
            'شهادات Safero ERP',
            'manage_options',
            'safero-erp-settings',
            [$this, 'settings_page_html']
        );
    }

    public function register_settings()
    {
        register_setting('safero_erp_settings_group', 'safero_erp_url');
        register_setting('safero_erp_settings_group', 'safero_show_live_frame');
    }

    public function settings_page_html()
    {
        $erp_url = get_option('safero_erp_url', 'http://localhost:8000');
        $show_frame = get_option('safero_show_live_frame', '1');
        ?>
        <div class="wrap" dir="rtl" style="font-family: Tahoma, 'Cairo', sans-serif;">
            <h1 style="color: #0E4DA4; font-weight: bold;">إعدادات ربط نظام التحقق من شهادات Safero ERP</h1>
            <p>اربط موقع ووردبريس بنظام الـ ERP للتحقق المباشر من شهادات التدريب المعتمدة وشهادات الفحص والمعايرة.</p>

            <form method="post" action="options.php" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #ccd0d4; max-width: 800px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <?php settings_fields('safero_erp_settings_group'); ?>
                
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row" style="font-weight: bold;">رابط نظام الـ ERP (Base URL):</th>
                        <td>
                            <input type="url" name="safero_erp_url" value="<?php echo esc_attr($erp_url); ?>" style="width: 100%; max-width: 450px; direction: ltr; font-weight: bold;" placeholder="http://localhost:8000 أو https://safero.oxtech.uk" required />
                            <p class="description">أدخل الرابط الكامل لنظام الـ ERP بدون شرطة مائلة في النهاية (مثلاً: <code>http://localhost:8000</code> محلياً، أو <code>https://safero.oxtech.uk</code> على السيرفر).</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" style="font-weight: bold;">معاينة الفريم المباشر للشهادة:</th>
                        <td>
                            <label>
                                <input type="checkbox" name="safero_show_live_frame" value="1" <?php checked('1', $show_frame); ?> />
                                عرض فريم الشهادة التفاعلي المباشر (Live Iframe) تلقائياً عند التحقق من شهادة تدريب
                            </label>
                            <p class="description">عند تفعيل هذا الخيار، سيظهر تصميم الشهادة الرسمي الأصلي داخل الفريم مباشرة أسفل بيانات التحقق.</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('حفظ وتطبيق الإعدادات'); ?>
            </form>

            <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 12px; border: 1px solid #ccd0d4; border-right: 5px solid #006837; max-width: 800px;">
                <h3 style="color: #006837; margin-top: 0;">أكواد الـ Shortcodes المتاحة للاستخدام في الصفحات:</h3>
                
                <div style="margin-bottom: 18px;">
                    <p style="font-weight: bold; margin-bottom: 4px;">1. الشورت كود الشامل للتحقق (تدريب + فحص معايرة):</p>
                    <code style="background: #f0fdf4; color: #166534; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">[safero_certificate_verifier]</code>
                    <p style="font-size: 12px; color: #64748b; margin-top: 4px;">يقوم بإنشاء صندوق بحث تفاعلي، ويتعرف تلقائياً على رقم الشهادة سواء كان فحص (SAF/...) أو تدريب (2026...)، كما يقرأ الرابط التلقائي من الـ QR Code عند مسحه.</p>
                </div>

                <div style="margin-bottom: 18px;">
                    <p style="font-weight: bold; margin-bottom: 4px;">2. شورت كود فريم شهادة التدريب المباشر (Embedded Iframe):</p>
                    <code style="background: #eff6ff; color: #1e40af; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">[safero_certificate_frame]</code>
                    <p style="font-size: 12px; color: #64748b; margin-top: 4px;">يقرأ رقم الشهادة من رابط الصفحة مباشرة (مثال: <code>?cert=2026101001</code>) ويعرض فريم الشهادة الرسمية فوراً بدون الحاجة لكتابة الرقم أو الضغط على زر.</p>
                </div>

                <div>
                    <p style="font-weight: bold; margin-bottom: 4px;">3. شورت كود خاص بشهادات التدريب فقط:</p>
                    <code style="background: #fefce8; color: #854d0e; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">[safero_training_certificate_verifier]</code>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Universal Certificate Verifier Shortcode
     */
    public function render_universal_verifier($atts)
    {
        return $this->build_verifier_html('universal', $atts);
    }

    /**
     * Training Certificate Verifier Shortcode
     */
    public function render_training_verifier($atts)
    {
        return $this->build_verifier_html('training', $atts);
    }

    /**
     * Direct Certificate Frame Shortcode
     */
    public function render_certificate_frame($atts)
    {
        $a = shortcode_atts([
            'serial' => '',
            'height' => '680',
        ], $atts);

        // Read query param if not set in shortcode
        $cert_serial = ! empty($a['serial']) ? $a['serial'] : sanitize_text_field($_GET['cert'] ?? $_GET['serial'] ?? $_GET['number'] ?? '');
        $saved_erp = get_option('safero_erp_url');
        $erp_url = rtrim(! empty($saved_erp) ? $saved_erp : 'http://localhost/saferoo/public', '/');

        if (empty($cert_serial)) {
            return $this->build_verifier_html('training', $atts);
        }

        $pdf_url = $erp_url.'/training-certificates/'.urlencode($cert_serial).'/pdf';
        $download_url = $pdf_url.'?download=1';
        $frame_src = $pdf_url.'#toolbar=1&navpanes=0';

        ob_start();
        ?>
        <div class="safero-direct-frame-wrapper" style="max-width: 1050px; margin: 25px auto; text-align: right; font-family: 'Cairo', Tahoma, sans-serif;" dir="rtl">
            <div style="background: #fff; padding: 15px 22px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="text-align: right;">
                    <span style="display: inline-block; background: #E8F7EC; color: #006837; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">✓ شهادة تدريب معتمدة وموثقة</span>
                    <h3 style="margin: 6px 0 0 0; color: #1e293b; font-size: 17px; font-weight: 800;">شهادة التدريب الرسمية رقم #<?php echo esc_html($cert_serial); ?></h3>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="<?php echo esc_url($download_url); ?>" download style="background: #006837; color: #fff; text-decoration: none; padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 10px rgba(0,104,55,0.25);">
                        <span>📥 تحميل النسخة الرسمية PDF</span>
                    </a>
                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" style="background: #f1f5f9; color: #334155; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;">
                        ⤢ فتح بملء الشاشة
                    </a>
                </div>
            </div>
            
            <div style="width: 100%; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #cbd5e1; background: #525659;">
                <iframe src="<?php echo esc_url($frame_src); ?>" width="100%" height="<?php echo esc_attr($a['height']); ?>" frameborder="0" style="width: 100%; min-height: <?php echo esc_attr($a['height']); ?>px; display: block; border: none;" allowfullscreen title="Training Certificate PDF"></iframe>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Core HTML & JS Builder for the Verifier Widget
     */
    private function build_verifier_html($mode = 'universal', $atts = [])
    {
        $ajax_url = admin_url('admin-ajax.php');
        $initial_cert = sanitize_text_field($_GET['cert'] ?? $_GET['serial'] ?? $_GET['number'] ?? $_GET['certificate'] ?? '');
        $auto_run = ! empty($initial_cert);
        $show_frame = get_option('safero_show_live_frame', '1') === '1';

        $title = ($mode === 'training') ? 'التحقق من شهادات التدريب المعتمدة' : 'التحقق من شهادات الفحص والتدريب المعتمدة';
        $placeholder = ($mode === 'training') ? 'مثال: 2026101001 أو 2025112310' : 'أدخل رقم الشهادة (مثال: 2026101001 أو SAF/2026110807)';

        ob_start();
        ?>
        <div id="safero-verifier-wrapper" style="max-width: 820px; margin: 30px auto; font-family: 'Cairo', Tahoma, sans-serif; direction: rtl; text-align: right;">
            <style>
                #safero-verifier-box {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 30px 25px;
                    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
                }
                .safero-input-group {
                    display: flex;
                    gap: 10px;
                    margin-top: 15px;
                }
                .safero-input {
                    flex: 1;
                    padding: 12px 18px;
                    border: 2px solid #cbd5e1;
                    border-radius: 12px;
                    font-size: 15px;
                    font-weight: bold;
                    outline: none;
                    transition: border-color 0.2s;
                    direction: ltr;
                    text-align: center;
                }
                .safero-input:focus {
                    border-color: #006837;
                }
                .safero-btn {
                    background-color: #006837;
                    color: #ffffff;
                    border: none;
                    padding: 12px 28px;
                    border-radius: 12px;
                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background-color 0.2s, transform 0.1s;
                }
                .safero-btn:hover {
                    background-color: #00522c;
                }
                .safero-badge-valid {
                    background-color: #F8FAFC;
                    border: 1px solid #E2E8F0;
                    padding: 20px;
                    border-radius: 16px;
                    margin-top: 25px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                }
                .safero-badge-invalid {
                    background-color: #fef2f2;
                    color: #b91c1c;
                    border: 1px solid #fecaca;
                    padding: 18px;
                    border-radius: 14px;
                    margin-top: 20px;
                    text-align: center;
                }
                .safero-grid-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #f1f5f9;
                    font-size: 13.5px;
                }
                .safero-grid-row:last-child {
                    border-bottom: none;
                }
                .safero-frame-box {
                    margin-top: 25px;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
                    border: 1px solid #cbd5e1;
                }
            </style>

            <div id="safero-verifier-box">
                <div style="text-align: center; margin-bottom: 22px;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 14px; background: #E8F7EC; color: #006837; margin-bottom: 10px;">
                        <svg style="width: 26px; height: 26px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 style="color: #0f172a; font-size: 22px; font-weight: 900; margin: 0 0 6px 0;"><?php echo esc_html($title); ?></h3>
                    <p style="color: #64748b; font-size: 13px; margin: 0;">نظام شركة سافيرو (Safero) المعتمد للتحقق الفوري من صحة ومطابقة الشهادات الرسمية</p>
                </div>

                <form id="safero-verify-form" onsubmit="saferoVerify(event)">
                    <div class="safero-input-group">
                        <input type="text" 
                               id="safero-cert-input" 
                               class="safero-input" 
                               value="<?php echo esc_attr($initial_cert); ?>" 
                               placeholder="<?php echo esc_attr($placeholder); ?>" 
                               required />
                        <button type="submit" id="safero-submit-btn" class="safero-btn">تحقق الآن</button>
                    </div>
                </form>

                <div id="safero-loading" style="display: none; text-align: center; margin-top: 25px; color: #006837; font-size: 13px; font-weight: bold;">
                    <div style="display: inline-block; width: 22px; height: 22px; border: 3px solid #E8F7EC; border-top-color: #006837; border-radius: 50%; animation: saferoSpin 0.8s linear infinite; vertical-align: middle; margin-left: 8px;"></div>
                    جاري فحص السجلات والاتصال بنظام Safero ERP...
                </div>

                <div id="safero-result-container"></div>
            </div>

            <style>
                @keyframes saferoSpin {
                    to { transform: rotate(360deg); }
                }
            </style>

            <script>
                function saferoVerify(e) {
                    if (e && e.preventDefault) e.preventDefault();
                    var certNo = document.getElementById('safero-cert-input').value.trim();
                    if (!certNo) return;

                    var loader = document.getElementById('safero-loading');
                    var container = document.getElementById('safero-result-container');
                    var btn = document.getElementById('safero-submit-btn');

                    loader.style.display = 'block';
                    container.innerHTML = '';
                    btn.disabled = true;

                    var formData = new FormData();
                    formData.append('action', 'safero_verify_cert');
                    formData.append('cert_number', certNo);

                    fetch('<?php echo esc_url($ajax_url); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(res) {
                        loader.style.display = 'none';
                        btn.disabled = false;

                        if (res.success && res.data) {
                            var d = res.data;
                            var isTraining = (d.certificate_type === 'training' || !d.equipment_description);
                            var brandColor = isTraining ? '#006837' : '#0E4DA4';
                            var brandBg = isTraining ? '#E8F7EC' : '#E6F2FF';

                            var html = '<div class="safero-badge-valid" style="border-right: 5px solid ' + brandColor + ';">';
                            
                            // Header Status
                            html += '<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0;">';
                            html += '  <div style="display: flex; align-items: center; gap: 8px;">';
                            html += '    <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: ' + brandBg + '; color: ' + brandColor + '; font-weight: bold; font-size: 16px;">✓</span>';
                            html += '    <span style="font-weight: 800; font-size: 16px; color: ' + brandColor + ';">' + (res.status_label || 'شهادة معتمدة وموثقة') + '</span>';
                            html += '  </div>';
                            html += '  <span style="background: ' + brandBg + '; color: ' + brandColor + '; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: bold;">' + (isTraining ? 'أكاديمية التدريب (Training)' : 'فحص ومعايرة (Inspection)') + '</span>';
                            html += '</div>';

                            // Certificate Data Rows
                            if (isTraining) {
                                html += '<div class="safero-grid-row"><span>رقم مسلسل الشهادة:</span><strong style="direction:ltr; font-family: monospace; font-size: 15px; color:' + brandColor + ';">' + (d.certificate_serial || d.certificate_number) + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>اسم المتدرب (Holder):</span><strong style="font-size: 14px; color: #0f172a;">' + (d.trainee_name || d.customer_name || '—') + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>عنوان البرنامج / الدورة:</span><strong style="color: #0f172a;">' + (d.program_title || '—') + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>مجال التدريب (Scope):</span><span>' + (d.training_scope || '—') + '</span></div>';
                                html += '<div class="safero-grid-row"><span>عدد الساعات:</span><strong style="direction:ltr;">' + (d.hours ? d.hours + ' hrs' : '—') + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>تاريخ الشهادة:</span><strong style="direction:ltr;">' + (d.issue_date || '—') + '</strong></div>';
                                if (d.expiry_date) {
                                    html += '<div class="safero-grid-row"><span>تاريخ الانتهاء:</span><strong style="direction:ltr;">' + d.expiry_date + '</strong></div>';
                                }
                                html += '<div class="safero-grid-row"><span>المحاضر المعتمد:</span><strong>' + (d.instructor_name || 'Dr. Hany Nabil') + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>مدير التدريب:</span><strong>' + (d.training_manager_name || 'Gehan Mahmoud') + '</strong></div>';
                            } else {
                                html += '<div class="safero-grid-row"><span>رقم الشهادة:</span><strong style="direction:ltr; font-family: monospace; font-size: 15px; color:' + brandColor + ';">' + d.certificate_number + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>اسم العميل / الشركة:</span><strong>' + (d.customer_name || '—') + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>المعدة المفحوصة:</span><strong>' + d.equipment_description + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>الحمل الآمن للتشغيل (SWL):</span><strong style="direction:ltr;">' + d.swl + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>تاريخ الفحص:</span><strong style="direction:ltr;">' + d.thorough_examination_date + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>الفحص القادم:</span><strong style="direction:ltr;">' + d.next_examination_due_date + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>المواصفة المرجعية:</span><strong style="direction:ltr;">' + d.reference_standard + '</strong></div>';
                                html += '<div class="safero-grid-row"><span>مهندس الفحص:</span><strong>' + (d.inspector_name || '—') + '</strong></div>';
                            }

                            // Download PDF CTA Button
                            if (d.pdf_url) {
                                html += '<div style="margin-top: 20px; text-align: center;">';
                                html += '  <a href="' + d.pdf_url + '" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: ' + brandColor + '; color: #ffffff; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-size: 13.5px; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">';
                                html += '    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
                                html += '    <span>تحميل النسخة الرسمية المعتمدة (PDF)</span>';
                                html += '  </a>';
                                html += '</div>';
                            }

                            html += '</div>'; // End card

                            // Live Certificate Iframe (for Training Certificates)
                            <?php if ($show_frame) { ?>
                            if (isTraining && (d.pdf_url || d.embed_url)) {
                                var pdfStreamUrl = d.pdf_view_url || d.pdf_url;
                                var pdfDownloadUrl = d.pdf_download_url || (d.pdf_url + '?download=1');

                                html += '<div class="safero-frame-box" style="margin-top: 25px; background: #ffffff; border-radius: 16px; border: 1px solid #cbd5e1; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06);">';
                                html += '  <div style="background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">';
                                html += '    <div style="display: flex; align-items: center; gap: 8px;">';
                                html += '      <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #006837;"></span>';
                                html += '      <strong style="font-size: 13.5px; color: #006837;">معاينة ملف الشهادة المعتمد (PDF Certificate)</strong>';
                                html += '    </div>';
                                html += '    <div style="display: flex; align-items: center; gap: 10px;">';
                                html += '      <a href="' + pdfDownloadUrl + '" download style="background: #006837; color: #ffffff; text-decoration: none; padding: 8px 18px; border-radius: 8px; font-weight: bold; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,104,55,0.25);">';
                                html += '        <span>📥 تحميل الشهادة PDF</span>';
                                html += '      </a>';
                                html += '      <a href="' + pdfStreamUrl + '" target="_blank" style="background: #e2e8f0; color: #334155; text-decoration: none; padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: bold;">';
                                html += '        ⤢ فتح بملء الشاشة';
                                html += '      </a>';
                                html += '    </div>';
                                html += '  </div>';
                                html += '  <iframe src="' + pdfStreamUrl + '#toolbar=1&navpanes=0" width="100%" height="750" frameborder="0" style="width: 100%; min-height: 750px; border: none; display: block;" allowfullscreen title="Training Certificate PDF"></iframe>';
                                html += '</div>';
                            }
                            <?php } ?>

                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<div class="safero-badge-invalid">' +
                                '<div style="font-weight: bold; font-size: 15px; margin-bottom: 6px;">لم يتم العثور على الشهادة</div>' +
                                '<p style="margin: 0; font-size: 12.5px;">' + (res.message || 'يرجى مراجعة الرقم والمحاولة مرة أخرى.') + '</p>' +
                            '</div>';
                        }
                    })
                    .catch(function(err) {
                        loader.style.display = 'none';
                        btn.disabled = false;
                        container.innerHTML = '<div class="safero-badge-invalid">تعذر الاتصال بسيرفر التحقق حالياً. يرجى مراجعة رابط السيرفر والمحاولة لاحقاً.</div>';
                    });
                }

                // Auto-run if certificate query param was supplied (e.g. from QR Code scan)
                document.addEventListener('DOMContentLoaded', function() {
                    <?php if ($auto_run) { ?>
                        saferoVerify();
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

        // Clean & normalize input
        $cert_number = trim($cert_number);

        // 1. Try primary unified verification endpoint via query parameter (?identifier=) to avoid Apache 404 with %2F
        $endpoint = rtrim($this->api_base_url, '/').'/api/v1/verify?identifier='.urlencode($cert_number);

        $response = wp_remote_get($endpoint, [
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        // If unified endpoint 404s, try secondary endpoints
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) === 404) {
            // Try training certificates endpoint directly
            $fallback_endpoint = rtrim($this->api_base_url, '/').'/api/v1/training-certificates/verify?identifier='.urlencode($cert_number);
            $fallback_response = wp_remote_get($fallback_endpoint, [
                'timeout' => 15,
                'headers' => ['Accept' => 'application/json'],
            ]);

            if (! is_wp_error($fallback_response) && wp_remote_retrieve_response_code($fallback_response) === 200) {
                $response = $fallback_response;
            } else {
                // Try inspection certificates endpoint directly
                $fallback_insp = rtrim($this->api_base_url, '/').'/api/v1/certificates/verify?identifier='.urlencode($cert_number);
                $fallback_insp_res = wp_remote_get($fallback_insp, [
                    'timeout' => 15,
                    'headers' => ['Accept' => 'application/json'],
                ]);
                if (! is_wp_error($fallback_insp_res) && wp_remote_retrieve_response_code($fallback_insp_res) === 200) {
                    $response = $fallback_insp_res;
                }
            }
        }

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'خطأ في الاتصال بنظام الـ ERP: '.$response->get_error_message()]);
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (! $data || empty($data['success'])) {
            wp_send_json_error([
                'message' => $data['message'] ?? 'لم يتم العثور على أي شهادة مسجلة بهذا الرقم في سجلات شركة سافيرو (Safero).',
            ]);
        }

        wp_send_json_success($data);
    }
}

new SaferoCertificateVerifier;
