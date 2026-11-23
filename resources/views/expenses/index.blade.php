@extends('layouts.app')

@section('title', __('سجل المصاريف والنفقات التشغيلية'))

@section('content')
<div class="space-y-4" x-data="{ openCreateModal: false }">

    <!-- Top Breadcrumb & Control Bar -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900">{{ __('سجل المصاريف والنفقات التشغيلية') }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('تسجيل وتصنيف نفقات الفحص، انتقالات المفتشين، الصيانة، والإيجارات لحساب الأرباح الحقيقية') }}</p>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('reports.index') }}" 
                   class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>{{ __('تحليل الأرباح الحقيقية') }}</span>
                </a>

                <button type="button" 
                        @click="openCreateModal = true"
                        class="px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('+ تسجيل مصروف جديد') }}</span>
                </button>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- 4 Expense KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Expenses All Time -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('إجمالي المصروفات المسجلة') }}</span>
                    <span class="text-2xl font-black text-rose-600 font-mono mt-0.5 block">
                        {{ number_format($totalExpenses, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">{{ __('إجمالي') }} {{ $expensesCount }} {{ __('سند صرف') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
            </div>

            <!-- 2. Expenses This Month -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('مصروفات الشهر الحالي') }}</span>
                    <span class="text-2xl font-black text-amber-600 font-mono mt-0.5 block">
                        {{ number_format($thisMonthExpenses, 2) }} <span class="text-xs font-bold text-slate-400">{{ __('ج.م') }}</span>
                    </span>
                    <span class="text-[11px] text-amber-700 font-bold mt-1 block">{{ __('الشهر الجاري') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>

            <!-- 3. Expenses Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 block">{{ __('عدد السندات المعاملات') }}</span>
                    <span class="text-2xl font-black text-slate-800 font-mono mt-0.5 block">
                        {{ $expenses->total() }} <span class="text-xs font-bold text-slate-400">{{ __('سند') }}</span>
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">{{ __('معاملات مالية معتمدة') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <!-- 4. Real Net Profit Link -->
            <div class="bg-gradient-to-br from-[#0E4DA4] to-[#0a3a7d] text-white rounded-2xl p-4 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-blue-200 block">{{ __('تأثير المصاريف على الأرباح') }}</span>
                    <span class="text-sm font-black mt-1 block">{{ __('حساب الأرباح الصافية') }}</span>
                    <a href="{{ route('reports.index') }}" class="text-[11px] text-amber-300 font-bold hover:underline block mt-1">
                        {{ __('عرض تقرير صافي الربح &larr;') }}
                    </a>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-2xs">
            <form action="{{ route('expenses.index') }}" method="GET" class="flex items-center justify-between flex-wrap gap-2 text-xs">
                
                <!-- Period Buttons -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl font-bold flex-wrap">
                    <a href="{{ route('expenses.index', array_merge(request()->except('period'), ['period' => 'all'])) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('كل الفترات') }}
                    </a>
                    <a href="{{ route('expenses.index', array_merge(request()->except('period'), ['period' => 'this_month'])) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('هذا الشهر') }}
                    </a>
                    <a href="{{ route('expenses.index', array_merge(request()->except('period'), ['period' => 'last_month'])) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'last_month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('الشهر الماضي') }}
                    </a>
                    <a href="{{ route('expenses.index', array_merge(request()->except('period'), ['period' => 'this_year'])) }}" 
                       class="px-2.5 py-1 rounded-lg transition {{ $period === 'this_year' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('هذا العام') }}
                    </a>
                </div>

                <!-- Category & Search Filters -->
                <div class="flex items-center gap-2 flex-wrap">
                    <select name="category" onchange="this.form.submit()" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 outline-none">
                        <option value="">-- {{ __('كل بنود المصروفات') }} --</option>
                        @foreach($categories as $key => $cat)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'en' ? $cat['en'] : $cat['ar'] }}
                            </option>
                        @endforeach
                    </select>

                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="{{ __('بحث بالبيان، الرقم، المستلم...') }}" 
                               class="w-48 sm:w-64 px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none">
                    </div>

                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-800 text-white font-bold hover:bg-slate-900 transition">
                        {{ __('بحث') }}
                    </button>

                    @if(request()->hasAny(['period', 'category', 'search']))
                        <a href="{{ route('expenses.index') }}" class="text-xs text-rose-600 font-bold hover:underline">
                            {{ __('إلغاء الفلتر') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200 text-[11px]">
                        <tr>
                            <th class="py-3 px-3 w-10 text-center">#</th>
                            <th class="py-3 px-3">{{ __('رقم السند') }}</th>
                            <th class="py-3 px-4">{{ __('تاريخ الصرف') }}</th>
                            <th class="py-3 px-4">{{ __('بيان المصروف') }}</th>
                            <th class="py-3 px-3 text-center">{{ __('التصنيف') }}</th>
                            <th class="py-3 px-4 text-left rtl:text-left">{{ __('المبلغ') }}</th>
                            <th class="py-3 px-3">{{ __('طريقة الدفع') }}</th>
                            <th class="py-3 px-3">{{ __('المدفوع له / المستلم') }}</th>
                            <th class="py-3 px-3 text-center">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($expenses as $idx => $exp)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-3 text-center font-mono text-slate-400 font-bold">
                                    {{ $expenses->firstItem() + $idx }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-[#0E4DA4]">
                                    {{ $exp->expense_number }}
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-600">
                                    {{ $exp->expense_date->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $exp->title }}</div>
                                    @if($exp->reference_no)
                                        <span class="text-[10px] text-slate-400 font-mono">{{ __('مرجع:') }} {{ $exp->reference_no }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $exp->category_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-left rtl:text-left font-mono font-black text-rose-600 text-sm">
                                    {{ number_format($exp->amount, 2) }} <span class="text-[10px] text-slate-400 font-sans">{{ __('ج.م') }}</span>
                                </td>
                                <td class="py-3 px-3 text-xs">
                                    {{ match($exp->payment_method) {
                                        'cash' => __('نقداً'),
                                        'bank_transfer' => __('تحويل بنكي'),
                                        'cheque' => __('شيك'),
                                        default => $exp->payment_method
                                    } }}
                                </td>
                                <td class="py-3 px-3 text-slate-600">
                                    {{ $exp->paid_to ?: '—' }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('expenses.edit', $exp) }}" 
                                           class="p-1 rounded hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition" 
                                           title="{{ __('تعديل') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('expenses.destroy', $exp) }}" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا السند؟') }}');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded hover:bg-red-100 text-red-600 hover:text-red-800 transition cursor-pointer" title="{{ __('حذف') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-10 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ __('لا توجد مصروفات مسجلة في هذه الفترة') }}</span>
                                        <button type="button" @click="openCreateModal = true" class="text-xs text-[#0E4DA4] font-bold hover:underline cursor-pointer">
                                            {{ __('+ إضافة أول مصروف الآن') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-3 border-t border-slate-200">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Quick Add Expense Modal -->
    <div x-show="openCreateModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.away="openCreateModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-5 bg-rose-600 rounded-full inline-block"></span>
                    <h2 class="text-base font-black text-slate-900">{{ __('تسجيل مصروف ونفقة جديدة') }}</h2>
                </div>
                <button type="button" @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
            </div>

            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ __('بيان المصروف (Description)') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="{{ __('مثال: وقود وانتقالات فحص أوناش الإسكندرية') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('التصنيف (Category)') }} <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white outline-none">
                            @foreach($categories as $k => $c)
                                <option value="{{ $k }}">{{ app()->getLocale() === 'en' ? $c['en'] : $c['ar'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('المبلغ (ج.م) *') }}</label>
                        <input type="number" step="0.01" name="amount" min="0.01" required placeholder="0.00" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono font-bold outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('تاريخ الصرف') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('طريقة الدفع') }} <span class="text-red-500">*</span></label>
                        <select name="payment_method" required class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white outline-none">
                            <option value="cash">{{ __('نقداً (كاش)') }}</option>
                            <option value="bank_transfer">{{ __('تحويل بنكي') }}</option>
                            <option value="cheque">{{ __('شيك') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('المدفوع له (المستلم / الجهة)') }}</label>
                        <input type="text" name="paid_to" placeholder="{{ __('اسم المفتش أو الشركة...') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">{{ __('رقم الإيصال / الفاتورة الخارجية') }}</label>
                        <input type="text" name="reference_no" placeholder="REC-00123" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ __('ملاحظات وتفاصيل إضافية') }}</label>
                    <textarea name="notes" rows="2" placeholder="{{ __('أي تفاصيل تتعلق بأمر الفحص أو المحطة...') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold hover:bg-slate-50 transition cursor-pointer">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-md transition cursor-pointer">
                        {{ __('حفظ واعتماد المصروف') }}
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
@endsection
