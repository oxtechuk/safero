@extends('layouts.app')

@section('title', 'لوحة المديولات الرئيسية')

@section('content')
<div class="space-y-8">

    <!-- Top Greeting & Welcome Bar -->
    <div class="bg-gradient-to-l from-[#0E4DA4] via-[#0b418c] to-[#083370]  p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-10 -top-10 w-40 h-40 /20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
           
                <h1 class="text-2xl sm:text-3xl font-black text-white">
                    مرحباً بك، {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-blue-100 text-sm mt-1 max-w-xl">
                    نظام شركة سافيرو (Safero) المتكامل لإصدار تقارير الفحص الشامل لمعدات الرفع، وإصدار شهادات الدورات التدريبية المعتمدة للمتدربين مع الربط التلقائي بأكواد الـ QR وإطار التضمين بموقع الشركة.
                </p>
            </div>
            <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('training-certificates.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-[#006837] hover:bg-[#00522c] text-white font-bold text-xs sm:text-sm shadow-lg transition duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>إصدار شهادة تدريب</span>
                </a>
                <a href="{{ route('certificates.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-lg transition duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>إصدار شهادة فحص</span>
                </a>
                <a href="{{ route('trainees.create') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>متدرب جديد</span>
                </a>
            </div>
        </div>
    </div>

    <!-- IsoOx-Style App Modules Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-[#0E4DA4] rounded-full inline-block"></span>
                    مديولات النظام (IsoOx-Style Apps)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">انقر على أي مديول للبدء في إدارة وتتبع عملياته</p>
            </div>
            <span class="text-xs font-bold text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                {{ count($modules) }} مديولات
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
            @foreach($modules as $module)
                <a href="{{ $module['active'] ? $module['route'] : 'javascript:void(0)' }}" 
                   class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-[#0E4DA4]/50 hover:-translate-y-1 transition-all duration-200 flex flex-col items-center text-center relative overflow-hidden {{ !$module['active'] ? 'opacity-70 cursor-not-allowed' : '' }}">
                    
                    <!-- App Icon Box with IsoOx vibe -->
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 shadow-sm transition-transform duration-200 group-hover:scale-110" 
                         style="background-color: {{ $module['bg_light'] }}; color: {{ $module['color'] }};">
                        @if($module['icon'] === 'document-check')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4"/>
                            </svg>
                        @elseif($module['icon'] === 'academic-cap')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                            </svg>
                        @elseif($module['icon'] === 'user-group' || $module['icon'] === 'users')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        @elseif($module['icon'] === 'plus-circle')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($module['icon'] === 'shield-check')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Module Title -->
                    <h3 class="font-bold text-slate-800 text-xs sm:text-sm line-clamp-1 group-hover:text-[#0E4DA4] transition">
                        {{ $module['name'] }}
                    </h3>
                    <span class="text-[11px] text-slate-400 font-en block mt-0.5">{{ $module['name_en'] }}</span>

                    <!-- Badge / Count -->
                    @if($module['count'] !== null)
                        <div class="mt-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold" 
                             style="background-color: {{ $module['bg_light'] }}; color: {{ $module['color'] }};">
                            {{ $module['count'] }} {{ $module['count_label'] }}
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block mb-1">شهادات التدريب الصادرة</span>
                <span class="text-2xl font-black text-[#006837]">{{ $stats['total_training_certificates'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#E8F7EC] text-[#006837] flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block mb-1">المتدربون المسجلون</span>
                <span class="text-2xl font-black text-slate-900">{{ $stats['total_trainees'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#E6F2FF] text-[#0E4DA4] flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block mb-1">شهادات فحص المعدات</span>
                <span class="text-2xl font-black text-slate-900">{{ $stats['total_certificates'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#E8F7EC] text-[#22A447] flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block mb-1">العملاء والشركات</span>
                <span class="text-2xl font-black text-slate-900">{{ $stats['total_customers'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#E6F2FF] text-[#0E4DA4] flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>

    <!-- Recent Training Certificates Section -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>
                    أحدث شهادات التدريب المعتمدة الصادرة
                </h3>
                <p class="text-xs text-slate-500">آخر شهادات الدورات التدريبية المعتمدة للمتدربين مع كود QR والـ PDF</p>
            </div>
            <a href="{{ route('training-certificates.index') }}" class="text-xs font-bold text-[#006837] hover:underline flex items-center gap-1">
                <span>عرض جميع شهادات التدريب ({{ $stats['total_training_certificates'] }})</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="p-3.5">اسم المتدرب (Holder)</th>
                        <th class="p-3.5">رقم المسلسل</th>
                        <th class="p-3.5">عنوان البرنامج التدريبي</th>
                        <th class="p-3.5">المجال</th>
                        <th class="p-3.5 text-center">الساعات</th>
                        <th class="p-3.5">تاريخ الشهادة</th>
                        <th class="p-3.5 text-center">الحالة</th>
                        <th class="p-3.5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTrainingCertificates as $tCert)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <a href="{{ route('training-certificates.show', $tCert) }}" class="hover:text-[#006837] transition">
                                    {{ $tCert->trainee_name }}
                                </a>
                            </td>
                            <td class="p-3.5 font-bold text-[#006837] font-en">
                                {{ $tCert->certificate_serial }}
                            </td>
                            <td class="p-3.5 font-semibold text-slate-800">
                                {{ $tCert->program_title }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $tCert->training_scope }}
                            </td>
                            <td class="p-3.5 text-center font-bold font-en text-slate-700">
                                {{ $tCert->hours }} hrs
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $tCert->issue_date->format('d/m/Y') }}
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                    معتمدة
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('training-certificates.show', $tCert) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-[#E8F7EC] text-slate-700 hover:text-[#006837] transition" 
                                       title="معاينة">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.pdf', $tCert) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg bg-red-50 hover:bg-red-600 text-red-600 hover:text-white transition" 
                                       title="تحميل PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">
                                لا توجد شهادات تدريب مسجلة بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Inspection Certificates Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#0E4DA4] rounded-full inline-block"></span>
                    أحدث تقارير وشهادات الفحص الشامل
                </h3>
                <p class="text-xs text-slate-500">آخر تقارير الفحص الصادرة للمعدات</p>
            </div>
            <a href="{{ route('certificates.index') }}" class="text-xs font-bold text-[#0E4DA4] hover:underline flex items-center gap-1">
                <span>عرض جميع شهادات الفحص ({{ $stats['total_certificates'] }})</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 font-bold">رقم الشهادة</th>
                        <th class="p-3.5 font-bold">اسم العميل</th>
                        <th class="p-3.5 font-bold">وصف المعدة</th>
                        <th class="p-3.5 font-bold">الحمل الآمن (SWL)</th>
                        <th class="p-3.5 font-bold">تاريخ الفحص</th>
                        <th class="p-3.5 font-bold">تاريخ الاستحقاق</th>
                        <th class="p-3.5 font-bold">الحالة</th>
                        <th class="p-3.5 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentCertificates as $cert)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-[#0E4DA4] font-en">
                                <a href="{{ route('certificates.show', $cert) }}" class="hover:underline">
                                    {{ $cert->certificate_number }}
                                </a>
                            </td>
                            <td class="p-3.5 font-semibold text-slate-900">
                                {{ $cert->customer?->name }}
                            </td>
                            <td class="p-3.5 text-slate-700">
                                {{ $cert->equipment_description }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-900 font-en">
                                {{ $cert->swl }}
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->thorough_examination_date->format('Y-m-d') }}
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->next_examination_due_date->format('Y-m-d') }}
                            </td>
                            <td class="p-3.5">
                                @if($cert->is_safe_to_operate)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                        آمنة للتشغيل
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        غير آمنة
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('certificates.show', $cert) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-[#E6F2FF] text-slate-700 hover:text-[#0E4DA4] transition" 
                                       title="معاينة الشهادة والـ QR">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white transition" 
                                       title="تحميل PDF رسمي">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                لا توجد شهادات مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
