<?php
/**
 * Plugin Name: Safero Certificate Verifier (إضافة التحقق من شهادات سافيرو)
 * Plugin URI: https://safero-eg.com
 * Description: إضافة رسمية لربط موقع شركة سافيرو مع نظام الـ ERP للتحقق الفوري من شهادات فحص معدات الرفع وشهادات ISO المعتمدة عبر الـ Shortcode: [safero_certificate_verifier].
 * Version: 1.0.0
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
        // ERP Base URL (يمكن تعديلها من إعدادات الإضافة أو الافتراضي للنطاق الفرعي)
        $this->api_base_url = get_option('safero_erp_url', 'http://localhost:8000');

        add_shortcode('safero_certificate_verifier', [$this, 'render_shortcode']);
        add_action('wp_ajax_safero_verify_cert', [$this, 'ajax_verify']);
        add_action('wp_ajax_nopriv_safero_verify_cert', [$this, 'ajax_verify']);
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
    }

    public function settings_page_html()
    {
        ?>
        <div class="wrap" dir="rtl">
            <h1>إعدادات ربط نظام Safero ERP</h1>
            <form method="post" action="options.php">
                <?php settings_fields('safero_erp_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">رابط نظام الـ ERP (Subdomain URL):</th>
                        <td>
                            <input type="url" name="safero_erp_url" value="<?php echo esc_attr(get_option('safero_erp_url', 'https://erp.safero-eg.com')); ?>" style="width: 400px; direction: ltr;" placeholder="https://erp.safero-eg.com" />
                            <p class="description">أدخل الرابط الكامل لنظام الـ ERP (مثال: https://erp.safero-eg.com أو الرابط المحلي أثناء التجربة).</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('حفظ الإعدادات'); ?>
            </form>
            <div style="margin-top: 20px; padding: 15px; background: #fff; border-right: 4px solid #0E4DA4;">
                <h3>كيفية الاستخدام:</h3>
                <p>قم بإنشاء صفحة جديدة في ووردبريس باسم <strong>التحقق من الشهادات</strong> وضع بداخلها هذا الكود المختصر:</p>
                <code>[safero_certificate_verifier]</code>
            </div>
        </div>
        <?php
    }

    public function render_shortcode($atts)
    {
        $ajax_url = admin_url('admin-ajax.php');
        ob_start();
        ?>
        <div id="safero-verifier-wrapper" style="max-width: 680px; margin: 30px auto; font-family: 'Cairo', Tahoma, sans-serif; direction: rtl; text-align: right;">
            <style>
                #safero-verifier-box {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 30px;
                    box-shadow: 0 10px 25px -5px rgba(14, 77, 164, 0.08);
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
                    border-color: #0E4DA4;
                }
                .safero-btn {
                    background-color: #0E4DA4;
                    color: #ffffff;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 12px;
                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background-color 0.2s;
                }
                .safero-btn:hover {
                    background-color: #0a3a7d;
                }
                .safero-badge-valid {
                    background-color: #E8F7EC;
                    color: #22A447;
                    border: 1px solid rgba(34, 164, 71, 0.3);
                    padding: 15px;
                    border-radius: 14px;
                    margin-top: 20px;
                }
                .safero-badge-invalid {
                    background-color: #fef2f2;
                    color: #b91c1c;
                    border: 1px solid #fecaca;
                    padding: 15px;
                    border-radius: 14px;
                    margin-top: 20px;
                }
                .safero-grid-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #e5e7eb;
                    font-size: 13px;
                }
                .safero-grid-row:last-child {
                    border-bottom: none;
                }
            </style>

            <div id="safero-verifier-box">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3 style="color: #0E4DA4; font-size: 22px; font-weight: 900; margin: 0 0 8px 0;">التحقق من صحة شهادات الفحص والمعايرة</h3>
                    <p style="color: #64748b; font-size: 13px; margin: 0;">أدخل رقم الشهادة للتحقق المباشر من مطابقتها وسريانها الرسمي</p>
                </div>

                <form id="safero-verify-form" onsubmit="saferoVerify(event)">
                    <div class="safero-input-group">
                        <input type="text" id="safero-cert-input" class="safero-input" placeholder="SAF/2026110807" required />
                        <button type="submit" id="safero-submit-btn" class="safero-btn">تحقق الآن</button>
                    </div>
                </form>

                <div id="safero-loading" style="display: none; text-align: center; margin-top: 20px; color: #0E4DA4; font-size: 13px;">
                    جاري الاتصال بنظام Safero ERP والتحقق من السجلات...
                </div>

                <div id="safero-result-container"></div>
            </div>

            <script>
                function saferoVerify(e) {
                    e.preventDefault();
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
                    .then(response => response.json())
                    .then(res => {
                        loader.style.display = 'none';
                        btn.disabled = false;

                        if (res.success && res.data) {
                            var d = res.data;
                            var html = '<div class="safero-badge-valid">' +
                                '<div style="font-weight: bold; font-size: 16px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">' +
                                    '<span style="color: #22A447; font-size: 20px;">✓</span> ' + res.status_label +
                                '</div>' +
                                '<div class="safero-grid-row"><span>رقم الشهادة:</span><strong style="direction:ltr;">' + d.certificate_number + '</strong></div>' +
                                '<div class="safero-grid-row"><span>اسم العميل:</span><strong>' + (d.customer_name || '—') + '</strong></div>' +
                                '<div class="safero-grid-row"><span>المعدة المفحوصة:</span><strong>' + d.equipment_description + '</strong></div>' +
                                '<div class="safero-grid-row"><span>الحمل الآمن (SWL):</span><strong style="direction:ltr;">' + d.swl + '</strong></div>' +
                                '<div class="safero-grid-row"><span>تاريخ الفحص:</span><strong style="direction:ltr;">' + d.thorough_examination_date + '</strong></div>' +
                                '<div class="safero-grid-row"><span>تاريخ الاستحقاق القادم:</span><strong style="direction:ltr;">' + d.next_examination_due_date + '</strong></div>' +
                                '<div class="safero-grid-row"><span>المواصفة المرجعية:</span><strong style="direction:ltr;">' + d.reference_standard + '</strong></div>' +
                                '<div class="safero-grid-row"><span>مهندس الفحص:</span><strong style="direction:ltr;">' + d.inspector_name + '</strong></div>' +
                                '<div style="margin-top: 15px; text-align: center;">' +
                                    '<a href="' + d.pdf_url + '" target="_blank" style="display: inline-block; background: #0E4DA4; color: #fff; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 13px; font-weight: bold;">تحميل النسخة الرسمية المعتمدة (PDF) &larr;</a>' +
                                '</div>' +
                            '</div>';
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<div class="safero-badge-invalid">' +
                                '<div style="font-weight: bold; font-size: 15px; margin-bottom: 6px;">لم يتم العثور على الشهادة</div>' +
                                '<p style="margin: 0; font-size: 12px;">' + (res.message || 'يرجى مراجعة الرقم والمحاولة مرة أخرى.') + '</p>' +
                            '</div>';
                        }
                    })
                    .catch(err => {
                        loader.style.display = 'none';
                        btn.disabled = false;
                        container.innerHTML = '<div class="safero-badge-invalid">تعذر الاتصال بسيرفر التحقق حالياً. يرجى المحاولة لاحقاً.</div>';
                    });
                }
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajax_verify()
    {
        $cert_number = sanitize_text_field($_POST['cert_number'] ?? '');
        if (empty($cert_number)) {
            wp_send_json_error(['message' => 'الرجاء إدخال رقم الشهادة']);
        }

        $endpoint = rtrim($this->api_base_url, '/').'/api/v1/certificates/verify/'.urlencode($cert_number);

        $response = wp_remote_get($endpoint, [
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'خطأ في الاتصال بنظام الـ ERP: '.$response->get_error_message()]);
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (! $data || empty($data['success'])) {
            wp_send_json_error([
                'message' => $data['message'] ?? 'لم يتم العثور على شهادة بهذا الرقم في سجلات شركة سافيرو.',
            ]);
        }

        wp_send_json_success($data);
    }
}

new SaferoCertificateVerifier;
