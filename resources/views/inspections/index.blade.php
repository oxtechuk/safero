@extends('layouts.app')

@section('title', 'سجل الفحوصات الميدانية - Field Inspections')

@section('content')
<div class="space-y-4">

    <!-- IsoOx Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="جلسات الفحص الميداني (Field Inspections)"
        new-url="{{ route('inspections.start') }}"
        new-text="بدء فحص جديد"
        new-color="bg-[#006837]"
        search-action="{{ route('inspections.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث برقم الفحص، اسم المعدة، العميل، رقم الأصل..."
        :paginator="$inspections"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">حالة الفحص (Status)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('inspections.index', array_merge(request()->except(['status', 'page']), ['status' => 'draft'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'draft' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مسودة / جاري الفحص
                    </a>
                    <a href="{{ route('inspections.index', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'completed' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مكتمل (جاهز لإصدار الشهادة)
                    </a>
                    <a href="{{ route('inspections.index', array_merge(request()->except(['status', 'page']), ['status' => 'certified'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'certified' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        تم إصدار الشهادة
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('inspections.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Top Actions Bar -->
        <div class="bg-white border border-slate-200 rounded-xl p-2 flex items-center justify-between gap-2 shadow-2xs flex-wrap">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a href="{{ route('inspections.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('status') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    الكل ({{ $stats['total'] }})
                </a>
                <a href="{{ route('inspections.index', ['status' => 'draft']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'draft' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    مسودة ({{ $stats['draft'] }})
                </a>
                <a href="{{ route('inspections.index', ['status' => 'completed']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'completed' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    مكتمل ({{ $stats['completed'] }})
                </a>
                <a href="{{ route('inspections.index', ['status' => 'certified']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'certified' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    تم إصدار الشهادة ({{ $stats['certified'] }})
                </a>
            </div>

            <a href="{{ route('inspections.start') }}" 
               class="px-4 py-2 rounded-lg bg-[#006837] hover:bg-[#00522c] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>بدء فحص ميداني جديد</span>
            </a>
        </div>

        <!-- Quick KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                <span class="text-[11px] font-bold text-slate-500 block">إجمالي الفحوصات الميدانية</span>
                <span class="text-xl font-black text-[#0E4DA4] font-mono">{{ $stats['total'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                <span class="text-[11px] font-bold text-slate-500 block">معدات آمنة (Safe to Use)</span>
                <span class="text-xl font-black text-emerald-600 font-mono">{{ $stats['safe'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                <span class="text-[11px] font-bold text-slate-500 block">معدات بها عيوب (Unsafe)</span>
                <span class="text-xl font-black text-red-600 font-mono">{{ $stats['unsafe'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                <span class="text-[11px] font-bold text-slate-500 block">تم تحويلها لشهادات معتمدة</span>
                <span class="text-xl font-black text-purple-600 font-mono">{{ $stats['certified'] }}</span>
            </div>
        </div>

        <!-- Inspections Table -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            @if($inspections->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">لا توجد عمليات فحص ميدانية مسجلة حتى الآن</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">ابدأ فحص جديد باختيار العميل والمعدة وتعبئة الشيك ليست من الموبايل في الموقع.</p>
                    <a href="{{ route('inspections.start') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#006837] text-white text-xs font-bold hover:bg-[#00522c] transition shadow-xs">
                        + بدء أول فحص ميداني
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-600 text-[11px] font-bold">
                            <tr>
                                <th class="py-3 px-4">رقم الفحص</th>
                                <th class="py-3 px-4">العميل</th>
                                <th class="py-3 px-4">نوع واسم المعدة</th>
                                <th class="py-3 px-4">رقم الأصل / السيريال</th>
                                <th class="py-3 px-4">تاريخ الفحص</th>
                                <th class="py-3 px-4 text-center">النتيجة الشاملة</th>
                                <th class="py-3 px-4 text-center">حالة الفحص</th>
                                <th class="py-3 px-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($inspections as $insp)
                                @php $summary = $insp->checklistSummary(); @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-[#0E4DA4]">
                                        <a href="{{ route('inspections.inspect', $insp) }}" class="hover:underline">
                                            {{ $insp->inspection_number }}
                                        </a>
                                        @if($insp->doc_number)
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $insp->doc_number }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">
                                        {{ $insp->customer->name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-800">{{ $insp->equipment_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $insp->reference_standard }}</div>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-600">
                                        {{ $insp->asset_tag ?: ($insp->serial_no ?: '—') }}
                                        @if($insp->swl)
                                            <span class="block text-[10px] text-emerald-700 font-bold">SWL: {{ $insp->swl }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-600">
                                        {{ $insp->inspection_date ? $insp->inspection_date->format('Y-m-d') : '—' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($insp->overall_condition === 'safe')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>آمن للاستخدام</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                <span>غير آمن / به عيوب</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($insp->status === 'certified' && $insp->certificate)
                                            <a href="{{ route('certificates.show', $insp->certificate) }}" 
                                               class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition">
                                                <span>شهادة: {{ $insp->certificate->certificate_number }}</span>
                                            </a>
                                        @elseif($insp->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                مكتمل الفحص
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                مسودة ({{ $summary['pass'] + $summary['fail'] }}/{{ $summary['total'] }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Print Sticker Tag -->
                                            <a href="{{ route('inspections.sticker', $insp) }}" 
                                               target="_blank"
                                               class="p-1.5 rounded-lg text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 transition text-sm"
                                               title="طباعة كارت واستيكر فحص المعدة (Sticker Tag)">
                                                🏷️
                                            </a>

                                            <!-- Go to Mobile Checklist -->
                                            <a href="{{ route('inspections.inspect', $insp) }}" 
                                               class="p-1.5 rounded-lg text-slate-500 hover:text-[#0E4DA4] hover:bg-blue-50 transition"
                                               title="فتح شيك ليست الفحص من الموبايل">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                            </a>

                                            <!-- Convert to Certificate Button -->
                                            @if(!$insp->certificate_id)
                                                <form action="{{ route('inspections.convert', $insp) }}" method="POST" class="inline" onsubmit="return confirm('هل تريد إصدار شهادة الفحص المعتمدة فوراً من هذا الفحص؟');">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="px-2 py-1 rounded-lg text-[10px] font-extrabold bg-[#006837] hover:bg-[#00522c] text-white transition flex items-center gap-1 shadow-2xs"
                                                            title="المرحلة 2: إنشاء الشهادة">
                                                        <span>إصدار شهادة</span>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('certificates.show', $insp->certificate_id) }}" 
                                                   class="p-1.5 rounded-lg text-purple-600 hover:bg-purple-50 transition" title="عرض الشهادة">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $inspections->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
