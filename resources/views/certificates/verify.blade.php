<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التحقق من شهادة الفحص - Safero Services</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Poppins', system-ui, sans-serif;
            background-color: #F4F6F8;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col justify-between p-4 sm:p-6">

    <div class="max-w-2xl w-full mx-auto space-y-6">

        <!-- Header with Logo -->
        <div class="text-center pt-4">
            <div class="inline-block p-3 bg-white rounded-2xl shadow-sm border border-slate-200 mb-2">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-14 w-auto mx-auto object-contain">
            </div>
            <h1 class="text-lg font-black text-[#0E4DA4] font-en tracking-wider">SAFERO SERVICES &amp; CONSULTATIONS</h1>
            <p class="text-xs text-slate-500 font-medium">بوابة التحقق الإلكتروني الرسمي من شهادات وتقارير الفحص</p>
        </div>

        <!-- Verification Result Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden">
            
            <!-- Card Top Status Banner -->
            @if($certificate->status === 'issued' && $certificate->is_safe_to_operate && !$certificate->next_examination_due_date->isPast())
                <div class="bg-gradient-to-r from-[#22A447] to-[#1b8539] p-6 text-white text-center">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3 shadow-inner">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-bold font-en uppercase tracking-wider mb-1">
                        OFFICIALLY VERIFIED &amp; APPROVED
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black">شهادة فحص معتمدة وسارية</h2>
                    <p class="text-xs text-emerald-100 mt-1">
                        تم التحقق من صحة هذه الشهادة ومطابقتها للسجلات الرقمية الرسمية لشركة سافيرو
                    </p>
                </div>
            @elseif($certificate->next_examination_due_date->isPast())
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-6 text-white text-center">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black">شهادة فحص منتهية الصلاحية</h2>
                    <p class="text-xs text-amber-100 mt-1">
                        هذه الشهادة مسجلة ولكن تجاوزت موعد الفحص الدوري القادم (Due for Re-examination)
                    </p>
                </div>
            @else
                <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 text-white text-center">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black">المعدة غير مصرح بتشغيلها</h2>
                    <p class="text-xs text-red-100 mt-1">
                        الشهادة مسجلة كغير آمنة للتشغيل أو ملغاة
                    </p>
                </div>
            @endif

            <!-- Certificate Details Grid -->
            <div class="p-6 sm:p-8 space-y-6">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400 block font-en">CERTIFICATE NO</span>
                        <span class="text-xl font-black text-[#0E4DA4] font-en">{{ $certificate->certificate_number }}</span>
                    </div>
                    @if($certificate->order_no)
                        <div class="text-left">
                            <span class="text-xs text-slate-400 block font-en">ORDER NO</span>
                            <span class="text-sm font-bold text-slate-700 font-en">#{{ $certificate->order_no }}</span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">اسم العميل (Customer)</span>
                        <span class="font-bold text-slate-900 text-sm block">{{ $certificate->customer?->name }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">موقع الفحص (Address)</span>
                        <span class="font-bold text-slate-900 text-sm block">{{ $certificate->examination_address }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">المعدة المفحوصة (Equipment)</span>
                        <span class="font-bold text-slate-900 text-sm block">{{ $certificate->equipment_description }}</span>
                        <span class="text-[11px] text-slate-500 font-en block mt-0.5">ID: {{ $certificate->equipment_id_range ?? '—' }} | عدد: {{ $certificate->quantity }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">الحمل الآمن للتشغيل (SWL)</span>
                        <span class="font-black text-[#0E4DA4] text-base block font-en">{{ $certificate->swl }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">تاريخ الفحص الشامل (Exam Date)</span>
                        <span class="font-bold text-slate-800 text-sm block font-en">{{ $certificate->thorough_examination_date->format('d/m/Y') }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">تاريخ الاستحقاق القادم (Due Date)</span>
                        <span class="font-bold text-slate-800 text-sm block font-en">{{ $certificate->next_examination_due_date->format('d/m/Y') }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">المواصفة المرجعية (Standard)</span>
                        <span class="font-bold text-slate-800 text-sm block font-en">{{ $certificate->reference_standard }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                        <span class="text-slate-400 block mb-1">القرار الفني للسلامة</span>
                        <span class="font-bold {{ $certificate->is_safe_to_operate ? 'text-[#22A447]' : 'text-red-600' }} text-sm block">
                            {{ $certificate->is_safe_to_operate ? 'آمنة للتشغيل (Safe to Operate)' : 'غير آمنة للتشغيل' }}
                        </span>
                    </div>
                </div>

                <!-- Signatures & Authority -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs gap-3">
                    <div>
                        <span class="text-slate-400 block">مهندس الفحص:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->issuer_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">معتمد الشهادة:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->authenticator_name }}</span>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#E6F2FF] text-[#0E4DA4] font-bold text-[11px]">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            ختم رسمي موثق
                        </span>
                    </div>
                </div>

                <!-- Download Button -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <a href="{{ route('certificates.pdf', $certificate) }}" 
                       target="_blank"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-sm shadow-lg shadow-[#0E4DA4]/25 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>تحميل النسخة الرسمية المعتمدة (PDF)</span>
                    </a>
                </div>

            </div>

        </div>

        <!-- Footer Info -->
        <div class="text-center text-xs text-slate-400 space-y-1">
            <p>شركة سافيرو للخدمات والاستشارات (Safero Services &amp; Consultations)</p>
            <p class="font-en">Website: <a href="https://safero-eg.com" target="_blank" class="text-[#0E4DA4] hover:underline">safero-eg.com</a></p>
        </div>

    </div>

</body>
</html>

