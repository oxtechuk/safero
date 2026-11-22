@extends('layouts.app')

@section('title', 'تعديل قالب فحص - ' . $certificateTemplate->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-4" 
     x-data="certificateStudio({
        type: '{{ old('equipment_type', $certificateTemplate->equipment_type) }}',
        specs: {{ json_encode(old('custom_specifications', $certificateTemplate->custom_specifications ?: [])) }},
        checklist: {{ json_encode(old('checklist_items', $certificateTemplate->checklist_items ?: [])) }}
     })">

    <!-- Top Breadcrumb & Actions Bar -->
    <div class="flex items-center justify-between flex-wrap gap-3 pb-2 border-b border-slate-200">
        <div class="flex items-center gap-2">
            <a href="{{ route('certificate-templates.index') }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E6F2FF] text-[#0E4DA4]">{{ __('ستوديو قوالب الفحص') }}</span>
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900">{{ __('تعديل') }}: {{ $certificateTemplate->name }}</h1>
                </div>
                <p class="text-[11px] text-slate-500">{{ __('عدل مواصفات المعدة وأدوات الفحص وبنود الـ Checklist في هذا القالب') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('certificate-templates.index') }}" class="px-3 py-1.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition">
                {{ __('إلغاء') }}
            </a>
            <a href="{{ route('certificates.create', ['type' => $certificateTemplate->equipment_type, 'template_id' => $certificateTemplate->id]) }}" 
               class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                <span>⚡ {{ __('استخدام هذا القالب الآن') }}</span>
            </a>
            <button type="submit" form="studioForm" class="px-4 py-1.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __('حفظ التعديلات') }}</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 space-y-1">
            <strong class="block font-bold">{{ __('يرجى تصحيح الأخطاء التالية:') }}</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('certificate-templates.update', $certificateTemplate) }}" id="studioForm" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- CARD 1: Template Identity & Category -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0E4DA4] text-xs font-bold flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-800 text-xs sm:text-sm">{{ __('بيانات وهوية القالب (Template Identity)') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Basic Properties</span>
            </div>

            <!-- Equipment Type Selector Tabs -->
            <div>
                <label class="block font-bold text-slate-700 text-xs mb-2">{{ __('نوع فئة القالب (Equipment Category)') }} <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3 transition"
                           :class="type === 'crane' ? 'border-[#0E4DA4] bg-blue-50/50' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="equipment_type" value="crane" x-model="type" class="sr-only">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <img src="{{ asset('branding/IsoOx-icons/fleet.svg') }}" class="w-5 h-5">
                        </div>
                        <div>
                            <strong class="text-xs font-bold block text-slate-800">{{ __('أوناش وروافع') }}</strong>
                            <span class="text-[10px] text-slate-500 font-en">Cranes &amp; Lifting Appliances</span>
                        </div>
                    </label>

                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3 transition"
                           :class="type === 'accessory' ? 'border-[#0E4DA4] bg-blue-50/50' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="equipment_type" value="accessory" x-model="type" class="sr-only">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                            <img src="{{ asset('branding/IsoOx-icons/maintenance.svg') }}" class="w-5 h-5">
                        </div>
                        <div>
                            <strong class="text-xs font-bold block text-slate-800">{{ __('مهمات رفع وحبال') }}</strong>
                            <span class="text-[10px] text-slate-500 font-en">Slings, Shackles &amp; Gear</span>
                        </div>
                    </label>

                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3 transition"
                           :class="type === 'general' ? 'border-[#0E4DA4] bg-blue-50/50' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="equipment_type" value="general" x-model="type" class="sr-only">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                            <img src="{{ asset('branding/IsoOx-icons/repair.svg') }}" class="w-5 h-5">
                        </div>
                        <div>
                            <strong class="text-xs font-bold block text-slate-800">{{ __('معدات عامة وباليتات') }}</strong>
                            <span class="text-[10px] text-slate-500 font-en">General Cargo Units</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 mb-1">
                        {{ __('اسم القالب في الاستوديو (Template Name)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $certificateTemplate->name) }}" required 
                           class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold text-slate-800">
                </div>

                <div>
                    <label for="code" class="block font-bold text-slate-700 mb-1">
                        {{ __('كود القالب الاختياري (Template Code)') }}
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code', $certificateTemplate->code) }}" 
                           class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-mono outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                    <label for="validity_months" class="block font-bold text-slate-700 mb-1">
                        {{ __('دورة الصلاحية الافتراضية (الفحص القادم)') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="validity_months" id="validity_months" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white outline-none font-bold text-[#0E4DA4]">
                        <option value="6" {{ old('validity_months', $certificateTemplate->validity_months) == 6 ? 'selected' : '' }}>كل 6 أشهر (نصف سنوي)</option>
                        <option value="12" {{ old('validity_months', $certificateTemplate->validity_months) == 12 ? 'selected' : '' }}>كل 12 شهراً (سنوي)</option>
                        <option value="3" {{ old('validity_months', $certificateTemplate->validity_months) == 3 ? 'selected' : '' }}>كل 3 أشهر (ربع سنوي)</option>
                        <option value="24" {{ old('validity_months', $certificateTemplate->validity_months) == 24 ? 'selected' : '' }}>{{ __('كل 24 شهراً (سنتين)') }}</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $certificateTemplate->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#0E4DA4]">
                        <span class="font-bold text-slate-700">{{ __('تفعيل القالب في الاستوديو') }}</span>
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $certificateTemplate->is_default) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#0E4DA4]">
                        <span class="font-bold text-purple-700">{{ __('تعيين كقالب معتمد افتراضي للفئة') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- CARD 2: Standard Equipment Specifications -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0E4DA4] text-xs font-bold flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-800 text-xs sm:text-sm">{{ __('المواصفات الفنية الافتراضية للمعدة') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Default Specifications</span>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                        {{ __('وصف المعدة (Equipment Description)') }}
                    </label>
                    <textarea name="equipment_description" id="equipment_description" rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold text-slate-800">{{ old('equipment_description', $certificateTemplate->equipment_description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label for="swl" class="block font-bold text-slate-700 mb-1">{{ __('الحمل الآمن للتشغيل (SWL)') }}</label>
                        <input type="text" name="swl" id="swl" value="{{ old('swl', $certificateTemplate->swl) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-bold text-[#0E4DA4] outline-none">
                    </div>

                    <div>
                        <label for="reference_standard" class="block font-bold text-slate-700 mb-1">المواصفة المرجعية</label>
                        <input type="text" name="reference_standard" id="reference_standard" value="{{ old('reference_standard', $certificateTemplate->reference_standard) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>

                    <div>
                        <label for="safety_factor" class="block font-bold text-slate-700 mb-1">معامل الأمان (Safety Factor)</label>
                        <input type="text" name="safety_factor" id="safety_factor" value="{{ old('safety_factor', $certificateTemplate->safety_factor) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>

                    <div>
                        <label for="capacity" class="block font-bold text-slate-700 mb-1">{{ __('السعة / أقصى قدرة') }}</label>
                        <input type="text" name="capacity" id="capacity" value="{{ old('capacity', $certificateTemplate->capacity) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label for="manufacturer" class="block font-bold text-slate-700 mb-1">الشركة المصنعة</label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $certificateTemplate->manufacturer) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>

                    <div>
                        <label for="model" class="block font-bold text-slate-700 mb-1">الموديل (Model)</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $certificateTemplate->model) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>

                    <div>
                        <label for="dimensions" class="block font-bold text-slate-700 mb-1">{{ __('الأبعاد / الطول والقطر') }}</label>
                        <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $certificateTemplate->dimensions) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>

                    <div>
                        <label for="length" class="block font-bold text-slate-700 mb-1">{{ __('الطول الفعال (Length)') }}</label>
                        <input type="text" name="length" id="length" value="{{ old('length', $certificateTemplate->length) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: CMS DYNAMIC CUSTOM SPECIFICATIONS & INSPECTION TOOLS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0E4DA4] text-xs font-bold flex items-center justify-center">3</span>
                    <div>
                        <h2 class="font-bold text-slate-800 text-xs sm:text-sm">أدوات ومعدات الفحص والمواصفات الديناميكية (CMS Tools &amp; Equipment)</h2>
                        <p class="text-[11px] text-slate-500">أجهزة القياس، اختبارات NDT، معايير التحقق، وأي أدوات يرغب العميل بإضافتها</p>
                    </div>
                </div>
                <button type="button" @click="addSpec()" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0E4DA4] text-xs font-bold transition flex items-center gap-1 border border-blue-200">
                    <span>{{ __('+ إضافة أداة / مواصفة') }}</span>
                </button>
            </div>

            <div class="space-y-2">
                <template x-for="(spec, idx) in specs" :key="idx">
                    <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="w-1/3">
                            <input type="text" :name="`custom_specifications[${idx}][name]`" x-model="spec.name" 
                                   placeholder="اسم الأداة / المعدة" 
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-semibold">
                        </div>
                        <div class="w-1/4">
                            <select :name="`custom_specifications[${idx}][type]`" x-model="spec.type" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-bold text-slate-700">
                                <option value="tool">أداة قياس (Tool)</option>
                                <option value="equipment">جهاز فحص / NDT</option>
                                <option value="standard">مواصفة / كود</option>
                                <option value="safety">نظام أمان (Safety)</option>
                                <option value="compliance">متطلب قانوني</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="text" :name="`custom_specifications[${idx}][value]`" x-model="spec.value" 
                                   placeholder="القيمة / كود المعايرة / الرقم التسلسلي" 
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white">
                        </div>
                        <button type="button" @click="removeSpec(idx)" class="p-1.5 rounded-lg hover:bg-red-100 text-slate-400 hover:text-red-600 transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>

                <div x-show="specs.length === 0" class="text-center py-6 border border-dashed border-slate-300 rounded-xl text-slate-400 text-xs">
                    لا توجد أدوات فحص مخصصة. اضغط على "+ إضافة أداة / مواصفة" لإضافة أجهزة ومعدات الفحص.
                </div>
            </div>
        </div>

        <!-- CARD 4: INTERACTIVE CHECKLIST BUILDER -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0E4DA4] text-xs font-bold flex items-center justify-center">4</span>
                    <div>
                        <h2 class="font-bold text-slate-800 text-xs sm:text-sm">قائمة الفحص المعتمدة للقالب (Checklist Items Manager)</h2>
                        <p class="text-[11px] text-slate-500">حدد بنود الفحص المعتمدة التي ستظهر وتُطبع في الشهادة لهذا القالب</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="addChecklistItem()" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0E4DA4] text-xs font-bold transition flex items-center gap-1 border border-blue-200">
                        <span>+ إضافة بند فحص</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto max-h-96 border border-slate-200 rounded-xl">
                <table class="w-full text-right text-xs border-collapse">
                    <thead class="bg-slate-100 text-slate-700 sticky top-0 font-bold">
                        <tr>
                            <th class="p-2.5 w-10 text-center">#</th>
                            <th class="p-2.5 w-1/4">التصنيف (Category)</th>
                            <th class="p-2.5">بند الفحص وموضع الاختبار (Item / Question)</th>
                            <th class="p-2.5 w-24 text-center">النتيجة الافتراضية</th>
                            <th class="p-2.5 w-1/4">ملاحظات الحالة (Remarks)</th>
                            <th class="p-2.5 w-10 text-center">حذف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-for="(item, idx) in checklist" :key="idx">
                            <tr class="hover:bg-slate-50">
                                <td class="p-2 text-center text-slate-400 font-mono" x-text="idx + 1"></td>
                                <td class="p-2">
                                    <input type="text" :name="`checklist_items[${idx}][category]`" x-model="item.category" 
                                           class="w-full px-2 py-1 rounded border border-slate-300 font-semibold text-slate-800 text-[11px]">
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="`checklist_items[${idx}][question]`" x-model="item.question" 
                                           class="w-full px-2 py-1 rounded border border-slate-300 font-en font-medium text-slate-900 text-[11px]">
                                </td>
                                <td class="p-2 text-center">
                                    <select :name="`checklist_items[${idx}][status]`" x-model="item.status" class="px-2 py-1 rounded border border-slate-300 font-bold text-[11px]">
                                        <option value="Pass">Pass (مطابق)</option>
                                        <option value="Fail">Fail (معيب)</option>
                                        <option value="N/A">N/A (غير منطبق)</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="`checklist_items[${idx}][remarks]`" x-model="item.remarks" 
                                           class="w-full px-2 py-1 rounded border border-slate-300 text-[11px]">
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" @click="removeChecklistItem(idx)" class="text-slate-400 hover:text-red-600 p-1">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD 5: DEFAULT TESTS & REMARKS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0E4DA4] text-xs font-bold flex items-center justify-center">5</span>
                    <h2 class="font-bold text-slate-800 text-xs sm:text-sm">نصوص الفحوصات المنفذة والملاحظات</h2>
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label for="tests_carried_out" class="block font-bold text-slate-700 mb-1">الفحوصات والاختبارات المنفذة الافتراضية (Tests Carried Out)</label>
                    <textarea name="tests_carried_out" id="tests_carried_out" rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none font-en">{{ old('tests_carried_out', $certificateTemplate->tests_carried_out) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="defect_identification" class="block font-bold text-slate-700 mb-1">تعيين العيوب الافتراضي (Defects)</label>
                        <input type="text" name="defect_identification" id="defect_identification" value="{{ old('defect_identification', $certificateTemplate->defect_identification) }}" 
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 font-en outline-none">
                    </div>
                    <div>
                        <label for="repair_particulars" class="block font-bold text-slate-700 mb-1">تفاصيل الإصلاحات الافتراضية (Repairs)</label>
                        <input type="text" name="repair_particulars" id="repair_particulars" value="{{ old('repair_particulars', $certificateTemplate->repair_particulars) }}" 
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 font-en outline-none">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block font-bold text-slate-700 mb-1">ملاحظات داخلية للقالب (Internal Studio Notes)</label>
                    <textarea name="notes" id="notes" rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">{{ old('notes', $certificateTemplate->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="sticky bottom-4 bg-slate-900/90 backdrop-blur-md text-white p-3.5 rounded-2xl flex items-center justify-between shadow-2xl z-30">
            <div class="flex items-center gap-2 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse"></span>
                <span>تعديل قالب: {{ $certificateTemplate->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('certificate-templates.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition">
                    إلغاء
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0E4DA4] hover:bg-blue-600 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                    <span>حفظ التعديلات في الاستوديو</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function certificateStudio(config) {
    return {
        type: config.type || 'crane',
        specs: config.specs || [],
        checklist: config.checklist || [],

        addSpec() {
            this.specs.push({
                name: '',
                type: 'tool',
                value: ''
            });
        },

        removeSpec(index) {
            this.specs.splice(index, 1);
        },

        addChecklistItem() {
            this.checklist.push({
                category: this.type === 'crane' ? 'Crane Systems' : (this.type === 'accessory' ? 'Visual Examination' : 'General Inspection'),
                question: 'New Inspection Check Item',
                status: 'Pass',
                remarks: 'Accepted'
            });
        },

        removeChecklistItem(index) {
            this.checklist.splice(index, 1);
        }
    };
}
</script>
@endsection
