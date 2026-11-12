<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التحقق من صحة شهادة التدريب #{{ $certificate->certificate_serial }} - شركة سافيرو (Safero)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Poppins', system-ui, sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-[#006837] selection:text-white">

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 py-3 px-4 shadow-sm">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-12 w-auto object-contain">
                <div>
                    <span class="text-base font-black text-[#006837] block leading-tight font-en">SAFERO</span>
                    <span class="text-[11px] text-slate-500 font-semibold block">خدمات الفحص والاستشارات والتدريب المعتمد</span>
                </div>
            </div>

            <a href="{{ route('training-certificates.pdf', $certificate) }}" 
               target="_blank"
               class="px-4 py-2 rounded-xl bg-[#006837] hover:bg-[#00522c] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>تحميل نسخة PDF المعتمدة</span>
            </a>
        </div>
    </header>

    <main class="flex-1 py-8 px-4">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Official Status Banner -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-center sm:text-right">
                    <div class="w-16 h-16 rounded-2xl {{ $certificate->status === 'issued' ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-red-50 text-red-600' }} flex items-center justify-center shrink-0">
                        @if($certificate->status === 'issued')
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @else
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 justify-center sm:justify-start">
                            <span class="text-xs font-bold text-slate-400">بوابة التحقق الرسمية</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span class="text-xs font-mono font-bold text-[#006837]">{{ $certificate->certificate_serial }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">
                            @if($certificate->status === 'issued')
                                شهادة تدريب معتمدة ومسجلة رسمياً
                            @else
                                حالة الشهادة: غير سارية أو مسودة
                            @endif
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            تم التحقق من صحة هذه الشهادة مباشرة من قاعدة بيانات شركة سافيرو للاستشارات والفحص المعتمد.
                        </p>
                    </div>
                </div>

                <div class="text-center sm:text-left shrink-0">
                    <div class="p-1 bg-white border border-slate-300 rounded inline-block">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-20 h-20">
                    </div>
                    <span class="block text-[10px] font-mono text-slate-400 mt-1">#{{ $certificate->certificate_serial }}</span>
                </div>
            </div>

            <!-- Certificate Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h2 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>
                    بيانات المتدرب والبرنامج التدريبي
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">اسم المتدرب (Holder):</span>
                        <span class="font-bold text-slate-900 text-sm block font-en">{{ $certificate->trainee_name }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">عنوان البرنامج التدريبي:</span>
                        <span class="font-bold text-[#006837] text-sm block font-en">{{ $certificate->program_title }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">مجال التدريب (Training Scope):</span>
                        <span class="font-bold text-slate-800 block font-en">{{ $certificate->training_scope }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">عدد الساعات التدريبية:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->hours }} ساعة تدريبية معتمدة</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">تاريخ الإصدار:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->issue_date->format('d/m/Y') }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">تاريخ الانتهاء:</span>
                        <span class="font-bold text-slate-800 font-en">
                            {{ $certificate->expiry_date ? $certificate->expiry_date->format('d/m/Y') : 'دائمة' }}
                        </span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">المحاضر / المدرب:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->instructor_name }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">مدير التدريب:</span>
                        <span class="font-bold text-slate-800 font-en">{{ $certificate->training_manager_name }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">الاعتماد:</span>
                        <span class="font-bold text-[#006837]">معتمد من المعهد القومي للجودة (NQIT 347/686)</span>
                    </div>
                </div>
            </div>

            <!-- Interactive Embed Preview -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-black text-slate-800">معاينة الشهادة المعتمدة</h3>
                    <a href="{{ route('training-certificates.pdf', $certificate) }}" target="_blank" class="text-xs text-[#006837] hover:underline font-bold">
                        فتح ملف PDF في نافذة جديدة &larr;
                    </a>
                </div>
                <iframe src="{{ route('training-certificates.embed', $certificate->uuid) }}" 
                        width="100%" 
                        height="640" 
                        class="rounded-xl border border-slate-200 shadow-inner" 
                        frameborder="0">
                </iframe>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} شركة سافيرو للخدمات والاستشارات (Safero Services &amp; Consultations) - جميع الحقوق محفوظة.</p>
    </footer>

</body>
</html>

