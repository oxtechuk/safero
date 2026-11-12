@extends('layouts.app')

@section('title', 'شهادة فحص رقم ' . $certificate->certificate_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full {{ $certificate->is_safe_to_operate ? 'bg-[#22A447]' : 'bg-red-500' }}"></span>
            <div>
                <span class="text-xs text-slate-400 block font-en">CERTIFICATE NUMBER</span>
                <span class="text-lg font-black text-[#0E4DA4] font-en">{{ $certificate->certificate_number }}</span>
            </div>
            @if($certificate->is_safe_to_operate)
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#E8F7EC] text-[#22A447] mr-2">
                    معتمدة وآمنة للتشغيل
                </span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Download PDF Button -->
            <a href="{{ route('certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-4 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#0E4DA4]/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>تحميل ملف PDF المعتمد</span>
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

    <!-- Official Certificate Document Container (Printable Paper Style) -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/90 p-8 sm:p-12 print:p-0 print:shadow-none print:border-none print:rounded-none" dir="ltr">
        
        <!-- Document Header -->
        <div class="flex items-center justify-between pb-4 border-b-2 border-[#0E4DA4]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-16 w-auto object-contain">
                <div>
                    <h2 class="text-xl font-black text-[#0E4DA4] font-en tracking-wider leading-none">SAFERO</h2>
                    <p class="text-xs font-semibold text-[#22A447] font-en">Services &amp; Consultations</p>
                    <p class="text-[10px] text-slate-500 font-en mt-0.5">Inspection, Calibration &amp; Environmental Solutions</p>
                </div>
            </div>

            <!-- Dynamic QR Code -->
            <div class="text-center">
                <div class="p-1.5 bg-white border border-slate-300 rounded-lg inline-block shadow-sm">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-24 h-24">
                </div>
                <span class="block text-[9px] font-bold text-slate-500 font-en tracking-wider mt-0.5">SCAN TO VERIFY</span>
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

        <!-- 4-Column Metadata Table (Exact match to Word document) -->
        <div class="border border-slate-400 text-xs font-en text-slate-900">
            
            <!-- Row 1 -->
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

            <!-- Row 2 -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                <div class="p-2 border-r border-slate-400 font-bold">Name and address of Customer</div>
                <div class="p-2 border-r border-slate-400 font-bold">Examination Address</div>
                <div class="p-2 border-r border-slate-400 font-bold">Date of Manufacture</div>
                <div class="p-2 font-bold">Reference Standard</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-400">
                <div class="p-2 border-r border-slate-400 font-semibold">{{ $certificate->customer?->name }}</div>
                <div class="p-2 border-r border-slate-400">{{ $certificate->examination_address }}</div>
                <div class="p-2 border-r border-slate-400">{{ $certificate->date_of_manufacture ?? '—' }}</div>
                <div class="p-2 font-semibold">{{ $certificate->reference_standard }}</div>
            </div>

            <!-- Row 3: Equipment Details -->
            <div class="grid grid-cols-4 border-b border-slate-400 bg-slate-50">
                <div class="col-span-3 p-2 border-r border-slate-400 font-bold">Description and identification of the Equipment</div>
                <div class="p-2 font-bold">Equipment ID</div>
            </div>
            <div class="grid grid-cols-4 border-b border-slate-400">
                <div class="col-span-3 p-3 border-r border-slate-400">
                    <p class="font-bold text-sm text-slate-900 mb-1">{{ $certificate->equipment_description }}</p>
                    <p class="text-[11px] text-slate-600">Dimensions: {{ $certificate->dimensions ?? 'N/A' }}</p>
                    <p class="text-xs font-black text-[#0E4DA4] mt-1">SWL : {{ $certificate->swl }} &nbsp;&nbsp;|&nbsp;&nbsp; Quantity : {{ sprintf('%02d', $certificate->quantity) }}</p>
                </div>
                <div class="p-3 font-semibold flex items-center justify-center">
                    {{ $certificate->equipment_id_range ?? '—' }}
                </div>
            </div>

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
                    <span class="font-bold px-2">{{ $certificate->in_accordance_with_scheme ? 'Yes' : 'No' }}</span>
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

            <!-- Tests Carried Out -->
            <div class="border-b border-slate-400 p-2">
                <span class="font-bold block text-slate-600 mb-0.5">Particulars of any tests are carried out as part of thorough examination, And standard applied:</span>
                <span class="font-bold text-slate-900">{{ $certificate->tests_carried_out }}</span>
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

            <!-- Signatures and Stamp Section (Exact Match to Image) -->
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

</div>
@endsection

