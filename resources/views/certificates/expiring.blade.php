@extends('layouts.app')

@section('title', 'الشهادات المنتهية والقريبة من الانتهاء - Expiring Certificates')

@section('content')
<div class="space-y-4">

    <!-- IsoOx Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="متابعة صلاحية وتجديد شهادات الفحص"
        new-url="{{ route('certificates.create') }}"
        new-text="إصدار شهادة جديدة"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('certificates.expiring') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث برقم الشهادة، العميل، اسم المعدة، أمر الشغل..."
        :paginator="$certificates"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3 min-w-[280px]">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">نطاق الصلاحية (Expiry Timeframe)</span>
                <div class="grid grid-cols-2 gap-1.5">
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => 'expired'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ request('timeframe') === 'expired' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        منتهية بالفعل
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '7'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ request('timeframe') === '7' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        خلال 7 أيام
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '15'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ request('timeframe') === '15' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        خلال 15 يوماً
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '30'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ (request('timeframe', '30') === '30') ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        خلال شهر (30 يوم)
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '60'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ request('timeframe') === '60' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        خلال شهرين (60 يوم)
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => 'all_expiring'])) }}" 
                       class="px-2.5 py-1.5 rounded text-xs font-semibold text-center transition {{ request('timeframe') === 'all_expiring' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        الكل (منتهية وقريبة)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">نوع المعدة (Equipment Type)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['type', 'page']), ['type' => 'crane'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'crane' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        الأوناش (Crane)
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['type', 'page']), ['type' => 'accessory'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'accessory' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        المهمات (Accessory)
                    </a>
                    <a href="{{ route('certificates.expiring', array_merge(request()->except(['type', 'page']), ['type' => 'general'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'general' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        عام (General)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('certificates.expiring') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Secondary Sub-Menu Tabs Navigation (Unified Odoo Theme) -->
        <div class="bg-white border border-slate-200 rounded-xl p-1.5 flex items-center justify-between gap-2 shadow-2xs flex-wrap">
            <div class="flex items-center gap-1 flex-wrap">
                <a href="{{ route('certificates.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <img src="{{ asset('branding/IsoOx-icons/documents.svg') }}" class="w-4 h-4">
                    <span>جميع الشهادات</span>
                </a>
                <a href="{{ route('certificates.index', ['status' => 'issued']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>السارية المعتمدة</span>
                </a>
                <a href="{{ route('certificates.index', ['status' => 'draft']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>المسودات</span>
                </a>
                <a href="{{ route('certificates.expiring') }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-[#0E4DA4] text-white shadow-xs">
                    <img src="{{ asset('branding/IsoOx-icons/timer.svg') }}" class="w-4 h-4 brightness-0 invert">
                    <span>الشهادات القريبة من الانتهاء</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20 font-mono">{{ $stats['due_30'] + $stats['expired'] }}</span>
                </a>
                <a href="{{ route('certificate-templates.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-4 h-4">
                    <span>ستوديو قوالب الفحص</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('certificates.create') }}" 
                   class="px-3 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>إصدار شهادة جديدة</span>
                </a>
            </div>
        </div>

        <!-- Expiration KPI Cards (Unified Serene Theme) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">منتهية الصلاحية</span>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-2xl font-black text-slate-900 font-en">{{ $stats['expired'] }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold">شهادة مستحقة</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/product_expiry.svg') }}" class="w-full h-full object-contain opacity-75">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">تنتهي خلال 7 أيام</span>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-2xl font-black text-[#0E4DA4] font-en">{{ $stats['due_7'] }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold">متابعة عاجلة</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/timer.svg') }}" class="w-full h-full object-contain opacity-75">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">تنتهي خلال 30 يوم</span>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-2xl font-black text-[#0E4DA4] font-en">{{ $stats['due_30'] }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold">جدولة فحص دوري</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/calendar.svg') }}" class="w-full h-full object-contain opacity-75">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">إجمالي السارية بالنظام</span>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-2xl font-black text-[#0E4DA4] font-en">{{ $stats['total_active'] }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold">صالحة ومعتمدة</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/quality_control.svg') }}" class="w-full h-full object-contain opacity-75">
                </div>
            </div>
        </div>

        <!-- Filter Sub-nav Pills (Unified Theme) -->
        <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-xs text-slate-500 font-bold ml-1">عرض حسب المهلة:</span>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '30'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe', '30') === '30' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    خلال شهر (30 يوم)
                </a>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '7'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe') === '7' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    خلال 7 أيام
                </a>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '15'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe') === '15' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    خلال 15 يوم
                </a>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => '60'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe') === '60' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    خلال شهرين
                </a>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => 'expired'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe') === 'expired' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    منتهية الصلاحية
                </a>
                <a href="{{ route('certificates.expiring', array_merge(request()->except(['timeframe', 'page']), ['timeframe' => 'all_expiring'])) }}" 
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ request('timeframe') === 'all_expiring' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    الجميع
                </a>
            </div>

            <!-- Customer Quick Filter Dropdown -->
            <form action="{{ route('certificates.expiring') }}" method="GET" class="flex items-center gap-1.5">
                <input type="hidden" name="timeframe" value="{{ request('timeframe', '30') }}">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <select name="customer_id" onchange="this.form.submit()" class="text-xs border border-slate-300 rounded-md py-1 px-2.5 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0E4DA4]">
                    <option value="">جميع العملاء والشركات</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('customer_id') || request('type') || request('search'))
                    <a href="{{ route('certificates.expiring', ['timeframe' => request('timeframe', '30')]) }}" class="text-xs text-red-600 hover:underline px-1 font-semibold">
                        مسح
                    </a>
                @endif
            </form>
        </div>

        <!-- Certificates Data Table -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs border-collapse">
                    <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                        <tr>
                            <th class="px-3 py-2.5 font-bold">رقم الشهادة</th>
                            <th class="px-3 py-2.5 font-bold">العميل / المنشأة</th>
                            <th class="px-3 py-2.5 font-bold">المعدة والمواصفات</th>
                            <th class="px-3 py-2.5 font-bold">حمل التشغيل (SWL)</th>
                            <th class="px-3 py-2.5 font-bold">تاريخ الفحص</th>
                            <th class="px-3 py-2.5 font-bold">تاريخ الاستحقاق (Due Date)</th>
                            <th class="px-3 py-2.5 font-bold text-center">المهلة المتبقية</th>
                            <th class="px-3 py-2.5 font-bold text-center">إجراء التجديد السريع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($certificates as $cert)
                            @php
                                $dueDate = \Carbon\Carbon::parse($cert->next_examination_due_date);
                                $isExpired = $dueDate->lt($today);
                                $daysDiff = $today->diffInDays($dueDate, false); // negative if expired
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <!-- Certificate Number -->
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('certificates.show', $cert) }}" class="font-bold text-[#0E4DA4] hover:underline font-mono text-xs">
                                            {{ $cert->certificate_number }}
                                        </a>
                                        @if($cert->equipment_type === 'crane')
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-semibold border border-slate-200">ونش</span>
                                        @elseif($cert->equipment_type === 'accessory')
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-semibold border border-slate-200">مهمات</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-semibold border border-slate-200">عام</span>
                                        @endif
                                    </div>
                                    @if($cert->order_no)
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">PO: {{ $cert->order_no }}</div>
                                    @endif
                                </td>

                                <!-- Customer -->
                                <td class="px-3 py-3">
                                    <span class="font-bold text-slate-800 block">{{ $cert->customer->name ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 truncate max-w-[160px] block">{{ $cert->examination_address }}</span>
                                </td>

                                <!-- Equipment -->
                                <td class="px-3 py-3">
                                    <div class="max-w-[220px]">
                                        <span class="font-medium text-slate-700 block truncate" title="{{ $cert->equipment_description }}">
                                            {{ $cert->equipment_description }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $cert->reference_standard }}</span>
                                    </div>
                                </td>

                                <!-- SWL -->
                                <td class="px-3 py-3 font-mono font-bold text-slate-800">
                                    {{ $cert->swl }}
                                </td>

                                <!-- Exam Date -->
                                <td class="px-3 py-3 font-mono text-slate-600">
                                    {{ \Carbon\Carbon::parse($cert->thorough_examination_date)->format('Y-m-d') }}
                                </td>

                                <!-- Due Date -->
                                <td class="px-3 py-3 font-mono font-bold {{ $isExpired ? 'text-slate-900' : 'text-slate-800' }}">
                                    {{ $dueDate->format('Y-m-d') }}
                                </td>

                                <!-- Countdown Status Pill (Serene & Clean) -->
                                <td class="px-3 py-3 text-center">
                                    @if($isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>منتهية منذ {{ abs($daysDiff) }} يوم</span>
                                        </span>
                                    @elseif($daysDiff === 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>تستحق اليوم</span>
                                        </span>
                                    @elseif($daysDiff <= 7)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>باقي {{ $daysDiff }} أيام</span>
                                        </span>
                                    @elseif($daysDiff <= 30)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-[#0E4DA4] border border-blue-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#0E4DA4]"></span>
                                            <span>باقي {{ $daysDiff }} يوماً</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>باقي {{ $daysDiff }} يوم</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Quick Renewal Actions (Unified Odoo Blue) -->
                                <td class="px-3 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- One-click Renewal Action -->
                                        <a href="{{ route('certificates.create', ['renew_from' => $cert->id, 'type' => $cert->equipment_type]) }}" 
                                           title="إصدار شهادة فحص دوري جديدة بناءً على بيانات هذه الشهادة"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-[11px] transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span>فحص دوري جديد</span>
                                        </a>

                                        <!-- PDF download -->
                                        <a href="{{ route('certificates.pdf', ['certificate' => $cert, 'download' => 1]) }}" 
                                           title="تحميل الشهادة السابقة PDF"
                                           class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-slate-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>

                                        <!-- View Details -->
                                        <a href="{{ route('certificates.show', $cert) }}" 
                                           title="عرض التفاصيل"
                                           class="p-1 rounded text-slate-400 hover:text-[#0E4DA4] hover:bg-slate-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <img src="{{ asset('branding/IsoOx-icons/documents.svg') }}" class="w-6 h-6 opacity-40">
                                        </div>
                                        <span class="text-sm font-semibold text-slate-600">لا توجد شهادات مطابقة للفلاتر المحددة</span>
                                        <span class="text-xs text-slate-400">جميع الشهادات ضمن النطاق سارية ولا يوجد فحص مستحق حالياً</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer / Pagination -->
            <div class="px-4 py-2.5 border-t border-slate-200 bg-[#F8FAFC] flex items-center justify-between text-xs text-slate-500">
                <span>عرض {{ $certificates->count() }} من أصل {{ $certificates->total() }} شهادة</span>
                <div>
                    {{ $certificates->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
