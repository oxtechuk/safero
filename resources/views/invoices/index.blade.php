@extends('layouts.app')

@section('title', 'فواتير وحسابات العملاء - Invoices & Billing')

@section('content')
<div class="space-y-4">

    <!-- IsoOx Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="فواتير وحسابات الفحص (Invoices &amp; Accounts)"
        new-url="{{ route('invoices.create') }}"
        new-text="إنشاء فاتورة جديدة"
        new-color="bg-[#006837]"
        search-action="{{ route('invoices.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث برقم الفاتورة، اسم العميل..."
        :paginator="$invoices"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">حالة السداد (Payment Status)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'paid'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'paid' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مدفوع بالكامل (Paid)
                    </a>
                    <a href="{{ route('invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'partial'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'partial' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مدفوع جزئياً (Partial)
                    </a>
                    <a href="{{ route('invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'unpaid'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'unpaid' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        غير مدفوع (Unpaid)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('invoices.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Top Actions Bar -->
        <div class="bg-white border border-slate-200 rounded-xl p-2 flex items-center justify-between gap-2 shadow-2xs flex-wrap">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a href="{{ route('invoices.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('status') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    جميع الفواتير ({{ $stats['count'] }})
                </a>
                <a href="{{ route('invoices.index', ['status' => 'paid']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'paid' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    مدفوعة ({{ $stats['paid_count'] }})
                </a>
                <a href="{{ route('invoices.index', ['status' => 'partial']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'partial' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    سداد جزئي ({{ $stats['partial_count'] }})
                </a>
                <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'unpaid' ? 'bg-red-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    معلقة / غير مدفوعة ({{ $stats['unpaid_count'] }})
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}" 
                   class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>تقارير الأرباح</span>
                </a>
                <a href="{{ route('invoices.create') }}" 
                   class="px-4 py-1.5 rounded-lg bg-[#006837] hover:bg-[#00522c] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>فاتورة جديدة</span>
                </a>
            </div>
        </div>

        <!-- Financial KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">إجمالي الفواتير الصادرة</span>
                    <span class="text-2xl font-black text-[#0E4DA4] font-mono mt-0.5 block">
                        {{ number_format($stats['total_invoiced'], 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0E4DA4] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">إجمالي المبالغ المحصلة (دفع كم)</span>
                    <span class="text-2xl font-black text-emerald-600 font-mono mt-0.5 block">
                        {{ number_format($stats['total_paid'], 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">المتبقي والمستحق (ديون العملاء)</span>
                    <span class="text-2xl font-black text-red-600 font-mono mt-0.5 block">
                        {{ number_format($stats['total_remaining'], 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span>
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            @if($invoices->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">لا توجد فواتير مسجلة حتى الآن</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">أنشئ فاتورة جديدة وضم شهادات فحص العميل إليها مع تحديد السعر والخصم والمبلغ المدفوع.</p>
                    <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#006837] text-white text-xs font-bold hover:bg-[#00522c] transition shadow-xs">
                        + إنشاء أول فاتورة
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-600 text-[11px] font-bold">
                            <tr>
                                <th class="py-3 px-4">رقم الفاتورة</th>
                                <th class="py-3 px-4">العميل</th>
                                <th class="py-3 px-4">تاريخ الفاتورة</th>
                                <th class="py-3 px-4 text-center">الشهادات المرفقة</th>
                                <th class="py-3 px-4">المجموع الفرعي</th>
                                <th class="py-3 px-4">الخصم</th>
                                <th class="py-3 px-4">الإجمالي النهائي</th>
                                <th class="py-3 px-4">المدفوع</th>
                                <th class="py-3 px-4">المتبقي</th>
                                <th class="py-3 px-4 text-center">حالة السداد</th>
                                <th class="py-3 px-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($invoices as $inv)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-[#0E4DA4]">
                                        <a href="{{ route('invoices.show', $inv) }}" class="hover:underline">
                                            {{ $inv->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">
                                        {{ $inv->customer->name }}
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-600">
                                        {{ $inv->invoice_date->format('Y-m-d') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0E4DA4] font-mono">
                                            {{ $inv->items->count() }} بند
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono font-semibold">
                                        {{ number_format($inv->subtotal, 2) }}
                                    </td>
                                    <td class="py-3 px-4 font-mono text-amber-700 font-semibold">
                                        @if($inv->discount_amount > 0)
                                            -{{ number_format($inv->discount_amount, 2) }}
                                            <span class="text-[10px] text-slate-400">({{ $inv->discount_type === 'percentage' ? $inv->discount_value.'%' : 'ثابت' }})</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-mono font-extrabold text-slate-900">
                                        {{ number_format($inv->total_amount, 2) }} ج.م
                                    </td>
                                    <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                                        {{ number_format($inv->paid_amount, 2) }}
                                    </td>
                                    <td class="py-3 px-4 font-mono font-bold {{ $inv->remaining_balance > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                        {{ number_format($inv->remaining_balance, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($inv->payment_status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                مدفوع بالكامل
                                            </span>
                                        @elseif($inv->payment_status === 'partial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                سداد جزئي
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                                غير مدفوع
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('invoices.show', $inv) }}" 
                                               class="p-1.5 rounded-lg text-slate-500 hover:text-[#0E4DA4] hover:bg-blue-50 transition" title="عرض وطباعة الفاتورة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('invoices.edit', $inv) }}" 
                                               class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition" title="تعديل">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
