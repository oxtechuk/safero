@extends('layouts.app')

@section('title', 'أداة التحقق من شهادات الفحص')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#E8F7EC] text-[#22A447] text-xs font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>أداة الاستعلام الرقمي الفوري</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900">التحقق من صحة شهادات وتقارير الفحص</h1>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
            أدخل رقم الشهادة المطبوع على تقرير فحص معدات الرفع أو رمز التحقق للتأكد من مطابقتها وسريانها الرسمي.
        </p>
    </div>

    <!-- Search Input Box -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 sm:p-8">
        <form method="GET" action="{{ route('certificates.verify.lookup') }}" class="space-y-4">
            <div>
                <label for="number" class="block text-xs font-bold text-slate-700 mb-2">رقم الشهادة أو الرمز المرجعي</label>
                <div class="relative">
                    <input type="text" 
                           name="number" 
                           id="number" 
                           value="{{ $search }}" 
                           required 
                           placeholder="مثال: SAF/2026110807"
                           class="w-full px-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-[#0E4DA4] focus:ring-4 focus:ring-[#0E4DA4]/10 outline-none text-base font-en font-bold text-slate-900 placeholder:font-normal placeholder:text-slate-400">
                    <button type="submit" 
                            class="absolute inset-y-2 left-2 px-6 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>تحقق الآن</span>
                    </button>
                </div>
                <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-400">
                    <span>شهادة تجريبية للاختبار:</span>
                    <button type="button" 
                            onclick="document.getElementById('number').value='SAF/2026110807'; this.form.submit();"
                            class="font-en font-bold text-[#0E4DA4] hover:underline">
                        SAF/2026110807
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Result Section -->
    @if($searched)
        @if($certificate)
            <div class="bg-white rounded-3xl border border-emerald-200 shadow-xl overflow-hidden animate-fade-in">
                <div class="p-6 bg-gradient-to-r from-[#22A447] to-[#1b8539] text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <span class="text-xs text-emerald-100 font-bold block">نتيجة الفحص الرسمي:</span>
                            <h3 class="text-lg font-black">شهادة معتمدة ومطابقة للسجلات</h3>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white text-[#22A447]">
                        سارية
                    </span>
                </div>

                <div class="p-6 sm:p-8 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block mb-1">رقم الشهادة:</span>
                            <span class="font-bold text-slate-900 text-sm font-en text-[#0E4DA4]">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">اسم العميل:</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $certificate->customer?->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">المعدة المفحوصة:</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $certificate->equipment_description }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">الحمل الآمن (SWL):</span>
                            <span class="font-black text-slate-900 text-sm font-en">{{ $certificate->swl }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">تاريخ الفحص:</span>
                            <span class="font-bold text-slate-800 font-en">{{ $certificate->thorough_examination_date->format('Y-m-d') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">تاريخ الاستحقاق القادم:</span>
                            <span class="font-bold text-slate-800 font-en">{{ $certificate->next_examination_due_date->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('certificates.show', $certificate) }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 font-bold text-slate-700">
                            عرض الشهادة الكاملة
                        </a>
                        <a href="{{ route('certificates.pdf', $certificate) }}" target="_blank" class="px-5 py-2 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold">
                            تحميل نسخة PDF
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-red-200 shadow-md p-6 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">لم يتم العثور على شهادة بهذا الرقم</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    الرقم <strong class="font-en text-slate-800">{{ $search }}</strong> غير مسجل في قاعدة بيانات شهادات الفحص لشركة سافيرو. يرجى التأكد من كتابة الرقم بدقة أو التواصل مع الإدارة الفنية.
                </p>
            </div>
        @endif
    @endif

</div>
@endsection

