@extends('layouts.app')

@section('title', 'عرض شهادة الفحص - ' . $certificate->certificate_number)

@section('content')
<!-- Print & Certificate Sheet Stylesheet -->
<style>
    @page {
        size: A4 portrait;
        margin: 0;
    }
    .cert-paper-sheet {
        position: relative;
        width: 100%;
        max-width: 860px;
        margin: 0 auto 30px auto;
        background-color: #ffffff;
        background-image: url('{{ asset(file_exists(public_path("build/assets/cert-inspect.pdf.jpg")) ? "build/assets/cert-inspect.pdf.jpg" : "branding/cert_inspect_bg.jpg") }}');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-position: top center;
        border-radius: 12px;
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.12), 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #cbd5e1;
        padding-top: 154px;
        padding-bottom: 35px;
        padding-left: 36px;
        padding-right: 36px;
        min-height: 1120px;
        box-sizing: border-box;
    }
    @media (max-width: 640px) {
        .cert-paper-sheet {
            padding-top: 110px;
            padding-bottom: 25px;
            padding-left: 12px;
            padding-right: 12px;
            min-height: auto;
        }
    }
    @media print {
        *, *::before, *::after {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box !important;
        }
        header, nav, aside, footer, .no-print {
            display: none !important;
        }
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            width: 210mm !important;
        }
        .cert-paper-sheet {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding-top: 154px !important;
            padding-bottom: 25px !important;
            padding-left: 28px !important;
            padding-right: 28px !important;
            width: 210mm !important;
            max-width: 210mm !important;
            height: 297mm !important;
            max-height: 297mm !important;
            min-height: 297mm !important;
            margin: 0 !important;
            page-break-after: always !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            overflow: hidden !important;
            background-image: url('{{ asset(file_exists(public_path("build/assets/cert-inspect.pdf.jpg")) ? "build/assets/cert-inspect.pdf.jpg" : "branding/cert_inspect_bg.jpg") }}') !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
        }
        .cert-paper-sheet:last-of-type {
            page-break-after: avoid !important;
        }
        table, tr, td, th, div, .printable-block {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }
</style>

<!-- Breadcrumb & Top Bar -->
<div class="mb-4 no-print">
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
        <a href="{{ route('certificates.index') }}" class="hover:text-[#0E4DA4]">{{ __('شهادات الفحص') }}</a>
        <span>/</span>
        <span class="text-slate-800 font-bold font-en">{{ $certificate->certificate_number }}</span>
    </div>
</div>

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full {{ $certificate->is_safe_to_operate ? 'bg-[#22A447]' : 'bg-red-500' }}"></span>
            <div>
                <span class="text-xs text-slate-400 block font-en uppercase tracking-wider">{{ __('رقم الشهادة') }}</span>
                <span class="text-lg font-black text-[#0E4DA4] font-en">{{ $certificate->certificate_number }}</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Print Equipment Sticker Button -->
            <a href="{{ route('certificates.sticker', $certificate) }}" 
               target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5"
               title="{{ __('طباعة استيكر الباركود فردي') }}">
                <span>{{ __('استيكر المعدة') }}</span>
            </a>

            <!-- Download PDF Button -->
            <a href="{{ route('certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-4 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#0E4DA4]/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __('تحميل PDF') }}</span>
            </a>

            <!-- Print Official PDF Button -->
            <button type="button" 
                    onclick="printOfficialPdf('{{ route('certificates.pdf', $certificate) }}')"
                    class="px-3.5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>{{ __('طباعة') }}</span>
            </button>

            <!-- Public Verification Link -->
            <a href="{{ route('certificates.verify', $certificate->uuid) }}" 
               target="_blank"
               class="px-3.5 py-2.5 rounded-xl border border-[#22A447]/30 bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white font-bold text-xs sm:text-sm transition flex items-center gap-1.5"
               title="{{ __('رابط التحقق العام عبر الـ QR') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{{ __('صفحة التحقق (QR)') }}</span>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('certificates.edit', $certificate) }}" 
               class="px-3 py-2.5 rounded-xl border border-slate-200 hover:bg-amber-50 text-slate-700 hover:text-amber-600 font-semibold text-xs sm:text-sm transition">
                {{ __('تعديل') }}
            </a>
        </div>
    </div>

    <!-- ================= PAGE 1: Thorough Examination Report ================= -->
    <div class="cert-paper-sheet printable-sheet" dir="ltr">
        
        <!-- Official Report Title -->
        <div class="text-center mb-3">
            <h1 class="text-base sm:text-lg font-black text-[#0E4DA4] tracking-wide uppercase font-en">
                REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT
            </h1>
            <p class="text-[10px] text-slate-600 font-en italic mt-0.5">
                This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998
            </p>
        </div>

        <!-- Metadata Table (Exact match to Word / PDF document) -->
        <div class="border-2 border-slate-800 text-xs font-en text-slate-900">
            
            <!-- Row 1: Certificate Meta -->
            <div class="grid grid-cols-4 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778]">
                <div class="p-1.5 border-r border-slate-700 font-bold">Thorough Examination Date</div>
                <div class="p-1.5 border-r border-slate-700 font-bold">Certificate Number</div>
                <div class="p-1.5 border-r border-slate-700 font-bold">Next Examination Due Date</div>
                <div class="p-1.5 font-bold">Order No</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-700 bg-white">
                <div class="p-1.5 border-r border-slate-700 font-bold">{{ $certificate->thorough_examination_date?->format('d/m/Y') }}</div>
                <div class="p-1.5 border-r border-slate-700 font-black text-[#0E4DA4]">{{ $certificate->certificate_number }}</div>
                <div class="p-1.5 border-r border-slate-700 font-bold {{ $certificate->next_examination_due_date?->isPast() ? 'text-red-600' : '' }}">
                    {{ $certificate->next_examination_due_date?->format('d/m/Y') }}
                </div>
                <div class="p-1.5">{{ $certificate->order_no ?? '—' }}</div>
            </div>

            <!-- Row 2: Customer & Standard -->
            <div class="grid grid-cols-4 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778]">
                <div class="p-1.5 border-r border-slate-700 font-bold">Name and address of Customer</div>
                <div class="p-1.5 border-r border-slate-700 font-bold">Examination Address</div>
                <div class="p-1.5 border-r border-slate-700 font-bold">
                    @if($certificate->equipment_type === 'accessory')
                        Manufacturer
                    @else
                        Date of Manufacture
                    @endif
                </div>
                <div class="p-1.5 font-bold">Reference Standard</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-700 bg-white">
                <div class="p-1.5 border-r border-slate-700">
                    <p class="font-bold text-slate-900">{{ $certificate->customer?->name }}</p>
                    @if($certificate->customer?->address && $certificate->customer->address !== $certificate->examination_address)
                        <p class="text-[10px] text-slate-500">{{ $certificate->customer->address }}</p>
                    @endif
                </div>
                <div class="p-1.5 border-r border-slate-700">{{ $certificate->examination_address ?: 'Owner Yard' }}</div>
                <div class="p-1.5 border-r border-slate-700">
                    @if($certificate->equipment_type === 'accessory')
                        {{ $certificate->manufacturer ?: 'Lifting safety' }}
                    @else
                        {{ $certificate->date_of_manufacture ?? '—' }}
                    @endif
                </div>
                <div class="p-1.5 font-bold">{{ $certificate->reference_standard }}</div>
            </div>

            <!-- Row 3: Equipment Details -->
            <div class="grid grid-cols-4 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778]">
                <div class="col-span-3 p-1.5 border-r border-slate-700 font-bold">Description and identification of the Equipment</div>
                <div class="p-1.5 font-bold text-center">Equipment ID</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-700 bg-white">
                <div class="col-span-3 p-2 border-r border-slate-700">
                    <p class="font-bold text-sm text-slate-900 mb-1">{{ $certificate->equipment_description }}</p>

                    @if($certificate->equipment_type === 'crane')
                        <!-- Crane Specifications Compact Grid -->
                        <div class="grid grid-cols-2 gap-x-4 gap-y-0.5 text-xs text-slate-700 bg-slate-50 p-2 rounded border border-slate-300">
                            <div><strong class="text-slate-900">Manufacturer:</strong> {{ $certificate->manufacturer ?: '—' }}</div>
                            <div><strong class="text-slate-900">Model:</strong> {{ $certificate->model ?: '—' }}</div>
                            <div><strong class="text-slate-900">Capacity:</strong> {{ $certificate->capacity ?: '—' }}</div>
                            <div><strong class="text-slate-900">Chassis No.:</strong> <span class="font-bold text-[#0E4DA4]">{{ $certificate->chassis_no ?: '—' }}</span></div>
                            <div><strong class="text-slate-900">Main Wire:</strong> {{ $certificate->main_wire ?: '—' }}</div>
                            <div><strong class="text-slate-900">Hook Block:</strong> {{ $certificate->hook_block ?: '—' }}</div>
                        </div>
                    @elseif($certificate->equipment_type === 'accessory')
                        <!-- Lifting Accessories Breakdown -->
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-700 bg-slate-50 p-1.5 rounded border border-slate-300">
                            <div><strong class="text-slate-900">SWL:</strong> <span class="font-bold text-[#0E4DA4]">{{ $certificate->swl }}</span></div>
                            <div><strong class="text-slate-900">Quantity:</strong> {{ $certificate->quantity }}</div>
                            @if($certificate->length)
                                <div><strong class="text-slate-900">Length:</strong> {{ $certificate->length }}</div>
                            @endif
                            @if($certificate->safety_factor)
                                <div><strong class="text-slate-900">Safety factor:</strong> <span class="font-bold text-emerald-700">{{ $certificate->safety_factor }}</span></div>
                            @endif
                        </div>
                    @else
                        <!-- General / Pallets / CCUs -->
                        <div class="text-xs text-slate-700">
                            @if($certificate->dimensions)
                                <p class="mb-0.5"><strong class="text-slate-900">Dimensions:</strong> {{ $certificate->dimensions }}</p>
                            @endif
                            <p class="font-bold text-[#0E4DA4]">SWL : {{ $certificate->swl }} &nbsp;&nbsp;|&nbsp;&nbsp; Quantity : {{ sprintf('%02d', $certificate->quantity) }}</p>
                        </div>
                    @endif
                </div>

                <div class="p-2 font-semibold flex items-center justify-center text-center">
                    <span class="font-bold text-xs text-[#0E4DA4] tracking-wider">{{ $certificate->equipment_id_range ?? '—' }}</span>
                </div>
            </div>

            <!-- Optional Row: Load Test Details (For Cranes or when load test data is present) -->
            @if($certificate->equipment_type === 'crane' || $certificate->load_test_cert_no || $certificate->date_of_load_test)
                <div class="grid grid-cols-4 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778]">
                    <div class="p-1.5 border-r border-slate-700 font-bold">Load Test Certificate Number</div>
                    <div class="p-1.5 border-r border-slate-700 font-bold">Date of Load Test</div>
                    <div class="p-1.5 border-r border-slate-700 font-bold">Safe Working Load</div>
                    <div class="p-1.5 font-bold">Date of Last thorough examination</div>
                </div>
                <div class="grid grid-cols-4 border-b border-slate-700 bg-white">
                    <div class="p-1.5 border-r border-slate-700 font-semibold">{{ $certificate->load_test_cert_no ?: 'NONE' }}</div>
                    <div class="p-1.5 border-r border-slate-700">{{ $certificate->date_of_load_test?->format('d/m/Y') ?: '—' }}</div>
                    <div class="p-1.5 border-r border-slate-700 font-bold text-[#0E4DA4]">{{ $certificate->swl }}</div>
                    <div class="p-1.5">{{ $certificate->last_examination_date?->format('d/m/Y') ?: '—' }}</div>
                </div>
            @endif

            <!-- Questions Checklist Table -->
            <div class="grid grid-cols-2 border-b border-slate-700">
                <div class="p-1.5 border-r border-slate-700 flex justify-between items-center bg-white">
                    <span>Is this first examination after installation?</span>
                    <span class="font-bold px-2">{{ $certificate->is_first_examination_after_installation ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-1.5 flex justify-between items-center bg-white">
                    <span>Was the examination carried out within 6 months?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->was_examination_within_6_months ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 border-b border-slate-700">
                <div class="p-1.5 border-r border-slate-700 flex justify-between items-center bg-white">
                    <span>Has the Equipment been installed correctly?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->has_equipment_installed_correctly ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-1.5 flex justify-between items-center bg-white">
                    <span>Was the examination carried out within 12 months?</span>
                    <span class="font-bold px-2">{{ $certificate->was_examination_within_12_months ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 border-b border-slate-700">
                <div class="p-1.5 border-r border-slate-700 flex justify-between items-center bg-white">
                    <span>In Accordance with examination scheme?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->in_accordance_with_scheme ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-1.5 flex justify-between items-center bg-white">
                    <span>After the occurrence of exceptional circumstances?</span>
                    <span class="font-bold px-2">{{ $certificate->after_exceptional_circumstances ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <!-- Defects / Alterations -->
            <div class="grid grid-cols-4 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778]">
                <div class="col-span-3 p-1.5 border-r border-slate-700 font-bold">Identification of any part found to have defect</div>
                <div class="p-1.5 font-bold text-center">Is the defect danger to process</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-700 bg-white">
                <div class="col-span-3 p-1.5 border-r border-slate-700">
                    {{ $certificate->defect_identification ?? 'None observed at time of inspection.' }}
                </div>
                <div class="p-1.5 text-center font-bold {{ $certificate->is_defect_danger_to_process ? 'text-red-600' : 'text-[#22A447]' }}">
                    {{ $certificate->is_defect_danger_to_process ? 'YES' : 'NO' }}
                </div>
            </div>

            <div class="p-1.5 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778] font-bold">
                Particulars of any repair, renewal or alteration required to remedy the defect identified above
            </div>
            <div class="p-1.5 border-b border-slate-700 bg-white">
                {{ $certificate->repair_particulars ?? 'None required.' }}
            </div>

            <!-- Particulars of Tests -->
            <div class="p-1.5 border-b border-slate-700 bg-[#E2EEFB] text-[#0A3778] font-bold">
                Particulars of any tests are carried out as part of thorough examination. And standard applied
            </div>
            <div class="p-1.5 border-b border-slate-700 bg-white">
                <p class="font-bold text-slate-900">{{ $certificate->tests_carried_out }}</p>
                @if($certificate->test_point)
                    <p class="text-[11px] text-slate-600 mt-0.5 font-semibold">Test Point: {{ $certificate->test_point }}</p>
                @endif
            </div>

            <!-- Is Safe to Operate Decision -->
            <div class="grid grid-cols-4 border-b border-slate-700 bg-slate-50 p-1.5 items-center">
                <div class="col-span-3 font-bold text-xs text-slate-900">
                    Is the Equipment Safe to Operate
                </div>
                <div class="font-black text-xs text-center {{ $certificate->is_safe_to_operate ? 'text-[#22A447]' : 'text-red-600' }}">
                    {{ $certificate->is_safe_to_operate ? 'YES' : 'NO' }}
                </div>
            </div>

            <!-- Signatures, Stamp and QR Row (4 Columns matching PDF) -->
            <div class="grid grid-cols-12 border-b border-slate-700 text-center bg-white">
                <!-- Inspector -->
                <div class="col-span-4 p-2 border-r border-slate-700 flex flex-col justify-between h-28">
                    <span class="text-[9.5px] font-bold text-slate-600">Name and Signature of the person issue this certificate</span>
                    <div class="my-auto">
                        @php
                            $engSign = setting('engineer_signature_path');
                            $engSignPath = $engSign ? ltrim($engSign, '/\\') : null;
                        @endphp
                        @if($engSignPath && file_exists(public_path($engSignPath)))
                            <img src="{{ asset($engSignPath) }}" alt="Engineer Signature" class="h-10 mx-auto object-contain">
                        @else
                            <img src="{{ asset('branding/eng_signature.svg') }}" alt="Engineer Signature" class="h-10 mx-auto object-contain">
                        @endif
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->issuer_name }}</span>
                </div>

                <!-- Safero Stamp -->
                <div class="col-span-3 p-1.5 border-r border-slate-700 flex flex-col items-center justify-center">
                    <span class="text-[9px] font-bold text-slate-400 mb-0.5">STAMP</span>
                    @php
                        $stamp = setting('stamp_path');
                        $stampPath = $stamp ? ltrim($stamp, '/\\') : null;
                    @endphp
                    @if($stampPath && file_exists(public_path($stampPath)))
                        <img src="{{ asset($stampPath) }}" alt="Safero Stamp" class="h-16 w-16 object-contain">
                    @else
                        <img src="{{ asset('branding/stamp.svg') }}" alt="Safero Stamp" class="h-16 w-16 object-contain">
                    @endif
                </div>

                <!-- Authenticator -->
                <div class="col-span-3 p-2 border-r border-slate-700 flex flex-col justify-between h-28">
                    <span class="text-[9.5px] font-bold text-slate-600">Name and Signature of person authenticating this certificate</span>
                    <div class="my-auto">
                        @php
                            $dirSign = setting('director_signature_path');
                            $dirSignPath = $dirSign ? ltrim($dirSign, '/\\') : null;
                        @endphp
                        @if($dirSignPath && file_exists(public_path($dirSignPath)))
                            <img src="{{ asset($dirSignPath) }}" alt="Director Signature" class="h-10 mx-auto object-contain">
                        @else
                            <img src="{{ asset('branding/director_signature.svg') }}" alt="Director Signature" class="h-10 mx-auto object-contain">
                        @endif
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->authenticator_name }}</span>
                </div>

                <!-- QR Code -->
                <div class="col-span-2 p-1.5 flex flex-col items-center justify-center">
                    <div class="p-0.5 bg-white border border-slate-300 rounded shadow-xs inline-block">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-14 h-14">
                    </div>
                    <span class="block text-[7.5px] font-bold text-slate-500 font-en tracking-wider mt-0.5">SCAN TO VERIFY</span>
                </div>
            </div>

            <!-- Conditions & Disclaimer Paragraph -->
            <div class="p-2 text-[8.5px] leading-tight text-slate-600 text-justify bg-white">
                This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees. Under no circumstances shall Safero be liable for any injury or damage to any person occurring by reason of negligent operation or any defect in materials, machinery, equipment or other items other than those defects ascertainable by normally accepted testing standards and only those items actually inspected by Safero and which are covered by this certificate.
            </div>

        </div>

    </div>

    <!-- ================= PAGE 2: Crane Inspection Checklist (When Applicable) ================= -->
    @if($certificate->equipment_type === 'crane' || (!empty($certificate->checklist_items) && count($certificate->checklist_items) > 0))
        @php
            $page2Title = $certificate->equipment_type === 'crane' 
                ? 'MOBILE CRANE INSPECTION CHECKLIST' 
                : 'EQUIPMENT INSPECTION CHECKLIST';
        @endphp
        <div class="cert-paper-sheet printable-sheet page-break" dir="ltr">
            
            <!-- Page 2 Title -->
            <div class="text-center mb-3">
                <h2 class="text-base sm:text-lg font-black text-[#0E4DA4] tracking-wide uppercase font-en underline decoration-[#0E4DA4] decoration-2">
                    {{ $page2Title }}
                </h2>
                <p class="text-xs text-slate-600 font-en mt-0.5 font-semibold">
                    Certificate No: <span class="font-bold text-[#0E4DA4] font-mono">{{ $certificate->certificate_number }}</span> &bull; 
                    Date: {{ $certificate->thorough_examination_date?->format('d F Y') }}
                </p>
            </div>

            <!-- Checklist Table -->
            <div class="border-2 border-slate-800 text-xs font-en text-slate-900 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#E2EEFB] text-[#0A3778] border-b border-slate-700 font-bold">
                        <tr>
                            <th class="p-1.5 w-10 text-center border-r border-slate-700">#</th>
                            <th class="p-1.5 border-r border-slate-700">Inspection Item</th>
                            <th class="p-1.5 w-28 text-center border-r border-slate-700">Status</th>
                            <th class="p-1.5 w-72">Remarks / Observations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300">
                        @php 
                            $items = $certificate->checklist_items ?: \App\Models\Certificate::defaultCraneChecklist();
                            $lastCategory = null;
                        @endphp
                        @foreach($items as $chk)
                            @if($lastCategory !== ($chk['category'] ?? ''))
                                @php $lastCategory = $chk['category'] ?? ''; @endphp
                                <tr class="bg-blue-50/80 font-bold text-[#0E4DA4]">
                                    <td colspan="4" class="p-1.5 border-b border-slate-400 uppercase tracking-wider text-[11px]">
                                        {{ $lastCategory }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="hover:bg-slate-50 bg-white">
                                <td class="p-1 text-center font-bold text-slate-600 border-r border-slate-300">
                                    {{ $chk['no'] ?? $loop->iteration }}
                                </td>
                                <td class="p-1 border-r border-slate-300 font-medium">
                                    {{ $chk['item'] ?? $chk['question'] ?? '—' }}
                                </td>
                                <td class="p-1 text-center border-r border-slate-300">
                                    @php 
                                        $st = $chk['status'] ?? $chk['check'] ?? 'Pass'; 
                                    @endphp
                                    @if(in_array(strtolower($st), ['pass', 'in order', 'accepted', 'good']))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E8F7EC] text-[#22A447]">PASS</span>
                                    @elseif(in_array(strtolower($st), ['fail', 'defect', 'rejected']))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">FAIL</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ strtoupper($st) }}</span>
                                    @endif
                                </td>
                                <td class="p-1 text-slate-700">
                                    {{ $chk['remarks'] ?? $chk['comment'] ?? 'Good condition' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Page 2 Signatures & Stamp -->
            <div class="grid grid-cols-3 border border-t-0 border-slate-800 text-center text-xs font-en bg-white">
                <div class="p-2 border-r border-slate-800 flex flex-col justify-between h-24">
                    <span class="text-[9.5px] font-bold text-slate-600">Inspector Signature</span>
                    @if($engSignPath && file_exists(public_path($engSignPath)))
                        <img src="{{ asset($engSignPath) }}" alt="Signature" class="h-8 mx-auto object-contain">
                    @else
                        <img src="{{ asset('branding/eng_signature.svg') }}" alt="Signature" class="h-8 mx-auto object-contain">
                    @endif
                    <span class="font-bold text-[10px] text-slate-900">{{ $certificate->issuer_name }}</span>
                </div>
                <div class="p-1.5 border-r border-slate-800 flex flex-col items-center justify-center">
                    @if($stampPath && file_exists(public_path($stampPath)))
                        <img src="{{ asset($stampPath) }}" alt="Stamp" class="h-16 w-16 object-contain">
                    @else
                        <img src="{{ asset('branding/stamp.svg') }}" alt="Stamp" class="h-16 w-16 object-contain">
                    @endif
                </div>
                <div class="p-2 flex flex-col justify-between h-24">
                    <span class="text-[9.5px] font-bold text-slate-600">Managing Director Signature</span>
                    @if($dirSignPath && file_exists(public_path($dirSignPath)))
                        <img src="{{ asset($dirSignPath) }}" alt="Signature" class="h-8 mx-auto object-contain">
                    @else
                        <img src="{{ asset('branding/director_signature.svg') }}" alt="Signature" class="h-8 mx-auto object-contain">
                    @endif
                    <span class="font-bold text-[10px] text-slate-900">{{ $certificate->authenticator_name }}</span>
                </div>
            </div>

        </div>
    @endif

</div>

<!-- Print Official PDF Script -->
<script>
function printOfficialPdf(url) {
    const printFrame = document.createElement('iframe');
    printFrame.style.position = 'fixed';
    printFrame.style.right = '0';
    printFrame.style.bottom = '0';
    printFrame.style.width = '0';
    printFrame.style.height = '0';
    printFrame.style.border = '0';
    printFrame.src = url;
    document.body.appendChild(printFrame);
    printFrame.onload = function() {
        try {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();
        } catch (e) {
            window.open(url, '_blank');
        }
    };
}
</script>
@endsection
