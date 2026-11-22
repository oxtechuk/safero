@extends('layouts.app')

@section('title', 'فحص ميداني: '.$inspection->inspection_number.' - '.$inspection->equipment_name)
@section('hide_bottom_nav', '1')

@section('content')
<!-- Safe JSON Payload for Checklist & Condition Data -->
<script id="inspection-data-json" type="application/json">
{!! json_encode([
    'items' => $inspection->checklist_results ?? [],
    'overall' => old('overall_condition', $inspection->overall_condition ?: 'safe'),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}
</script>

<script>
function inspectionInspectorApp() {
    let payload = { items: [], overall: 'safe' };
    try {
        const raw = document.getElementById('inspection-data-json');
        if (raw && raw.textContent) {
            payload = JSON.parse(raw.textContent);
        }
    } catch (e) {
        console.error('Inspection data parse error', e);
    }

    return {
        filter: 'all',
        overallCondition: payload.overall || 'safe',
        items: Array.isArray(payload.items) ? payload.items : [],

        get filteredItems() {
            if (this.filter === 'all') return this.items;
            return this.items.filter(i => i.status === this.filter);
        },

        get passCount() {
            return this.items.filter(i => i.status === 'pass').length;
        },

        get failCount() {
            return this.items.filter(i => i.status === 'fail').length;
        },

        get naCount() {
            return this.items.filter(i => i.status === 'na').length;
        },

        get progressPercent() {
            if (!this.items.length) return 0;
            const done = this.items.filter(i => i.status && i.status !== '').length;
            return Math.round((done / this.items.length) * 100);
        },

        setItemStatus(item, val) {
            item.status = val;
            if (val === 'fail') {
                this.overallCondition = 'unsafe';
            } else {
                if (!this.items.some(i => i.status === 'fail')) {
                    this.overallCondition = 'safe';
                }
            }
        }
    };
}
</script>

<div class="space-y-4 pb-36 md:pb-16" x-data="inspectionInspectorApp()">

    <!-- Sticky Header for Mobile & Desktop -->
    <div class="sticky top-13 z-40 bg-white/95 backdrop-blur border-b border-slate-200 px-3 sm:px-6 py-2.5 shadow-xs">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            
            <div class="flex items-center gap-2.5 min-w-0">
                <a href="{{ route('inspections.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition shrink-0">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#0E4DA4]/10 text-[#0E4DA4] font-mono">
                            {{ $inspection->inspection_number }}
                        </span>
                        @if($inspection->doc_number)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 font-mono">
                                {{ $inspection->doc_number }}
                            </span>
                        @endif
                        <span class="text-xs font-bold text-slate-600 truncate max-w-[200px] sm:max-w-none">
                            {{ $inspection->customer->name }}
                        </span>
                    </div>
                    <h1 class="text-xs sm:text-sm font-extrabold text-slate-900 truncate">
                        {{ $inspection->equipment_name }}
                    </h1>
                </div>
            </div>

            <!-- Real-time Counters on Top -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-center flex items-center gap-1">
                    <span class="text-[10px] font-bold">مقبول</span>
                    <span class="text-xs font-black font-mono" x-text="passCount">0</span>
                </div>
                <div class="px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-800 text-center flex items-center gap-1">
                    <span class="text-[10px] font-bold">مرفوض</span>
                    <span class="text-xs font-black font-mono" x-text="failCount">0</span>
                </div>
                <div class="px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-center flex items-center gap-1">
                    <span class="text-[10px] font-bold">N/A</span>
                    <span class="text-xs font-black font-mono" x-text="naCount">0</span>
                </div>
                <div class="hidden sm:flex px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[#0E4DA4] text-center items-center gap-1">
                    <span class="text-[10px] font-bold">الإنجاز</span>
                    <span class="text-xs font-black font-mono" x-text="progressPercent + '%'">0%</span>
                </div>
                <a href="{{ route('inspections.sticker', $inspection) }}" 
                   target="_blank"
                   class="px-2.5 py-1 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-2xs"
                   title="طباعة كارت واستيكر فحص المعدة (Sticker Tag)">
                    <span>🏷️</span>
                    <span class="hidden md:inline">استيكر المعدة</span>
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
            <div class="h-1.5 transition-all duration-300 rounded-full"
                 :class="failCount > 0 ? 'bg-rose-500' : 'bg-emerald-500'"
                 :style="'width: ' + progressPercent + '%'"></div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-3 sm:px-6">

        <!-- Mobile Filter Buttons -->
        <div class="flex items-center justify-between gap-1.5 py-2 overflow-x-auto">
            <div class="flex items-center gap-1.5 text-xs font-bold">
                <button type="button" @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg transition shadow-2xs cursor-pointer">
                    {{ __('الكل') }} (<span x-text="items.length"></span>)
                </button>
                <button type="button" @click="filter = 'fail'" 
                        :class="filter === 'fail' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-rose-200 text-rose-700 hover:bg-rose-50'"
                        class="px-3 py-1.5 rounded-lg transition shadow-2xs flex items-center gap-1 cursor-pointer">
                    <span>{{ __('العيوب / مرفوض') }}</span>
                    <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-800 text-[10px] flex items-center justify-center font-mono font-bold" x-text="failCount"></span>
                </button>
                <button type="button" @click="filter = 'pass'" 
                        :class="filter === 'pass' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50'"
                        class="px-3 py-1.5 rounded-lg transition shadow-2xs cursor-pointer">
                    {{ __('مقبول') }} (<span x-text="passCount"></span>)
                </button>
                <button type="button" @click="filter = 'na'" 
                        :class="filter === 'na' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg transition shadow-2xs cursor-pointer">
                    {{ __('غير منطبق') }} (<span x-text="naCount"></span>)
                </button>
            </div>

            <div class="text-[11px] text-slate-500 font-semibold hidden sm:block">
                اضغط على الزر المناسب لكل بند أثناء التواجد في الموقع
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('inspections.update_checklist', $inspection) }}" method="POST" id="checklistForm" class="space-y-4">
            @csrf

            <!-- Checklist Items Loop -->
            <div class="space-y-3">
                <template x-for="(item, index) in filteredItems" :key="item.id">
                    <div class="bg-white border rounded-2xl shadow-xs p-4 sm:p-5 transition-all duration-150"
                         :class="{
                             'border-slate-200 border-r-4 border-r-emerald-500': item.status === 'pass',
                             'border-rose-300 border-r-4 border-r-rose-600 bg-rose-50/20 ring-1 ring-rose-200': item.status === 'fail',
                             'border-slate-200 border-r-4 border-r-slate-400': item.status === 'na'
                         }">
                        
                        <!-- Item Header: Clause Ref & Item Title -->
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold font-mono shrink-0 mt-0.5" x-text="item.no || (index + 1)"></span>
                                <div>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <template x-if="item.clause_ref">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80 font-mono" x-text="'Clause ' + item.clause_ref"></span>
                                        </template>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug" x-text="item.item"></h3>
                                    </div>

                                    <!-- Standard Criteria (Modern, Soft High-Legibility Two-Tone Style) -->
                                    <div class="mt-2.5 p-2.5 rounded-xl bg-slate-50/90 border border-slate-200/60 text-xs space-y-1.5">
                                        <template x-if="item.acceptance_criteria">
                                            <div class="flex items-start gap-2">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-100/90 text-emerald-900 border border-emerald-300/60 shrink-0">معيار القبول ✓</span>
                                                <span class="text-slate-700 leading-relaxed font-medium" x-text="item.acceptance_criteria"></span>
                                            </div>
                                        </template>
                                        <template x-if="item.rejection_criteria">
                                            <div class="flex items-start gap-2">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md bg-rose-100/90 text-rose-900 border border-rose-300/60 shrink-0">معيار الرفض ✕</span>
                                                <span class="text-slate-700 leading-relaxed font-medium" x-text="item.rejection_criteria"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Touch-Friendly Result Buttons (Mobile Optimized Segmented Group) -->
                        <div class="mt-4 pt-3.5 border-t border-slate-100 grid grid-cols-3 gap-2">
                            
                            <!-- Pass Button (مقبول / صح) -->
                            <button type="button" 
                                    @click="setItemStatus(item, 'pass')"
                                    :class="item.status === 'pass' ? 'bg-emerald-600 text-white font-black shadow-md ring-2 ring-emerald-300' : 'bg-white hover:bg-emerald-50/60 text-slate-700 font-bold border border-slate-200 hover:border-emerald-300'"
                                    class="py-3 px-2 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>مقبول</span>
                            </button>

                            <!-- Fail Button (مرفوض / غلط) -->
                            <button type="button" 
                                    @click="setItemStatus(item, 'fail')"
                                    :class="item.status === 'fail' ? 'bg-rose-600 text-white font-black shadow-md ring-2 ring-rose-300' : 'bg-white hover:bg-rose-50/60 text-slate-700 font-bold border border-slate-200 hover:border-rose-300'"
                                    class="py-3 px-2 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>مرفوض</span>
                            </button>

                            <!-- NA Button (غير منطبق) -->
                            <button type="button" 
                                    @click="setItemStatus(item, 'na')"
                                    :class="item.status === 'na' ? 'bg-slate-800 text-white font-black shadow-md ring-2 ring-slate-300' : 'bg-white hover:bg-slate-100 text-slate-500 font-bold border border-slate-200'"
                                    class="py-3 px-2 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1 transition active:scale-95 cursor-pointer">
                                <span>غير منطبق</span>
                            </button>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" :name="'checklist[' + item.id + '][status]'" :value="item.status">
                        </div>

                        <!-- Remarks / Defect Description Input -->
                        <div class="mt-3 p-3 rounded-xl bg-rose-50/60 border border-rose-200/80 space-y-1.5" x-show="item.status === 'fail' || item.remarks" x-transition>
                            <label class="block text-xs font-bold text-rose-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span x-show="item.status === 'fail'">تفاصيل العيب / سبب الرفض بالتفصيل:</span>
                                <span x-show="item.status !== 'fail'">ملاحظات الفاحص:</span>
                            </label>
                            <input type="text" 
                                   :name="'checklist[' + item.id + '][remarks]'"
                                   x-model="item.remarks"
                                   :placeholder="item.status === 'fail' ? 'اكتب وصف العيب بدقة طبقاً للمعيار...' : 'أي ملاحظة إضافية...'"
                                   class="w-full text-xs rounded-lg py-2.5 px-3 border border-rose-300 bg-white text-slate-900 focus:ring-2 focus:ring-rose-400 focus:border-rose-400 outline-none">
                        </div>
                    </div>
                </template>
            </div>

            <!-- Overall Declaration & Safe to Use Section -->
            <div class="bg-white border rounded-2xl shadow-xs p-4 sm:p-6 space-y-4"
                 :class="overallCondition === 'safe' ? 'border-emerald-300 bg-emerald-50/10' : 'border-rose-300 bg-rose-50/10'">
                
                <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-bold"
                             :class="overallCondition === 'safe' ? 'bg-emerald-600' : 'bg-rose-600'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-extrabold text-slate-900">إقرار النتيجة الشاملة للفحص (Declaration)</h2>
                            <p class="text-[11px] text-slate-500">تحديد الحالة النهائية للمعدة وفقاً للمعاينة الميدانية</p>
                        </div>
                    </div>

                    <!-- Safe / Unsafe Toggle Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="overallCondition = 'safe'"
                                :class="overallCondition === 'safe' ? 'bg-emerald-600 text-white ring-2 ring-emerald-300 font-extrabold' : 'bg-slate-100 text-slate-700 font-semibold'"
                                class="px-4 py-2 rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>آمن للاستخدام (SAFE)</span>
                        </button>

                        <button type="button" 
                                @click="overallCondition = 'unsafe'"
                                :class="overallCondition === 'unsafe' ? 'bg-rose-600 text-white ring-2 ring-rose-300 font-extrabold' : 'bg-slate-100 text-slate-700 font-semibold'"
                                class="px-4 py-2 rounded-xl text-xs sm:text-sm transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>غير آمن (UNSAFE)</span>
                        </button>
                    </div>
                </div>

                <!-- Hidden Input -->
                <input type="hidden" name="overall_condition" id="overall_condition_field" :value="overallCondition" value="{{ old('overall_condition', $inspection->overall_condition ?: 'safe') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Defects Identified -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            العيوب المكتشفة (Defect(s) Identified)
                        </label>
                        <textarea name="defects_identified" rows="3" 
                                  placeholder="اكتب العيوب المكتشفة إن وجدت، أو اتركها خالية إن كانت المعدة سليمة تماماً..."
                                  class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#0E4DA4] focus:border-[#0E4DA4]">{{ old('defects_identified', $inspection->defects_identified) }}</textarea>
                    </div>

                    <!-- Corrective Action -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            الإجراءات التصحيحية المطلوبة (Corrective Action Required)
                        </label>
                        <textarea name="corrective_actions" rows="3" 
                                  placeholder="التوصيات الفنية أو الإصلاحات المطلوبة قبل التشغيل..."
                                  class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#0E4DA4] focus:border-[#0E4DA4]">{{ old('corrective_actions', $inspection->corrective_actions) }}</textarea>
                    </div>
                </div>

                <!-- Repeat Examination Checkbox -->
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="repeat_examination_required" id="repeat_examination_required" value="1"
                           {{ old('repeat_examination_required', $inspection->repeat_examination_required) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 w-4 h-4">
                    <label for="repeat_examination_required" class="text-xs font-bold text-slate-700">
                        يتطلب إعادة فحص بعد استكمال الإصلاحات (Repeat Examination Required)
                    </label>
                </div>
            </div>

            <!-- Sticky Bottom Action Bar (Fixed for Mobile) -->
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200 px-3 sm:px-6 py-3 shadow-lg">
                <div class="max-w-4xl mx-auto flex items-center justify-between gap-2 flex-wrap">
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <!-- Sticker Tag Button -->
                        <a href="{{ route('inspections.sticker', $inspection) }}" 
                           target="_blank"
                           class="px-3 py-2.5 rounded-xl border border-emerald-600 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-xs font-bold transition shadow-2xs flex items-center gap-1.5 shrink-0"
                           title="طباعة كارت واستيكر فحص المعدة">
                            <span>🏷️</span>
                            <span>استيكر المعدة</span>
                        </a>

                        <!-- Save Draft Button -->
                        <button type="submit" name="status" value="draft"
                                class="flex-1 sm:flex-none px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs text-center cursor-pointer active:scale-95">
                            {{ __('حفظ كمسودة') }}
                        </button>

                        <!-- Save Completed Button -->
                        <button type="submit" name="status" value="completed"
                                class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-2xs text-center cursor-pointer active:scale-95">
                            {{ __('إتمام وحفظ الفحص') }}
                        </button>
                    </div>

                    <!-- Stage 2: 1-Click Generate Official Certificate Button -->
                    <button type="submit" name="convert_to_certificate" value="1"
                            class="w-full sm:w-auto flex-1 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#006837] to-[#22A447] hover:from-[#00522c] hover:to-[#1b8439] text-white text-xs sm:text-sm font-extrabold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 active:scale-98 cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ __('المرحلة 2: إنشاء شهادة الفحص المعتمدة فوراً') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
