<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Training Certificate {{ $certificate->certificate_serial }} - Safero</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', 'Cairo', system-ui, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 12px;
        }
    </style>
</head>
<body class="text-slate-900 antialiased" dir="ltr">

    <!-- Top Action Bar for WordPress Iframe -->
    <div class="max-w-4xl mx-auto mb-3 p-2.5 bg-white border border-slate-200 rounded-xl flex items-center justify-between gap-3 text-xs shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $certificate->status === 'issued' ? 'bg-[#22A447]' : 'bg-amber-500' }}"></span>
            <span class="font-bold text-[#006837] font-mono">Certificate #{{ $certificate->certificate_serial }}</span>
            @if($certificate->status === 'issued')
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                    Officially Verified &amp; Accredited
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('training-certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-3 py-1.5 rounded-lg bg-[#006837] hover:bg-[#00522c] text-white font-bold transition flex items-center gap-1.5">
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

    <!-- Official Certificate Canvas matching User's Image -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-300 rounded-2xl p-6 sm:p-10 shadow-md relative overflow-hidden">
        
        <!-- Background Shield Watermark Pattern -->
        <div class="absolute inset-0 opacity-[0.035] pointer-events-none flex items-center justify-center">
            <svg class="w-[420px] h-[420px]" viewBox="0 0 24 24" fill="#006837">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.54-3.14 8.78-7 9.87-3.86-1.09-7-5.33-7-9.87V6.3l7-3.12z"/>
            </svg>
        </div>

        <!-- Decorative Green/Gold Top Right Wave Accent -->
        <div class="absolute -top-1 -right-1 w-56 h-40 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 200 150" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 50 0 C 120 20, 150 70, 200 60 L 200 0 Z" fill="#FBB03B" opacity="0.9"/>
                <path d="M 90 0 C 140 30, 160 80, 200 90 L 200 0 Z" fill="#006837"/>
            </svg>
        </div>

        <!-- Decorative Green/Gold Bottom Left Wave Accent -->
        <div class="absolute -bottom-1 -left-1 w-64 h-44 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 220 160" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 0 60 C 50 110, 130 120, 220 160 L 0 160 Z" fill="#FBB03B" opacity="0.85"/>
                <path d="M 0 90 C 60 120, 120 135, 180 160 L 0 160 Z" fill="#006837"/>
            </svg>
        </div>

        <!-- Decorative Bottom Right Wave Accent -->
        <div class="absolute -bottom-1 -right-1 w-56 h-32 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 200 120" class="w-full h-full" preserveAspectRatio="none">
                <path d="M 0 120 C 70 80, 130 110, 200 90 L 200 120 Z" fill="#006837"/>
                <path d="M 40 120 C 100 95, 150 115, 200 105 L 200 120 Z" fill="#FBB03B"/>
            </svg>
        </div>

        <div class="relative z-10">

            <!-- Top Header: Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-14 w-auto object-contain">
                <div>
                    <h2 class="text-lg font-black text-[#006837] tracking-wider leading-none">SAFERO</h2>
                    <p class="text-[11px] font-bold text-[#0E4DA4]">Services &amp; Consultations</p>
                </div>
            </div>

            <!-- Certificate Heading -->
            <div class="text-center mt-3">
                <h1 class="text-3xl sm:text-4xl font-black text-[#006837] tracking-[0.2em] font-serif uppercase">
                    CERTIFICATE
                </h1>
                
                <div class="flex items-center justify-center gap-3 my-1.5">
                    <span class="w-16 h-0.5 bg-[#006837]"></span>
                    <span class="text-xs sm:text-sm font-bold text-[#006837] tracking-wider">This Certificate Presents</span>
                    <span class="w-16 h-0.5 bg-[#006837]"></span>
                </div>

                <span class="block text-xs sm:text-sm text-[#006837] font-semibold my-0.5">to</span>

                <!-- Certificate Holder -->
                <div class="max-w-md mx-auto my-2 pb-1 border-b-2 border-slate-800">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-wide">
                        {{ $certificate->trainee_name }}
                    </h2>
                </div>

                <p class="text-xs text-slate-700 font-semibold mt-2">
                    Who has successfully completed the course of
                </p>

                <!-- Program Title -->
                <h3 class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                    {{ $certificate->program_title }}
                </h3>

                <!-- Dates -->
                <div class="flex items-center justify-center gap-6 sm:gap-10 text-[11px] font-bold text-slate-800 mt-2 font-mono">
                    <div>
                        <span>On the date :- </span>
                        <span class="underline decoration-slate-400">{{ $certificate->issue_date->format('d/m/Y') }}</span>
                    </div>
                    @if($certificate->expiry_date)
                        <div>
                            <span>expire on :- </span>
                            <span class="underline decoration-slate-400">{{ $certificate->expiry_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Bottom: Signatures, QR, Stamp -->
            <div class="grid grid-cols-3 items-end mt-8 pt-2">

                <!-- Left -->
                <div class="text-left space-y-2">
                    <div>
                        <span class="font-serif italic text-base text-slate-700 block">{{ $certificate->instructor_name }}</span>
                        <div class="w-32 border-t border-slate-700 pt-0.5">
                            <span class="block text-[10px] font-bold text-slate-800">Instructor Signature</span>
                        </div>
                    </div>

                    <div class="text-[8.5px] text-slate-600 leading-tight">
                        <p class="font-bold text-slate-700">Safero services &amp; consultation is accredited by</p>
                        <p>Ministry of Industry</p>
                        <p>Egyptian organization for standardization &amp; quality</p>
                        <p>National quality Institute</p>
                        <p class="font-bold text-[#006837]">NQIT 347/686</p>
                    </div>
                </div>

                <!-- Center: QR Code -->
                <div class="text-center">
                    <div class="inline-block p-1 bg-white border border-slate-300 rounded shadow-sm">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-20 h-20 mx-auto">
                    </div>
                    <div class="mt-1">
                        <span class="block text-[11px] font-bold text-slate-900 font-mono">
                            Certificate # {{ $certificate->certificate_serial }}
                        </span>
                        <span class="text-[8.5px] text-[#006837] font-mono block break-all">
                            https://safero-eg.com/certificate-verification/
                        </span>
                    </div>
                </div>

                <!-- Right: Stamp & Training Manager -->
                <div class="text-right flex flex-col items-end">
                    <div class="relative mb-1 pr-1">
                        <div class="border border-[#0E4DA4] text-[#0E4DA4] rounded p-1.5 text-center text-[9px] font-black uppercase tracking-wider rotate-[-6deg] bg-white/90 inline-block">
                            <div class="text-[10px] italic font-serif leading-none">{{ $certificate->training_manager_name }}</div>
                            <div class="border-t border-[#0E4DA4] my-0.5"></div>
                            <div>SAFERO</div>
                            <div class="text-[7.5px]">Training Manager</div>
                        </div>
                    </div>
                    <div class="w-32 border-t border-slate-700 pt-0.5 text-center">
                        <span class="block text-[10px] font-bold text-slate-800">Training Manager</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
</html>

