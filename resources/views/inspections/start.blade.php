@extends('layouts.app')

@section('title', 'بدء فحص ميداني جديد - Start Inspection')

@section('content')
<div class="space-y-4">

    <!-- Top Breadcrumb & Header -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('inspections.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-800 border border-slate-200 font-mono">
                            {{ $nextNumber }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">{{ __('بدء فحص ميداني جديد (Start Inspection)') }}</h1>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('الخطوة 1: اختيار العميل ونوع المعدة / القالب لتجهيز شيك ليست الفحص الميداني') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('inspections.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                    {{ __('إلغاء') }}
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-2">
        <form action="{{ route('inspections.store') }}" method="POST" id="startInspectionForm" class="space-y-6">
            @csrf

            <!-- Client & Template Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-[#0E4DA4] text-white flex items-center justify-center font-bold text-xs shadow-xs">1</div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">{{ __('العميل ونموذج الفحص') }}</h2>
                            <p class="text-[11px] text-slate-500">{{ __('اختر العميل المعني ونوع المعدة لتحميل بنود الفحص والمعايير المعتمدة تلقائياً') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Customer Selector -->
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            {{ __('العميل') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2.5 bg-white">
                            <option value="">-- {{ __('العميل') }} --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $selectedCustomerId) == $customer->id) ? 'selected' : '' }} data-address="{{ $customer->address }}">
                                    {{ $customer->name }} {{ $customer->phone ? '('.$customer->phone.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Template Selector (All 28 Sheets) -->
                    <div>
                        <label for="certificate_template_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            {{ __('قالب الفحص / نوع المعدة') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="certificate_template_id" id="certificate_template_id" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2.5 bg-white">
                            <option value="">-- {{ __('قالب الفحص / نوع المعدة') }} (28) --</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl->id }}" 
                                    {{ (old('certificate_template_id', $selectedTemplate?->id) == $tpl->id) ? 'selected' : '' }}
                                    data-name="{{ $tpl->name }}"
                                    data-code="{{ $tpl->code }}"
                                    data-type="{{ $tpl->equipment_type }}"
                                    data-standard="{{ $tpl->reference_standard }}"
                                    data-swl="{{ $tpl->swl }}"
                                    data-validity="{{ $tpl->validity_months }}"
                                    data-desc="{{ $tpl->equipment_description }}">
                                    [{{ $tpl->code }}] {{ $tpl->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">يحتوي كل قالب على بنود الفحص المعتمدة وفقاً لمعايير ISO 17020 و LOLER</p>
                    </div>

                    <!-- Equipment Name (Auto-populated or editable) -->
                    <div class="md:col-span-2">
                        <label for="equipment_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            اسم ووصف المعدة (Equipment Name & Description) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="equipment_name" id="equipment_name" required
                               value="{{ old('equipment_name', $selectedTemplate?->name) }}"
                               placeholder="مثال: Mobile Telescopic Crane 90T / ونش تلسكوبي متنقل 90 طن"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2.5">
                    </div>

                    <!-- Hidden Type & Doc No -->
                    <input type="hidden" name="equipment_type" id="equipment_type" value="{{ old('equipment_type', $selectedTemplate?->equipment_type ?? 'crane') }}">
                    <input type="hidden" name="doc_number" id="doc_number" value="{{ old('doc_number', $selectedTemplate?->code ?? 'SAF-INS-01') }}">

                    <!-- Reference Standard -->
                    <div>
                        <label for="reference_standard" class="block text-xs font-bold text-slate-700 mb-1.5">
                            المواصفة المرجعية (Applicable Standard)
                        </label>
                        <input type="text" name="reference_standard" id="reference_standard"
                               value="{{ old('reference_standard', $selectedTemplate?->reference_standard) }}"
                               placeholder="BS 7121 / BS EN 15011 / LOLER 1998"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2 bg-slate-50">
                    </div>

                    <!-- Inspection Type -->
                    <div>
                        <label for="inspection_type" class="block text-xs font-bold text-slate-700 mb-1.5">
                            نوع الفحص (Inspection Type) <span class="text-red-500">*</span>
                        </label>
                        <select name="inspection_type" id="inspection_type" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2 bg-white">
                            <option value="periodic" {{ old('inspection_type') === 'periodic' ? 'selected' : '' }}>فحص دوري (Periodic Examination)</option>
                            <option value="initial" {{ old('inspection_type') === 'initial' ? 'selected' : '' }}>فحص أول مرة بعد التركيب (Initial Inspection)</option>
                            <option value="post_repair" {{ old('inspection_type') === 'post_repair' ? 'selected' : '' }}>فحص بعد الإصلاح / التعديل (Post-Repair)</option>
                            <option value="post_incident" {{ old('inspection_type') === 'post_incident' ? 'selected' : '' }}>فحص بعد حادث / ظروف استثنائية (Post-Incident)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Asset Identification Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-[#006837] text-white flex items-center justify-center font-bold text-xs shadow-xs">2</div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">بيانات المعدة والأصل (Asset Identification)</h2>
                            <p class="text-[11px] text-slate-500">الحقول المميزة بالأصفر في استمارة الفحص الميدانية لتمييز الأصل</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Asset / Tag No -->
                    <div>
                        <label for="asset_tag" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم الأصل / التاج (Asset / Tag No.)
                        </label>
                        <input type="text" name="asset_tag" id="asset_tag" value="{{ old('asset_tag') }}"
                               placeholder="مثال: CR-001 / TAG-2026-X"
                               class="w-full text-xs font-semibold rounded-lg border-amber-300 bg-amber-50/40 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2 font-mono">
                    </div>

                    <!-- Serial / Chassis No -->
                    <div>
                        <label for="serial_no" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الرقم المسلسل / الشاسيه (Serial / Chassis No.)
                        </label>
                        <input type="text" name="serial_no" id="serial_no" value="{{ old('serial_no') }}"
                               placeholder="مثال: SN-98234-A1"
                               class="w-full text-xs font-semibold rounded-lg border-amber-300 bg-amber-50/40 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2 font-mono">
                    </div>

                    <!-- SWL / Rated Capacity -->
                    <div>
                        <label for="swl" class="block text-xs font-bold text-slate-700 mb-1.5">
                            حمولة التشغيل الآمنة (SWL / Rated Capacity)
                        </label>
                        <input type="text" name="swl" id="swl" value="{{ old('swl', $selectedTemplate?->swl) }}"
                               placeholder="مثال: 50 Ton / 5000 kg"
                               class="w-full text-xs font-semibold rounded-lg border-amber-300 bg-amber-50/40 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>

                    <!-- Manufacturer -->
                    <div>
                        <label for="manufacturer" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الشركة المصنعة (Manufacturer)
                        </label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer') }}"
                               placeholder="مثال: Liebherr / Demag / Crosby"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>

                    <!-- Model No -->
                    <div>
                        <label for="model_no" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الموديل / الطراز (Model No.)
                        </label>
                        <input type="text" name="model_no" id="model_no" value="{{ old('model_no') }}"
                               placeholder="مثال: LTM 1090 / G-2130"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>

                    <!-- Year of Manufacture -->
                    <div>
                        <label for="year_of_manufacture" class="block text-xs font-bold text-slate-700 mb-1.5">
                            سنة الصنع (Year of Manufacture)
                        </label>
                        <input type="text" name="year_of_manufacture" id="year_of_manufacture" value="{{ old('year_of_manufacture') }}"
                               placeholder="2022"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>

                    <!-- Location / Site -->
                    <div class="sm:col-span-2 md:col-span-3">
                        <label for="location" class="block text-xs font-bold text-slate-700 mb-1.5">
                            موقع الفحص / الساحة (Location / Site)
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               placeholder="مثال: ميناء دمياط - رصيف الحاويات / ساحة ورش الشركة"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>
                </div>
            </div>

            <!-- Examination Meta & Inspector -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">3</div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">المفتش وتاريخ الفحص (Inspector & Examination Date)</h2>
                            <p class="text-[11px] text-slate-500">بيانات الفاحص المعتمد وتاريخ المعاينة الميدانية</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Date -->
                    <div>
                        <label for="inspection_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            تاريخ الفحص (Date of Examination) <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="inspection_date" id="inspection_date" required value="{{ old('inspection_date', $today) }}"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>

                    <!-- Inspector Name & Selection -->
                    <div x-data="{
                        customInspector: false,
                        onInspectorChange(e) {
                            const val = e.target.value;
                            if (val === 'custom') {
                                this.customInspector = true;
                                document.getElementById('inspector_id_input').value = '';
                                document.getElementById('inspector_name').value = '';
                                $nextTick(() => document.getElementById('inspector_name').focus());
                            } else {
                                this.customInspector = false;
                                document.getElementById('inspector_id_input').value = val;
                                const opt = e.target.selectedOptions[0];
                                document.getElementById('inspector_name').value = opt ? opt.getAttribute('data-name') : '';
                            }
                        }
                    }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">
                                المهندس الفاحص (Inspector) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click="customInspector = !customInspector; if (customInspector) { document.getElementById('inspector_id_input').value = ''; } else { document.getElementById('inspector_id_input').value = '{{ $user->id }}'; document.getElementById('inspector_name').value = '{{ $user->name }}'; }" class="text-[11px] text-[#0E4DA4] hover:underline font-semibold">
                                <span x-show="!customInspector">+ مهندس فحص آخر</span>
                                <span x-show="customInspector" style="display:none;">اختيار من القائمة المسجلة</span>
                            </button>
                        </div>

                        <input type="hidden" name="inspector_id" id="inspector_id_input" value="{{ old('inspector_id', $user->id) }}">

                        <div x-show="!customInspector">
                            <select @change="onInspectorChange($event)" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2 bg-white">
                                @foreach($inspectors as $insp)
                                    <option value="{{ $insp->id }}" data-name="{{ $insp->name }}" {{ (old('inspector_id', $user->id) == $insp->id) ? 'selected' : '' }}>
                                        {{ $insp->name }} ({{ $insp->job_title_ar ?: ($insp->primaryRoleName() ?? 'فاحص') }}) {{ $insp->signature_path ? '✍️' : '' }}
                                    </option>
                                @endforeach
                                <option value="custom">-- مهندس غير مسجل (إدخال يدوي) --</option>
                            </select>
                        </div>

                        <div :class="customInspector ? 'block mt-1' : 'hidden'">
                            <input type="text" name="inspector_name" id="inspector_name" required value="{{ old('inspector_name', $user->name) }}"
                                   placeholder="اكتب اسم مهندس الفحص..."
                                   class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                        </div>
                    </div>

                    <!-- Competency / Certification Ref -->
                    <div>
                        <label for="inspector_cert_ref" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم اعتماد الكفاءة (Certification Ref.)
                        </label>
                        <input type="text" name="inspector_cert_ref" id="inspector_cert_ref" value="{{ old('inspector_cert_ref', 'LEEA / ISO 17020 Inspector') }}"
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                    </div>
                </div>
            </div>

            <!-- Submit Button (Large & Prominent) -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#006837] hover:bg-[#00522c] text-white text-sm font-extrabold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                    <span>البدء في شيك ليست الفحص من الموبايل</span>
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('certificate_template_id');
    const nameInput = document.getElementById('equipment_name');
    const typeInput = document.getElementById('equipment_type');
    const docInput = document.getElementById('doc_number');
    const standardInput = document.getElementById('reference_standard');
    const swlInput = document.getElementById('swl');

    const customerSelect = document.getElementById('customer_id');
    const locationInput = document.getElementById('location');

    // When template changes, auto-populate details
    templateSelect.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        if (option && option.value) {
            if (!nameInput.value || nameInput.value.trim() === '') {
                nameInput.value = option.getAttribute('data-name') || '';
            }
            typeInput.value = option.getAttribute('data-type') || 'crane';
            docInput.value = option.getAttribute('data-code') || '';
            standardInput.value = option.getAttribute('data-standard') || '';
            if (!swlInput.value && option.getAttribute('data-swl')) {
                swlInput.value = option.getAttribute('data-swl');
            }
        }
    });

    // When customer changes, suggest address for location
    customerSelect.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        if (option && option.value) {
            const addr = option.getAttribute('data-address');
            if (addr && (!locationInput.value || locationInput.value.trim() === '')) {
                locationInput.value = addr;
            }
        }
    });
});
</script>
@endsection
