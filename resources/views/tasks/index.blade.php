@extends('layouts.app')

@section('title', 'مهام الفحص الميداني وأوامر الشغل - Inspection Tasks & Work Orders')

@section('content')
<div class="space-y-4" x-data="{ currentView: '{{ $currentView }}' }">

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="مهام الفحص الميداني وأوامر الشغل (Tasks &amp; Work Orders)"
        new-url="{{ route('tasks.create') }}"
        new-text="New Task"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('tasks.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث برقم المهمة، أمر الشغل، العميل، المعدة، أو الفاحص..."
        :paginator="$tasks"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">حالة المهمة (Task Status)</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['scheduled' => 'مجدولة', 'in_progress' => 'قيد الفحص', 'completed' => 'مكتملة', 'certified' => 'معتمدة بشهادة'] as $stKey => $stLabel)
                        <a href="{{ route('tasks.index', array_merge(request()->except(['status', 'page']), ['status' => $stKey])) }}" 
                           class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === $stKey ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            {{ $stLabel }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">نوع المعدة (Equipment Type)</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['crane' => 'أوناش وروافع', 'accessory' => 'مهمات رفع', 'general' => 'معدات عامة', 'environmental' => 'بيئي'] as $typeKey => $typeLabel)
                        <a href="{{ route('tasks.index', array_merge(request()->except(['equipment_type', 'page']), ['equipment_type' => $typeKey])) }}" 
                           class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('equipment_type') === $typeKey ? 'bg-purple-700 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            {{ $typeLabel }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
                <span class="text-[10px] text-slate-400 font-en">Odoo Task Matrix</span>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Top Overview Stats & Sync Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-2xs">
            
            <div class="flex items-center gap-4 sm:gap-6 flex-wrap text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                    <span class="text-slate-500">إجمالي المهام:</span>
                    <span class="font-extrabold text-slate-900 font-en">{{ $stats['total'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-slate-500">مجدولة:</span>
                    <span class="font-extrabold text-blue-700 font-en">{{ $stats['scheduled'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-slate-500">قيد الفحص الميداني:</span>
                    <span class="font-extrabold text-amber-700 font-en">{{ $stats['in_progress'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#22A447]"></span>
                    <span class="text-slate-500">فحص مكتمل:</span>
                    <span class="font-extrabold text-[#22A447] font-en">{{ $stats['completed'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span class="text-slate-500">تم إصدار الشهادة:</span>
                    <span class="font-extrabold text-purple-700 font-en">{{ $stats['certified'] }}</span>
                </div>
            </div>

            <!-- Right: Sync All Google Sheets + View Mode Switcher -->
            <div class="flex items-center gap-2 self-end sm:self-auto">
                <form method="POST" action="{{ route('tasks.sync_all') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-3 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs flex items-center gap-1.5 transition active:scale-95" 
                            title="مزامنة فورية لكافة المهام مع جداول بيانات Google Sheets">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>مزامنة Google Sheets</span>
                    </button>
                </form>

                <div class="flex items-center gap-1 bg-slate-100 p-0.5 rounded-lg">
                    <button type="button" 
                            @click="currentView = 'kanban'" 
                            :class="currentView === 'kanban' ? 'bg-white shadow-2xs text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-800'"
                            class="px-2.5 py-1 rounded text-xs flex items-center gap-1.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        <span>لوحة كانبان (Kanban)</span>
                    </button>
                    <button type="button" 
                            @click="currentView = 'list'" 
                            :class="currentView === 'list' ? 'bg-white shadow-2xs text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-800'"
                            class="px-2.5 py-1 rounded text-xs flex items-center gap-1.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>جدول (List)</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- 1. Odoo Enterprise Kanban Board View -->
        <div x-show="currentView === 'kanban'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
            
            <!-- Column 1: Scheduled (مجدولة) -->
            <div class="bg-slate-100/80 rounded-xl p-3 border border-slate-200/80 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <h3 class="text-xs font-extrabold text-slate-800">مهام مجدولة (Scheduled)</h3>
                    </div>
                    <span class="text-xs font-bold text-slate-500 font-en">{{ $tasks->where('status', 'scheduled')->count() }}</span>
                </div>

                <div class="space-y-3 min-h-[160px]">
                    @forelse($tasks->where('status', 'scheduled') as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs border border-dashed border-slate-200 rounded-lg">
                            لا توجد مهام مجدولة حالياً
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: In Progress (قيد الفحص الميداني) -->
            <div class="bg-amber-50/50 rounded-xl p-3 border border-amber-200/80 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-amber-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <h3 class="text-xs font-extrabold text-amber-950">قيد الفحص (In Progress)</h3>
                    </div>
                    <span class="text-xs font-bold text-amber-800 font-en">{{ $tasks->where('status', 'in_progress')->count() }}</span>
                </div>

                <div class="space-y-3 min-h-[160px]">
                    @forelse($tasks->where('status', 'in_progress') as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs border border-dashed border-amber-200 rounded-lg">
                            لا توجد مهام قيد الفحص الميداني
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Completed (فحص مكتمل بانتظار الاعتماد) -->
            <div class="bg-emerald-50/50 rounded-xl p-3 border border-emerald-200/80 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-emerald-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#22A447]"></span>
                        <h3 class="text-xs font-extrabold text-emerald-950">فحص مكتمل (Completed)</h3>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 font-en">{{ $tasks->where('status', 'completed')->count() }}</span>
                </div>

                <div class="space-y-3 min-h-[160px]">
                    @forelse($tasks->where('status', 'completed') as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs border border-dashed border-emerald-200 rounded-lg">
                            لا توجد مهام مكتملة بانتظار الاعتماد
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 4: Certified (معتمدة بشهادة فحص) -->
            <div class="bg-purple-50/50 rounded-xl p-3 border border-purple-200/80 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-purple-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        <h3 class="text-xs font-extrabold text-purple-950">معتمدة بشهادة (Certified)</h3>
                    </div>
                    <span class="text-xs font-bold text-purple-800 font-en">{{ $tasks->whereNotNull('certificate_id')->count() }}</span>
                </div>

                <div class="space-y-3 min-h-[160px]">
                    @forelse($tasks->whereNotNull('certificate_id') as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs border border-dashed border-purple-200 rounded-lg">
                            لا توجد مهام معتمدة بشهادات بعد
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- 2. Odoo Enterprise List View -->
        <div x-show="currentView === 'list'" class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs border-collapse">
                    <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                        <tr>
                            <th class="w-12 px-3 py-2.5 text-center">#</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">رقم المهمة (Task #)</th>
                            <th class="px-4 py-2.5 font-bold tracking-tight text-slate-700">العميل والموقع</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">المعدة والفحص</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">المهندس الفاحص</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">تاريخ الفحص</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">نسبة الشيك ليست</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">الحالة</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Google Sheets</th>
                            <th class="w-28 px-3 py-2.5 text-center text-slate-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tasks as $task)
                            @php $summary = $task->checklistSummary(); @endphp
                            <tr class="hover:bg-slate-50/90 transition-colors group">
                                <td class="px-3 py-2.5 text-center text-slate-400 font-mono">{{ $loop->iteration }}</td>

                                <!-- Task Number -->
                                <td class="px-3 py-2.5 whitespace-nowrap">
                                    <a href="{{ route('tasks.edit', $task) }}" class="font-bold text-[#0E4DA4] font-mono group-hover:underline block">
                                        {{ $task->task_number }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $task->title_ar }}</span>
                                </td>

                                <!-- Customer & Site -->
                                <td class="px-4 py-2.5">
                                    <span class="font-bold text-slate-900 block">{{ $task->customer?->name }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $task->examination_location }}</span>
                                </td>

                                <!-- Equipment -->
                                <td class="px-3 py-2.5">
                                    <span class="font-bold text-slate-800 block text-xs">{{ $task->equipmentTypeLabel('ar') }}</span>
                                    @if($task->equipment_identifier)
                                        <span class="font-mono text-[10px] text-slate-500">ID: {{ $task->equipment_identifier }}</span>
                                    @endif
                                </td>

                                <!-- Inspector -->
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded bg-purple-100 text-[#714B67] flex items-center justify-center font-bold text-[10px] uppercase font-en">
                                            {{ mb_substr($task->inspector?->name ?? '—', 0, 1) }}
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $task->inspector?->name ?? 'غير مكلف' }}</span>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-3 py-2.5 text-center font-mono text-[11px] text-slate-600">
                                    {{ $task->scheduled_date->format('d/m/Y') }}
                                </td>

                                <!-- Checklist Progress -->
                                <td class="px-3 py-2.5 text-center">
                                    <div class="w-24 mx-auto space-y-1">
                                        <div class="flex justify-between text-[10px] font-mono font-bold">
                                            <span class="text-emerald-700">{{ $summary['pass'] }}/{{ $summary['total'] }}</span>
                                            <span class="text-slate-500">{{ $summary['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-[#22A447] h-1.5 rounded-full" style="width: {{ $summary['percent'] }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-2.5 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold 
                                        @if($task->certificate_id) bg-purple-50 text-purple-700 border border-purple-200
                                        @elseif($task->status === 'completed') bg-[#E8F7EC] text-[#22A447]
                                        @elseif($task->status === 'in_progress') bg-amber-50 text-amber-700
                                        @else bg-blue-50 text-[#0E4DA4] @endif">
                                        <span>{{ $task->statusLabel('ar') }}</span>
                                    </span>
                                </td>

                                <!-- Google Sheets Sync -->
                                <td class="px-3 py-2.5 text-center">
                                    @if($task->synced_to_sheets_at)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-700 font-bold" title="تمت المزامنة: {{ $task->synced_to_sheets_at->format('d/m H:i') }}">
                                            <svg class="w-3 h-3 text-[#22A447]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>متزامن</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400">بانتظار المزامنة</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-3 py-2.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('tasks.edit', $task) }}" class="p-1 rounded text-slate-400 hover:text-[#0E4DA4] hover:bg-blue-50" title="تعبئة الشيك ليست">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        </a>

                                        @if(!$task->certificate_id)
                                            <a href="{{ route('tasks.convert', $task) }}" class="p-1 rounded text-slate-400 hover:text-purple-600 hover:bg-purple-50" title="إصدار شهادة الفحص">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-10 text-slate-400">
                                    لا توجد مهام فحص مطابقة للبحث حالياً.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tasks->hasPages())
                <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

