<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate {{ $certificate->certificate_number }} - Safero</title>
    <style>
        @page {
            margin: 8mm 8mm 6mm 8mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            font-size: 10px;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0E4DA4;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .title-area {
            text-align: center;
            margin: 6px 0 10px 0;
        }
        .main-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .subtitle {
            font-size: 8.5px;
            color: #4b5563;
            font-style: italic;
            margin-top: 2px;
        }
        .cert-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #374151;
            font-size: 9.5px;
        }
        .cert-table td, .cert-table th {
            border: 1px solid #374151;
            padding: 4px 6px;
            vertical-align: top;
        }
        .bg-head {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1f2937;
        }
        .val-bold {
            font-weight: bold;
            color: #0E4DA4;
        }
        .safe-box {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            background-color: #f0fdf4;
            color: #15803d;
        }
        .sign-cell {
            height: 95px;
            text-align: center;
            vertical-align: middle;
            padding: 4px;
        }
        .sign-title {
            font-size: 8px;
            font-weight: bold;
            color: #4b5563;
            display: block;
            margin-bottom: 4px;
        }
        .sign-img {
            max-height: 40px;
            display: block;
            margin: 0 auto;
        }
        .stamp-img {
            max-height: 75px;
            display: block;
            margin: 0 auto;
        }
        .disclaimer-text {
            font-size: 7.2px;
            color: #4b5563;
            text-align: justify;
            line-height: 1.15;
            padding: 4px 6px;
        }
        .footer-note {
            width: 100%;
            margin-top: 6px;
            font-size: 7.5px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <!-- Header Table with Logo and QR Code -->
    <table class="header-table">
        <tr>
            <td style="width: 75%; vertical-align: middle;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 70px; vertical-align: middle;">
                            @if(!empty($logoBase64))
                                <img src="data:image/jpeg;base64,{{ $logoBase64 }}" style="height: 55px; width: auto;" alt="Logo">
                            @endif
                        </td>
                        <td style="vertical-align: middle; padding-left: 10px;">
                            <div style="font-size: 18px; font-weight: 900; color: #0E4DA4; letter-spacing: 1px;">SAFERO</div>
                            <div style="font-size: 10px; font-weight: bold; color: #22A447;">Services &amp; Consultations</div>
                            <div style="font-size: 8px; color: #6b7280; margin-top: 2px;">
                                Inspection &amp; Calibration | ISO Compliance | Environmental &amp; Safety Assessments
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <div style="text-align: center; display: inline-block;">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" style="width: 68px; height: 68px;" alt="QR Code">
                    <div style="font-size: 7px; font-weight: bold; color: #0E4DA4; margin-top: 1px;">SCAN TO VERIFY</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Title -->
    <div class="title-area">
        <div class="main-title">REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT</div>
        <div class="subtitle">This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998</div>
    </div>

    <!-- Main Grid Table -->
    <table class="cert-table">
        <!-- Row 1 Headers -->
        <tr class="bg-head">
            <td style="width: 25%;">Thorough Examination Date</td>
            <td style="width: 25%;">Certificate Number</td>
            <td style="width: 25%;">Next Examination Due Date</td>
            <td style="width: 25%;">Order No</td>
        </tr>
        <!-- Row 1 Values -->
        <tr>
            <td>{{ $certificate->thorough_examination_date->format('d/m/Y') }}</td>
            <td class="val-bold">{{ $certificate->certificate_number }}</td>
            <td style="font-weight: bold;">{{ $certificate->next_examination_due_date->format('d/m/Y') }}</td>
            <td>{{ $certificate->order_no ?? '100000' }}</td>
        </tr>

        <!-- Row 2 Headers -->
        <tr class="bg-head">
            <td>Name and address of Customer</td>
            <td>Examination Address</td>
            <td>Date of Manufacture</td>
            <td>Reference Standard</td>
        </tr>
        <!-- Row 2 Values -->
        <tr>
            <td style="font-weight: bold;">{{ $certificate->customer?->name }}</td>
            <td>{{ $certificate->examination_address }}</td>
            <td>{{ $certificate->date_of_manufacture ?? '2009' }}</td>
            <td style="font-weight: bold;">{{ $certificate->reference_standard }}</td>
        </tr>

        <!-- Equipment Description & ID -->
        <tr class="bg-head">
            <td colspan="3">Description and identification of the Equipment</td>
            <td>Equipment ID</td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 6px;">
                <div style="font-weight: bold; font-size: 11px; margin-bottom: 2px;">{{ $certificate->equipment_description }}</div>
                <div style="color: #4b5563; font-size: 8.5px;">Dimensions: {{ $certificate->dimensions ?? 'N/A' }}</div>
                <div style="font-weight: bold; color: #0E4DA4; font-size: 10px; margin-top: 3px;">
                    SWL : {{ $certificate->swl }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Quantity : {{ sprintf('%02d', $certificate->quantity) }}
                </div>
            </td>
            <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10px;">
                {{ $certificate->equipment_id_range ?? '—' }}
            </td>
        </tr>

        <!-- Checklist Questions -->
        <tr>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">Is this first examination after installation?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px;">
                            {{ $certificate->is_first_examination_after_installation ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">Was the examination carried out within 6 months?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px; color: #15803d;">
                            {{ $certificate->was_examination_within_6_months ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">Has the Equipment been installed correctly?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px; color: #15803d;">
                            {{ $certificate->has_equipment_installed_correctly ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">Was the examination carried out within 12 months?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px;">
                            {{ $certificate->was_examination_within_12_months ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">In Accordance with examination scheme?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px;">
                            {{ $certificate->in_accordance_with_scheme ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 1px 0;">After the occurrence of exceptional circumstances?</td>
                        <td style="border: none; text-align: right; font-weight: bold; width: 40px;">
                            {{ $certificate->after_exceptional_circumstances ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Defects -->
        <tr>
            <td colspan="2">
                <div style="color: #6b7280; font-size: 8px;">Identification of any part found to have defect:</div>
                <div style="font-weight: bold; font-size: 9px; margin-top: 1px;">{{ $certificate->defect_identification ?? 'NONE' }}</div>
            </td>
            <td colspan="2">
                <div style="color: #6b7280; font-size: 8px;">Is the defect danger to process:</div>
                <div style="font-weight: bold; font-size: 9px; margin-top: 1px;">{{ $certificate->is_defect_danger_to_process ? 'Yes' : 'No' }}</div>
            </td>
        </tr>

        <!-- Repairs -->
        <tr>
            <td colspan="4">
                <div style="color: #6b7280; font-size: 8px;">Particulars of any repair, renewal or alteration required to remedy the defect identified above:</div>
                <div style="font-weight: bold; font-size: 9px; margin-top: 1px;">{{ $certificate->repair_particulars ?? 'NONE' }}</div>
            </td>
        </tr>

        <!-- Tests Carried Out -->
        <tr>
            <td colspan="4">
                <div style="color: #6b7280; font-size: 8px;">Particulars of any tests are carried out as part of thorough examination, And standard applied:</div>
                <div style="font-weight: bold; font-size: 9.5px; color: #111827; margin-top: 1px;">
                    {{ $certificate->tests_carried_out }}
                </div>
            </td>
        </tr>

        <!-- Safe to operate -->
        <tr class="bg-head">
            <td colspan="3" style="font-size: 11px; font-weight: bold; vertical-align: middle;">
                Is the Equipment Safe to Operate
            </td>
            <td class="safe-box">
                {{ $certificate->is_safe_to_operate ? 'YES' : 'NO' }}
            </td>
        </tr>

        <!-- Signatures and Stamp Row -->
        <tr>
            <!-- Inspector -->
            <td colspan="1" class="sign-cell" style="width: 33%;">
                <span class="sign-title">Name and Signature of the person issue this certificate</span>
                @if(!empty($engSignBase64))
                    <img src="data:image/svg+xml;base64,{{ $engSignBase64 }}" class="sign-img" alt="Sign">
                @endif
                <div style="font-weight: bold; font-size: 9px; margin-top: 2px;">{{ $certificate->issuer_name }}</div>
            </td>

            <!-- Stamp -->
            <td colspan="2" class="sign-cell" style="width: 34%;">
                <span class="sign-title">Stamp</span>
                @if(!empty($stampBase64))
                    <img src="data:image/svg+xml;base64,{{ $stampBase64 }}" class="stamp-img" alt="Stamp">
                @endif
            </td>

            <!-- Authenticator -->
            <td colspan="1" class="sign-cell" style="width: 33%;">
                <span class="sign-title">Name and Signature of the person authenticating this certificate</span>
                @if(!empty($dirSignBase64))
                    <img src="data:image/svg+xml;base64,{{ $dirSignBase64 }}" class="sign-img" alt="Sign">
                @endif
                <div style="font-weight: bold; font-size: 9px; margin-top: 2px;">{{ $certificate->authenticator_name }}</div>
            </td>
        </tr>

        <!-- Legal Disclaimer Condition -->
        <tr>
            <td colspan="4" class="disclaimer-text">
                This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees. Under no circumstances shall Safero be liable for any injury or damage to any person occurring by reason of negligent operation or any defect in materials, machinery, equipment or other items other than those defects ascertainable by normally accepted testing standards and only those items actually inspected by Safero and which are covered by this certificate.
            </td>
        </tr>
    </table>

    <!-- Bottom verification note -->
    <table class="footer-note">
        <tr>
            <td style="text-align: left;">
                Official Digital Verification: <strong>{{ $verifyUrl }}</strong>
            </td>
            <td style="text-align: right;">
                System UUID: {{ $certificate->uuid }}
            </td>
        </tr>
    </table>

</body>
</html>

