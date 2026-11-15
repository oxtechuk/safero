@extends('layouts.app')

@section('title', 'عرض شهادة الفحص - ' . $certificate->certificate_number)

@section('content')
<!-- Print & Certificate Sheet Stylesheet -->
<style>
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
        padding-top: 135px;
        padding-bottom: 90px;
        padding-left: 36px;
        padding-right: 36px;
        min-height: 1216px;
        box-sizing: border-box;
    }
    @media (max-width: 640px) {
        .cert-paper-sheet {
            padding-top: 95px;
            padding-bottom: 65px;
            padding-left: 12px;
            padding-right: 12px;
            min-height: auto;
        }
    }
    @media print {
        header, nav, aside, footer, .no-print {
            display: none !important;
        }
        body {
            background: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .cert-paper-sheet {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding-top: 135px !important;
            padding-bottom: 90px !important;
            padding-left: 32px !important;
            padding-right: 32px !important;
            width: 100% !important;
            max-width: 100% !important;
            min-height: 100vh !important;
            page-break-after: always;
            background-image: url('{{ asset(file_exists(public_path("build/assets/cert-inspect.pdf.jpg")) ? "build/assets/cert-inspect.pdf.jpg" : "branding/cert_inspect_bg.jpg") }}') !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .page-break {
            page-break-before: always;
        }
    }
</style>

<!-- Breadcrumb & Top Bar -->
<div class="mb-4 no-print">
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
        <a href="{{ route('certificates.index') }}" class="hover:text-[#0E4DA4]">شهادات الفحص</a>
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
                <span class="text-xs text-slate-400 block font-en uppercase tracking-wider">CERTIFICATE NUMBER</span>
                <span class="text-lg font-black text-[#0E4DA4] font-en">{{ $certificate->certificate_number }}</span>
            </div>
            @if($certificate->is_safe_to_operate)
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#E8F7EC] text-[#22A447] mr-2">
                    معتمدة وآمنة للتشغيل
                </span>
            @endif
            @if($certificate->equipment_type === 'crane')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0E4DA4] border border-blue-200">
                    <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-3.5 h-3.5 object-contain">
                    <span>ونش / رافعة (تقرير صفحتين)</span>
                </span>
            @elseif($certificate->equipment_type === 'accessory')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-3.5 h-3.5 object-contain">
                    <span>مهمات رفع (وايرات / حبال)</span>
                </span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Download PDF Button -->
            <a href="{{ route('certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-4 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#0E4DA4]/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>تحميل PDF المعتمد (ثيم Safero)</span>
            </a>

            <!-- Print Button -->
            <button type="button" 
                    onclick="window.print()"
                    class="px-3.5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة</span>
            </button>

            <!-- Public Verification Link -->
            <a href="{{ route('certificates.verify', $certificate->uuid) }}" 
               target="_blank"
               class="px-3.5 py-2.5 rounded-xl border border-[#22A447]/30 bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white font-bold text-xs sm:text-sm transition flex items-center gap-1.5"
               title="رابط التحقق العام عبر الـ QR">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>صفحة التحقق (QR)</span>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('certificates.edit', $certificate) }}" 
               class="px-3 py-2.5 rounded-xl border border-slate-200 hover:bg-amber-50 text-slate-700 hover:text-amber-600 font-semibold text-xs sm:text-sm transition">
                تعديل
            </a>
        </div>
    </div>

    <!-- ================= PAGE 1: Thorough Examination Report ================= -->
    <div class="cert-paper-sheet printable-sheet" dir="ltr">
        
        <!-- Header Strip: QR Code & Certificate Badge -->
        <div class="flex items-center justify-between pb-3 mb-2 border-b border-slate-300/80">
            <div>
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">OFFICIAL CERTIFICATE</span>
                <span class="text-sm sm:text-base font-black text-[#0E4DA4] font-mono tracking-wider">{{ $certificate->certificate_number }}</span>
                <span class="block text-[10px] text-slate-500 font-medium">Standard: {{ $certificate->reference_standard }}</span>
            </div>

            <!-- Dynamic QR Code -->
            <div class="text-center">
                <div class="p-1 bg-white border border-slate-300 rounded shadow-xs inline-block">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-16 h-16 sm:w-20 sm:h-20">
                </div>
                <span class="block text-[8px] font-bold text-slate-500 font-en tracking-wider mt-0.5">SCAN TO VERIFY</span>
            </div>
        </div>
        <!-- Official Report Title -->
        <div class="text-center my-4">
            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-wide uppercase font-en underline decoration-[#0E4DA4] decoration-2">
                REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT
            </h1>
            <p class="text-[11px] text-slate-600 font-en italic mt-1">
                This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998
            </p>
        </div>

        <!-- Metadata Table (Exact match to Word / PDF document) -->
        <div class="border border-slate-400 text-xs font-en text-slate-900">
            
            <!-- Row 1: Certificate Meta -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                <div class="p-2 border-r border-slate-400 font-bold">Thorough Examination Date</div>
                <div class="p-2 border-r border-slate-400 font-bold">Certificate Number</div>
                <div class="p-2 border-r border-slate-400 font-bold">Next Examination Due Date</div>
                <div class="p-2 font-bold">Order No</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400">{{ $certificate->thorough_examination_date->format('d/m/Y') }}</div>
                <div class="p-2 border-r border-slate-400 font-black text-[#0E4DA4]">{{ $certificate->certificate_number }}</div>
                <div class="p-2 border-r border-slate-400 font-bold">{{ $certificate->next_examination_due_date->format('d/m/Y') }}</div>
                <div class="p-2">{{ $certificate->order_no ?? '—' }}</div>
            </div>

            <!-- Row 2: Customer & Standard -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                <div class="p-2 border-r border-slate-400 font-bold">Name and address of Customer</div>
                <div class="p-2 border-r border-slate-400 font-bold">Examination Address</div>
                <div class="p-2 border-r border-slate-400 font-bold">
                    @if($certificate->equipment_type === 'accessory')
                        Manufacturer
                    @else
                        Date of Manufacture
                    @endif
                </div>
                <div class="p-2 font-bold">Reference Standard</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400">
                    <p class="font-bold text-slate-900">{{ $certificate->customer?->name }}</p>
                    @if($certificate->customer?->address && $certificate->customer->address !== $certificate->examination_address)
                        <p class="text-[10px] text-slate-500">{{ $certificate->customer->address }}</p>
                    @endif
                </div>
                <div class="p-2 border-r border-slate-400">{{ $certificate->examination_address ?: 'Owner Yard' }}</div>
                <div class="p-2 border-r border-slate-400">
                    @if($certificate->equipment_type === 'accessory')
                        {{ $certificate->manufacturer ?: 'Lifting safety' }}
                    @else
                        {{ $certificate->date_of_manufacture ?? '—' }}
                    @endif
                </div>
                <div class="p-2 font-semibold">{{ $certificate->reference_standard }}</div>
            </div>

            <!-- Row 3: Equipment Details -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                <div class="col-span-3 p-2 border-r border-slate-400 font-bold">Description and identification of the Equipment</div>
                <div class="p-2 font-bold text-center">Equipment ID</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-400">
                <div class="col-span-3 p-3 border-r border-slate-400">
                    <p class="font-bold text-sm text-slate-900 mb-1.5">{{ $certificate->equipment_description }}</p>

                    @if($certificate->equipment_type === 'crane')
                        <!-- Crane Specifications Breakdown -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-1 text-xs text-slate-700 bg-slate-50/70 p-2.5 rounded-lg border border-slate-200">
                            @if($certificate->manufacturer)
                                <div><strong class="text-slate-900">Manufacturer:</strong> {{ $certificate->manufacturer }}</div>
                            @endif
                            @if($certificate->model)
                                <div><strong class="text-slate-900">Model:</strong> {{ $certificate->model }}</div>
                            @endif
                            @if($certificate->capacity)
                                <div><strong class="text-slate-900">Capacity:</strong> {{ $certificate->capacity }}</div>
                            @endif
                            @if($certificate->chassis_no)
                                <div><strong class="text-slate-900">Chassis No.:</strong> <span class="font-bold text-[#0E4DA4]">{{ $certificate->chassis_no }}</span></div>
                            @endif
                            @if($certificate->main_wire)
                                <div><strong class="text-slate-900">Main Wire:</strong> {{ $certificate->main_wire }}</div>
                            @endif
                            @if($certificate->hook_block)
                                <div><strong class="text-slate-900">Hook Block:</strong> {{ $certificate->hook_block }}</div>
                            @endif
                        </div>
                    @elseif($certificate->equipment_type === 'accessory')
                        <!-- Lifting Accessories Breakdown -->
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-700 bg-slate-50/70 p-2.5 rounded-lg border border-slate-200">
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
                                <p class="mb-1"><strong class="text-slate-900">Dimensions:</strong> {{ $certificate->dimensions }}</p>
                            @endif
                            <p class="font-black text-[#0E4DA4]">SWL : {{ $certificate->swl }} &nbsp;&nbsp;|&nbsp;&nbsp; Quantity : {{ sprintf('%02d', $certificate->quantity) }}</p>
                        </div>
                    @endif
                </div>

                <div class="p-3 font-semibold flex items-center justify-center text-center">
                    <span class="font-bold text-xs text-[#0E4DA4] tracking-wider">{{ $certificate->equipment_id_range ?? '—' }}</span>
                </div>
            </div>

            <!-- Optional Row: Load Test Details (For Cranes or when load test data is present) -->
            @if($certificate->equipment_type === 'crane' || $certificate->load_test_cert_no || $certificate->date_of_load_test)
                <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                    <div class="p-2 border-r border-slate-400 font-bold">Load Test Certificate Number</div>
                    <div class="p-2 border-r border-slate-400 font-bold">Date of Load Test</div>
                    <div class="p-2 border-r border-slate-400 font-bold">Safe Working Load</div>
                    <div class="p-2 font-bold">Date of Last thorough examination</div>
                </div>
                <div class="grid grid-cols-4 border-b border-slate-400">
                    <div class="p-2 border-r border-slate-400 font-semibold">{{ $certificate->load_test_cert_no ?: 'NONE' }}</div>
                    <div class="p-2 border-r border-slate-400">{{ $certificate->date_of_load_test?->format('d/m/Y') ?: '—' }}</div>
                    <div class="p-2 border-r border-slate-400 font-bold text-[#0E4DA4]">{{ $certificate->swl }}</div>
                    <div class="p-2">{{ $certificate->last_examination_date?->format('d/m/Y') ?: '—' }}</div>
                </div>
            @endif

            <!-- Questions Checklist Table -->
            <div class="grid grid-cols-2 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400 flex justify-between items-center">
                    <span>Is this first examination after installation?</span>
                    <span class="font-bold px-2">{{ $certificate->is_first_examination_after_installation ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-2 flex justify-between items-center">
                    <span>Was the examination carried out within 6 months?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->was_examination_within_6_months ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400 flex justify-between items-center">
                    <span>Has the Equipment been installed correctly?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->has_equipment_installed_correctly ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-2 flex justify-between items-center">
                    <span>Was the examination carried out within 12 months?</span>
                    <span class="font-bold px-2">{{ $certificate->was_examination_within_12_months ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400 flex justify-between items-center">
                    <span>In Accordance with examination scheme?</span>
                    <span class="font-bold px-2 text-[#22A447]">{{ $certificate->in_accordance_with_scheme ? 'Yes' : 'No' }}</span>
                </div>
                <div class="p-2 flex justify-between items-center">
                    <span>After the occurrence of exceptional circumstances?</span>
                    <span class="font-bold px-2">{{ $certificate->after_exceptional_circumstances ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <!-- Defects & Danger -->
            <div class="grid grid-cols-2 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400">
                    <span class="font-bold block text-slate-600 mb-0.5">Identification of any part found to have defect:</span>
                    <span class="font-bold text-slate-800">{{ $certificate->defect_identification ?? 'NONE' }}</span>
                </div>
                <div class="p-2">
                    <span class="font-bold block text-slate-600 mb-0.5">Is the defect danger to process:</span>
                    <span class="font-bold text-slate-800">{{ $certificate->is_defect_danger_to_process ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <!-- Repairs -->
            <div class="border-b border-slate-400 p-2">
                <span class="font-bold block text-slate-600 mb-0.5">Particulars of any repair, renewal or alteration required to remedy the defect identified above:</span>
                <span class="font-bold text-slate-800">{{ $certificate->repair_particulars ?? 'NONE' }}</span>
            </div>

            <!-- Tests Carried Out & Test Point -->
            <div class="border-b border-slate-400 p-2">
                <span class="font-bold block text-slate-600 mb-0.5">Particulars of any tests are carried out as part of thorough examination, And standard applied:</span>
                <p class="font-bold text-slate-900">{{ $certificate->tests_carried_out }}</p>
                @if($certificate->test_point)
                    <p class="text-[11px] text-slate-600 mt-1 font-semibold">Test Point: {{ $certificate->test_point }}</p>
                @endif
            </div>

            <!-- Is Safe to Operate Decision -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50 p-2 items-center">
                <div class="col-span-3 font-bold text-sm text-slate-900">
                    Is the Equipment Safe to Operate
                </div>
                <div class="font-black text-sm text-center {{ $certificate->is_safe_to_operate ? 'text-[#22A447]' : 'text-red-600' }}">
                    {{ $certificate->is_safe_to_operate ? 'YES' : 'NO' }}
                </div>
            </div>

            <!-- Signatures and Stamp Section -->
            <div class="grid grid-cols-3 border-b border-slate-400 text-center">
                <!-- Inspector -->
                <div class="p-3 border-r border-slate-400 flex flex-col justify-between h-36">
                    <span class="text-[11px] font-bold text-slate-600">Name and Signature of the person issue this certificate</span>
                    <div class="my-auto">
                        <img src="{{ asset('branding/eng_signature.svg') }}" alt="Engineer Signature" class="h-12 mx-auto object-contain">
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->issuer_name }}</span>
                </div>

                <!-- Safero Stamp -->
                <div class="p-2 border-r border-slate-400 flex flex-col items-center justify-center relative">
                    <span class="text-[10px] font-bold text-slate-400 mb-1">OFFICIAL STAMP</span>
                    <img src="{{ asset('branding/stamp.svg') }}" alt="Safero Stamp" class="h-28 w-28 object-contain">
                </div>

                <!-- Authenticator -->
                <div class="p-3 flex flex-col justify-between h-36">
                    <span class="text-[11px] font-bold text-slate-600">Name and Signature of the person authenticating this certificate</span>
                    <div class="my-auto">
                        <img src="{{ asset('branding/director_signature.svg') }}" alt="Director Signature" class="h-12 mx-auto object-contain">
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->authenticator_name }}</span>
                </div>
            </div>

            <!-- Conditions & Disclaimer Paragraph -->
            <div class="p-2.5 text-[9.5px] leading-tight text-slate-600 text-justify">
                This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees. Under no circumstances shall Safero be liable for any injury or damage to any person occurring by reason of negligent operation or any defect in materials, machinery, equipment or other items other than those defects ascertainable by normally accepted testing standards and only those items actually inspected by Safero and which are covered by this certificate.
            </div>

        </div>

        <!-- Verification URL info footer -->
        <div class="mt-4 pt-3 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 font-en">
            <div>
                Official digital verification URL: 
                <a href="{{ $verifyUrl }}" target="_blank" class="text-[#0E4DA4] font-bold underline">{{ $verifyUrl }}</a>
            </div>
            <div>
                UUID: {{ $certificate->uuid }}
            </div>
        </div>

    </div>

    <!-- ================= PAGE 2: Crane Inspection Checklist (When Applicable) ================= -->
    @if($certificate->equipment_type === 'crane' || !empty($certificate->checklist_items))
        <div class="cert-paper-sheet printable-sheet page-break" dir="ltr">
            
            <!-- Header Strip Page 2 -->
            <div class="flex items-center justify-between pb-3 mb-2 border-b border-slate-300/80">
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">ATTACHMENT TO CERTIFICATE</span>
                    <span class="text-sm sm:text-base font-black text-[#0E4DA4] font-mono">{{ $certificate->certificate_number }}</span>
                </div>
                <div class="text-right text-xs font-en font-bold text-slate-600">
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">Page 2 of 2</span>
                </div>
            </div>
            <!-- Page 2 Title -->
            <div class="text-center my-4">
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-wide uppercase font-en underline decoration-[#0E4DA4] decoration-2">
                    MOBILE CRANE INSPECTION CHECKLIST
                </h2>
                <p class="text-xs text-slate-600 font-en mt-1 font-semibold">
                    Crane: {{ $certificate->equipment_description }} &bull; Model: {{ $certificate->model ?? '—' }} &bull; Chassis No: {{ $certificate->chassis_no ?? '—' }}
                </p>
            </div>

            <!-- 27-Point Checklist Table -->
            <div class="border border-slate-400 text-xs font-en text-slate-900 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-400 font-bold">
                        <tr>
                            <th class="p-2 w-10 text-center border-r border-slate-400">#</th>
                            <th class="p-2 border-r border-slate-400">Inspection Item</th>
                            <th class="p-2 w-28 text-center border-r border-slate-400">Status</th>
                            <th class="p-2 w-72">Remarks / Observations</th>
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
                                    <td colspan="4" class="p-2 border-b border-slate-400 uppercase tracking-wider text-[11px]">
                                        {{ $lastCategory }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="hover:bg-slate-50">
                                <td class="p-1.5 text-center font-bold text-slate-600 border-r border-slate-300">
                                    {{ $chk['no'] ?? $loop->iteration }}
                                </td>
                                <td class="p-1.5 border-r border-slate-300 font-medium">
                                    {{ $chk['item'] ?? $chk['question'] ?? '—' }}
                                </td>
                                <td class="p-1.5 text-center border-r border-slate-300">
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
                                <td class="p-1.5 text-slate-700">
                                    {{ $chk['remarks'] ?? $chk['comment'] ?? 'Good condition' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Page 2 Signatures -->
            <div class="grid grid-cols-3 border border-t-0 border-slate-400 text-center text-xs font-en">
                <div class="p-3 border-r border-slate-400 flex flex-col justify-between h-28">
                    <span class="text-[10px] font-bold text-slate-600">Inspector Signature</span>
                    <img src="{{ asset('branding/eng_signature.svg') }}" alt="Signature" class="h-9 mx-auto object-contain">
                    <span class="font-bold text-[11px] text-slate-900">{{ $certificate->issuer_name }}</span>
                </div>
                <div class="p-2 border-r border-slate-400 flex flex-col items-center justify-center">
                    <img src="{{ asset('branding/stamp.svg') }}" alt="Stamp" class="h-20 w-20 object-contain">
                </div>
                <div class="p-3 flex flex-col justify-between h-28">
                    <span class="text-[10px] font-bold text-slate-600">Managing Director Signature</span>
                    <img src="{{ asset('branding/director_signature.svg') }}" alt="Signature" class="h-9 mx-auto object-contain">
                    <span class="font-bold text-[11px] text-slate-900">{{ $certificate->authenticator_name }}</span>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
