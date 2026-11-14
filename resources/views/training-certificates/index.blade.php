@extends('layouts.app')

@section('title', 'شهادات التدريب المعتمدة - Training Certificates')

@section('content')
<div>

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="سجل شهادات التدريب المعتمدة"
        new-url="{{ route('training-certificates.create') }}"
        new-text="New"
        new-color="bg-[#006837]"
        search-action="{{ route('training-certificates.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث بالمتدرب، الرقم التسلسلي، الدورة..."
        :paginator="$certificates"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">الحالة (Status)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('training-certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'issued'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'issued' ? 'bg-[#006837] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        ✓ سارية ومعتمدة (In Progress)
                    </a>
                    <a href="{{ route('training-certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'draft'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'draft' ? 'bg-sky-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مسودة (Draft)
                    </a>
                    <a href="{{ route('training-certificates.index', array_merge(request()->except(['status', 'page']), ['status' => 'revoked'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'revoked' ? 'bg-red-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        ملغاة (Revoked)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('training-certificates.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
                <span class="text-[10px] text-slate-400 font-en">Odoo 17 Filter Logic</span>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-3 space-y-3">

        <!-- Batch Selection Toolbar (Appears when rows are selected) -->
        <div id="batchActionBar" class="hidden bg-slate-900 text-white px-4 py-2.5 rounded-lg flex items-center justify-between shadow-lg text-xs animate-fade-in">
        <div class="flex items-center gap-3">
            <span class="font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                تم تحديد <span id="selectedCount" class="font-en text-amber-300 font-extrabold">0</span> سجل
            </span>
            <span class="text-slate-400">|</span>
            <button type="button" onclick="batchPrintPdf()" class="hover:text-emerald-300 font-medium flex items-center gap-1 transition">
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

    <!-- Active Filters Pills Bar (if filters are active) -->
    @if(request()->hasAny(['search', 'status']))
        <div class="flex items-center gap-2 text-xs flex-wrap px-1">
            <span class="text-slate-400">الفلاتر النشطة:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 text-xs">
                    بحث: "{{ request('search') }}"
                    <a href="{{ route('training-certificates.index', request()->except(['search', 'page'])) }}" class="hover:text-red-600 font-bold">×</a>
                </span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-[#006837] text-xs font-semibold">
                    الحالة: {{ request('status') }}
                    <a href="{{ route('training-certificates.index', request()->except(['status', 'page'])) }}" class="hover:text-red-600 font-bold">×</a>
                </span>
            @endif
            <a href="{{ route('training-certificates.index') }}" class="text-[#0E4DA4] hover:underline text-xs font-semibold mr-1">
                مسح الكل
            </a>
        </div>
    @endif

    <!-- 1. Odoo Data Table View (Default) -->
    <div id="odooListView" class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden transition-all duration-150">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <!-- Odoo Table Header -->
                <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                    <tr>
                        <th class="w-10 px-3 py-2.5 text-center">
                            <input type="checkbox" 
                                   id="masterCheckbox" 
                                   onchange="toggleSelectAll(this)" 
                                   class="rounded border-slate-300 text-[#006837] focus:ring-[#006837] w-3.5 h-3.5 cursor-pointer">
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">
                            <span class="flex items-center gap-1">
                                <span>Number</span>
                                <span class="text-slate-400 text-[10px]">#</span>
                            </span>
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Certificate Holder</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Program / Course</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">
                            <span class="flex items-center gap-1 text-[#006837]">
                                <span>Certificate Date</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </span>
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Instructor</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Activities</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Hours</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Status</th>
                        <th class="w-16 px-3 py-2.5 text-center text-slate-400">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </th>
                    </tr>
                </thead>

                <!-- Odoo Table Rows -->
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/90 transition-colors group">
                            <!-- Checkbox -->
                            <td class="w-10 px-3 py-2.5 text-center">
                                <input type="checkbox" 
                                       value="{{ $cert->id }}" 
                                       data-pdf="{{ route('training-certificates.pdf', $cert) }}"
                                       onchange="updateBatchBar()" 
                                       class="cert-row-checkbox rounded border-slate-300 text-[#006837] focus:ring-[#006837] w-3.5 h-3.5 cursor-pointer">
                            </td>

                            <!-- Serial Number (Bold, Odoo Style e.g. S00016) -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <a href="{{ route('training-certificates.show', $cert) }}" 
                                   class="font-mono font-bold text-slate-900 group-hover:text-[#006837] transition font-en tracking-wide text-xs">
                                    {{ $cert->certificate_serial }}
                                </a>
                            </td>

                            <!-- Certificate Holder (Trainee Name + Company) -->
                            <td class="px-3 py-2.5">
                                <div class="font-medium text-slate-900">
                                    <a href="{{ route('training-certificates.show', $cert) }}" class="hover:underline">
                                        {{ $cert->trainee_name }}
                                    </a>
                                </div>
                                @if($cert->trainee?->company)
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $cert->trainee->company }}</div>
                                @endif
                            </td>

                            <!-- Program / Course Title -->
                            <td class="px-3 py-2.5">
                                <div class="font-semibold text-slate-800">{{ $cert->program_title }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $cert->training_scope }}</div>
                            </td>

                            <!-- Date (Highlight Red if expired) -->
                            <td class="px-3 py-2.5 whitespace-nowrap font-en">
                                @if($cert->expiry_date && $cert->expiry_date->isPast())
                                    <span class="text-rose-600 font-bold" title="منتهية بتاريخ {{ $cert->expiry_date->format('d/m/Y') }}">
                                        {{ $cert->issue_date->format('m/d/Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-600">
                                        {{ $cert->issue_date->format('m/d/Y') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Instructor (Avatar Pill + Name) -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-[#714B67] text-white flex items-center justify-center font-bold font-en text-[10px] uppercase shrink-0">
                                        {{ mb_substr($cert->instructor_name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-slate-700 text-xs">{{ $cert->instructor_name ?? 'Hany Nabil' }}</span>
                                </div>
                            </td>

                            <!-- Activities (Icon + Label, Odoo Reference) -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-slate-600 text-[11px]">
                                    <span class="text-emerald-600">🎓</span>
                                    <span>دورة معتمدة</span>
                                </div>
                            </td>

                            <!-- Hours -->
                            <td class="px-3 py-2.5 text-center font-en font-semibold text-slate-700 whitespace-nowrap">
                                {{ $cert->hours }} hrs
                            </td>

                            <!-- Status (Odoo Pill Badges: In Progress / Draft / Revoked) -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                @if($cert->status === 'issued')
                                    <span class="inline-flex items-center justify-center min-w-[90px] px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#15803D] border border-[#22A447]/20">
                                        In Progress
                                    </span>
                                @elseif($cert->status === 'draft')
                                    <span class="inline-flex items-center justify-center min-w-[90px] px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E0F2FE] text-[#0369A1] border border-sky-200">
                                        Draft
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-[90px] px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FFE4E6] text-[#BE123C] border border-rose-200">
                                        Revoked
                                    </span>
                                @endif
                            </td>

                            <!-- Quick Action Icons -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                    <a href="{{ route('training-certificates.show', $cert) }}" 
                                       class="p-1 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" 
                                       title="معاينة الشهادة">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition" 
                                       title="تحميل / طباعة PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.edit', $cert) }}" 
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
                                    <div class="font-bold text-slate-700 text-sm">لا توجد شهادات تدريب مطابقة</div>
                                    <p class="text-xs text-slate-500">لم يتم العثور على أي سجلات في هذا النطاق، يمكنك إضافة شهادة جديدة الآن.</p>
                                    <a href="{{ route('training-certificates.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md bg-[#006837] text-white font-bold text-xs hover:bg-[#00522c] transition">
                                        + إصدار شهادة تدريب جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Odoo Kanban View (Cards Grid) -->
    <div id="odooKanbanView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($certificates as $cert)
            <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-xs hover:shadow-md transition relative flex flex-col justify-between group">
                <div>
                    <!-- Card Top: Serial & Status -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="font-mono font-bold text-[#006837] text-xs font-en">
                            {{ $cert->certificate_serial }}
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

                    <!-- Holder Name -->
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-[#006837] transition">
                        <a href="{{ route('training-certificates.show', $cert) }}">
                            {{ $cert->trainee_name }}
                        </a>
                    </h3>
                    @if($cert->trainee?->company)
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $cert->trainee->company }}</p>
                    @endif

                    <!-- Course Title & Scope -->
                    <div class="mt-3 p-2.5 rounded bg-slate-50 border border-slate-100 text-xs">
                        <div class="font-bold text-slate-800">{{ $cert->program_title }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $cert->training_scope }}</div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-full bg-[#714B67] text-white flex items-center justify-center font-bold font-en text-[10px] uppercase">
                            {{ mb_substr($cert->instructor_name ?? 'A', 0, 1) }}
                        </div>
                        <span class="font-en text-[11px]">{{ $cert->hours }} hrs</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('training-certificates.pdf', $cert) }}" target="_blank" class="p-1 rounded text-slate-400 hover:text-red-600 transition" title="PDF">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </a>
                        <a href="{{ route('training-certificates.show', $cert) }}" class="p-1 rounded text-slate-400 hover:text-slate-800 transition" title="عرض">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-slate-400">
                لا توجد شهادات معروضة
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
        alert('جاري تجهيز تقرير Excel للشهادات المحددة...');
    }
</script>
@endsection
