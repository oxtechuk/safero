<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate {{ $certificate->certificate_number }} - Safero</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', 'Cairo', system-ui, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 10px;
        }
    </style>
</head>
<body class="text-slate-900 antialiased" dir="ltr">

    <!-- Top Action Bar for WordPress Iframe -->
    <div class="max-w-4xl mx-auto mb-4 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $certificate->is_safe_to_operate ? 'bg-[#22A447]' : 'bg-red-500' }}"></span>
            <span class="font-bold text-[#0E4DA4] font-en">{{ $certificate->certificate_number }}</span>
            @if($certificate->is_safe_to_operate)
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                    Officially Verified &amp; Safe to Operate
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-3 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Download PDF</span>
            </a>
            <button type="button" 
                    onclick="window.print()"
                    class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Official Certificate Document Container -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-300 rounded-xl p-6 sm:p-8 shadow-sm">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b-2 border-[#0E4DA4]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-14 w-auto object-contain">
                <div>
                    <h2 class="text-xl font-black text-[#0E4DA4] tracking-wider leading-none">SAFERO</h2>
                    <p class="text-xs font-semibold text-[#22A447]">Services &amp; Consultations</p>
                    <p class="text-[9.5px] text-slate-500 mt-0.5">Inspection, Calibration &amp; ISO Solutions</p>
                </div>
            </div>

            <!-- Dynamic QR Code -->
            <div class="text-center">
                <div class="p-1 bg-white border border-slate-300 rounded inline-block">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-20 h-20">
                </div>
                <span class="block text-[8px] font-bold text-slate-500 tracking-wider mt-0.5">SCAN TO VERIFY</span>
            </div>
        </div>

        <!-- Official Report Title -->
        <div class="text-center my-3">
            <h1 class="text-sm sm:text-base font-black text-slate-900 tracking-wide uppercase underline decoration-[#0E4DA4] decoration-2">
                REPORT THOROUGH EXAMINATION OF LIFTING EQUIPMENT
            </h1>
            <p class="text-[10px] text-slate-600 italic mt-0.5">
                This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998
            </p>
        </div>

        <!-- 4-Column Metadata Table -->
        <div class="border border-slate-400 text-xs text-slate-900">
            
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
                <div class="col-span-3 p-2.5 border-r border-slate-400">
                    <p class="font-bold text-xs text-slate-900 mb-0.5">{{ $certificate->equipment_description }}</p>
                    <p class="text-[10px] text-slate-600">Dimensions: {{ $certificate->dimensions ?? 'N/A' }}</p>
                    <p class="text-xs font-black text-[#0E4DA4] mt-1">SWL : {{ $certificate->swl }} &nbsp;&nbsp;|&nbsp;&nbsp; Quantity : {{ sprintf('%02d', $certificate->quantity) }}</p>
                </div>
                <div class="p-2.5 font-semibold flex items-center justify-center">
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
                    <span class="font-bold block text-slate-600 text-[10px]">Identification of any part found to have defect:</span>
                    <span class="font-bold text-slate-800">{{ $certificate->defect_identification ?? 'NONE' }}</span>
                </div>
                <div class="p-2">
                    <span class="font-bold block text-slate-600 text-[10px]">Is the defect danger to process:</span>
                    <span class="font-bold text-slate-800">{{ $certificate->is_defect_danger_to_process ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            <!-- Repairs -->
            <div class="border-b border-slate-400 p-2">
                <span class="font-bold block text-slate-600 text-[10px]">Particulars of any repair, renewal or alteration required to remedy the defect:</span>
                <span class="font-bold text-slate-800">{{ $certificate->repair_particulars ?? 'NONE' }}</span>
            </div>

            <!-- Tests Carried Out -->
            <div class="border-b border-slate-400 p-2">
                <span class="font-bold block text-slate-600 text-[10px]">Particulars of any tests are carried out as part of thorough examination:</span>
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

            <!-- Signatures and Stamp Section -->
            <div class="grid grid-cols-3 border-b border-slate-400 text-center">
                <!-- Inspector -->
                <div class="p-2 border-r border-slate-400 flex flex-col justify-between h-32">
                    <span class="text-[10px] font-bold text-slate-600">Name and Signature of the person issue this certificate</span>
                    <div class="my-auto">
                        <img src="{{ asset(setting('engineer_signature_path', 'branding/eng_signature.svg')) }}" alt="Engineer Signature" class="h-10 mx-auto object-contain">
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->issuer_name }}</span>
                </div>

                <!-- Safero Stamp -->
                <div class="p-1 border-r border-slate-400 flex flex-col items-center justify-center relative">
                    <img src="{{ asset(setting('stamp_path', 'branding/stamp.svg')) }}" alt="Safero Stamp" class="h-24 w-24 object-contain">
                </div>

                <!-- Authenticator -->
                <div class="p-2 flex flex-col justify-between h-32">
                    <span class="text-[10px] font-bold text-slate-600">Name and Signature of the person authenticating this certificate</span>
                    <div class="my-auto">
                        <img src="{{ asset(setting('director_signature_path', 'branding/director_signature.svg')) }}" alt="Director Signature" class="h-10 mx-auto object-contain">
                    </div>
                    <span class="font-bold text-xs text-slate-900">{{ $certificate->authenticator_name }}</span>
                </div>
            </div>

            <!-- Conditions & Disclaimer Paragraph -->
            <div class="p-2 text-[9px] leading-tight text-slate-600 text-justify">
                This certificate is granted subject to the condition that it is understood and agreed that nothing herein contained shall be deemed to relieve any designer, manufacturer, seller, repairer or operator of any warranty, expressed or implied and Safero liability shall be limited to the willful negligence or deliberate omissions of its employees.
            </div>

        </div>

    </div>

</body>
</html>

