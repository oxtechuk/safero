<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate {{ $certificate->certificate_serial }} - Safero</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 28px 45px 25px 45px;
            background-color: #ffffff;
            position: relative;
            box-sizing: border-box;
        }

        /* Top Right Corner Wave */
        .corner-top-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 260px;
            height: 160px;
            z-index: 1;
        }

        /* Bottom Left Corner Wave */
        .corner-bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 280px;
            height: 180px;
            z-index: 1;
        }

        /* Bottom Right Corner Wave */
        .corner-bottom-right {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 250px;
            height: 120px;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 10;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-img {
            height: 58px;
            vertical-align: middle;
        }
        .brand-text {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 900;
            color: #006837;
            letter-spacing: 1px;
            margin: 0;
            line-height: 1;
        }
        .brand-sub {
            font-size: 11px;
            font-weight: bold;
            color: #0E4DA4;
            margin-top: 2px;
        }

        /* Main Heading */
        .main-title {
            text-align: center;
            font-size: 42px;
            font-weight: 900;
            color: #006837;
            letter-spacing: 6px;
            margin-top: 15px;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-family: Georgia, 'Times New Roman', serif;
        }

        .subtitle-bar {
            text-align: center;
            margin-top: 4px;
            margin-bottom: 6px;
        }
        .subtitle-line {
            display: inline-block;
            width: 70px;
            height: 2px;
            background-color: #006837;
            vertical-align: middle;
        }
        .subtitle-text {
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            color: #006837;
            margin: 0 12px;
            vertical-align: middle;
        }

        .to-text {
            text-align: center;
            font-size: 14px;
            color: #006837;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* Trainee Name */
        .trainee-wrapper {
            text-align: center;
            margin: 8px auto 14px auto;
            width: 65%;
            border-bottom: 2px solid #222222;
            padding-bottom: 4px;
        }
        .trainee-name {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Course */
        .course-label {
            text-align: center;
            font-size: 13px;
            color: #374151;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .course-title {
            text-align: center;
            font-size: 24px;
            font-weight: 900;
            color: #111827;
            margin: 0 0 14px 0;
            letter-spacing: 0.5px;
        }

        /* Dates */
        .dates-table {
            width: 60%;
            margin: 0 auto 20px auto;
            border-collapse: collapse;
        }
        .date-col {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            text-align: center;
        }
        .date-val {
            text-decoration: underline;
            font-family: 'Courier New', Courier, monospace;
        }

        /* Bottom Section: 3 Columns */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .col-instructor {
            width: 32%;
            vertical-align: bottom;
            text-align: left;
        }
        .col-qr {
            width: 36%;
            vertical-align: bottom;
            text-align: center;
        }
        .col-manager {
            width: 32%;
            vertical-align: bottom;
            text-align: right;
        }

        .sign-name {
            font-size: 17px;
            font-style: italic;
            font-family: Georgia, serif;
            color: #374151;
            margin-bottom: 4px;
        }
        .sign-line {
            width: 150px;
            border-top: 1px solid #374151;
            padding-top: 3px;
        }
        .sign-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
        }

        .accreditation-box {
            font-size: 8px;
            color: #4b5563;
            line-height: 1.25;
            margin-top: 12px;
        }
        .accreditation-highlight {
            font-weight: bold;
            color: #006837;
            font-size: 8.5px;
        }

        .qr-box {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #d1d5db;
            padding: 4px;
            border-radius: 4px;
        }
        .qr-img {
            width: 85px;
            height: 85px;
        }
        .cert-serial-text {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            margin-top: 4px;
            font-family: 'Courier New', Courier, monospace;
        }
        .cert-url-text {
            font-size: 8px;
            color: #006837;
            margin-top: 2px;
            text-decoration: underline;
        }

        .stamp-box {
            display: inline-block;
            border: 2px solid #0E4DA4;
            color: #0E4DA4;
            padding: 4px 10px;
            border-radius: 6px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <!-- SVG Ribbons Simulation for DomPDF -->
    <!-- Top Right Corner -->
    <div class="corner-top-right">
        <svg viewBox="0 0 200 130" width="260" height="160">
            <path d="M 40 0 C 110 20, 140 60, 200 50 L 200 0 Z" fill="#FBB03B"/>
            <path d="M 80 0 C 130 25, 150 70, 200 80 L 200 0 Z" fill="#006837"/>
        </svg>
    </div>

    <!-- Bottom Left Corner -->
    <div class="corner-bottom-left">
        <svg viewBox="0 0 220 150" width="280" height="180">
            <path d="M 0 50 C 50 90, 120 110, 220 150 L 0 150 Z" fill="#FBB03B"/>
            <path d="M 0 80 C 50 110, 110 125, 170 150 L 0 150 Z" fill="#006837"/>
        </svg>
    </div>

    <!-- Bottom Right Corner -->
    <div class="corner-bottom-right">
        <svg viewBox="0 0 200 100" width="250" height="120">
            <path d="M 0 100 C 60 70, 120 90, 200 70 L 200 100 Z" fill="#006837"/>
            <path d="M 30 100 C 90 80, 140 95, 200 85 L 200 100 Z" fill="#FBB03B"/>
        </svg>
    </div>

    <div class="content">

        <!-- Top Header Logo -->
        <table class="header-table">
            <tr>
                <td style="text-align: left;">
                    @if($logoBase64)
                        <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="Safero Logo" class="logo-img">
                    @endif
                    <div class="brand-text">
                        <div class="brand-title">SAFERO</div>
                        <div class="brand-sub">Services &amp; Consultations</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Certificate Title -->
        <div class="main-title">
            CERTIFICATE
        </div>

        <div class="subtitle-bar">
            <span class="subtitle-line"></span>
            <span class="subtitle-text">This Certificate Presents</span>
            <span class="subtitle-line"></span>
        </div>

        <div class="to-text">to</div>

        <!-- Trainee Name -->
        <div class="trainee-wrapper">
            <div class="trainee-name">{{ arabic_pdf($certificate->trainee_name) }}</div>
        </div>

        <!-- Course Statement -->
        <div class="course-label">Who has successfully completed the course of</div>
        <div class="course-title">{{ arabic_pdf($certificate->program_title) }}</div>

        <!-- Dates -->
        <table class="dates-table">
            <tr>
                <td class="date-col">
                    On the date :- <span class="date-val">{{ $certificate->issue_date->format('d/m/Y') }}</span>
                </td>
                @if($certificate->expiry_date)
                    <td class="date-col">
                        expire on :- <span class="date-val">{{ $certificate->expiry_date->format('d/m/Y') }}</span>
                    </td>
                @endif
            </tr>
        </table>

        <!-- Bottom Signatures, QR Code & Accreditation -->
        <table class="bottom-table">
            <tr>
                <!-- Left: Instructor & Accreditation -->
                <td class="col-instructor">
                    <div class="sign-name">{{ arabic_pdf($certificate->instructor_name) }}</div>
                    @if(!empty($engSignDataUri))
                        <div style="height: 36px; text-align: left; margin-bottom: 2px;">
                            <img src="{{ $engSignDataUri }}" style="max-height: 36px; max-width: 140px;" alt="Signature">
                        </div>
                    @elseif(!empty($engSignBase64))
                        <div style="height: 36px; text-align: left; margin-bottom: 2px;">
                            <img src="data:{{ $engSignMime ?? 'image/jpeg' }};base64,{{ $engSignBase64 }}" style="max-height: 36px; max-width: 140px;" alt="Signature">
                        </div>
                    @endif
                    <div class="sign-line">
                        <span class="sign-title">Instructor Signature</span>
                    </div>

                    <div class="accreditation-box">
                        <span style="font-weight: bold; color: #374151;">Safero services &amp; consultation is accredited by</span><br>
                        Ministry of Industry<br>
                        Egyptian organization for standardization &amp; quality<br>
                        National quality Institute<br>
                        <span class="accreditation-highlight">NQIT 347/686</span>
                    </div>
                </td>

                <!-- Center: Dynamic QR Code & Serial -->
                <td class="col-qr">
                    <div class="qr-box">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="qr-img">
                    </div>
                    <div class="cert-serial-text">Certificate # {{ $certificate->certificate_serial }}</div>
                    <div class="cert-url-text">https://safero-eg.com/certificate-verification/</div>
                </td>

                <!-- Right: Training Manager & Stamp -->
                <td class="col-manager">
                    <div style="display: inline-block; text-align: center;">
                        @if(!empty($stampDataUri))
                            <div style="margin-bottom: 4px; text-align: center;">
                                <img src="{{ $stampDataUri }}" style="max-height: 65px; max-width: 90px; display: inline-block;" alt="Stamp">
                            </div>
                        @elseif(!empty($stampBase64))
                            <div style="margin-bottom: 4px; text-align: center;">
                                <img src="data:{{ $stampMime ?? 'image/jpeg' }};base64,{{ $stampBase64 }}" style="max-height: 65px; max-width: 90px; display: inline-block;" alt="Stamp">
                            </div>
                        @else
                            <div class="stamp-box">
                                <div style="font-style: italic; font-size: 10px;">{{ arabic_pdf($certificate->training_manager_name) }}</div>
                                <div style="border-top: 1px solid #0E4DA4; margin: 2px 0;"></div>
                                <div>SAFERO</div>
                                <div style="font-size: 7.5px;">Training Manager</div>
                            </div>
                        @endif
                        <div class="sign-line" style="margin-left: auto;">
                            <span class="sign-title">Training Manager</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>

