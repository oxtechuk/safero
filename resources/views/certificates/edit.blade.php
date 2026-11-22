@extends('layouts.app')

@php
    $type = $certificate->equipment_type ?? 'crane';
    $typeTitles = [
        'crane' => [
            'ar' => 'فحص الأوناش والروافع المتنقلة',
            'en' => 'Lifting Appliances (Mobile Cranes)',
            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        ],
        'accessory' => [
            'ar' => 'فحص مهمات الرفع والحبال والشكالات',
            'en' => 'Lifting Accessories (Slings)',
            'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
        ],
        'general' => [
            'ar' => 'فحص المعدات العامة ووحدات الشحن والباليتات',
            'en' => 'General Cargo Units',
            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        ],
    ];
    $activeMeta = $typeTitles[$type] ?? $typeTitles['crane'];
@endphp

@section('title', __('تعديل شهادة:') . ' ' . $certificate->certificate_number)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-4">

   

    <!-- Main Edit Form -->
    <form method="POST" action="{{ route('certificates.update', $certificate) }}" id="certEditForm" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="equipment_type" value="{{ $type }}">

        <!-- CARD 1: Meta -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-800 text-xs">{{ __('معلومات الشهادة والعميل وموقع الفحص') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">General Metadata</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <label for="certificate_number" class="block font-bold text-slate-700 mb-1">{{ __('رقم الشهادة') }}</label>
                    <input type="text" name="certificate_number" id="certificate_number" value="{{ old('certificate_number', $certificate->certificate_number) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold text-[#0E4DA4] bg-slate-50 outline-none">
                </div>

                <div>
                    <label for="order_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم أمر الشغل') }}</label>
                    <input type="text" name="order_no" id="order_no" value="{{ old('order_no', $certificate->order_no) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="customer_id" class="block font-bold text-slate-700 mb-1">{{ __('اسم العميل') }}</label>
                    <select name="customer_id" id="customer_id" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (old('customer_id', $certificate->customer_id) == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="examination_address" class="block font-bold text-slate-700 mb-1">{{ __('موقع الفحص') }}</label>
                    <input type="text" name="examination_address" id="examination_address" value="{{ old('examination_address', $certificate->examination_address) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-1">
                <div>
                    <label for="thorough_examination_date" class="block font-bold text-slate-700 mb-1">{{ __('تاريخ الفحص') }}</label>
                    <input type="date" name="thorough_examination_date" id="thorough_examination_date" value="{{ old('thorough_examination_date', $certificate->thorough_examination_date?->format('Y-m-d')) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="next_examination_due_date" class="block font-bold text-slate-700 mb-1">{{ __('تاريخ الاستحقاق القادم') }}</label>
                    <input type="date" name="next_examination_due_date" id="next_examination_due_date" value="{{ old('next_examination_due_date', $certificate->next_examination_due_date?->format('Y-m-d')) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="reference_standard" class="block font-bold text-slate-700 mb-1">{{ __('المواصفة المرجعية') }}</label>
                    <input type="text" name="reference_standard" id="reference_standard" value="{{ old('reference_standard', $certificate->reference_standard) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="swl" class="block font-bold text-slate-700 mb-1">{{ __('الحمل الآمن (SWL)') }}</label>
                    <input type="text" name="swl" id="swl" value="{{ old('swl', $certificate->swl) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold text-[#0E4DA4] outline-none">
                </div>
            </div>
        </div>

        <!-- CARD 2: Specialized Specs -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-800 text-xs">{{ __('مواصفات وبيانات المعدة المفحوصة') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Equipment Specs</span>
            </div>

            @if($type === 'crane')
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">وصف المعدة</label>
                            <input type="text" name="equipment_description" id="equipment_description" value="{{ old('equipment_description', $certificate->equipment_description) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-semibold outline-none">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">الشركة المصنعة</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $certificate->manufacturer) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="model" class="block font-bold text-slate-700 mb-1">{{ __('الموديل (Model)') }}</label>
                            <input type="text" name="model" id="model" value="{{ old('model', $certificate->model) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="capacity" class="block font-bold text-slate-700 mb-1">{{ __('السعة (Capacity)') }}</label>
                            <input type="text" name="capacity" id="capacity" value="{{ old('capacity', $certificate->capacity) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="chassis_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم الشاسيه / السيريال') }}</label>
                            <input type="text" name="chassis_no" id="chassis_no" value="{{ old('chassis_no', $certificate->chassis_no) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold outline-none">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">سنة الصنع</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', $certificate->date_of_manufacture) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="main_wire" class="block font-bold text-slate-700 mb-1">{{ __('قطر الواير الرئيسي') }}</label>
                            <input type="text" name="main_wire" id="main_wire" value="{{ old('main_wire', $certificate->main_wire) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="hook_block" class="block font-bold text-slate-700 mb-1">{{ __('سعة الهوك الرئيسي') }}</label>
                            <input type="text" name="hook_block" id="hook_block" value="{{ old('hook_block', $certificate->hook_block) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="load_test_cert_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم شهادة تجربة الحمل') }}</label>
                            <input type="text" name="load_test_cert_no" id="load_test_cert_no" value="{{ old('load_test_cert_no', $certificate->load_test_cert_no) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="date_of_load_test" class="block font-bold text-slate-700 mb-1">{{ __('تاريخ تجربة الحمل') }}</label>
                            <input type="date" name="date_of_load_test" id="date_of_load_test" value="{{ old('date_of_load_test', $certificate->date_of_load_test?->format('Y-m-d')) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="test_point" class="block font-bold text-slate-700 mb-1">مواصفات ونقطة تجربة الحمل</label>
                        <input type="text" name="test_point" id="test_point" value="{{ old('test_point', $certificate->test_point) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                    </div>
                </div>
            @elseif($type === 'accessory')
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">وصف المهمات</label>
                            <input type="text" name="equipment_description" id="equipment_description" value="{{ old('equipment_description', $certificate->equipment_description) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-semibold outline-none">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">جهة التصنيع</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $certificate->manufacturer) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="safety_factor" class="block font-bold text-slate-700 mb-1">معامل الأمان</label>
                            <input type="text" name="safety_factor" id="safety_factor" value="{{ old('safety_factor', $certificate->safety_factor) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold text-emerald-700 outline-none">
                        </div>
                        <div>
                            <label for="length" class="block font-bold text-slate-700 mb-1">الطول</label>
                            <input type="text" name="length" id="length" value="{{ old('length', $certificate->length) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">الكمية</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $certificate->quantity) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">سيريال / كود المهمات</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', $certificate->equipment_id_range) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">وصف المعدة</label>
                            <input type="text" name="equipment_description" id="equipment_description" value="{{ old('equipment_description', $certificate->equipment_description) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-semibold outline-none">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">سنة الصنع</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', $certificate->date_of_manufacture) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="dimensions" class="block font-bold text-slate-700 mb-1">الأبعاد (Dimensions)</label>
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $certificate->dimensions) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">الكمية</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $certificate->quantity) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">ترقيم المعدات</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', $certificate->equipment_id_range) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- CARD 3: CRANE CHECKLIST (When crane) -->
        @if($type === 'crane')
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">3</span>
                        <h2 class="font-bold text-slate-800 text-xs">قائمة الفحص الهندسي للونش (28 نقطة)</h2>
                    </div>
                    <button type="button" onclick="markAllChecklist('Pass')" class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 text-[11px] font-bold transition flex items-center gap-1 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('تحديد الكل سليم (Pass All)') }}</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    @php $items = $craneChecklist ?? []; @endphp
                    @foreach($items as $idx => $chk)
                        @php
                            $no = $chk['no'] ?? ($idx + 1);
                            $itemName = $chk['item'] ?? $chk['question'] ?? '';
                            $cat = $chk['category'] ?? 'Inspection';
                            $st = $chk['status'] ?? $chk['check'] ?? 'Pass';
                            $rm = $chk['remarks'] ?? $chk['comment'] ?? 'Good condition';
                        @endphp
                        <div class="flex items-center justify-between gap-2 py-1.5 px-2 rounded-lg bg-slate-50/70 border border-slate-200/60 hover:bg-slate-100/70 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-5 h-5 rounded bg-white border border-slate-200 text-slate-500 font-bold text-[10px] flex items-center justify-center shrink-0 font-en">{{ $no }}</span>
                                <div class="truncate">
                                    <span class="font-semibold text-slate-800 block truncate font-en text-[11px]" title="{{ $itemName }}">{{ $itemName }}</span>
                                    <span class="text-[9px] text-slate-400 block truncate">{{ $cat }}</span>
                                </div>
                            </div>

                            <input type="hidden" name="checklist_items[{{ $idx }}][no]" value="{{ $no }}">
                            <input type="hidden" name="checklist_items[{{ $idx }}][item]" value="{{ $itemName }}">
                            <input type="hidden" name="checklist_items[{{ $idx }}][category]" value="{{ $cat }}">

                            <div class="flex items-center gap-1 shrink-0 font-en text-[10px]">
                                <label class="cursor-pointer">
                                    <input type="radio" name="checklist_items[{{ $idx }}][status]" value="Pass" {{ in_array(strtolower($st), ['pass', 'in order']) ? 'checked' : '' }} class="sr-only peer checklist-radio-pass">
                                    <span class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 peer-checked:bg-[#22A447] peer-checked:text-white peer-checked:border-[#22A447] font-bold block transition">Pass</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="checklist_items[{{ $idx }}][status]" value="Fail" {{ in_array(strtolower($st), ['fail', 'defect']) ? 'checked' : '' }} class="sr-only peer">
                                    <span class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 font-bold block transition">Fail</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="checklist_items[{{ $idx }}][status]" value="N/A" {{ in_array(strtolower($st), ['n/a', 'not applicable']) ? 'checked' : '' }} class="sr-only peer">
                                    <span class="px-2 py-0.5 rounded border border-slate-200 text-slate-600 peer-checked:bg-slate-600 peer-checked:text-white peer-checked:border-slate-600 font-bold block transition">N/A</span>
                                </label>
                                <input type="hidden" name="checklist_items[{{ $idx }}][remarks]" value="{{ $rm }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CARD 4: STATUTORY QUESTIONS -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">{{ $type === 'crane' ? '4' : '3' }}</span>
                    <h2 class="font-bold text-slate-800 text-xs">أسئلة الفحص والقرار الفني</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Statutory Findings</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[11px]">
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('أول فحص بعد التركيب؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="1" {{ old('is_first_examination_after_installation', $certificate->is_first_examination_after_installation) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="0" {{ !old('is_first_examination_after_installation', $certificate->is_first_examination_after_installation) ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('الفحص خلال 6 أشهر؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_6_months" value="1" {{ old('was_examination_within_6_months', $certificate->was_examination_within_6_months) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="was_examination_within_6_months" value="0" {{ !old('was_examination_within_6_months', $certificate->was_examination_within_6_months) ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('تم التركيب بشكل سليم؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="has_equipment_installed_correctly" value="1" {{ old('has_equipment_installed_correctly', $certificate->has_equipment_installed_correctly) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="has_equipment_installed_correctly" value="0" {{ !old('has_equipment_installed_correctly', $certificate->has_equipment_installed_correctly) ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('الفحص خلال 12 شهر؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="1" {{ old('was_examination_within_12_months', $certificate->was_examination_within_12_months) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="0" {{ !old('was_examination_within_12_months', $certificate->was_examination_within_12_months) ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('وفق مخطط الفحص؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="in_accordance_with_scheme" value="1" {{ old('in_accordance_with_scheme', $certificate->in_accordance_with_scheme) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="in_accordance_with_scheme" value="0" {{ !old('in_accordance_with_scheme', $certificate->in_accordance_with_scheme) ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('بعد ظروف استثنائية؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="1" {{ old('after_exceptional_circumstances', $certificate->after_exceptional_circumstances) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="0" {{ !old('after_exceptional_circumstances', $certificate->after_exceptional_circumstances) ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                <div>
                    <label for="defect_identification" class="block font-bold text-slate-700 mb-1">الأجزاء المعيبة</label>
                    <input type="text" name="defect_identification" id="defect_identification" value="{{ old('defect_identification', $certificate->defect_identification) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="repair_particulars" class="block font-bold text-slate-700 mb-1">{{ __('الإصلاحات المطلوبة') }}</label>
                    <input type="text" name="repair_particulars" id="repair_particulars" value="{{ old('repair_particulars', $certificate->repair_particulars) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ __('هل العيب يشكل خطورة على العمليات؟') }}</label>
                    <div class="flex items-center gap-3 h-8 px-2 rounded-lg bg-slate-50 border border-slate-200 font-en text-[11px] font-bold">
                        <label class="cursor-pointer text-red-600"><input type="radio" name="is_defect_danger_to_process" value="1" {{ old('is_defect_danger_to_process', $certificate->is_defect_danger_to_process) ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-[#22A447]"><input type="radio" name="is_defect_danger_to_process" value="0" {{ !old('is_defect_danger_to_process', $certificate->is_defect_danger_to_process) ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <div class="text-xs">
                <label for="tests_carried_out" class="block font-bold text-slate-700 mb-1">{{ __('تفاصيل الفحوصات والاختبارات المنفذة') }}</label>
                <input type="text" name="tests_carried_out" id="tests_carried_out" value="{{ old('tests_carried_out', $certificate->tests_carried_out) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
            </div>

            <div class="p-3 rounded-lg border border-emerald-300 bg-emerald-50/50 flex items-center justify-between">
                <div>
                    <span class="block font-bold text-slate-900 text-xs">هل المعدة آمنة تماماً للتشغيل؟ (Safe to Operate)</span>
                </div>
                <div class="flex items-center gap-2 font-en text-xs font-bold">
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-[#22A447] shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="1" {{ old('is_safe_to_operate', $certificate->is_safe_to_operate) ? 'checked' : '' }}>
                        <span>{{ __('YES (آمنة)') }}</span>
                    </label>
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-red-300 text-red-600 shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="0" {{ !old('is_safe_to_operate', $certificate->is_safe_to_operate) ? 'checked' : '' }}>
                        <span>{{ __('NO (غير آمنة)') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- CARD 5: SIGNATURES -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs items-end">
                <div>
                    <label for="issuer_name" class="block font-bold text-slate-700 mb-1">مهندس الفحص</label>
                    <input type="text" name="issuer_name" id="issuer_name" value="{{ old('issuer_name', $certificate->issuer_name) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="authenticator_name" class="block font-bold text-slate-700 mb-1">معتمد الشهادة</label>
                    <input type="text" name="authenticator_name" id="authenticator_name" value="{{ old('authenticator_name', $certificate->authenticator_name) }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="status" class="block font-bold text-slate-700 mb-1">{{ __('حالة الشهادة') }}</label>
                    <select name="status" id="status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                        <option value="issued" {{ old('status', $certificate->status) === 'issued' ? 'selected' : '' }}>{{ __('معتمدة ومصدرة (Issued)') }}</option>
                        <option value="draft" {{ old('status', $certificate->status) === 'draft' ? 'selected' : '' }}>{{ __('مسودة للمراجعة (Draft)') }}</option>
                        <option value="revoked" {{ old('status', $certificate->status) === 'revoked' ? 'selected' : '' }}>ملغاة (Revoked)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('certificates.show', $certificate) }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                        {{ __('إلغاء') }}
                    </a>
                    <button type="submit" class="px-5 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ التعديلات</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function markAllChecklist(status) {
        const radios = document.querySelectorAll('.checklist-radio-pass');
        radios.forEach(r => {
            r.checked = true;
        });
    }
</script>
@endpush
@endsection
