@extends('layouts.app')

@section('title', 'شهادة تدريب - '.$trainingCertificate->certificate_serial)

@section('content')
<div class="space-y-6">

    <!-- Odoo Form View Header & Action Bar -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 py-2.5 -mx-4 sm:-mx-6 lg:-mx-8 -mt-6 mb-4 sticky top-14 z-40 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Left: Breadcrumbs & Action Buttons -->
        <div class="flex items-center gap-3">
            <a href="{{ route('training-certificates.index') }}" 
               class="p-1.5 rounded hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition" 
               title="الرجوع للقائمة">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>

            <a href="{{ route('training-certificates.create') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-[#006837] hover:bg-[#00522c] text-white font-bold text-xs shadow-xs transition">
                <span class="font-en font-black">New</span>
            </a>

            <!-- Breadcrumbs -->
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <a href="{{ route('training-certificates.index') }}" class="hover:underline">شهادات التدريب</a>
                <span>/</span>
                <span class="font-bold text-slate-900 font-mono">{{ $trainingCertificate->certificate_serial }}</span>
            </div>
        </div>

        <!-- Right: Smart Actions & Pipeline Stage Indicator -->
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
            <!-- Smart Action: Print PDF -->
            <a href="{{ route('training-certificates.pdf', $trainingCertificate) }}" 
               target="_blank"
               class="px-3 py-1.5 rounded-md bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>طباعة PDF</span>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('training-certificates.edit', $trainingCertificate) }}" 
               class="px-3 py-1.5 rounded-md border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>تعديل</span>
            </a>

            <!-- Odoo Status Pipeline Arrow Badges -->
            <div class="inline-flex items-center rounded border border-slate-300 overflow-hidden text-[11px] font-bold select-none bg-white">
                <span class="px-2.5 py-1 {{ $trainingCertificate->status === 'draft' ? 'bg-sky-50 text-sky-700 font-extrabold border-r border-slate-200' : 'text-slate-400 border-r border-slate-200' }}">
                    Draft
                </span>
                <span class="px-2.5 py-1 {{ $trainingCertificate->status === 'issued' ? 'bg-[#E8F7EC] text-[#006837] font-extrabold' : 'text-slate-400' }}">
                    ✓ In Progress
                </span>
                @if($trainingCertificate->status === 'revoked')
                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 font-extrabold border-l border-slate-200">
                        Revoked
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- WordPress Integration & Shortcode Box -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-4 sm:p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-[#0E4DA4] text-white text-[11px] font-black tracking-wide">WordPress / Iframe Integration</span>
                    <span class="text-xs font-bold text-slate-700">شورت كود التضمين في موقع الشركة الأساسي</span>
                </div>
                <p class="text-xs text-slate-600">
                    عندما يقوم أي شخص بمسح رمز الـ QR يدخل على صفحة التحقق في موقع الشركة. يتم تضمين الشهادة مباشرة عبر الشورت كود أو رابط الـ iframe التالي:
                </p>
            </div>
            <a href="{{ $embedUrl }}" target="_blank" class="shrink-0 text-xs text-[#0E4DA4] hover:underline font-bold flex items-center gap-1">
                <span>فتح الفريم المباشر</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
            <!-- Iframe Code -->
            <div class="bg-white p-3 rounded-xl border border-blue-200">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-bold text-slate-500 font-mono">كود الـ Iframe المباشر:</span>
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('<iframe src=\'{{ $embedUrl }}\' width=\'100%\' height=\'680\' frameborder=\'0\' style=\'border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08);\'></iframe>'); alert('تم نسخ كود الـ iframe بنجاح!');"
                            class="text-[11px] font-bold text-[#0E4DA4] hover:underline">
                        نسخ الكود
                    </button>
                </div>
                <code class="text-[11px] font-mono text-slate-700 bg-slate-50 p-2 rounded block overflow-x-auto select-all" dir="ltr">
                    &lt;iframe src="{{ $embedUrl }}" width="100%" height="680" frameborder="0"&gt;&lt;/iframe&gt;
                </code>
            </div>

            <!-- Verification URL / Shortcode -->
            <div class="bg-white p-3 rounded-xl border border-blue-200">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-bold text-slate-500 font-mono">رابط التحقق الخاص بالـ QR:</span>
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('{{ $verifyUrl }}'); alert('تم نسخ رابط التحقق بنجاح!');"
                            class="text-[11px] font-bold text-[#0E4DA4] hover:underline">
                        نسخ الرابط
                    </button>
                </div>
                <code class="text-[11px] font-mono text-[#0E4DA4] bg-slate-50 p-2 rounded block overflow-x-auto select-all" dir="ltr">
                    {{ $verifyUrl }}
                </code>
            </div>
        </div>
    </div>

    <!-- Official Certificate Canvas Preview (Matching User's Uploaded Certificate Design) -->
    <div class="bg-white rounded-3xl border border-slate-300 shadow-xl overflow-hidden p-6 sm:p-12 relative max-w-5xl mx-auto" dir="ltr" style="min-height: 640px;">
        
        <!-- Background Shield Watermark Pattern -->
        <div class="absolute inset-0 opacity-[0.035] pointer-events-none flex items-center justify-center">
            <svg class="w-[450px] h-[450px]" viewBox="0 0 24 24" fill="#006837">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.54-3.14 8.78-7 9.87-3.86-1.09-7-5.33-7-9.87V6.3l7-3.12z"/>
            </svg>
        </div>

        <!-- Decorative Green/Gold Top Right Wave Accent -->
        <div class="absolute -top-1 -right-1 w-64 h-48 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 200 150" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 50 0 C 120 20, 150 70, 200 60 L 200 0 Z" fill="#FBB03B" opacity="0.9"/>
                <path d="M 90 0 C 140 30, 160 80, 200 90 L 200 0 Z" fill="#006837"/>
            </svg>
        </div>

        <!-- Decorative Green/Gold Bottom Left Wave Accent -->
        <div class="absolute -bottom-1 -left-1 w-72 h-52 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 220 160" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 0 60 C 50 110, 130 120, 220 160 L 0 160 Z" fill="#FBB03B" opacity="0.85"/>
                <path d="M 0 90 C 60 120, 120 135, 180 160 L 0 160 Z" fill="#006837"/>
            </svg>
        </div>

        <!-- Decorative Bottom Right Wave Accent -->
        <div class="absolute -bottom-1 -right-1 w-64 h-36 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 200 120" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 0 120 C 70 80, 130 110, 200 90 L 200 120 Z" fill="#006837"/>
                <path d="M 40 120 C 100 95, 150 115, 200 105 L 200 120 Z" fill="#FBB03B"/>
            </svg>
        </div>

        <!-- Content Container -->
        <div class="relative z-10">

            <!-- Top Header: Logo -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-16 w-auto object-contain">
                    <div>
                        <h2 class="text-xl font-black text-[#006837] tracking-wider leading-none font-en">SAFERO</h2>
                        <p class="text-xs font-bold text-[#0E4DA4] font-en">Services &amp; Consultations</p>
                    </div>
                </div>
            </div>

            <!-- Certificate Big Heading -->
            <div class="text-center mt-4">
                <h1 class="text-4xl sm:text-5xl font-black text-[#006837] tracking-[0.2em] font-serif uppercase">
                    CERTIFICATE
                </h1>
                
                <!-- Subtitle with horizontal rules -->
                <div class="flex items-center justify-center gap-4 my-2">
                    <span class="w-20 h-0.5 bg-[#006837]"></span>
                    <span class="text-sm font-bold text-[#006837] tracking-wider">This Certificate Presents</span>
                    <span class="w-20 h-0.5 bg-[#006837]"></span>
                </div>

                <span class="block text-sm text-[#006837] font-semibold my-1">to</span>

                <!-- Certificate Holder Name -->
                <div class="max-w-xl mx-auto my-3 pb-1 border-b-2 border-slate-800">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-wide">
                        {{ $trainingCertificate->trainee_name }}
                    </h2>
                </div>

                <!-- Course Description -->
                <p class="text-xs sm:text-sm text-slate-700 font-semibold mt-3">
                    Who has successfully completed the course of
                </p>

                <!-- Program Title -->
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-2 tracking-wide">
                    {{ $trainingCertificate->program_title }}
                </h3>

                @if($trainingCertificate->training_scope && $trainingCertificate->training_scope !== $trainingCertificate->program_title)
                    <p class="text-xs text-slate-600 mt-0.5">
                        Scope: <span class="font-bold">{{ $trainingCertificate->training_scope }}</span> ({{ $trainingCertificate->hours }} Hours)
                    </p>
                @endif

                <!-- Meta Dates -->
                <div class="flex items-center justify-center gap-8 sm:gap-14 text-xs font-bold text-slate-800 mt-3 font-mono">
                    <div>
                        <span>On the date :- </span>
                        <span class="underline decoration-slate-400 font-en">{{ $trainingCertificate->issue_date->format('d/m/Y') }}</span>
                    </div>
                    @if($trainingCertificate->expiry_date)
                        <div>
                            <span>expire on :- </span>
                            <span class="underline decoration-slate-400 font-en">{{ $trainingCertificate->expiry_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Bottom Section: Signatures, Accreditation & QR Code -->
            <div class="grid grid-cols-3 items-end mt-12 pt-4">

                <!-- Left: Instructor & Accreditation -->
                <div class="text-left space-y-3">
                    <div>
                        <div class="h-10 flex items-center">
                            <span class="font-serif italic text-lg text-slate-700">{{ $trainingCertificate->instructor_name }}</span>
                        </div>
                        <div class="w-36 border-t border-slate-700 pt-1">
                            <span class="block text-[11px] font-bold text-slate-800">Instructor Signature</span>
                        </div>
                    </div>

                    <!-- Accreditation note (bottom left) -->
                    <div class="text-[9px] text-slate-600 leading-tight pt-2">
                        <p class="font-bold text-slate-700">Safero services &amp; consultation is accredited by</p>
                        <p>Ministry of Industry</p>
                        <p>Egyptian organization for standardization &amp; quality</p>
                        <p>National quality Institute</p>
                        <p class="font-bold text-[#006837]">NQIT 347/686</p>
                    </div>
                </div>

                <!-- Center: QR Code & Serial -->
                <div class="text-center">
                    <div class="inline-block p-1.5 bg-white border border-slate-300 rounded-lg shadow-sm">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-24 h-24 mx-auto">
                    </div>
                    <div class="mt-1.5">
                        <span class="block text-xs font-bold text-slate-900 font-mono tracking-wider">
                            Certificate # {{ $trainingCertificate->certificate_serial }}
                        </span>
                        <a href="{{ $verifyUrl }}" target="_blank" class="text-[9.5px] text-[#006837] hover:underline font-mono block mt-0.5 break-all">
                            {{ $verifyUrl }}
                        </a>
                    </div>
                </div>

                <!-- Right: Training Manager & Stamp -->
                <div class="text-right flex flex-col items-end">
                    <!-- Stamp simulation -->
                    <div class="relative mb-2 pr-2">
                        <div class="border-2 border-[#0E4DA4] text-[#0E4DA4] rounded-lg p-2 text-center text-[10px] font-black uppercase tracking-wider rotate-[-6deg] bg-white/80 shadow-sm inline-block">
                            <div class="text-[11px] italic font-serif leading-none">{{ $trainingCertificate->training_manager_name }}</div>
                            <div class="border-t border-[#0E4DA4] my-0.5"></div>
                            <div>SAFERO</div>
                            <div class="text-[8px]">Training Manager</div>
                        </div>
                    </div>
                    <div class="w-36 border-t border-slate-700 pt-1 text-center">
                        <span class="block text-[11px] font-bold text-slate-800">Training Manager</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

