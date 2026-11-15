@extends('layouts.app')

@section('title', 'شيك ليست الفحص الهندسي وأمر الشغل - ' . $task->task_number)

@section('content')
<form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6" x-data="{
    status: '{{ old('status', $task->status) }}',
    setAllStatus(val) {
        document.querySelectorAll('.checklist-status-radio').forEach(el => {
            if (el.value === val) el.checked = true;
        });
    }
}">
    @csrf
    @method('PUT')

    <!-- Odoo Enterprise Form Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13 gap-2">
                
                <!-- Left: Breadcrumb & Title -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        مهام الفحص
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight font-mono text-[#0E4DA4]">
                        {{ $task->task_number }}
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $task->status === 'completed' ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-blue-50 text-[#0E4DA4]' }}">
                        {{ $task->statusLabel('ar') }}
                    </span>
                </div>

                <!-- Right: Save / Generate Certificate / Sync Actions -->
                <div class="flex items-center gap-2">
                    <!-- 1. Save Changes -->
                    <button type="submit" 
                            class="px-4 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ التعديلات (Save)</span>
                    </button>

                    <!-- 2. One-Click Certificate Conversion -->
                    @if(!$task->certificate_id)
                        <button type="submit" 
                                name="convert_to_certificate" 
                                value="1" 
                                class="px-3.5 py-1.5 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5"
                                title="حفظ الشيك ليست والتحويل المباشر لإصدار شهادة الفحص الرسمية">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>إصدار الشهادة الرسمية</span>
                        </button>
                    @else
                        <a href="{{ route('certificates.show', $task->certificate_id) }}" 
                           class="px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 font-bold text-xs transition flex items-center gap-1">
                            <span>عرض الشهادة: {{ $task->certificate?->certificate_number }}</span>
                        </a>
                    @endif

                    <!-- 3. Immediate Sync -->
                    <button type="button" 
                            onclick="document.getElementById('syncSingleForm').submit();"
                            class="px-3 py-1.5 rounded-lg border border-emerald-300 text-emerald-800 bg-emerald-50 hover:bg-emerald-100 font-bold text-xs transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>مزامنة الشيت</span>
                    </button>

                    <a href="{{ route('tasks.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition">
                        إلغاء
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Hidden form for direct single sync -->
    <noscript></noscript>

    <!-- Main Form Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Checklist Live Compliance Scorecard -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-en">INSPECTION CHECKLIST SCORE</span>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-slate-900 font-mono">{{ $summary['pass'] }}/{{ $summary['total'] }}</span>
                    <span class="text-sm font-extrabold px-2.5 py-0.5 rounded-full {{ $summary['percent'] == 100 ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-blue-50 text-[#0E4DA4]' }} font-mono">
                        {{ $summary['percent'] }}% مطابق
                    </span>
                    @if($summary['fail'] > 0)
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-mono">
                            {{ $summary['fail'] }} عيوب مرصودة
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stage & Progress Radio Pill -->
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
                @foreach(['scheduled' => 'مجدولة', 'in_progress' => 'قيد الفحص', 'completed' => 'فحص مكتمل'] as $stK => $stL)
                    <label class="px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition select-none flex items-center gap-1.5"
                           :class="status === '{{ $stK }}' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'">
                        <input type="radio" name="status" value="{{ $stK }}" x-model="status" class="sr-only">
                        <span>{{ $stL }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Task & Equipment Overview Box -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">بيانات المهمة والمعدة الخاضعة للفحص</h3>
                    <h2 class="text-base font-extrabold text-slate-900">{{ $task->title_ar }}</h2>
                </div>
                <div class="text-left">
                    <span class="text-xs font-bold text-slate-800 block">{{ $task->customer?->name }}</span>
                    <span class="text-[11px] text-slate-400 block font-mono">{{ $task->examination_location }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block text-slate-400 text-[10px] uppercase font-bold mb-0.5">نوع المعدة</label>
                    <select name="equipment_type" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs bg-white font-medium">
                        <option value="crane" {{ $task->equipment_type === 'crane' ? 'selected' : '' }}>أوناش وروافع (Cranes)</option>
                        <option value="accessory" {{ $task->equipment_type === 'accessory' ? 'selected' : '' }}>مهمات رفع وحبال (Slings)</option>
                        <option value="general" {{ $task->equipment_type === 'general' ? 'selected' : '' }}>معدات عامة وباليتات</option>
                        <option value="environmental" {{ $task->equipment_type === 'environmental' ? 'selected' : '' }}>فحص بيئي وانبعاثات</option>
                    </select>
                </div>

                <div>
                    <label class="block text-slate-400 text-[10px] uppercase font-bold mb-0.5">رقم الشاسيه / التعريف</label>
                    <input type="text" name="equipment_identifier" value="{{ old('equipment_identifier', $task->equipment_identifier) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs font-en font-bold text-left">
                </div>

                <div>
                    <label class="block text-slate-400 text-[10px] uppercase font-bold mb-0.5">تاريخ الفحص</label>
                    <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $task->scheduled_date->format('Y-m-d')) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs font-mono">
                </div>

                <div>
                    <label class="block text-slate-400 text-[10px] uppercase font-bold mb-0.5">الفاحص المكلف</label>
                    <select name="assigned_inspector_id" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs bg-white font-medium">
                        <option value="">-- غير مكلف --</option>
                        @foreach($inspectors as $insp)
                            <option value="{{ $insp->id }}" {{ $task->assigned_inspector_id == $insp->id ? 'selected' : '' }}>
                                {{ $insp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden inputs to maintain validation -->
                <input type="hidden" name="title_ar" value="{{ $task->title_ar }}">
                <input type="hidden" name="title_en" value="{{ $task->title_en }}">
                <input type="hidden" name="customer_id" value="{{ $task->customer_id }}">
                <input type="hidden" name="priority" value="{{ $task->priority }}">
                <input type="hidden" name="examination_location" value="{{ $task->examination_location }}">
                <input type="hidden" name="equipment_description" value="{{ $task->equipment_description }}">
            </div>
        </div>

        <!-- The Interactive Checklist Sheet -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            
            <!-- Checklist Control Header -->
            <div class="border-b border-slate-200 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">بنود شيك ليست الفحص الميداني (Inspection Checklist Matrix)</h3>
                    <p class="text-xs text-slate-500">قم بفحص كل نقطة ميدانياً وتحديد حالتها (مطابق، به عيوب، أو لا ينطبق) وتسجيل الملاحظات الفنية.</p>
                </div>

                <!-- Fast Batch Actions -->
                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="setAllStatus('pass')" 
                            class="px-2.5 py-1 rounded bg-[#E8F7EC] hover:bg-[#d2f0d9] text-[#22A447] font-bold text-xs transition">
                        ✓ تحديد الكل مطابق (All Pass)
                    </button>
                    <button type="button" 
                            @click="setAllStatus('na')" 
                            class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                        تحديد الكل لا ينطبق (N/A)
                    </button>
                </div>
            </div>

            <!-- Checklist Items List -->
            <div class="divide-y divide-slate-100">
                @foreach($task->checklist_items ?? [] as $item)
                    @php
                        $itemId = $item['id'] ?? $loop->iteration;
                        $itemStatus = strtolower($item['status'] ?? 'pass');
                    @endphp
                    <div class="py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-3 hover:bg-slate-50/70 p-2.5 rounded-lg transition">
                        
                        <!-- Item Title & English Description -->
                        <div class="flex items-start gap-3 flex-1">
                            <span class="w-6 h-6 rounded bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs font-mono shrink-0 mt-0.5">
                                {{ $itemId }}
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">{{ $item['item_ar'] ?? '' }}</span>
                                <span class="text-[11px] text-slate-400 font-en block">{{ $item['item_en'] ?? '' }}</span>
                            </div>
                        </div>

                        <!-- 3-Way Status Toggle Buttons -->
                        <div class="flex items-center gap-1.5 shrink-0 self-start md:self-center">
                            
                            <!-- Pass (مطابق) -->
                            <label class="px-2.5 py-1 rounded-md text-xs font-bold cursor-pointer transition flex items-center gap-1 select-none border"
                                   :class="true ? 'hover:bg-[#E8F7EC]' : ''">
                                <input type="radio" 
                                       name="checklist[{{ $itemId }}][status]" 
                                       value="pass" 
                                       {{ in_array($itemStatus, ['pass', 'yes', 's']) ? 'checked' : '' }}
                                       class="checklist-status-radio text-[#22A447] focus:ring-[#22A447] w-3.5 h-3.5">
                                <span class="text-[#22A447]">مطابق (Pass)</span>
                            </label>

                            <!-- Fail (به عيوب) -->
                            <label class="px-2.5 py-1 rounded-md text-xs font-bold cursor-pointer transition flex items-center gap-1 select-none border"
                                   :class="true ? 'hover:bg-red-50' : ''">
                                <input type="radio" 
                                       name="checklist[{{ $itemId }}][status]" 
                                       value="fail" 
                                       {{ in_array($itemStatus, ['fail', 'no', 'u']) ? 'checked' : '' }}
                                       class="checklist-status-radio text-red-600 focus:ring-red-600 w-3.5 h-3.5">
                                <span class="text-red-600">به عيوب (Fail)</span>
                            </label>

                            <!-- N/A (لا ينطبق) -->
                            <label class="px-2.5 py-1 rounded-md text-xs font-bold cursor-pointer transition flex items-center gap-1 select-none border"
                                   :class="true ? 'hover:bg-slate-100' : ''">
                                <input type="radio" 
                                       name="checklist[{{ $itemId }}][status]" 
                                       value="na" 
                                       {{ in_array($itemStatus, ['na', 'none']) ? 'checked' : '' }}
                                       class="checklist-status-radio text-slate-500 focus:ring-slate-500 w-3.5 h-3.5">
                                <span class="text-slate-500">لا ينطبق (N/A)</span>
                            </label>

                        </div>

                        <!-- Inspector Notes on this Item -->
                        <div class="w-full md:w-56 shrink-0">
                            <input type="text" 
                                   name="checklist[{{ $itemId }}][notes]" 
                                   value="{{ $item['notes'] ?? '' }}" 
                                   placeholder="ملاحظات العيب إن وجد..." 
                                   class="w-full px-2.5 py-1 rounded border border-slate-200 text-xs focus:ring-1 focus:ring-[#0E4DA4] outline-hidden placeholder:text-slate-300">
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

    </div>
</form>

<!-- Single Sync Form for the sync button -->
<form id="syncSingleForm" method="POST" action="{{ route('tasks.sync_now', $task) }}" class="hidden">
    @csrf
</form>
@endsection

