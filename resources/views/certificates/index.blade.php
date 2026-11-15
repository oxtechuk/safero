@extends('layouts.app')

@section('title', 'شهادات الفحص الشامل - Inspection Certificates')

@section('content')
<div class="space-y-4">

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="شهادات الفحص والمعايرة"
        new-url="{{ route('certificates.create') }}"
        new-text="New"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('certificates.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث برقم الشهادة، أمر الشغل، العميل، المعدة..."
        :paginator="$certificates"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">الحالة (Status)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'issued'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'issued' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        ✓ سارية ومعتمدة (In Progress)
                    </a>
                    <a href="{{ route('certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'draft'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'draft' ? 'bg-sky-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مسودة (Draft)
                    </a>
                    <a href="{{ route('certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'revoked'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'revoked' ? 'bg-red-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        ملغاة (Revoked)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('certificates.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
                <span class="text-[10px] text-slate-400 font-en">IsoOx 17 Filter Logic</span>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-3 space-y-3">

        <!-- Batch Selection Toolbar -->
    <div id="batchActionBar" class="hidden bg-slate-900 text-white px-4 py-2.5 rounded-lg flex items-center justify-between shadow-lg text-xs animate-fade-in">
        <div class="flex items-center gap-3">
            <span class="font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                تم تحديد <span id="selectedCount" class="font-en text-amber-300 font-extrabold">0</span> سجل
            </span>
            <span class="text-slate-400">|</span>
            <button type="button" onclick="batchPrintPdf()" class="hover:text-blue-300 font-medium flex items-center gap-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة مجمعة</span>
            </button>
            <button type="button" onclick="batchExportCsv()" class="hover:text-sky-300 font-medium flex items-center gap-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>تصدير Excel</span>
            </button>
        </div>
        <button type="button" onclick="clearAllSelections()" class="text-slate-400 hover:text-white transition">
            إلغاء التحديد ✕
        </button>
    </div>

    <!-- Active Filters Pills -->
    @if(request()->hasAny(['search', 'status']))
        <div class="flex items-center gap-2 text-xs flex-wrap px-1">
            <span class="text-slate-400">الفلاتر النشطة:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 text-xs">
                    بحث: "{{ request('search') }}"
                    <a href="{{ route('certificates.index', request()->except(['search', 'page'])) }}" class="hover:text-red-600 font-bold">×</a>
                </span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-[#0E4DA4] text-xs font-semibold">
                    الحالة: {{ request('status') }}
                    <a href="{{ route('certificates.index', request()->except(['status', 'page'])) }}" class="hover:text-red-600 font-bold">×</a>
                </span>
            @endif
            <a href="{{ route('certificates.index') }}" class="text-[#0E4DA4] hover:underline text-xs font-semibold mr-1">
                مسح الكل
            </a>
        </div>
    @endif

    <!-- 1. IsoOx Data Table View -->
    <div id="IsoOxListView" class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                    <tr>
                        <th class="w-10 px-3 py-2.5 text-center">
                            <input type="checkbox" 
                                   id="masterCheckbox" 
                                   onchange="toggleSelectAll(this)" 
                                   class="rounded border-slate-300 text-[#0E4DA4] focus:ring-[#0E4DA4] w-3.5 h-3.5 cursor-pointer">
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Number</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Customer</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Equipment / Standard</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">SWL</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 whitespace-nowrap">
                            <span>Next Exam</span>
                            <span class="text-slate-400 font-mono text-[10px]">^</span>
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Inspector</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Activities</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Status</th>
                        <th class="w-16 px-3 py-2.5 text-center text-slate-400">
                            <!-- Column Config Icon (Image 1 match) -->
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/90 transition-colors group">
                            <!-- Checkbox -->
                            <td class="w-10 px-3 py-2.5 text-center">
                                <input type="checkbox" 
                                       value="{{ $cert->id }}" 
                                       data-pdf="{{ route('certificates.pdf', $cert) }}"
                                       onchange="updateBatchBar()" 
                                       class="cert-row-checkbox rounded border-slate-300 text-[#0E4DA4] focus:ring-[#0E4DA4] w-3.5 h-3.5 cursor-pointer">
                            </td>

                            <!-- Serial Number (Bold, IsoOx Style) -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <a href="{{ route('certificates.show', $cert) }}" 
                                   class="font-mono font-bold text-slate-900 group-hover:text-[#0E4DA4] transition font-en tracking-wide text-xs">
                                    {{ $cert->certificate_number }}
                                </a>
                                @if($cert->order_no)
                                    <div class="text-[10px] text-slate-400 font-en">PO #{{ $cert->order_no }}</div>
                                @endif
                            </td>

                            <!-- Customer Name -->
                            <td class="px-3 py-2.5">
                                <div class="font-medium text-slate-900">
                                    <a href="{{ route('customers.show', $cert->customer) }}" class="hover:underline">
                                        {{ $cert->customer?->name }}
                                    </a>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $cert->examination_address }}</div>
                            </td>

                            <!-- Equipment / Standard -->
                            <td class="px-3 py-2.5">
                                <div class="font-semibold text-slate-800">{{ $cert->equipment_description }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-xs font-en">{{ $cert->reference_standard }} | Qty: {{ $cert->quantity }}</div>
                            </td>

                            <!-- SWL -->
                            <td class="px-3 py-2.5 whitespace-nowrap font-en">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-bold text-slate-800 text-[11px]">
                                    {{ $cert->swl }}
                                </span>
                            </td>

                            <!-- Next Due Date (Red if overdue, Image 1 match) -->
                            <td class="px-3 py-2.5 whitespace-nowrap font-en">
                                @if($cert->next_examination_due_date && $cert->next_examination_due_date->isPast())
                                    <span class="text-rose-600 font-bold" title="مستحق الفحص / متأخر">
                                        {{ $cert->next_examination_due_date->format('m/d/Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-600">
                                        {{ $cert->next_examination_due_date?->format('m/d/Y') ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Inspector (Image 1 Match: Pink/Plum Square Badge + Name) -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded bg-[#9E1B62] text-white flex items-center justify-center font-bold font-en text-[10px] uppercase shrink-0">
                                        {{ mb_substr($cert->issuer_name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-slate-700 text-xs font-medium">{{ $cert->issuer_name ?? 'Administrator' }}</span>
                                </div>
                            </td>

                            <!-- Activities Column (Image 1 Match: Phone, Clock, Upload, Mail) -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 text-xs">
                                    <!-- WhatsApp / Call -->
                                    <a href="https://wa.me/?text={{ urlencode('Safero Inspection Certificate: ' . $cert->certificate_number . ' - ' . route('certificates.show', $cert)) }}" 
                                       target="_blank"
                                       class="text-emerald-600 hover:text-emerald-700 p-0.5" 
                                       title="مشاركة عبر واتساب / اتصال">
                                        <svg class="w-3.5 h-3.5 inline" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </a>
                                    <!-- Schedule / Clock -->
                                    <span class="text-slate-400 p-0.5" title="موعد الفحص الدوري">
                                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <!-- PDF Document Upload / Download -->
                                    <a href="{{ route('certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="text-emerald-600 hover:text-emerald-700 p-0.5" 
                                       title="مستند PDF المعتمد">
                                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </a>
                                    <!-- Verification / QR Tool -->
                                    <a href="{{ route('certificates.verify.lookup', ['cert' => $cert->certificate_number]) }}" 
                                       class="text-amber-500 hover:text-amber-600 p-0.5" 
                                       title="التحقق الرقمي من الشهادة">
                                        <svg class="w-3.5 h-3.5 inline" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>

                            <!-- Status (Image 1 Match: Saturated Rounded Full Pills) -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                @if($cert->status === 'issued')
                                    <span class="inline-flex items-center justify-center min-w-[95px] px-3 py-0.5 rounded-full text-[11px] font-bold bg-[#198754] text-white shadow-2xs font-en">
                                        In Progress
                                    </span>
                                @elseif($cert->status === 'draft')
                                    <span class="inline-flex items-center justify-center min-w-[95px] px-3 py-0.5 rounded-full text-[11px] font-bold bg-[#017E84] text-white shadow-2xs font-en">
                                        Quotation
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-[95px] px-3 py-0.5 rounded-full text-[11px] font-bold bg-rose-600 text-white shadow-2xs font-en">
                                        Revoked
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                    <a href="{{ route('certificates.show', $cert) }}" 
                                       class="p-1 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" 
                                       title="معاينة الشهادة">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition" 
                                       title="تحميل / طباعة PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.edit', $cert) }}" 
                                       class="p-1 rounded text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" 
                                       title="تعديل">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-slate-700 text-sm">لا توجد شهادات فحص مطابقة</div>
                                    <p class="text-xs text-slate-500">لم يتم العثور على أي شهادات فحص في هذا النطاق، يمكنك إصدار شهادة فحص جديدة الآن.</p>
                                    <a href="{{ route('certificates.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md bg-[#0E4DA4] text-white font-bold text-xs hover:bg-[#0a3a7d] transition">
                                        + إصدار شهادة فحص جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. IsoOx Kanban View -->
    <div id="IsoOxKanbanView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($certificates as $cert)
            <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-xs hover:shadow-md transition relative flex flex-col justify-between group">
                <div>
                    <!-- Top -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="font-mono font-bold text-[#0E4DA4] text-xs font-en">
                            {{ $cert->certificate_number }}
                        </span>
                        @if($cert->status === 'issued')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E8F7EC] text-[#15803D]">
                                In Progress
                            </span>
                        @elseif($cert->status === 'draft')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E0F2FE] text-[#0369A1]">
                                Draft
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FFE4E6] text-[#BE123C]">
                                Revoked
                            </span>
                        @endif
                    </div>

                    <!-- Customer -->
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-[#0E4DA4] transition">
                        <a href="{{ route('certificates.show', $cert) }}">
                            {{ $cert->customer?->name }}
                        </a>
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $cert->examination_address }}</p>

                    <!-- Equipment & SWL -->
                    <div class="mt-3 p-2.5 rounded bg-slate-50 border border-slate-100 text-xs">
                        <div class="font-bold text-slate-800">{{ $cert->equipment_description }}</div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1 font-en">
                            <span>SWL: <strong>{{ $cert->swl }}</strong></span>
                            <span>Exam: {{ $cert->thorough_examination_date?->format('m/d/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-full bg-[#0E4DA4] text-white flex items-center justify-center font-bold font-en text-[10px] uppercase">
                            {{ mb_substr($cert->issuer_name ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-[11px]">{{ $cert->issuer_name ?? 'Inspector' }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('certificates.pdf', $cert) }}" target="_blank" class="p-1 rounded text-slate-400 hover:text-red-600 transition" title="PDF">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </a>
                        <a href="{{ route('certificates.show', $cert) }}" class="p-1 rounded text-slate-400 hover:text-slate-800 transition" title="عرض">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-slate-400">
                لا توجد شهادات فحص معروضة
            </div>
        @endforelse
    </div>

    </div>
</div>

<!-- Interactive Batch Actions Script -->
<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.cert-row-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateBatchBar();
    }

    function updateBatchBar() {
        const checked = document.querySelectorAll('.cert-row-checkbox:checked');
        const count = checked.length;
        const bar = document.getElementById('batchActionBar');
        const countSpan = document.getElementById('selectedCount');
        const master = document.getElementById('masterCheckbox');

        if (countSpan) countSpan.textContent = count;
        if (bar) {
            if (count > 0) {
                bar.classList.remove('hidden');
            } else {
                bar.classList.add('hidden');
                if (master) master.checked = false;
            }
        }
    }

    function clearAllSelections() {
        const checkboxes = document.querySelectorAll('.cert-row-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        const master = document.getElementById('masterCheckbox');
        if (master) master.checked = false;
        updateBatchBar();
    }

    function batchPrintPdf() {
        const checked = document.querySelectorAll('.cert-row-checkbox:checked');
        checked.forEach(cb => {
            const pdfUrl = cb.getAttribute('data-pdf');
            if (pdfUrl) {
                window.open(pdfUrl, '_blank');
            }
        });
    }

    function batchExportCsv() {
        alert('جاري تجهيز تقرير Excel لشهادات الفحص المحددة...');
    }
</script>
@endsection
