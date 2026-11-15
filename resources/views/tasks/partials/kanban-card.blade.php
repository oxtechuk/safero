@php
    $summary = $task->checklistSummary();
@endphp
<div class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs hover:shadow-md transition space-y-3 group">
    
    <!-- Header: Task Number & Priority Badge -->
    <div class="flex items-start justify-between gap-2">
        <div>
            <a href="{{ route('tasks.edit', $task) }}" class="font-mono font-bold text-xs text-[#0E4DA4] group-hover:underline block">
                {{ $task->task_number }}
            </a>
            <h4 class="font-bold text-xs text-slate-900 line-clamp-1 mt-0.5">{{ $task->title_ar }}</h4>
        </div>

        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded uppercase
            @if($task->priority === 'urgent') bg-red-100 text-red-700
            @elseif($task->priority === 'high') bg-amber-100 text-amber-800
            @else bg-slate-100 text-slate-600 @endif">
            {{ $task->priorityLabel('ar') }}
        </span>
    </div>

    <!-- Customer & Equipment Info -->
    <div class="space-y-1 text-xs text-slate-600 bg-slate-50/70 p-2 rounded-lg border border-slate-100">
        <div class="font-semibold text-slate-800 truncate" title="{{ $task->customer?->name }}">
            {{ $task->customer?->name }}
        </div>
        <div class="flex items-center justify-between text-[11px] text-slate-500">
            <span>{{ $task->equipmentTypeLabel('ar') }}</span>
            @if($task->equipment_identifier)
                <span class="font-mono font-bold text-slate-700">#{{ $task->equipment_identifier }}</span>
            @endif
        </div>
    </div>

    <!-- Checklist Progress Bar -->
    <div class="space-y-1">
        <div class="flex justify-between text-[10px] font-mono font-bold">
            <span class="text-slate-500">الشيك ليست (Checklist):</span>
            <span class="{{ $summary['percent'] == 100 ? 'text-[#22A447]' : 'text-slate-700' }}">
                {{ $summary['pass'] }}/{{ $summary['total'] }} ({{ $summary['percent'] }}%)
            </span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
            <div class="bg-[#22A447] h-1.5 rounded-full" style="width: {{ $summary['percent'] }}%"></div>
        </div>
    </div>

    <!-- Footer: Inspector & Actions -->
    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
        <!-- Inspector Avatar -->
        <div class="flex items-center gap-1.5" title="{{ $task->inspector?->name ?? 'غير مكلف' }}">
            <div class="w-5 h-5 rounded-full bg-purple-100 text-[#714B67] flex items-center justify-center font-bold text-[9px] uppercase font-en">
                {{ mb_substr($task->inspector?->name ?? '—', 0, 1) }}
            </div>
            <span class="text-[11px] text-slate-700 font-medium truncate max-w-[80px]">
                {{ $task->inspector?->name ?? 'غير مكلف' }}
            </span>
        </div>

        <!-- Right Quick Action -->
        <div class="flex items-center gap-1">
            @if($task->certificate_id)
                <a href="{{ route('certificates.show', $task->certificate_id) }}" 
                   class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200" 
                   title="عرض الشهادة الرسمية المعتمدة">
                    الشهادة الرسمية
                </a>
            @else
                <a href="{{ route('tasks.edit', $task) }}" 
                   class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 hover:bg-blue-100 text-[#0E4DA4]" 
                   title="متابعة وتعبئة شيك ليست الفحص">
                    شيك ليست الفحص
                </a>
            @endif
        </div>
    </div>

</div>

