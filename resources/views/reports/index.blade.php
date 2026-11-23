@extends('layouts.app')

@section('title', __('تقارير الأرباح'))

@section('content')
<div class="space-y-4">

    <!-- Top Breadcrumb & Control Bar (Screen Only) -->
    <div class="print:hidden no-print bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900">{{ __('لوحة تقارير الفحص وتحليل الأرباح') }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('مؤشرات الأداء المالي، التدفقات النقدية، وحجم أعمال الفحص لجميع العملاء') }}</p>
                </div>
            </div>

            <!-- Date Period Filters -->
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                    <a href="{{ route('reports.index', ['period' => 'all']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('كل الفترات') }}
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'this_month']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('هذا الشهر') }}
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'last_month']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'last_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('الشهر الماضي') }}
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'this_year']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_year' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('هذا العام') }}
                    </a>
                </div>

                <button type="button" onclick="window.print()" 
                        class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>{{ __('طباعة التقرير') }}</span>
                </button>
            </form>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-5 print:p-0 print:m-0 print:space-y-4">

        <!-- Official Printable Letterhead (Printed only) -->
        <div class="hidden print:block pb-4 mb-4 border-b-2 border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" 
                         alt="{{ setting('company_name_en', 'Safero') }}" 
                         class="h-14 w-auto object-contain">
                    <div>
                        <h2 class="text-base font-black text-slate-900 leading-tight">
                            {{ setting('company_name_ar', 'شركة سافيرو للخدمات والاستشارات الهندسية والتفتيش') }}
                        </h2>
                        <p class="text-xs font-bold text-[#0E4DA4] font-mono mt-0.5">
                            {{ setting('company_name_en', 'SAFERO Services & Consultations - Inspection Body') }}
                        </p>
                        <p class="text-[10px] text-slate-500 mt-0.5">
                            {{ __('تقرير مالي رسمي لتحليل الأرباح وحجم الأعمال') }} &bull; ISO/IEC 17020 Certified Body
                        </p>
                    </div>
                </div>
                <div class="text-left rtl:text-right text-xs font-mono text-slate-600 space-y-0.5">
                    <div>{{ __('الفترة الزمنية:') }} <strong class="text-slate-900">{{ match($period) {
                        'this_month' => __('هذا الشهر'),
                        'last_month' => __('الشهر الماضي'),
                        'this_year' => __('هذا العام'),
                        default => __('كل الفترات')
                    } }}</strong></div>
                    <div>{{ __('تاريخ وتوقيت استخراج التقرير:') }} <strong>{{ now()->format('Y-m-d H:i') }}</strong></div>
                    <div class="text-emerald-700 font-bold">{{ __('صافي الأرباح الحقيقية:') }} {{ number_format($realNetProfit, 2) }} {{ __('ج.م') }}</div>
                </div>
            </div>
        </div>

        <!-- 5 Primary Financial & Real Profit KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            
            <!-- 1. Total Invoiced -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('إجمالي الإيرادات المفوترة') }}</span>
                    <span class="text-xl sm:text-2xl font-black text-[#0E4DA4] font-mono mt-0.5 block">
                        {{ number_format($totalInvoiced, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">{{ __('إجمالي') }} {{ $invoicesCount }} {{ __('فاتورة') }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0E4DA4] flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- 2. Cash Collected (Paid) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('الإيرادات المحصلة فعلياً') }}</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-600 font-mono mt-0.5 block">
                        {{ number_format($totalPaid, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-emerald-700 font-bold mt-1 block">{{ __('نسبة التحصيل:') }} {{ $collectionRate }}%</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- 3. Total Operational Expenses (بند المصاريف الجديد) -->
            <div class="bg-white border border-rose-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-rose-800 block">{{ __('المصروفات والنفقات التشغيلية') }}</span>
                    <span class="text-xl sm:text-2xl font-black text-rose-600 font-mono mt-0.5 block">
                        {{ number_format($totalExpenses, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-rose-600 font-bold mt-1 block">{{ $expensesCount }} {{ __('سند صرف') }} &bull; <a href="{{ route('expenses.index') }}" class="underline hover:text-rose-800">{{ __('إدارة المصاريف') }}</a></span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <!-- 4. REAL NET PROFIT (صافي الأرباح الحقيقية) -->
            <div class="rounded-2xl p-4 shadow-sm flex items-center justify-between border {{ $realNetProfit >= 0 ? 'bg-gradient-to-br from-emerald-600 to-teal-700 text-white border-emerald-500' : 'bg-gradient-to-br from-rose-600 to-red-700 text-white border-rose-500' }}">
                <div>
                    <span class="text-xs font-bold block {{ $realNetProfit >= 0 ? 'text-emerald-100' : 'text-rose-100' }}">{{ __('صافي الأرباح الحقيقية') }}</span>
                    <span class="text-xl sm:text-2xl font-black font-mono mt-0.5 block">
                        {{ number_format($realNetProfit, 2) }} <span class="text-xs font-bold font-sans">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] font-bold mt-1 block {{ $realNetProfit >= 0 ? 'text-amber-300' : 'text-yellow-200' }}">
                        {{ __('هامش الربح:') }} {{ $profitMargin }}% ({{ __('المحصل - المصاريف') }})
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>

            <!-- 5. Outstanding Receivables -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('المتبقي لدى العملاء') }}</span>
                    <span class="text-xl sm:text-2xl font-black text-red-600 font-mono mt-0.5 block">
                        {{ number_format($totalRemaining, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-red-500 font-bold mt-1 block">{{ __('ديون واجبة التحصيل') }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

        </div>

        <!-- Real Profit Equation & Expenses Statement Banner -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <h2 class="text-sm font-black text-slate-900">{{ __('معادلة احتساب الأرباح الحقيقية الصافية (Real Net Profit Equation)') }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('expenses.create') }}" class="px-3 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-bold text-xs flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>{{ __('+ تسجيل مصروف جديد') }}</span>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-xs transition">
                        <span>{{ __('سجل المصاريف والنفقات') }}</span>
                    </a>
                </div>
            </div>

            <!-- Visual Math Equation -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center text-center pt-3 text-xs">
                
                <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-200">
                    <span class="text-emerald-800 font-bold block text-[11px]">{{ __('1. الإيرادات المحصلة نقداً') }}</span>
                    <span class="font-mono font-black text-emerald-700 text-base block mt-0.5">{{ number_format($totalPaid, 2) }} {{ __('ج.م') }}</span>
                </div>

                <div class="font-black text-slate-400 text-xl hidden md:block">&minus;</div>

                <div class="bg-rose-50 p-3 rounded-xl border border-rose-200">
                    <span class="text-rose-800 font-bold block text-[11px]">{{ __('2. إجمالي المصاريف والنفقات') }}</span>
                    <span class="font-mono font-black text-rose-600 text-base block mt-0.5">{{ number_format($totalExpenses, 2) }} {{ __('ج.م') }}</span>
                </div>

                <div class="font-black text-slate-400 text-xl hidden md:block">&#61;</div>

                <div class="p-3 rounded-xl border {{ $realNetProfit >= 0 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-rose-600 text-white border-rose-600' }}">
                    <span class="block text-[11px] font-bold text-white/90">{{ __('3. صافي الأرباح الحقيقية الفعلية') }}</span>
                    <span class="font-mono font-black text-base block mt-0.5">{{ number_format($realNetProfit, 2) }} {{ __('ج.م') }}</span>
                </div>

            </div>
        </div>
                    </span>
                    <span class="text-[11px] text-red-500 font-bold mt-1 block">{{ __('مستحقات واجبة التحصيل') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

            <!-- 4. Inspection Volume & Average Price -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('إجمالي شهادات الفحص الصادرة') }}</span>
                    <span class="text-2xl font-black text-purple-700 font-mono mt-0.5 block">
                        {{ $totalCertificates }} <span class="text-xs font-bold text-slate-400">{{ __('شهادة') }}</span>
                    </span>
                    <span class="text-[11px] text-purple-600 font-bold mt-1 block">{{ __('متوسط الفحص:') }} {{ number_format($avgCertPrice, 2) }} {{ __('ج.م') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        <!-- Inspection Quality & Safety Stats -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">{{ __('مؤشرات الجودة والسلامة الفنية (Safety & Compliance Ratio)') }}</h2>
                    <p class="text-[11px] text-slate-500">{{ __('نسبة المعدات الآمنة للاستخدام مقابل المعدات التي تم رصد عيوب بها') }}</p>
                </div>
                <div class="text-xs font-bold text-slate-600">
                    {{ __('إجمالي الفحوصات الميدانية:') }} <span class="font-mono text-slate-900 font-black">{{ $totalInspections }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-emerald-800 block">{{ __('معدات مطابقة وآمنة (Safe)') }}</span>
                        <span class="text-xl font-black text-emerald-700 font-mono">{{ $safeInspections }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-emerald-200/60 text-emerald-800 font-mono">
                        {{ $totalInspections > 0 ? round(($safeInspections / $totalInspections) * 100) : 0 }}%
                    </span>
                </div>

                <div class="bg-red-50/60 border border-red-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-red-800 block">{{ __('معدات بها عيوب / مرفوضة (Unsafe)') }}</span>
                        <span class="text-xl font-black text-red-700 font-mono">{{ $unsafeInspections }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-red-200/60 text-red-800 font-mono">
                        {{ $totalInspections > 0 ? round(($unsafeInspections / $totalInspections) * 100) : 0 }}%
                    </span>
                </div>

                <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-amber-800 block">{{ __('إجمالي الخصومات الممنوحة') }}</span>
                        <span class="text-xl font-black text-amber-700 font-mono">{{ number_format($totalDiscount, 2) }} {{ __('ج.م') }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-amber-200/60 text-amber-800">
                        {{ __('خصم تجاري') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Operational Expenses Breakdown Section -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">{{ __('تحليل وتوزيع المصاريف التشغيلية (Expenses Breakdown)') }}</h2>
                    <p class="text-[11px] text-slate-500">{{ __('توزيع نفقات الفحص وانتقالات المفتشين وأتعاب التدريب وصيانة الأجهزة') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                        {{ __('إجمالي المصاريف:') }} <span class="font-mono font-black">{{ number_format($totalExpenses, 2) }}</span> {{ __('ج.م') }}
                    </span>
                    <a href="{{ route('expenses.index') }}" class="text-xs text-[#0E4DA4] font-bold hover:underline">
                        {{ __('عرض السجل الكامل &larr;') }}
                    </a>
                </div>
            </div>

            @if($expensesByCategory->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($expensesByCategory as $expCat)
                        @php
                            $catConfig = \App\Models\Expense::categories()[$expCat->category] ?? null;
                            $catName = $catConfig ? (app()->getLocale() === 'en' ? $catConfig['en'] : $catConfig['ar']) : $expCat->category;
                            $percent = $totalExpenses > 0 ? round(($expCat->total_amount / $totalExpenses) * 100, 1) : 0;
                        @endphp
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">{{ $catName }}</span>
                                <span class="font-mono font-bold text-slate-500 text-[11px]">{{ $expCat->count }} {{ __('سند') }}</span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="font-mono font-black text-rose-600 text-sm">
                                    {{ number_format($expCat->total_amount, 2) }} <span class="text-[10px] text-slate-400 font-sans">{{ __('ج.م') }}</span>
                                </span>
                                <span class="font-mono font-bold text-xs text-slate-600">{{ $percent }}%</span>
                            </div>
                            <!-- Mini Progress Bar -->
                            <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400">
                    {{ __('لم يتم تسجيل مصروفات تشغيلية لهذه الفترة حتى الآن.') }}
                    <a href="{{ route('expenses.create') }}" class="text-[#0E4DA4] font-bold hover:underline block mt-1">
                        {{ __('+ إضافة أول مصروف الآن') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- 6-Month Revenue, Collection & Expense Trend -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <h2 class="text-sm font-extrabold text-slate-900 mb-1">{{ __('حركة الإيرادات والتحصيل والمصاريف خلال آخر 6 أشهر') }}</h2>
            <p class="text-[11px] text-slate-500 mb-4">{{ __('مقارنة شهرية متكاملة بين المفوتر، المحصل، المصاريف، وصافي الربح الحقيقي') }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($monthlyTrends as $trend)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center space-y-1">
                        <span class="text-[11px] font-bold text-slate-600 block truncate">{{ $trend['month'] }}</span>
                        
                        <div class="text-[10.5px] text-slate-400 font-mono">
                            {{ __('مفوتر:') }} <strong class="text-slate-800">{{ number_format($trend['invoiced'], 0) }}</strong>
                        </div>
                        
                        <div class="text-[11px] font-bold text-emerald-700 font-mono">
                            {{ __('محصل:') }} {{ number_format($trend['paid'], 0) }}
                        </div>

                        <div class="text-[10.5px] font-bold text-rose-600 font-mono">
                            {{ __('مصاريف:') }} {{ number_format($trend['expenses'], 0) }}
                        </div>

                        <div class="pt-1 border-t border-slate-200">
                            <span class="text-[9.5px] text-slate-400 block">{{ __('صافي الربح:') }}</span>
                            <span class="font-mono font-black text-xs {{ $trend['real_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ number_format($trend['real_profit'], 0) }} {{ __('ج.م') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Client Profitability & Balance Sheet -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">{{ __('سجل أرباح ومديونيات العملاء (Client Profitability)') }}</h2>
                    <p class="text-[11px] text-slate-500">{{ __('حجم الأعمال والمبالغ المسددة والمتبقية لكل عميل') }}</p>
                </div>
                <span class="text-xs font-bold text-slate-500 font-mono">{{ $customerReports->count() }} {{ __('عميل') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">{{ __('العميل') }}</th>
                            <th class="py-3 px-4 text-center">{{ __('شهادات الفحص') }}</th>
                            <th class="py-3 px-4 text-center">{{ __('الفواتير') }}</th>
                            <th class="py-3 px-4">{{ __('إجمالي الأعمال المفوترة') }}</th>
                            <th class="py-3 px-4">{{ __('المسدد (الأرباح المحصلة)') }}</th>
                            <th class="py-3 px-4">{{ __('المتبقي (ديون مستحقة)') }}</th>
                            <th class="py-3 px-4 text-center">{{ __('حالة الحساب') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($customerReports as $cust)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    {{ $cust['name'] }}
                                    @if($cust['phone'])
                                        <span class="block text-[10px] text-slate-400 font-mono">{{ $cust['phone'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-[#0E4DA4]">
                                    {{ $cust['certificates_count'] }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-semibold">
                                    {{ $cust['invoices_count'] }}
                                </td>
                                <td class="py-3 px-4 font-mono font-extrabold text-slate-900">
                                    {{ number_format($cust['total_invoiced'], 2) }} {{ __('ج.م') }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                                    {{ number_format($cust['total_paid'], 2) }} {{ __('ج.م') }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold {{ $cust['total_remaining'] > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                    {{ number_format($cust['total_remaining'], 2) }} {{ __('ج.م') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($cust['status'] === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ __('ملتزم بالسداد') }}
                                        </span>
                                    @elseif($cust['status'] === 'partial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ __('متبقي جزء') }}
                                        </span>
                                    @elseif($cust['status'] === 'unpaid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            {{ __('مديونية معلقة') }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">{{ __('لا توجد بيانات حسابات مسجلة') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    body, html, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    @page {
        size: A4 landscape;
        margin: 8mm 10mm;
    }
}
</style>
@endsection
