<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate {{ $certificate->certificate_number }} - Safero</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 8.5px;
            line-height: 1.25;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        /* Strict page break rules */
        table, tr, td, th, thead, tbody, 
        .cert-table, .sign-area-table, .checklist-table, 
        .disclaimer-box, .title-block {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .page-container {
            position: relative;
            width: 100%;
            height: 100%;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: always !important;
        }
        .page-container:last-child {
            page-break-after: avoid !important;
        }
        .letterhead-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .content-area {
            position: relative;
            z-index: 10;
            padding-top: 154px;
            padding-left: 28px;
            padding-right: 28px;
            padding-bottom: 25px;
        }
        .title-block {
            text-align: center;
            margin-bottom: 6px;
        }
        .main-heading {
            font-size: 13px;
            font-weight: 900;
            color: #0E4DA4;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }
        .compliance-subtext {
            font-size: 8px;
            color: #374151;
            margin-top: 2px;
            font-weight: 500;
        }
        .cert-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #0f172a;
            margin-bottom: 5px;
            font-size: 8.5px;
        }
        .cert-table th, .cert-table td {
            border: 1px solid #1e293b;
            padding: 3px 5px;
            vertical-align: middle;
        }
        .th-cell {
            background-color: #E2EEFB;
            font-weight: bold;
            color: #0A3778;
            font-size: 8.5px;
        }
        .bold-val {
            font-weight: bold;
            color: #000000;
        }
        .safe-decision-box {
            font-weight: 900;
            font-size: 10.5px;
            text-align: center;
        }
        .sign-area-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #0f172a;
            margin-bottom: 4px;
        }
        .sign-area-table td {
            border: 1px solid #1e293b;
            vertical-align: top;
            padding: 2.5px 4px;
        }
        .sign-title {
            font-size: 7.8px;
            font-weight: bold;
            color: #0f172a;
            text-align: center;
            display: block;
            margin-bottom: 2px;
        }
        .sign-person-name {
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
            display: block;
            color: #000000;
        }
        .sign-graphic-box {
            height: 38px;
            text-align: center;
            vertical-align: middle;
        }
        .sign-img {
            max-height: 36px;
            max-width: 130px;
            display: inline-block;
        }
        .stamp-img {
            max-height: 58px;
            max-width: 85px;
            display: inline-block;
        }
        .qr-img {
            width: 55px;
            height: 55px;
            display: inline-block;
        }
        .disclaimer-box {
            font-size: 6.8px;
            color: #374151;
            text-align: justify;
            line-height: 1.2;
            border: 1px solid #1e293b;
            padding: 3px 5px;
            background-color: #ffffff;
        }
        /* Page 2: Checklist Table */
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #0f172a;
            font-size: 8px;
        }
        .checklist-table th, .checklist-table td {
            border: 1px solid #1e293b;
            padding: 2.2px 4px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <!-- ================= PAGE 1: Thorough Examination Report ================= -->
    <div class="page-container">
        <!-- Official Safero Letterhead Background Image (300 DPI) -->
        @if(!empty($bgDataUri))
            <img src="{{ $bgDataUri }}" class="letterhead-bg" alt="Official Safero Letterhead">
        @elseif(!empty($bgBase64))
            <img src="data:image/jpeg;base64,{{ $bgBase64 }}" class="letterhead-bg" alt="Official Safero Letterhead">
        @endif

        <div class="content-area">
            
            <!-- Document Title -->
            <div class="title-block">
                <h1 class="main-heading">REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT</h1>
                <div class="compliance-subtext">This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998</div>
            </div>

            <!-- Table 1: Header Examination & Customer Meta -->
            <table class="cert-table">
                <tr>
                    <td class="th-cell" style="width: 25%;">Thorough Examination Date</td>
                    <td class="th-cell" style="width: 25%;">Certificate Number</td>
                    <td class="th-cell" style="width: 25%;">Next Examination Due Date</td>
                    <td class="th-cell" style="width: 25%;">Order No</td>
                </tr>
                <tr>
                    <td class="bold-val">{{ $certificate->thorough_examination_date?->format('d/m/Y') }}</td>
                    <td class="bold-val" style="color: #0E4DA4; font-size: 9px;">{{ $certificate->certificate_number }}</td>
                    <td class="bold-val" style="{{ $certificate->next_examination_due_date?->isPast() ? 'color:#b91c1c;' : '' }}">
                        {{ $certificate->next_examination_due_date?->format('d/m/Y') }}
                    </td>
                    <td>{{ $certificate->order_no ?: '—' }}</td>
                </tr>
                <tr>
                    <td class="th-cell">Name and address of Customer</td>
                    <td class="th-cell">Examination Address</td>
                    <td class="th-cell">
                        @if($certificate->equipment_type === 'accessory')
                            Manufacture
                        @else
                            Date of Manufacture
                        @endif
                    </td>
                    <td class="th-cell">Reference Standard</td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: #000;">{{ arabic_pdf($certificate->customer?->name) }}</strong>
                        @if($certificate->customer?->address && $certificate->customer->address !== $certificate->examination_address)
                            <br><span style="font-size: 7.5px; color: #4b5563;">{{ arabic_pdf($certificate->customer->address) }}</span>
                        @endif
                    </td>
                    <td>{{ arabic_pdf($certificate->examination_address ?: 'Owner Yard') }}</td>
                    <td>
                        @if($certificate->equipment_type === 'accessory')
                            {{ arabic_pdf($certificate->manufacturer ?: 'Lifting safety') }}
                        @else
                            {{ $certificate->date_of_manufacture ?: '—' }}
                        @endif
                    </td>
                    <td class="bold-val">{{ $certificate->reference_standard }}</td>
                </tr>
            </table>

            <!-- Table 2: Equipment Description & ID -->
            <table class="cert-table">
                <tr>
                    <td class="th-cell" style="width: 68%;">Description and identification of the Equipment</td>
                    <td class="th-cell" style="width: 32%;">Equipment ID</td>
                </tr>
                <tr>
                    <td style="min-height: 55px; vertical-align: top;">
                        <strong style="font-size: 9.5px; color: #000; display: block; margin-bottom: 2px;">
                            {{ arabic_pdf($certificate->equipment_description) }}
                        </strong>

                        @if($certificate->equipment_type === 'crane')
                            <!-- Crane Specifications (Compact 2-Column Table to save height) -->
                            <table style="width: 100%; border-collapse: collapse; margin-top: 2px; font-size: 7.8px;">
                                <tr>
                                    <td style="border: none; padding: 1px 2px; width: 50%;">
                                        <strong>Manufacturer:</strong> {{ arabic_pdf($certificate->manufacturer ?: '—') }}
                                    </td>
                                    <td style="border: none; padding: 1px 2px; width: 50%;">
                                        <strong>Model:</strong> {{ arabic_pdf($certificate->model ?: '—') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 1px 2px;">
                                        <strong>Capacity:</strong> {{ $certificate->capacity ?: '—' }}
                                    </td>
                                    <td style="border: none; padding: 1px 2px;">
                                        <strong>Chassis No.:</strong> <span style="color: #0E4DA4; font-weight: bold;">{{ $certificate->chassis_no ?: '—' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 1px 2px;">
                                        <strong>Main Wire:</strong> {{ $certificate->main_wire ?: '—' }}
                                    </td>
                                    <td style="border: none; padding: 1px 2px;">
                                        <strong>Main Hook Block:</strong> {{ $certificate->hook_block ?: '—' }}
                                    </td>
                                </tr>
                            </table>
                        @elseif($certificate->equipment_type === 'accessory')
                            <!-- Lifting Accessories / Slings Specifications -->
                            <div style="font-size: 8px; line-height: 1.3;">
                                <div><strong>Quantity:</strong> {{ $certificate->quantity }}</div>
                                @if($certificate->length)
                                    <div><strong>Length:</strong> {{ $certificate->length }}</div>
                                @endif
                                @if($certificate->safety_factor)
                                    <div><strong>Safety factor:</strong> {{ $certificate->safety_factor }}</div>
                                @endif
                            </div>
                        @else
                            <!-- General / Pallets / Beams -->
                            <div style="font-size: 8px; line-height: 1.3;">
                                @if($certificate->dimensions)
                                    <div><strong>Dimensions:</strong> {{ arabic_pdf($certificate->dimensions) }}</div>
                                @endif
                                <div><strong>SWL :</strong> {{ $certificate->swl }}</div>
                                <div><strong>Quantity :-</strong> {{ sprintf('%02d', $certificate->quantity) }}</div>
                            </div>
                        @endif
                    </td>
                    <td style="font-family: monospace; font-size: 8.5px; vertical-align: middle; text-align: center;">
                        <div style="white-space: pre-line; font-weight: bold; color: #0E4DA4;">{{ $certificate->equipment_id_range ?: '—' }}</div>
                    </td>
                </tr>
            </table>

            <!-- Table 2b: Load Test Details (When Applicable) -->
            @if($certificate->equipment_type === 'crane' || $certificate->load_test_cert_no || $certificate->date_of_load_test)
                <table class="cert-table">
                    <tr>
                        <td class="th-cell" style="width: 25%;">Load Test Certificate Number</td>
                        <td class="th-cell" style="width: 25%;">Date of Load Test</td>
                        <td class="th-cell" style="width: 25%;">Safe Working Load</td>
                        <td class="th-cell" style="width: 25%;">Date of Last thorough examination</td>
                    </tr>
                    <tr>
                        <td>{{ $certificate->load_test_cert_no ?: 'NONE' }}</td>
                        <td>{{ $certificate->date_of_load_test?->format('d/m/Y') ?: '—' }}</td>
                        <td class="bold-val">{{ $certificate->swl }}</td>
                        <td>{{ $certificate->last_examination_date?->format('d/m/Y') ?: '—' }}</td>
                    </tr>
                </table>
            @endif

            <!-- Table 3: Statutory LOLER 6 Mandatory Questions -->
            <table class="cert-table">
                <tr>
                    <td style="width: 40%;">Is this first examination after installation</td>
                    <td class="bold-val" style="width: 10%; text-align: center;">{{ $certificate->is_first_examination_after_installation ? 'Yes' : 'No' }}</td>
                    <td style="width: 40%;">Was the examination carried out within 6 months</td>
                    <td class="bold-val" style="width: 10%; text-align: center;">{{ $certificate->was_examination_within_6_months ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td>Has the Equipment been installed correctly</td>
                    <td class="bold-val" style="text-align: center;">{{ $certificate->has_equipment_installed_correctly ? 'Yes' : 'No' }}</td>
                    <td>Was the examination carried out within 12 months</td>
                    <td class="bold-val" style="text-align: center;">{{ $certificate->was_examination_within_12_months ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td>In Accordance with examination scheme</td>
                    <td class="bold-val" style="text-align: center;">{{ $certificate->in_accordance_with_scheme ? 'Yes' : 'No' }}</td>
                    <td>After the occurrence of exceptional circumstances</td>
                    <td class="bold-val" style="text-align: center;">{{ $certificate->after_exceptional_circumstances ? 'Yes' : 'No' }}</td>
                </tr>
            </table>

            <!-- Table 4: Defects & Alterations -->
            <table class="cert-table">
                <tr>
                    <td class="th-cell" style="width: 70%;">Identification of any part found to have defect</td>
                    <td class="th-cell" style="width: 30%;">Is the defect danger to process</td>
                </tr>
                <tr>
                    <td>{{ arabic_pdf($certificate->defect_identification ?: 'NONE') }}</td>
                    <td class="bold-val" style="text-align: center;">{{ $certificate->is_defect_danger_to_process ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="th-cell">Particulars of any repair, renewal or alteration required to remedy the defect identified above</td>
                </tr>
                <tr>
                    <td colspan="2">{{ arabic_pdf($certificate->repair_particulars ?: 'NONE') }}</td>
                </tr>
            </table>

            <!-- Table 5: Particulars of Tests & Standards Applied -->
            <table class="cert-table">
                <tr>
                    <td class="th-cell">Particulars of any tests are carried out as part of thorough examination. And standard applied</td>
                </tr>
                <tr>
                    <td>
                        <div style="white-space: pre-line; line-height: 1.25;">{!! nl2br(e(arabic_pdf($certificate->tests_carried_out))) !!}</div>
                    </td>
                </tr>
            </table>

            <!-- Table 6: Statutory Safety Decision -->
            <table class="cert-table">
                <tr>
                    <td style="width: 80%; font-weight: bold; font-size: 9px; vertical-align: middle;">Is the Equipment Safe to Operate</td>
                    <td class="safe-decision-box" style="width: 20%; {{ $certificate->is_safe_to_operate ? 'background-color: #EAF5EA; color: #15803d;' : 'background-color: #FEE2E2; color: #B91C1C;' }}">
                        {{ $certificate->is_safe_to_operate ? 'Yes' : 'No' }}
                    </td>
                </tr>
            </table>

            <!-- Table 7: Signatures, Stamp & QR Code -->
            <table class="sign-area-table">
                <tr>
                    <td style="width: 34%; text-align: center;">
                        <span class="sign-title">Name and Signature of the person issue this certificate</span>
                        <div class="sign-person-name">{{ arabic_pdf($certificate->issuer_name) }}</div>
                        <div class="sign-graphic-box">
                            @if(!empty($engSignDataUri))
                                <img src="{{ $engSignDataUri }}" class="sign-img" alt="Signature">
                            @elseif(!empty($engSignBase64))
                                <img src="data:{{ $engSignMime ?? 'image/jpeg' }};base64,{{ $engSignBase64 }}" class="sign-img" alt="Signature">
                            @endif
                        </div>
                    </td>

                    <td style="width: 22%; text-align: center; vertical-align: middle;">
                        <span class="sign-title">Stamp</span>
                        <div style="height: 58px; text-align: center;">
                            @if(!empty($stampDataUri))
                                <img src="{{ $stampDataUri }}" class="stamp-img" alt="Safero Stamp">
                            @elseif(!empty($stampBase64))
                                <img src="data:{{ $stampMime ?? 'image/jpeg' }};base64,{{ $stampBase64 }}" class="stamp-img" alt="Safero Stamp">
                            @endif
                        </div>
                    </td>

                    <td style="width: 32%; text-align: center;">
                        <span class="sign-title">Name and Signature of the person authenticating this certificate</span>
                        <div class="sign-person-name">{{ arabic_pdf($certificate->authenticator_name) }}</div>
                        <div class="sign-graphic-box">
                            @if(!empty($dirSignDataUri))
                                <img src="{{ $dirSignDataUri }}" class="sign-img" alt="Authenticator Signature">
                            @elseif(!empty($dirSignBase64))
                                <img src="data:{{ $dirSignMime ?? 'image/jpeg' }};base64,{{ $dirSignBase64 }}" class="sign-img" alt="Authenticator Signature">
                            @endif
                        </div>
                    </td>

                    <td style="width: 12%; text-align: center; vertical-align: middle;">
                        <div style="height: 55px; text-align: center;">
                            <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" class="qr-img" alt="QR Code">
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Legal Conditions Disclaimer -->
            <div class="disclaimer-box">
                This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees. Under no circumstances shall Safero be liable for any injury or damage to any person occurring by reason of negligent operation or any defect in materials, machinery, equipment or other items other than those defects ascertainable by normally accepted testing standards and only those items actually inspected by Safero and which are covered by this certificate.
            </div>

        </div>
    </div>

    <!-- ================= PAGE 2: Inspection Checklist (Only when checklist items exist) ================= -->
    @if($certificate->equipment_type === 'crane' || (!empty($certificate->checklist_items) && count($certificate->checklist_items) > 0))
        @php
            $checklist = $certificate->checklist_items ?: \App\Models\Certificate::defaultCraneChecklist();
            $page2Title = $certificate->equipment_type === 'crane' 
                ? 'MOBILE CRANE INSPECTION CHECKLIST' 
                : 'EQUIPMENT INSPECTION CHECKLIST';
        @endphp
        <div class="page-container">
            <!-- Official Safero Letterhead Background Image (300 DPI) -->
            @if(!empty($bgDataUri))
                <img src="{{ $bgDataUri }}" class="letterhead-bg" alt="Official Safero Letterhead">
            @elseif(!empty($bgBase64))
                <img src="data:image/jpeg;base64,{{ $bgBase64 }}" class="letterhead-bg" alt="Official Safero Letterhead">
            @endif

            <div class="content-area">
                
                <!-- Page 2 Title -->
                <div class="title-block" style="margin-bottom: 8px;">
                    <h2 class="main-heading" style="font-size: 13px; text-decoration: underline;">{{ $page2Title }}</h2>
                </div>

                <!-- Meta Row -->
                <table style="width: 100%; margin-bottom: 5px; font-size: 8px;">
                    <tr>
                        <td style="width: 50%; font-weight: bold;">
                            Certificate No: <span style="color: #0E4DA4; font-family: monospace;">{{ $certificate->certificate_number }}</span>
                        </td>
                        <td style="width: 50%; text-align: right; font-weight: bold;">
                            Date of inspection: <span>{{ $certificate->thorough_examination_date?->format('d F Y') }}</span>
                        </td>
                    </tr>
                </table>

                <!-- Inspection Checklist Table -->
                <table class="checklist-table">
                    <thead>
                        <tr style="background-color: #E2EEFB; color: #0A3778;">
                            <th style="width: 55%; text-align: left; font-size: 8.5px; font-weight: bold;">Questions</th>
                            <th style="width: 20%; text-align: center; font-size: 8.5px; font-weight: bold;">Check</th>
                            <th style="width: 25%; text-align: center; font-size: 8.5px; font-weight: bold;">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklist as $item)
                            @php
                                $qText = $item['question'] ?? $item['item'] ?? '';
                                $cStatus = $item['check'] ?? $item['status'] ?? 'In Order';
                                $cNote = $item['comment'] ?? $item['remarks'] ?? '';
                                $isDefect = in_array(strtolower($cStatus), ['defect', 'fail', 'rejected']);
                            @endphp
                            <tr>
                                <td>{{ arabic_pdf($qText) }}</td>
                                <td style="text-align: center; font-weight: bold; color: {{ $isDefect ? '#dc2626' : '#000000' }};">
                                    {{ arabic_pdf($cStatus) }}
                                </td>
                                <td style="font-size: 7.5px; color: #4b5563;">{{ arabic_pdf($cNote) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Signature & Stamp Block for Page 2 -->
                <div style="margin-top: 6px; position: relative;">
                    <div class="disclaimer-box" style="margin-bottom: 0;">
                        This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees. Under no circumstances shall Safero be liable for any injury or damage to any person occurring by reason of negligent operation or any defect in materials, machinery, equipment or other items other than those defects ascertainable by normally accepted testing standards and only those items actually inspected by Safero and which are covered by this certificate.
                    </div>
                    @if(!empty($stampDataUri))
                        <div style="position: absolute; right: 25px; bottom: -12px; opacity: 0.95;">
                            <img src="{{ $stampDataUri }}" style="max-height: 65px;" alt="Stamp">
                        </div>
                    @elseif(!empty($stampBase64))
                        <div style="position: absolute; right: 25px; bottom: -12px; opacity: 0.95;">
                            <img src="data:{{ $stampMime ?? 'image/jpeg' }};base64,{{ $stampBase64 }}" style="max-height: 65px;" alt="Stamp">
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif

</body>
</html>
