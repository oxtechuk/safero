@extends('layouts.app')

@php
    $typeTitles = [
        'crane' => [
            'ar' => 'فحص الأوناش والروافع المتنقلة',
            'en' => 'Lifting Appliances (Mobile & Telescopic Cranes)',
            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'badge' => 'تقرير تفصيلي (صفحتين) + فحص 28 نقطة',
        ],
        'accessory' => [
            'ar' => 'فحص مهمات الرفع والحبال والشكالات',
            'en' => 'Lifting Accessories (Slings, Shackles & Clamps)',
            'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
            'badge' => 'تقرير فحص معتمد (صفحة واحدة)',
        ],
        'general' => [
            'ar' => 'فحص المعدات العامة ووحدات الشحن والباليتات',
            'en' => 'General Cargo Units & Pallet Equipment',
            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'badge' => 'تقرير فحص معتمد (صفحة واحدة)',
        ],
    ];
    $activeMeta = $typeTitles[$type] ?? $typeTitles['crane'];
@endphp

@section('title', 'إصدار شهادة - ' . $activeMeta['ar'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-4">

    <!-- Top Action & Navigation Bar (Odoo Style) -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] flex items-center justify-center shrink-0 p-1.5 border border-blue-100">
                @if($type === 'crane')
                    <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-7 h-7 object-contain" alt="Cranes">
                @elseif($type === 'accessory')
                    <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-7 h-7 object-contain" alt="Accessories">
                @else
                    <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-7 h-7 object-contain" alt="General">
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight">{{ $activeMeta['ar'] }}</h1>
                    <span class="hidden sm:inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0E4DA4] border border-blue-200">
                        {{ $activeMeta['badge'] }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 font-en leading-tight mt-0.5">{{ $activeMeta['en'] }} &bull; LOLER 1998 &amp; ISO/IEC 17020</p>
            </div>
        </div>

        <!-- Quick Switcher Tabs (Odoo Icons, Clean) -->
        <div class="flex items-center gap-1.5 self-end sm:self-center">
            <a href="{{ route('certificates.create', ['type' => 'crane']) }}" 
               class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $type === 'crane' ? 'bg-[#0E4DA4] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
               title="أوناش وروافع">
                <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-3.5 h-3.5 object-contain">
                <span>أوناش</span>
            </a>

            <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" 
               class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $type === 'accessory' ? 'bg-[#0E4DA4] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
               title="مهمات رفع وحبال">
                <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-3.5 h-3.5 object-contain">
                <span>مهمات رفع</span>
            </a>

            <a href="{{ route('certificates.create', ['type' => 'general']) }}" 
               class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $type === 'general' ? 'bg-[#0E4DA4] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
               title="معدات عامة وباليتات">
                <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-3.5 h-3.5 object-contain">
                <span>معدات عامة</span>
            </a>

            <button type="submit" form="certForm" class="mr-2 px-4 py-1.5 rounded-lg bg-[#22A447] hover:bg-[#1c873a] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>إصدار وحفظ</span>
            </button>
        </div>
    </div>

    <!-- Main Form Container -->
    <form method="POST" action="{{ route('certificates.store') }}" id="certForm" class="space-y-4">
        @csrf
        <input type="hidden" name="equipment_type" value="{{ $type }}">

        <!-- CARD 1: Certificate & Customer Meta (Compact 4-Column Grid) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-800 text-xs">بيانات الشهادة والعميل وموقع الفحص</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Certificate &amp; Customer Information</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <label for="certificate_number" class="block font-bold text-slate-700 mb-1">
                        رقم الشهادة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="certificate_number" id="certificate_number" 
                           value="{{ old('certificate_number', $defaultCertNo) }}" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] bg-slate-50 outline-none">
                </div>

                <div>
                    <label for="order_no" class="block font-bold text-slate-700 mb-1">رقم أمر الشغل (Order No)</label>
                    <input type="text" name="order_no" id="order_no" 
                           value="{{ old('order_no', '100000') }}" placeholder="100000"
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="customer_id" class="block font-bold text-slate-700 mb-1">
                        اسم العميل <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required onchange="updateCustomerAddress(this)"
                            class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] bg-white outline-none">
                        <option value="">-- اختر العميل --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-address="{{ $c->address }}" {{ (old('customer_id', $selectedCustomerId) == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="examination_address" class="block font-bold text-slate-700 mb-1">
                        موقع الفحص <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="examination_address" id="examination_address" 
                           value="{{ old('examination_address', $type === 'crane' ? 'Dabaa NPP' : 'Om Zoghio - Alexandria') }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-1">
                <div>
                    <label for="thorough_examination_date" class="block font-bold text-slate-700 mb-1">
                        تاريخ الفحص <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="thorough_examination_date" id="thorough_examination_date" 
                           value="{{ old('thorough_examination_date', $today->format('Y-m-d')) }}" required onchange="autoSetNextDue(this.value)"
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="next_examination_due_date" class="block font-bold text-slate-700 mb-1">
                        تاريخ الاستحقاق القادم <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="next_examination_due_date" id="next_examination_due_date" 
                           value="{{ old('next_examination_due_date', $nextDue->format('Y-m-d')) }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="reference_standard" class="block font-bold text-slate-700 mb-1">
                        المواصفة المرجعية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reference_standard" id="reference_standard" 
                           value="{{ old('reference_standard', $type === 'crane' ? 'EN 13000' : ($type === 'accessory' ? 'BS EN 1492-2' : 'ASTM A6')) }}" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                </div>

                <div>
                    <label for="swl" class="block font-bold text-slate-700 mb-1">
                        الحمل الآمن (SWL) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="swl" id="swl" 
                           value="{{ old('swl', $type === 'crane' ? '90  Ton' : ($type === 'accessory' ? '30 Ton' : '70  Ton')) }}" required
                           class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                </div>
            </div>
        </div>

        <!-- CARD 2: SPECIALIZED EQUIPMENT SPECS (Only fields relevant to the selected type) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-50 text-[#0E4DA4] text-[11px] font-bold flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-800 text-xs">مواصفات وبيانات المعدة المفحوصة</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Equipment Specifications</span>
            </div>

            @if($type === 'crane')
                <!-- CRANE SPECIALIZED SPECS (Compact 3x3 Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                وصف المعدة (Description) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', 'Mobile Telescopic Crane') }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">الشركة المصنعة (Manufacturer)</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', 'Liebherr') }}" placeholder="Liebherr / Grove" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="model" class="block font-bold text-slate-700 mb-1">الموديل (Model)</label>
                            <input type="text" name="model" id="model" value="{{ old('model', 'LTM 1090-4.1') }}" placeholder="LTM 1090-4.1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="capacity" class="block font-bold text-slate-700 mb-1">السعة (Capacity)</label>
                            <input type="text" name="capacity" id="capacity" value="{{ old('capacity', '90 Ton') }}" placeholder="90 Ton" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="chassis_no" class="block font-bold text-slate-700 mb-1">رقم الشاسيه / السيريال</label>
                            <input type="text" name="chassis_no" id="chassis_no" value="{{ old('chassis_no', '071490') }}" placeholder="071490" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold outline-none">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">سنة الصنع (Year)</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', '2008') }}" placeholder="2008" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="main_wire" class="block font-bold text-slate-700 mb-1">قطر الواير الرئيسي</label>
                            <input type="text" name="main_wire" id="main_wire" value="{{ old('main_wire', '19 mm') }}" placeholder="19 mm" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="hook_block" class="block font-bold text-slate-700 mb-1">سعة الهوك الرئيسي</label>
                            <input type="text" name="hook_block" id="hook_block" value="{{ old('hook_block', '59.2 Ton') }}" placeholder="59.2 Ton" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="load_test_cert_no" class="block font-bold text-slate-700 mb-1">رقم شهادة تجربة الحمل</label>
                            <input type="text" name="load_test_cert_no" id="load_test_cert_no" value="{{ old('load_test_cert_no', 'SAF/RS/2026090101') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="date_of_load_test" class="block font-bold text-slate-700 mb-1">تاريخ تجربة الحمل</label>
                            <input type="date" name="date_of_load_test" id="date_of_load_test" value="{{ old('date_of_load_test', $today->format('Y-m-d')) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="test_point" class="block font-bold text-slate-700 mb-1">مواصفات ونقطة تجربة الحمل (Test Point Particulars)</label>
                        <input type="text" name="test_point" id="test_point" value="{{ old('test_point', 'Boom length: 15.5m / Radius: 6.0m / Test Load: 15.5 Ton') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                    </div>
                </div>

            @elseif($type === 'accessory')
                <!-- ACCESSORY SPECS (Compact 4-Column Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                وصف المهمات (Description) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', 'Round Sling BS EN 1492-2') }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="manufacturer" class="block font-bold text-slate-700 mb-1">جهة التصنيع (Manufacturer)</label>
                            <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', 'Lifting safety') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label for="safety_factor" class="block font-bold text-slate-700 mb-1">معامل الأمان (Safety Factor)</label>
                            <input type="text" name="safety_factor" id="safety_factor" value="{{ old('safety_factor', '7:1') }}" placeholder="7:1 / 5:1 / 4:1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-emerald-700 outline-none">
                        </div>
                        <div>
                            <label for="length" class="block font-bold text-slate-700 mb-1">الطول (Length)</label>
                            <input type="text" name="length" id="length" value="{{ old('length', '6.0 M') }}" placeholder="6.0 M" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">الكمية (Quantity)</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 4) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">سيريال / كود المهمات (ID Range)</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', 'From R00772 To R00775') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
                    </div>
                </div>

            @else
                <!-- GENERAL EQUIPMENT SPECS (Compact 3-Column Grid) -->
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label for="equipment_description" class="block font-bold text-slate-700 mb-1">
                                وصف المعدة (Description) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="equipment_description" id="equipment_description" 
                                   value="{{ old('equipment_description', 'Move Pallet') }}" required
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] outline-none font-semibold">
                        </div>
                        <div>
                            <label for="date_of_manufacture" class="block font-bold text-slate-700 mb-1">سنة الصنع (Manufacture Date)</label>
                            <input type="text" name="date_of_manufacture" id="date_of_manufacture" value="{{ old('date_of_manufacture', '2009') }}" placeholder="2009" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="dimensions" class="block font-bold text-slate-700 mb-1">الأبعاد (Dimensions: L, W, H)</label>
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', 'Length: 0.48 m Width: 0.22 m Height: 0.19 m') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="quantity" class="block font-bold text-slate-700 mb-1">الكمية (Quantity)</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 6) }}" min="1" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
                        </div>
                        <div>
                            <label for="equipment_id_range" class="block font-bold text-slate-700 mb-1">ترقيم المعدات (Equipment ID)</label>
                            <input type="text" name="equipment_id_range" id="equipment_id_range" value="{{ old('equipment_id_range', 'From 01 To 06') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en font-bold text-[#0E4DA4] outline-none">
                        </div>
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
                        <h2 class="font-bold text-slate-800 text-xs">قائمة الفحص الهندسي للونش (28 نقطة فحص معتمدة)</h2>
                    </div>

                    <!-- Rapid Actions: "Mark All Pass" Button -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="markAllChecklist('Pass')" 
                                class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 text-[11px] font-bold transition flex items-center gap-1 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>تحديد الكل سليم (Pass All)</span>
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
                    <h2 class="font-bold text-slate-800 text-xs">أسئلة الفحص النظامية (LOLER 1998) والقرار الفني</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-en">Statutory Examination Scheme</span>
            </div>

            <!-- 6 Questions in Compact 3-Column Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[11px]">
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">أول فحص بعد التركيب؟</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="1" {{ old('is_first_examination_after_installation') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="is_first_examination_after_installation" value="0" {{ old('is_first_examination_after_installation', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">الفحص خلال 6 أشهر؟</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_6_months" value="1" {{ old('was_examination_within_6_months', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="was_examination_within_6_months" value="0" {{ old('was_examination_within_6_months') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">تم التركيب بشكل سليم؟</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="has_equipment_installed_correctly" value="1" {{ old('has_equipment_installed_correctly', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="has_equipment_installed_correctly" value="0" {{ old('has_equipment_installed_correctly') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">الفحص خلال 12 شهر؟</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="1" {{ old('was_examination_within_12_months') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="was_examination_within_12_months" value="0" {{ old('was_examination_within_12_months', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">وفق مخطط الفحص؟</span>
                    <div class="flex items-center gap-2 font-en font-bold text-[#22A447]">
                        <label class="cursor-pointer"><input type="radio" name="in_accordance_with_scheme" value="1" {{ old('in_accordance_with_scheme', '1') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-slate-600"><input type="radio" name="in_accordance_with_scheme" value="0" {{ old('in_accordance_with_scheme') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                    <span class="text-slate-700">بعد ظروف استثنائية؟</span>
                    <div class="flex items-center gap-2 font-en font-bold">
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="1" {{ old('after_exceptional_circumstances') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer"><input type="radio" name="after_exceptional_circumstances" value="0" {{ old('after_exceptional_circumstances', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <!-- Findings & Tests Carried Out -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                <div>
                    <label for="defect_identification" class="block font-bold text-slate-700 mb-1">الأجزاء المعيبة إن وجدت</label>
                    <input type="text" name="defect_identification" id="defect_identification" value="{{ old('defect_identification', 'NONE') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="repair_particulars" class="block font-bold text-slate-700 mb-1">الإصلاحات المطلوبة</label>
                    <input type="text" name="repair_particulars" id="repair_particulars" value="{{ old('repair_particulars', 'NONE') }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">هل العيب يشكل خطورة على العمليات؟</label>
                    <div class="flex items-center gap-3 h-8 px-2 rounded-lg bg-slate-50 border border-slate-200 font-en text-[11px] font-bold">
                        <label class="cursor-pointer text-red-600"><input type="radio" name="is_defect_danger_to_process" value="1" {{ old('is_defect_danger_to_process') == '1' ? 'checked' : '' }}> Yes</label>
                        <label class="cursor-pointer text-[#22A447]"><input type="radio" name="is_defect_danger_to_process" value="0" {{ old('is_defect_danger_to_process', '0') == '0' ? 'checked' : '' }}> No</label>
                    </div>
                </div>
            </div>

            <div class="text-xs">
                <label for="tests_carried_out" class="block font-bold text-slate-700 mb-1">
                    تفاصيل الفحوصات والاختبارات المنفذة <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tests_carried_out" id="tests_carried_out" required
                       value="{{ old('tests_carried_out', $type === 'crane' ? 'Visual and Functional Thorough Examination and Load Test has been conducted Satisfactorily in accordance with EN 13000' : ($type === 'accessory' ? 'Visual and Functional Thorough Examination has been conducted Satisfactorily in accordance with BS EN 1492-2' : 'Visual Inspection, Function Test Were Carried Out and ACCEPTED in accordance with ASTM A6')) }}"
                       class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-[#0E4DA4] font-en outline-none">
            </div>

            <!-- Decision Box (YES / NO) -->
            <div class="p-3 rounded-lg border border-emerald-300 bg-emerald-50/50 flex items-center justify-between">
                <div>
                    <span class="block font-bold text-slate-900 text-xs">هل المعدة آمنة تماماً للتشغيل؟ (Is the Equipment Safe to Operate)</span>
                    <span class="text-[10px] text-slate-500">يعتمد هذا الخيار سلامة المعدة والاعتماد النهائي في الشهادة</span>
                </div>
                <div class="flex items-center gap-2 font-en text-xs font-bold">
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-[#22A447] shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="1" {{ old('is_safe_to_operate', '1') == '1' ? 'checked' : '' }}>
                        <span>YES (آمنة)</span>
                    </label>
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-red-300 text-red-600 shadow-2xs">
                        <input type="radio" name="is_safe_to_operate" value="0" {{ old('is_safe_to_operate') == '0' ? 'checked' : '' }}>
                        <span>NO (غير آمنة)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- CARD 5: SIGNATURES & SUBMIT (Compact Footer) -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs items-end">
                <div>
                    <label for="issuer_name" class="block font-bold text-slate-700 mb-1">مهندس الفحص (Issuer) <span class="text-red-500">*</span></label>
                    <input type="text" name="issuer_name" id="issuer_name" value="{{ old('issuer_name', 'Eng. Mohammed fathy') }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="authenticator_name" class="block font-bold text-slate-700 mb-1">معتمد الشهادة (Authenticator) <span class="text-red-500">*</span></label>
                    <input type="text" name="authenticator_name" id="authenticator_name" value="{{ old('authenticator_name', 'Dr. Hany Nabil') }}" required class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 font-en outline-none">
                </div>

                <div>
                    <label for="status" class="block font-bold text-slate-700 mb-1">حالة الشهادة <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                        <option value="issued" {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}>معتمدة ومصدرة (Issued)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>مسودة للمراجعة (Draft)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('certificates.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                        إلغاء
                    </a>
                    <button type="submit" class="px-5 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ وإصدار</span>
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
