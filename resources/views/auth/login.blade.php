<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In &lsaquo; Safero Services &amp; Consultations &mdash; WordPress / ERP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Self-contained resilient CSS ensuring pixel-perfect layout in all environments -->
    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            color: #2c3338;
            -webkit-font-smoothing: antialiased;
        }
        .login-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        .login-side {
            width: 40%;
            min-width: 360px;
            max-width: 460px;
            background-color: #ffffff;
            padding: 30px 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 10;
        }
        .image-side {
            flex: 1;
            min-height: 100vh;
            position: relative;
            background-color: #1a1a1a;
            overflow: hidden;
        }
        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        .form-box {
            width: 100%;
            max-width: 320px;
            margin: auto;
            padding: 20px 0;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo {
            height: 56px;
            width: auto;
            display: inline-block;
            margin-bottom: 8px;
            object-fit: contain;
        }
        .brand-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 0.18em;
            color: #006837;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.22em;
            color: #0E4DA4;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .field-label {
            display: block;
            font-size: 13px;
            color: #444444;
            margin-bottom: 6px;
            font-weight: 400;
        }
        .field-input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #8c8f94;
            border-radius: 2px;
            font-size: 14px;
            color: #2c3338;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .field-input:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }
        .password-wrap {
            position: relative;
        }
        .password-wrap .field-input {
            padding-right: 36px;
        }
        .toggle-btn {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            color: #2271b1;
            padding: 0;
        }
        .toggle-btn:hover {
            color: #135e96;
        }
        .actions-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
            font-size: 13px;
        }
        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #444444;
            cursor: pointer;
            user-select: none;
        }
        .remember-checkbox {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: #000000;
            cursor: pointer;
        }
        .help-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 14px;
            border: 1px solid #999;
            border-radius: 50%;
            font-size: 9px;
            color: #666;
            font-weight: bold;
            margin-left: 2px;
        }
        .submit-btn {
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .submit-btn:hover {
            background-color: #23282d;
        }
        .error-notice {
            background-color: #fcf0f2;
            border-left: 4px solid #b32d2e;
            padding: 10px 12px;
            margin-bottom: 18px;
            font-size: 12px;
            color: #2c3338;
            line-height: 1.45;
        }
        .error-notice strong {
            color: #b32d2e;
        }
        .footer-links {
            text-align: center;
            margin-top: 26px;
            font-size: 12.5px;
        }
        .footer-links a {
            color: #2271b1;
            text-decoration: none;
            display: inline-block;
            margin: 4px 0;
        }
        .footer-links a:hover {
            color: #135e96;
            text-decoration: underline;
        }
        .demo-bar {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #f0f0f1;
            text-align: center;
        }
        .demo-bar span {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #8c8f94;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .demo-pill {
            display: inline-block;
            padding: 3px 8px;
            margin: 0 2px;
            font-size: 11px;
            background: #ffffff;
            border: 1px solid #dcdcde;
            border-radius: 3px;
            color: #50575e;
            cursor: pointer;
        }
        .demo-pill:hover {
            background: #f6f7f7;
            border-color: #000000;
            color: #000000;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Responsive Breakpoints */
        @media (max-width: 820px) {
            .login-layout {
                flex-direction: column;
            }
            .login-side {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                padding: 30px 20px;
            }
            .image-side {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="login-layout">

        <!-- Left Column: Login Form -->
        <div class="login-side">

            <!-- Top Header Status -->
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #8c8f94;">
                <span style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 8px; height: 8px; background-color: #22c55e; border-radius: 50%; display: inline-block;"></span>
                    Safero ERP Portal
                </span>
                <a href="{{ route('certificates.verify.lookup') }}" style="color: #2271b1; text-decoration: none; font-weight: 600;">
                    Certificate Verification &rarr;
                </a>
            </div>

            <!-- Form Centered Container -->
            <div class="form-box">

                <!-- Logo Section -->
                <div class="brand-header">
                    <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="brand-logo">
                    <div class="brand-title">SAFERO SERVICES</div>
                    <div class="brand-sub">Services &amp; Consultations</div>
                    <span class="sr-only">تسجيل الدخول للنظام</span>
                </div>

                <!-- Error Notice Box (Matching Screenshot Pink Style) -->
                @if($errors->any())
                    <div class="error-notice">
                        <strong>Error:</strong> {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Username / Email -->
                    <div class="form-group">
                        <label for="email" class="field-label">Username or Email Address</label>
                        <input type="text" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', 'admin@safero-eg.com') }}" 
                               required 
                               autocomplete="username"
                               autofocus
                               class="field-input">
                    </div>

                    <!-- Password with Eye Toggle -->
                    <div class="form-group">
                        <label for="password" class="field-label">Password</label>
                        <div class="password-wrap">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   value="password"
                                   required 
                                   autocomplete="current-password"
                                   class="field-input">
                            <button type="button" id="togglePasswordBtn" class="toggle-btn" title="Toggle password visibility">
                                <svg id="eyeIcon" style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Black Log In Button -->
                    <div class="actions-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" id="remember" class="remember-checkbox">
                            <span>Remember Me</span>
                            <span class="help-icon" title="Keep login active">?</span>
                        </label>

                        <button type="submit" class="submit-btn">
                            Log In
                        </button>
                    </div>

                </form>

                <!-- Footer Links -->
                <div class="footer-links">
                    <div>
                        <a href="javascript:void(0)" onclick="alert('Please contact admin@safero-eg.com to reset credentials.');">
                            Lost your password?
                        </a>
                    </div>
                    <div>
                        <a href="https://safero-eg.com" target="_blank">
                            &larr; Go to safero-eg.com
                        </a>
                    </div>
                </div>

                <!-- Quick Demo Accounts -->
                <div class="demo-bar">
                    <span>Quick Demo Accounts</span>
                    <div>
                        <button type="button" class="demo-pill" onclick="fillCreds('admin@safero-eg.com', 'password')">Admin</button>
                        <button type="button" class="demo-pill" onclick="fillCreds('eng.fathy@safero-eg.com', 'password')">Engineer</button>
                        <button type="button" class="demo-pill" onclick="fillCreds('dr.hany@safero-eg.com', 'password')">Director</button>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div style="text-align: center; font-size: 11px; color: #a7aaad;">
                &copy; {{ date('Y') }} Safero Services &amp; Consultations
            </div>

        </div>

        <!-- Right Column: Full Hero Image (Floral aesthetic matching uploaded screenshot) -->
        <div class="image-side">
            <img src="{{ asset('branding/login_bg.jpg') }}" alt="Login Cover Art">
        </div>

    </div>

    <!-- Toggle Password Script -->
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn?.addEventListener('click', function() {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        });

        function fillCreds(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>

</body>
</html>
