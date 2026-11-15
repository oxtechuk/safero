@extends('layouts.app')

@section('title', 'تفاصيل أمر الشغل والفحص - ' . $task->task_number)

@section('content')
<div class="space-y-6">

    <!-- Odoo Enterprise Form Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13 gap-2">
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        مهام الفحص
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs sm:text-sm font-extrabold text-[#0E4DA4] font-mono tracking-tight">
                        {{ $task->task_number }}
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $task->status === 'completed' ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-blue-50 text-[#0E4DA4]' }}">
                        {{ $task->statusLabel('ar') }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" 
                       class="px-4 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>تعديل والشيك ليست (Edit)</span>
                    </a>

                    @if(!$task->certificate_id)
                        <a href="{{ route('tasks.convert', $task) }}" 
                           class="px-3.5 py-1.5 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5" 
                           title="إصدار الشهادة الرسمية المعتمده من هذا الفحص">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>إصدار الشهادة الرسمية</span>
                        </a>
                    @else
                        <a href="{{ route('certificates.show', $task->certificate_id) }}" 
                           class="px-3.5 py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 font-bold text-xs transition">
                            عرض الشهادة: {{ $task->certificate?->certificate_number }}
                        </a>
                    @endif

                    <form method="POST" action="{{ route('tasks.sync_now', $task) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-1.5 rounded-lg border border-emerald-300 text-emerald-800 bg-emerald-50 hover:bg-emerald-100 font-bold text-xs transition flex items-center gap-1"
                                title="مزامنة فورية لهذه المهمة مع جداول Google Sheets">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>مزامنة Google Sheets</span>
                        </button>
                    </form>

                    <a href="{{ route('tasks.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition">
                        العودة للقائمة
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Card: Overview & Google Sheets Integration Strip -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="space-y-1">
                    <span class="text-[10px] font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded uppercase">
                        {{ $task->task_number }}
                    </span>
                    <h1 class="text-lg font-black text-slate-900 mt-1">{{ $task->title_ar }}</h1>
                    <span class="text-xs text-slate-400 font-en block">{{ $task->title_en }}</span>
                </div>

                <!-- Google Sheets Sync Status Box -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase block font-en">GOOGLE SHEETS STATUS</span>
                        <div class="flex items-center gap-2">
                            @if($task->synced_to_sheets_at)
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                    <span>متزامن ومحدث ({{ $task->synced_to_sheets_at->diffForHumans() }})</span>
                                </span>
                            @else
                                <span class="text-xs font-bold text-amber-700">بانتظار أول مزامنة</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-1">
                <div>
                    <span class="text-slate-400 text-[10px] block uppercase font-bold">العميل</span>
                    <a href="{{ route('customers.show', $task->customer) }}" class="font-bold text-slate-900 hover:text-[#0E4DA4] underline">
                        {{ $task->customer?->name }}
                    </a>
                </div>

                <div>
                    <span class="text-slate-400 text-[10px] block uppercase font-bold">نوع المعدة</span>
                    <span class="font-bold text-slate-800">{{ $task->equipmentTypeLabel('ar') }}</span>
                    @if($task->equipment_identifier)
                        <span class="font-mono text-slate-500 block text-[11px]">#{{ $task->equipment_identifier }}</span>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 text-[10px] block uppercase font-bold">تاريخ الفحص</span>
                    <span class="font-bold text-slate-800 font-mono">{{ $task->scheduled_date->format('d/m/Y') }}</span>
                </div>

                <div>
                    <span class="text-slate-400 text-[10px] block uppercase font-bold">الفاحص المكلف</span>
                    <span class="font-bold text-slate-800">{{ $task->inspector?->name ?? 'غير مكلف' }}</span>
                </div>
            </div>

            @if($task->notes)
                <div class="bg-amber-50/60 p-3 rounded-lg border border-amber-200 text-xs text-amber-950">
                    <strong class="font-bold block mb-0.5">تعليمات وملاحظات الموقع:</strong>
                    {{ $task->notes }}
                </div>
            @endif
        </div>

        <!-- Checklist Summary & Details -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">سجل بنود الفحص الميداني (Checklist Details)</h3>
                    <p class="text-xs text-slate-500">حالة البنود المفحوصة والملاحظات المرصودة أثناء التفتيش.</p>
                </div>

                <div class="flex items-center gap-2 text-xs font-mono font-bold">
                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">{{ $summary['pass'] }} مطابق</span>
                    <span class="text-red-700 bg-red-50 px-2 py-0.5 rounded">{{ $summary['fail'] }} عيوب</span>
                    <span class="text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $summary['na'] }} لا ينطبق</span>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($task->checklist_items ?? [] as $item)
                    @php $itemStatus = strtolower($item['status'] ?? 'pass'); @endphp
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="font-mono text-slate-400 text-[10px]">{{ $item['id'] ?? $loop->iteration }}</span>
                            <span class="font-bold text-slate-800">{{ $item['item_ar'] ?? '' }}</span>
                            @if(!empty($item['notes']))
                                <span class="text-amber-800 bg-amber-50 px-2 py-0.5 rounded text-[11px] font-medium border border-amber-200">
                                    ملاحظة: {{ $item['notes'] }}
                                </span>
                            @endif
                        </div>

                        <span class="px-2 py-0.5 rounded text-[10px] font-bold font-en uppercase
                            @if(in_array($itemStatus, ['pass', 'yes', 's'])) bg-[#E8F7EC] text-[#22A447]
                            @elseif(in_array($itemStatus, ['fail', 'no', 'u'])) bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-500 @endif">
                            {{ $itemStatus }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

