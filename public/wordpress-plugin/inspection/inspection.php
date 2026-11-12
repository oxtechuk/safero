<?php
/**
 * Plugin Name: Inspaction
 * Plugin URI: https://safero-eg.com
 * Description: إضافة رسمية من تطوير Ox Tech - Ahmed Gaber تتيح التحقق من شهادات الفحص والمعايرة لشركة Safero وعرض الشهادة الأصلية داخل إطار متجاوب (iFrame) عبر الشورت كود [inspection_certificate].
 * Version: 1.0.0
 * Author: Ox Tech - Ahmed Gaber
 * Author URI: https://safero-eg.com
 * License: GPL v2 or later
 * Text Domain: inspaction
 */
if (! defined('ABSPATH')) {
    exit;
}

class OxTechSaferoInspectionPlugin
{
    public function __construct()
    {
        add_shortcode('inspection_certificate', [$this, 'render_shortcode']);
        add_shortcode('inspection', [$this, 'render_shortcode']);
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_admin_menu()
    {
        add_menu_page(
            'Inspaction - شهادات الفحص',
            'Inspaction',
            'manage_options',
            'inspaction-settings',
            [$this, 'render_settings_page'],
            'dashicons-media-document',
            30
        );
    }

    public function register_settings()
    {
        register_setting('inspaction_settings_group', 'inspaction_erp_url');
        register_setting('inspaction_settings_group', 'inspaction_iframe_height');
    }

    public function render_settings_page()
    {
        $erp_url = get_option('inspaction_erp_url', 'http://127.0.0.1:8000');
        $iframe_height = get_option('inspaction_iframe_height', '950');
        ?>
        <div class="wrap" dir="rtl" style="font-family: 'Cairo', Tahoma, sans-serif;">
            <div style="background: #ffffff; border-radius: 16px; padding: 25px 35px; margin-top: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-right: 6px solid #0E4DA4;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
                    <div>
                        <h1 style="color: #0E4DA4; font-size: 24px; font-weight: 900; margin: 0;">Inspaction - إعدادات شهادات الفحص</h1>
                        <p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">تطوير: <strong style="color: #22A447;">Ox Tech - Ahmed Gaber</strong> | نظام ربط شهادات شركة Safero</p>
                    </div>
                    <span style="background: #E8F7EC; color: #22A447; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 12px;">إصدار 1.0.0</span>
                </div>

                <form method="post" action="options.php">
                    <?php settings_fields('inspaction_settings_group'); ?>
                    
                    <table class="form-table" style="font-size: 14px;">
                        <tr valign="top">
                            <th scope="row" style="font-weight: bold; color: #1e293b;">رابط نظام الـ ERP (النطاق الفرعي Subdomain):</th>
                            <td>
                                <input type="url" name="inspaction_erp_url" value="<?php echo esc_attr($erp_url); ?>" style="width: 420px; padding: 8px 12px; border-radius: 8px; direction: ltr;" placeholder="https://erp.safero-eg.com" required />
                                <p class="description" style="color: #64748b; margin-top: 6px;">
                                    الرابط الذي يعمل عليه نظام لارفيل ERP (مثال: <code>https://erp.safero-eg.com</code> أو <code>http://127.0.0.1:8000</code> محلياً).
                                </p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" style="font-weight: bold; color: #1e293b;">ارتفاع إطار العرض (iFrame Height):</th>
                            <td>
                                <input type="number" name="inspaction_iframe_height" value="<?php echo esc_attr($iframe_height); ?>" style="width: 120px; padding: 8px 12px; border-radius: 8px; direction: ltr;" min="600" max="2000" /> بكسل (px)
                                <p class="description" style="color: #64748b; margin-top: 6px;">الارتفاع الافتراضي لفريم عرض الشهادة داخل صفحة موقعك (الافتراضي 950 بكسل).</p>
                            </td>
                        </tr>
                    </table>

                    <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                        <?php submit_button('حفظ الإعدادات', 'primary', 'submit', false, ['style' => 'background: #0E4DA4; border-color: #0a3a7d; padding: 8px 24px; font-size: 14px; border-radius: 8px; font-weight: bold;']); ?>
                    </div>
                </form>

                <div style="margin-top: 35px; background: #F4F6F8; border-radius: 12px; padding: 20px; border: 1px dashed #cbd5e1;">
                    <h3 style="color: #0E4DA4; font-size: 16px; margin: 0 0 10px 0; font-weight: bold;">طريقة الاستخدام في صفحات الموقع:</h3>
                    <p style="color: #334155; font-size: 13px; line-height: 1.8; margin: 0 0 10px 0;">
                        1. أنشئ صفحة جديدة في ووردبريس (مثلاً سمّها: <strong>التحقق من الشهادات</strong> أو <strong>Verify Certificate</strong>).<br>
                        2. ضع بداخلها هذا الشورت كود البسيط:
                    </p>
                    <div style="background: #ffffff; padding: 12px 18px; border-radius: 8px; display: inline-block; border: 1px solid #e2e8f0; font-family: monospace; font-size: 16px; font-weight: bold; color: #0E4DA4;">
                        [inspection_certificate]
                    </div>
                    <p style="color: #64748b; font-size: 12px; margin-top: 10px;">
                        بمجرد أن يقوم أي عميل بمسح كود الـ QR بكاميرا هاتفه أو فتح رابط الشهادة، سيتم توجيهه إلى هذه الصفحة وتظهر له شهادته الأصلية فوراً داخل الفريم!
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_shortcode($atts)
    {
        $erp_url = rtrim(get_option('inspaction_erp_url', 'http://127.0.0.1:8000'), '/');
        $height = intval(get_option('inspaction_iframe_height', 950));

        // التحقق من وجود رقم شهادة أو UUID في الرابط (عند مسح الـ QR كود)
        $cert_param = '';
        if (! empty($_GET['cert'])) {
            $cert_param = sanitize_text_field($_GET['cert']);
        } elseif (! empty($_GET['number'])) {
            $cert_param = sanitize_text_field($_GET['number']);
        } elseif (! empty($_GET['uuid'])) {
            $cert_param = sanitize_text_field($_GET['uuid']);
        }

        $embed_src = '';
        if (! empty($cert_param)) {
            $embed_src = $erp_url.'/embed/certificate/'.rawurlencode($cert_param);
        }

        ob_start();
        ?>
        <div class="inspaction-container" style="max-width: 980px; margin: 20px auto; font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif; direction: rtl; text-align: right;">
            <style>
                .inspaction-search-card {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 25px 30px;
                    box-shadow: 0 10px 25px -5px rgba(14, 77, 164, 0.07);
                    margin-bottom: 25px;
                }
                .inspaction-title {
                    color: #0E4DA4;
                    font-size: 22px;
                    font-weight: 900;
                    margin: 0 0 6px 0;
                    text-align: center;
                }
                .inspaction-subtitle {
                    color: #64748b;
                    font-size: 13px;
                    margin: 0 0 20px 0;
                    text-align: center;
                }
                .inspaction-input-row {
                    display: flex;
                    gap: 10px;
                    max-width: 580px;
                    margin: 0 auto;
                }
                .inspaction-input {
                    flex: 1;
                    padding: 12px 18px;
                    border: 2px solid #cbd5e1;
                    border-radius: 12px;
                    font-size: 15px;
                    font-weight: bold;
                    outline: none;
                    transition: all 0.2s;
                    direction: ltr;
                    text-align: center;
                }
                .inspaction-input:focus {
                    border-color: #0E4DA4;
                    box-shadow: 0 0 0 3px rgba(14, 77, 164, 0.15);
                }
                .inspaction-btn {
                    background-color: #0E4DA4;
                    color: #ffffff;
                    border: none;
                    padding: 12px 28px;
                    border-radius: 12px;
                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background-color 0.2s;
                    white-space: nowrap;
                }
                .inspaction-btn:hover {
                    background-color: #0a3a7d;
                }
                .inspaction-frame-wrapper {
                    background: #ffffff;
                    border: 1px solid #cbd5e1;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                }
                .inspaction-frame-header {
                    background: #f8fafc;
                    border-bottom: 1px solid #e2e8f0;
                    padding: 12px 20px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 12px;
                }
                .inspaction-badge-verified {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #E8F7EC;
                    color: #22A447;
                    font-weight: bold;
                    padding: 4px 12px;
                    border-radius: 20px;
                }
                .inspaction-iframe {
                    width: 100%;
                    height: <?php echo esc_attr($height); ?>px;
                    border: none;
                    display: block;
                }
                @media (max-width: 640px) {
                    .inspaction-input-row {
                        flex-direction: column;
                    }
                    .inspaction-iframe {
                        height: 750px;
                    }
                }
            </style>

            <!-- Search Card -->
            <div class="inspaction-search-card">
                <div class="inspaction-title">التحقق من صحة شهادات الفحص والمعايرة</div>
                <div class="inspaction-subtitle">أدخل رقم الشهادة أو امسح كود الـ QR لعرض التقرير الرسمي المعتمد لمعداتك</div>

                <form id="inspaction-search-form" onsubmit="inspactionSubmit(event)">
                    <div class="inspaction-input-row">
                        <input type="text" 
                               id="inspaction-cert-input" 
                               class="inspaction-input" 
                               placeholder="مثال: SAF/2026110807" 
                               value="<?php echo esc_attr($cert_param); ?>" 
                               required />
                        <button type="submit" class="inspaction-btn">عرض الشهادة</button>
                    </div>
                </form>
            </div>

            <!-- Frame Container -->
            <div id="inspaction-frame-box" class="inspaction-frame-wrapper" style="<?php echo empty($embed_src) ? 'display: none;' : ''; ?>">
                <div class="inspaction-frame-header">
                    <div class="inspaction-badge-verified">
                        <span>●</span>
                        <span>شهادة فحص صادرة ومعتمدة من شركة Safero</span>
                    </div>
                    <div>
                        <button type="button" onclick="inspactionPrintFrame()" style="background: none; border: 1px solid #cbd5e1; border-radius: 8px; padding: 4px 10px; font-size: 11px; cursor: pointer; color: #475569;">
                            طباعة الشهادة
                        </button>
                    </div>
                </div>

                <iframe id="inspaction-iframe-element" 
                        class="inspaction-iframe" 
                        src="<?php echo esc_url($embed_src); ?>" 
                        loading="lazy" 
                        title="Inspection Certificate">
                </iframe>
            </div>

            <div style="text-align: center; margin-top: 15px; font-size: 11px; color: #94a3b8;">
                نظام التحقق الإلكتروني الرسمي &copy; Safero Services &amp; Consultations | Developed by Ox Tech - Ahmed Gaber
            </div>

            <script>
                var erpBaseUrl = '<?php echo esc_js($erp_url); ?>';

                function inspactionSubmit(e) {
                    e.preventDefault();
                    var inputVal = document.getElementById('inspaction-cert-input').value.trim();
                    if (!inputVal) return;

                    var frameBox = document.getElementById('inspaction-frame-box');
                    var iframe = document.getElementById('inspaction-iframe-element');

                    var finalUrl = erpBaseUrl + '/embed/certificate/' + encodeURIComponent(inputVal);
                    iframe.src = finalUrl;
                    frameBox.style.display = 'block';

                    // تحريك الصفحة بسلاسة نحو إطار الشهادة
                    frameBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                function inspactionPrintFrame() {
                    var iframe = document.getElementById('inspaction-iframe-element');
                    if (iframe && iframe.contentWindow) {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    }
                }
            </script>
        </div>
        <?php
        return ob_get_clean();
    }
}

new OxTechSaferoInspectionPlugin;
