@extends('layouts.app')

@section('title', 'تقارير الفحص ومؤشرات الأرباح - Profit & Inspection Analytics')

@section('content')
<div class="space-y-4">

    <!-- Top Breadcrumb & Control Bar -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900">لوحة تقارير الفحص وتحليل الأرباح</h1>
                    <p class="text-xs text-slate-500 mt-0.5">مؤشرات الأداء المالي، التدفقات النقدية، وحجم أعمال الفحص لجميع العملاء</p>
                </div>
            </div>

            <!-- Date Period Filters -->
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                    <a href="{{ route('reports.index', ['period' => 'all']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        كل الفترات
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'this_month']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        هذا الشهر
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'last_month']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'last_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        الشهر الماضي
                    </a>
                    <a href="{{ route('reports.index', ['period' => 'this_year']) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_year' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        هذا العام
                    </a>
                </div>

                <button type="button" onclick="window.print()" 
                        class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>طباعة التقرير</span>
                </button>
            </form>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-5">

        <!-- 4 Primary Financial KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Total Invoiced -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">إجمالي الإيرادات المفوترة</span>
                    <span class="text-2xl font-black text-[#0E4DA4] font-mono mt-0.5 block">
                        {{ number_format($totalInvoiced, 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">إجمالي {{ $invoicesCount }} فاتورة فحص</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0E4DA4] flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- 2. Cash Collected (Paid) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">المحصل نقداً / تحويلات (الأرباح المحققة)</span>
                    <span class="text-2xl font-black text-emerald-600 font-mono mt-0.5 block">
                        {{ number_format($totalPaid, 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                    <span class="text-[11px] text-emerald-700 font-bold mt-1 block">نسبة التحصيل: {{ $collectionRate }}%</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- 3. Outstanding Receivables -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">المتبقي لدى العملاء (الديون المستحقة)</span>
                    <span class="text-2xl font-black text-red-600 font-mono mt-0.5 block">
                        {{ number_format($totalRemaining, 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                    <span class="text-[11px] text-red-500 font-bold mt-1 block">مستحقات واجبة التحصيل</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

            <!-- 4. Inspection Volume & Average Price -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">إجمالي شهادات الفحص الصادرة</span>
                    <span class="text-2xl font-black text-purple-700 font-mono mt-0.5 block">
                        {{ $totalCertificates }} <span class="text-xs font-bold text-slate-400">شهادة</span>
                    </span>
                    <span class="text-[11px] text-purple-600 font-bold mt-1 block">متوسط الفحص: {{ number_format($avgCertPrice, 2) }} ج.م</span>
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
                    <h2 class="text-sm font-extrabold text-slate-900">مؤشرات الجودة والسلامة الفنية (Safety &amp; Compliance Ratio)</h2>
                    <p class="text-[11px] text-slate-500">نسبة المعدات الآمنة للاستخدام مقابل المعدات التي تم رصد عيوب بها</p>
                </div>
                <div class="text-xs font-bold text-slate-600">
                    إجمالي الفحوصات الميدانية: <span class="font-mono text-slate-900 font-black">{{ $totalInspections }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-emerald-800 block">معدات مطابقة وآمنة (Safe)</span>
                        <span class="text-xl font-black text-emerald-700 font-mono">{{ $safeInspections }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-emerald-200/60 text-emerald-800 font-mono">
                        {{ $totalInspections > 0 ? round(($safeInspections / $totalInspections) * 100) : 0 }}%
                    </span>
                </div>

                <div class="bg-red-50/60 border border-red-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-red-800 block">معدات بها عيوب / مرفوضة (Unsafe)</span>
                        <span class="text-xl font-black text-red-700 font-mono">{{ $unsafeInspections }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-red-200/60 text-red-800 font-mono">
                        {{ $totalInspections > 0 ? round(($unsafeInspections / $totalInspections) * 100) : 0 }}%
                    </span>
                </div>

                <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-amber-800 block">إجمالي الخصومات الممنوحة</span>
                        <span class="text-xl font-black text-amber-700 font-mono">{{ number_format($totalDiscount, 2) }} ج.م</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-amber-200/60 text-amber-800">
                        خصم تجاري
                    </span>
                </div>
            </div>
        </div>

        <!-- 6-Month Revenue & Collection Trend -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <h2 class="text-sm font-extrabold text-slate-900 mb-1">حركة الإيرادات والتحصيل خلال آخر 6 أشهر</h2>
            <p class="text-[11px] text-slate-500 mb-4">مقارنة شهرية بين المبالغ المفوترة والمبالغ المحصلة فعلياً</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($monthlyTrends as $trend)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center space-y-1">
                        <span class="text-[11px] font-bold text-slate-600 block truncate">{{ $trend['month'] }}</span>
                        <div class="text-xs font-black text-[#0E4DA4] font-mono">
                            {{ number_format($trend['invoiced'], 0) }} ج.م
                        </div>
                        <div class="text-[11px] font-bold text-emerald-700 font-mono">
                            تم تحصيل: {{ number_format($trend['paid'], 0) }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono pt-1 border-t border-slate-200">
                            {{ $trend['inspections'] }} فحص
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Client Profitability & Balance Sheet -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">سجل أرباح ومديونيات العملاء (Client Profitability)</h2>
                    <p class="text-[11px] text-slate-500">حجم الأعمال والمبالغ المسددة والمتبقية لكل عميل</p>
                </div>
                <span class="text-xs font-bold text-slate-500 font-mono">{{ $customerReports->count() }} عميل</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">العميل</th>
                            <th class="py-3 px-4 text-center">شهادات الفحص</th>
                            <th class="py-3 px-4 text-center">الفواتير</th>
                            <th class="py-3 px-4">إجمالي الأعمال المفوترة</th>
                            <th class="py-3 px-4">المسدد (الأرباح المحصلة)</th>
                            <th class="py-3 px-4">المتبقي (ديون مستحقة)</th>
                            <th class="py-3 px-4 text-center">حالة الحساب</th>
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
                                    {{ number_format($cust['total_invoiced'], 2) }} ج.م
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                                    {{ number_format($cust['total_paid'], 2) }} ج.م
                                </td>
                                <td class="py-3 px-4 font-mono font-bold {{ $cust['total_remaining'] > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                    {{ number_format($cust['total_remaining'], 2) }} ج.م
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($cust['status'] === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ملتزم بالسداد
                                        </span>
                                    @elseif($cust['status'] === 'partial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            متبقي جزء
                                        </span>
                                    @elseif($cust['status'] === 'unpaid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            مديونية معلقة
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">لا توجد بيانات حسابات مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
