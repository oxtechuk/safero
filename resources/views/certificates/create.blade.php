@extends('layouts.app')

@php
    $typeTitles = [
        'crane' => [
            'ar' => __('فحص الأوناش والروافع المتنقلة'),
            'en' => 'Lifting Appliances (Mobile & Telescopic Cranes)',
            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'badge' => __('تقرير تفصيلي (صفحتين) + فحص 28 نقطة'),
        ],
        'accessory' => [
            'ar' => __('فحص مهمات الرفع والحبال والشكالات'),
            'en' => 'Lifting Accessories (Slings, Shackles & Clamps)',
            'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
            'badge' => __('تقرير فحص معتمد (صفحة واحدة)'),
        ],
        'general' => [
            'ar' => __('فحص المعدات العامة ووحدات الشحن والباليتات'),
            'en' => 'General Cargo Units & Pallet Equipment',
            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'badge' => __('تقرير فحص معتمد (صفحة واحدة)'),
        ],
    ];
    $activeMeta = $typeTitles[$type] ?? $typeTitles['crane'];
@endphp

@section('title', __('إصدار شهادة') . ' - ' . $activeMeta['ar'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-4">

    <!-- Secondary Submenu & Template Selector Bar -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-2xs space-y-2">
        <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-slate-100">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a href="{{ route('certificates.index') }}" class="px-2.5 py-1 rounded text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    ← {{ __('سجل الشهادات') }}
                </a>
                <span class="text-slate-300">|</span>
                <span class="text-xs font-bold text-slate-700">{{ __('نوع الفحص:') }}</span>
                <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="px-2.5 py-1 rounded text-xs font-bold transition {{ $type === 'crane' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    {{ __('أوناش (Crane)') }}
                </a>
                <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="px-2.5 py-1 rounded text-xs font-bold transition {{ $type === 'accessory' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    {{ __('مهمات رفع (Accessory)') }}
                </a>
                <a href="{{ route('certificates.create', ['type' => 'general']) }}" class="px-2.5 py-1 rounded text-xs font-bold transition {{ $type === 'general' ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    {{ __('عام وباليتات (General)') }}
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('certificate-templates.index') }}" class="px-3 py-1 rounded-lg border border-slate-200 bg-white text-[#0E4DA4] hover:bg-slate-50 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-4 h-4 object-contain" alt="">
                    <span>{{ __('ستوديو قوالب الفحص') }}</span>
                </a>
            </div>
        </div>

        <!-- Studio Template Quick-Apply Bar -->
        <div class="flex items-center justify-between gap-3 flex-wrap bg-slate-50 p-2 rounded-lg border border-slate-200/80 text-xs">
            <div class="flex items-center gap-2 flex-grow sm:flex-grow-0">
                <span class="font-bold text-slate-700 whitespace-nowrap flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#0E4DA4]"></span>
                    <span>{{ __('تحميل قالب فحص من الاستوديو:') }}</span>
                </span>
                <select onchange="if(this.value) window.location.href=this.value;" class="bg-white border border-slate-300 rounded-md py-1 px-2.5 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-1 focus:ring-[#0E4DA4] max-w-[280px]">
                    <option value="{{ route('certificates.create', ['type' => $type]) }}">{{ __('-- اختر قالباً لتعبئة البيانات تلقائياً --') }}</option>
                    @foreach($templates as $tmpl)
                        <option value="{{ route('certificates.create', ['template_id' => $tmpl->id]) }}" {{ ($activeTemplate && $activeTemplate->id == $tmpl->id) ? 'selected' : '' }}>
                            {{ $tmpl->name }} [{{ $tmpl->equipment_type_label }} - {{ $tmpl->swl }}]
                        </option>
                    @endforeach
                </select>
            </div>

            @if($activeTemplate)
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md bg-blue-50 text-[#0E4DA4] border border-blue-200 font-bold text-[11px] flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('تم تفعيل القالب:') }} <strong>{{ $activeTemplate->name }}</strong></span>
                    </span>
                    <a href="{{ route('certificates.create', ['type' => $type]) }}" class="text-[11px] text-rose-600 hover:underline font-bold">
                        {{ __('إلغاء القالب') }}
                    </a>
                </div>
            @elseif($renewCertificate)
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 border border-slate-200 font-bold text-[11px] flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>{{ __('تجديد فحص دوري للشهادة:') }} <strong class="font-mono">{{ $renewCertificate->certificate_number }}</strong></span>
                    </span>
                    <a href="{{ route('certificates.create', ['type' => $type]) }}" class="text-[11px] text-rose-600 hover:underline font-bold">
                        {{ __('إلغاء') }}
                    </a>
                </div>
            @else
                <span class="text-[11px] text-slate-500">{{ __('اختر قالباً جاهزاً من الاستوديو لتعبئة المواصفات والأدوات تلقائياً') }}</span>
            @endif
        </div>
    </div>

    <!-- Main Form Container -->
    <form method="POST" action="{{ route('certificates.store') }}" id="certForm" class="space-y-4">
        @csrf
        <input type="hidden" name="equipment_type" value="{{ $type }}">
        @if($activeTemplate)
            <input type="hidden" name="template_id" value="{{ $activeTemplate->id }}">
        @endif

        <!-- CARD 1: Certificate & Customer Meta (Compact 4-Column Grid) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-800 text-xs">{{ __('بيانات الشهادة والعميل وموقع الفحص') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Certificate &amp; Customer Information</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <label for="certificate_number" class="block font-bold text-slate-700 mb-1">
                        {{ __('رقم الشهادة') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="certificate_number" id="certificate_number" 
                           value="{{ old('certificate_number', $defaultCertNo) }}" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] bg-slate-50 outline-none">
                </div>

                <div>
                    <label for="order_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم أمر الشغل (Order No)') }}</label>
                    <input type="text" name="order_no" id="order_no" 
                           value="{{ old('order_no', $renewCertificate ? $renewCertificate->order_no : '100000') }}" placeholder="100000"
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="customer_id" class="block font-bold text-slate-700 mb-1">
                        {{ __('اسم العميل') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required onchange="updateCustomerAddress(this)"
                            class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] bg-white outline-none">
                        <option value="">{{ __('-- اختر العميل --') }}</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-address="{{ $c->address }}" {{ (old('customer_id', $renewCertificate ? $renewCertificate->customer_id : $selectedCustomerId) == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="examination_address" class="block font-bold text-slate-700 mb-1">
                        {{ __('موقع الفحص') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="examination_address" id="examination_address" 
                           value="{{ old('examination_address', $renewCertificate?->examination_address ?? ($activeTemplate?->examination_address ?? ($type === 'crane' ? 'Dabaa NPP' : 'Om Zoghio - Alexandria'))) }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-1">
                <div>
                    <label for="thorough_examination_date" class="block font-bold text-slate-700 mb-1">
                        {{ __('تاريخ الفحص') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="thorough_examination_date" id="thorough_examination_date" 
                           value="{{ old('thorough_examination_date', $today->format('Y-m-d')) }}" required onchange="autoSetNextDue(this.value)"
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="next_examination_due_date" class="block font-bold text-slate-700 mb-1">
                        {{ __('تاريخ الاستحقاق القادم') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="next_examination_due_date" id="next_examination_due_date" 
                           value="{{ old('next_examination_due_date', $nextDue->format('Y-m-d')) }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="reference_standard" class="block font-bold text-slate-700 mb-1">
                        {{ __('المواصفة المرجعية') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reference_standard" id="reference_standard" 
                           value="{{ old('reference_standard', $activeTemplate?->reference_standard ?? ($renewCertificate?->reference_standard ?? ($type === 'crane' ? 'EN 13000' : ($type === 'accessory' ? 'BS EN 1492-2' : 'ASTM A6')))) }}" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="swl" class="block font-bold text-slate-700 mb-1">
                        {{ __('الحمل الآمن (SWL)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="swl" id="swl" 
                           value="{{ old('swl', $activeTemplate?->swl ?? ($renewCertificate?->swl ?? ($type === 'crane' ? '90  Ton' : ($type === 'accessory' ? '30 Ton' : '70  Ton')))) }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                </div>
            </div>
        </div>

        <!-- CARD 2: SPECIALIZED EQUIPMENT SPECS (Only fields relevant to the selected type) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-800 text-xs">{{ __('مواصفات وبيانات المعدة المفحوصة') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Equipment Specifications</span>
            </div>

            @if($type === 'crane')
                <!-- CRANE SPECIALIZED SPECS (Compact 3x3 Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                {{ __('وصف المعدة (Description)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', $activeTemplate?->description ?: ($activeTemplate?->name ?? ($renewCertificate?->equipment_description ?? 'Mobile Telescopic Crane'))) }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">{{ __('الشركة المصنعة (Manufacturer)') }}</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $activeTemplate?->default_manufacturer ?? ($renewCertificate?->manufacturer ?? 'Liebherr')) }}" placeholder="Liebherr / Grove" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="model" class="block font-bold text-slate-700 mb-1">{{ __('الموديل (Model)') }}</label>
                            <input type="text" name="model" id="model" value="{{ old('model', $activeTemplate?->default_model ?? ($renewCertificate?->model ?? 'LTM 1090-4.1')) }}" placeholder="LTM 1090-4.1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="capacity" class="block font-bold text-slate-700 mb-1">{{ __('السعة (Capacity)') }}</label>
                            <input type="text" name="capacity" id="capacity" value="{{ old('capacity', $activeTemplate?->swl ?? ($renewCertificate?->capacity ?? '90 Ton')) }}" placeholder="90 Ton" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="chassis_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم الشاسيه / السيريال') }}</label>
                            <input type="text" name="chassis_no" id="chassis_no" value="{{ old('chassis_no', $renewCertificate?->chassis_no ?? '071490') }}" placeholder="071490" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold outline-none">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">{{ __('سنة الصنع (Year)') }}</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', $renewCertificate?->date_of_manufacture ?? '2008') }}" placeholder="2008" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="main_wire" class="block font-bold text-slate-700 mb-1">{{ __('قطر الواير الرئيسي') }}</label>
                            <input type="text" name="main_wire" id="main_wire" value="{{ old('main_wire', '19 mm') }}" placeholder="19 mm" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="hook_block" class="block font-bold text-slate-700 mb-1">{{ __('سعة الهوك الرئيسي') }}</label>
                            <input type="text" name="hook_block" id="hook_block" value="{{ old('hook_block', '59.2 Ton') }}" placeholder="59.2 Ton" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="load_test_cert_no" class="block font-bold text-slate-700 mb-1">{{ __('رقم شهادة تجربة الحمل') }}</label>
                            <input type="text" name="load_test_cert_no" id="load_test_cert_no" value="{{ old('load_test_cert_no', 'SAF/RS/2026090101') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="date_of_load_test" class="block font-bold text-slate-700 mb-1">{{ __('تاريخ تجربة الحمل') }}</label>
                            <input type="date" name="date_of_load_test" id="date_of_load_test" value="{{ old('date_of_load_test', $today->format('Y-m-d')) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="test_point" class="block font-bold text-slate-700 mb-1">{{ __('مواصفات ونقطة تجربة الحمل (Test Point Particulars)') }}</label>
                        <input type="text" name="test_point" id="test_point" value="{{ old('test_point', 'Boom length: 15.5m / Radius: 6.0m / Test Load: 15.5 Ton') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>
                </div>

            @elseif($type === 'accessory')
                <!-- ACCESSORY SPECS (Compact 4-Column Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                {{ __('وصف المهمات (Description)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', $activeTemplate?->description ?: ($activeTemplate?->name ?? ($renewCertificate?->equipment_description ?? 'Round Sling BS EN 1492-2'))) }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">{{ __('جهة التصنيع (Manufacturer)') }}</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $activeTemplate?->default_manufacturer ?? ($renewCertificate?->manufacturer ?? 'Lifting safety')) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="safety_factor" class="block font-bold text-slate-700 mb-1">{{ __('معامل الأمان (Safety Factor)') }}</label>
                            <input type="text" name="safety_factor" id="safety_factor" value="{{ old('safety_factor', $renewCertificate?->safety_factor ?? '7:1') }}" placeholder="7:1 / 5:1 / 4:1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-emerald-700 outline-none">
                        </div>
                        <div>
                            <label for="length" class="block font-bold text-slate-700 mb-1">{{ __('الطول (Length)') }}</label>
                            <input type="text" name="length" id="length" value="{{ old('length', $renewCertificate?->length ?? '6.0 M') }}" placeholder="6.0 M" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">{{ __('الكمية (Quantity)') }}</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $renewCertificate?->quantity ?? 4) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">{{ __('سيريال / كود المهمات (ID Range)') }}</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', $renewCertificate?->equipment_id_range ?? 'From R00772 To R00775') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
                    </div>
                </div>

            @else
                <!-- GENERAL EQUIPMENT SPECS (Compact 3-Column Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                {{ __('وصف المعدة (Description)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', $activeTemplate?->description ?: ($activeTemplate?->name ?? ($renewCertificate?->equipment_description ?? 'Move Pallet'))) }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">{{ __('سنة الصنع (Manufacture Date)') }}</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', $renewCertificate?->date_of_manufacture ?? '2009') }}" placeholder="2009" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="dimensions" class="block font-bold text-slate-700 mb-1">{{ __('الأبعاد (Dimensions: L, W, H)') }}</label>
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $renewCertificate?->dimensions ?? 'Length: 0.48 m Width: 0.22 m Height: 0.19 m') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">{{ __('الكمية (Quantity)') }}</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $renewCertificate?->quantity ?? 6) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">{{ __('ترقيم المعدات (Equipment ID)') }}</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', $renewCertificate?->equipment_id_range ?? 'From 01 To 06') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if($activeTemplate && !empty($activeTemplate->custom_specifications) && count($activeTemplate->custom_specifications) > 0)
            <!-- SPECIALIZED CMS TOOLS & TEST EQUIPMENT (From Template) -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-2">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">
                            <svg class="w-3 h-3 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <h2 class="font-bold text-slate-800 text-xs">أدوات ومعدات الفحص المخصصة (قالب: {{ $activeTemplate->name }})</h2>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono">Custom CMS Equipment & Tools</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 text-xs">
                    @foreach($activeTemplate->custom_specifications as $spec)
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200 shadow-2xs">
                            <span class="block text-[11px] font-bold text-slate-600">{{ $spec['label'] ?? 'بند' }}</span>
                            <span class="block text-xs font-mono font-bold text-[#0E4DA4] mt-0.5">{{ $spec['value'] ?? '-' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        </div>

        <!-- CARD 3: CRANE 28-POINT CHECKLIST (Shown ONLY when type === 'crane', in Ultra-Compact 2-Column Grid with "Mark All Pass" button) -->
        @if($type === 'crane')
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">3</span>
                        <h2 class="font-bold text-slate-800 text-xs">{{ __('قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)') }}</h2>
                    </div>

                    <!-- Rapid Actions: "Mark All Pass" Button -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="markAllChecklist('Pass')" 
                                class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 text-[11px] font-bold transition flex items-center gap-1 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('تحديد الكل سليم (Pass All)') }}</span>
                        </button>
                        <span class="text-[10px] text-slate-400 font-en hidden sm:inline">Page 2 Checklist</span>
                    </div>
                </div>

                <!-- Compact 2-Column Grid (14 items on right, 14 items on left) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-xs divide-y md:divide-y-0 divide-slate-100">
                    @php $checklist = $defaultCraneChecklist ?? []; @endphp
                    @foreach($checklist as $idx => $chk)
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

                            <!-- Compact Segmented Control (Pass / Fail / N/A) -->
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

        <!-- CARD 4: STATUTORY QUESTIONS & TECHNICAL DECISION (Compact 3x2 Grid) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">{{ $type === 'crane' ? '4' : '3' }}</span>
                    <h2 class="font-bold text-slate-800 text-xs">{{ __('أسئلة الفحص النظامية (LOLER 1998) والقرار الفني') }}</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Statutory Examination Scheme</span>
            </div>

            <!-- 6 Questions in Compact 3-Column Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[11px]">
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('أول فحص بعد التركيب؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="1" {{ old('is_first_examination_after_installation') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="0" {{ old('is_first_examination_after_installation', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('الفحص خلال 6 أشهر؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_6_months" value="1" {{ old('was_examination_within_6_months', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="was_examination_within_6_months" value="0" {{ old('was_examination_within_6_months') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('تم التركيب بشكل سليم؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="has_equipment_installed_correctly" value="1" {{ old('has_equipment_installed_correctly', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="has_equipment_installed_correctly" value="0" {{ old('has_equipment_installed_correctly') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('الفحص خلال 12 شهر؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="1" {{ old('was_examination_within_12_months') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="0" {{ old('was_examination_within_12_months', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('وفق مخطط الفحص؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="in_accordance_with_scheme" value="1" {{ old('in_accordance_with_scheme', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="in_accordance_with_scheme" value="0" {{ old('in_accordance_with_scheme') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">{{ __('بعد ظروف استثنائية؟') }}</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="1" {{ old('after_exceptional_circumstances') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="0" {{ old('after_exceptional_circumstances', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <!-- Findings & Tests Carried Out -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                <div>
                    <label for="defect_identification" class="block font-bold text-slate-700 mb-1">{{ __('الأجزاء المعيبة إن وجدت') }}</label>
                    <input type="text" name="defect_identification" id="defect_identification" value="{{ old('defect_identification', 'NONE') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="repair_particulars" class="block font-bold text-slate-700 mb-1">{{ __('الإصلاحات المطلوبة') }}</label>
                    <input type="text" name="repair_particulars" id="repair_particulars" value="{{ old('repair_particulars', 'NONE') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ __('هل العيب يشكل خطورة على العمليات؟') }}</label>
                    <div class="flex items-center gap-3 h-8 px-2 rounded-lg bg-slate-50 border border-slate-200 font-en text-[11px] font-bold">
                        <label class="cursor-pointer text-red-600"><input type="radio" name="is_defect_danger_to_process" value="1" {{ old('is_defect_danger_to_process') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-[#22A447]"><input type="radio" name="is_defect_danger_to_process" value="0" {{ old('is_defect_danger_to_process', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <div class="text-xs">
                <label for="tests_carried_out" class="block font-bold text-slate-700 mb-1">
                    {{ __('تفاصيل الفحوصات والاختبارات المنفذة') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tests_carried_out" id="tests_carried_out" required
                       value="{{ old('tests_carried_out', ($activeTemplate && !empty($activeTemplate->custom_specifications)) ? ('Visual & Functional Thorough Examination conducted with: ' . collect($activeTemplate->custom_specifications)->pluck('value', 'label')->map(fn($v, $k) => "$k: $v")->implode(' | ') . ' in accordance with ' . ($activeTemplate->reference_standard ?? 'EN 13000')) : ($type === 'crane' ? 'Visual and Functional Thorough Examination and Load Test has been conducted Satisfactorily in accordance with EN 13000' : ($type === 'accessory' ? 'Visual and Functional Thorough Examination has been conducted Satisfactorily in accordance with BS EN 1492-2' : 'Visual Inspection, Function Test Were Carried Out and ACCEPTED in accordance with ASTM A6'))) }}"
                       class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
            </div>

            <!-- Decision Box (YES / NO) -->
            <div class="p-3 rounded-lg border border-emerald-300 bg-emerald-50/50 flex items-center justify-between">
                <div>
                    <span class="block font-bold text-slate-900 text-xs">{{ __('هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)') }}</span>
                    <span class="text-[10px] text-slate-500">{{ __('يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة') }}</span>
                </div>
                <div class="flex items-center gap-2 font-en text-xs font-bold">
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-[#22A447] shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="1" {{ old('is_safe_to_operate', '1') == '1' ? 'checked' : '' }}>
                        <span>{{ __('YES (آمنة)') }}</span>
                    </label>
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-red-300 text-red-600 shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="0" {{ old('is_safe_to_operate') == '0' ? 'checked' : '' }}>
                        <span>{{ __('NO (غير آمنة)') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- CARD 5: SIGNATURES & SUBMIT (Compact Footer) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs items-end">
                <div>
                    <label for="issuer_name" class="block font-bold text-slate-700 mb-1">{{ __('مهندس الفحص (Issuer)') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="issuer_name" id="issuer_name" value="{{ old('issuer_name', 'Eng. Mohammed fathy') }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="authenticator_name" class="block font-bold text-slate-700 mb-1">{{ __('معتمد الشهادة (Authenticator)') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="authenticator_name" id="authenticator_name" value="{{ old('authenticator_name', 'Dr. Hany Nabil') }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="status" class="block font-bold text-slate-700 mb-1">{{ __('حالة الشهادة') }} <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                        <option value="issued" {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}>{{ __('معتمدة ومصدرة (Issued)') }}</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('مسودة للمراجعة (Draft)') }}</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('certificates.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                        {{ __('إلغاء') }}
                    </a>
                    <button type="submit" class="px-5 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('حفظ وإصدار') }}</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function updateCustomerAddress(select) {
        const option = select.options[select.selectedIndex];
        const address = option.getAttribute('data-address');
        if (address) {
            document.getElementById('examination_address').value = address;
        }
    }

    function autoSetNextDue(dateStr) {
        if (!dateStr) return;
        const examDate = new Date(dateStr);
        examDate.setMonth(examDate.getMonth() + 6);
        examDate.setDate(examDate.getDate() - 1);
        const yyyy = examDate.getFullYear();
        const mm = String(examDate.getMonth() + 1).padStart(2, '0');
        const dd = String(examDate.getDate()).padStart(2, '0');
        document.getElementById('next_examination_due_date').value = `${yyyy}-${mm}-${dd}`;
    }

    function markAllChecklist(status) {
        const radios = document.querySelectorAll('.checklist-radio-pass');
        radios.forEach(r => {
            r.checked = true;
        });
    }
</script>
@endpush
@endsection
