@extends('layouts.app')

@section('title', 'إنشاء وتكليف مهمة فحص جديدة - New Inspection Task')

@section('content')
<form method="POST" action="{{ route('tasks.store') }}" class="space-y-6" x-data="{
    selectedType: '{{ old('equipment_type', $selectedType) }}'
}">
    @csrf

    <!-- Odoo Enterprise Form Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13">
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        مهام الفحص وأوامر الشغل
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">
                        أمر شغل جديد (New Task)
                    </span>
                    <span class="text-[10px] font-mono text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded font-bold">
                        {{ $nextNumber }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="px-4 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ وتوليد الشيك ليست (Save Task)</span>
                    </button>
                    <a href="{{ route('tasks.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition">
                        إلغاء (Discard)
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Form Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Section 1: Customer & Task Header -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">بيانات أمر الشغل والعميل (Task &amp; Customer)</h3>
                    <p class="text-xs text-slate-500">تحديد العميل وموقع العمل المستهدف للفحص الهندسي.</p>
                </div>
                <span class="text-xs font-mono font-bold text-slate-400">Order Dispatching</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Task Title Arabic -->
                <div>
                    <label for="title_ar" class="block text-xs font-bold text-slate-700 mb-1">
                        عنوان أمر الشغل بالعربية (Task Title) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title_ar" 
                           id="title_ar" 
                           value="{{ old('title_ar') }}" 
                           required 
                           placeholder="مثال: فحص دوري لنش هيدروليكي 50 طن - موقع العامرية" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition">
                    @error('title_ar')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Selection -->
                <div>
                    <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1">
                        العميل / الشركة المستفيدة (Customer) <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" 
                            id="customer_id" 
                            required 
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition bg-white">
                        <option value="">-- اختر الشركة أو العميل --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Examination Location -->
                <div>
                    <label for="examination_location" class="block text-xs font-bold text-slate-700 mb-1">
                        موقع الفحص الميداني (Site / Yard Location)
                    </label>
                    <input type="text" 
                           name="examination_location" 
                           id="examination_location" 
                           value="{{ old('examination_location') }}" 
                           placeholder="مثال: ساحة الكيلو 21 طريق الإسكندرية الصحراوي" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition">
                </div>

                <!-- Task Title English -->
                <div>
                    <label for="title_en" class="block text-xs font-bold text-slate-700 mb-1">
                        العنوان بالإنجليزية (English Title)
                    </label>
                    <input type="text" 
                           name="title_en" 
                           id="title_en" 
                           value="{{ old('title_en') }}" 
                           dir="ltr"
                           placeholder="e.g. 50T Mobile Crane Periodic Thorough Examination" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden font-en transition text-left">
                </div>

            </div>
        </div>

        <!-- Section 2: Equipment Specification & Type -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">نوع ومواصفات المعدة الخاضعة للفحص (Equipment Details)</h3>
                    <p class="text-xs text-slate-500">اختيار نوع المعدة يحدد تلقائياً بنود شيك ليست الفحص الهندسي المعتمدة.</p>
                </div>
            </div>

            <!-- Equipment Type Selector Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                
                <!-- Cranes -->
                <label class="p-3.5 rounded-xl border-2 cursor-pointer transition select-none flex flex-col justify-between"
                       :class="selectedType === 'crane' ? 'border-[#0E4DA4] bg-blue-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                    <div class="flex items-center justify-between mb-2">
                        <input type="radio" name="equipment_type" value="crane" x-model="selectedType" class="text-[#0E4DA4] focus:ring-[#0E4DA4]">
                        <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-xs text-slate-900 block">أوناش وروافع هيدروليكية</span>
                        <span class="text-[10px] text-slate-400 font-en">Cranes (28-Point LOLER)</span>
                    </div>
                </label>

                <!-- Accessories -->
                <label class="p-3.5 rounded-xl border-2 cursor-pointer transition select-none flex flex-col justify-between"
                       :class="selectedType === 'accessory' ? 'border-[#0E4DA4] bg-blue-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                    <div class="flex items-center justify-between mb-2">
                        <input type="radio" name="equipment_type" value="accessory" x-model="selectedType" class="text-[#0E4DA4] focus:ring-[#0E4DA4]">
                        <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-xs text-slate-900 block">مهمات الرفع والحبال</span>
                        <span class="text-[10px] text-slate-400 font-en">Slings, Shackles &amp; Chains</span>
                    </div>
                </label>

                <!-- General Equipment -->
                <label class="p-3.5 rounded-xl border-2 cursor-pointer transition select-none flex flex-col justify-between"
                       :class="selectedType === 'general' ? 'border-[#0E4DA4] bg-blue-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                    <div class="flex items-center justify-between mb-2">
                        <input type="radio" name="equipment_type" value="general" x-model="selectedType" class="text-[#0E4DA4] focus:ring-[#0E4DA4]">
                        <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-xs text-slate-900 block">معدات عامة وباليتات</span>
                        <span class="text-[10px] text-slate-400 font-en">General Cargo Units</span>
                    </div>
                </label>

                <!-- Environmental -->
                <label class="p-3.5 rounded-xl border-2 cursor-pointer transition select-none flex flex-col justify-between"
                       :class="selectedType === 'environmental' ? 'border-[#0E4DA4] bg-blue-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                    <div class="flex items-center justify-between mb-2">
                        <input type="radio" name="equipment_type" value="environmental" x-model="selectedType" class="text-[#0E4DA4] focus:ring-[#0E4DA4]">
                        <img src="{{ asset('branding/odoo-icons/industry_environmental_agency.svg') }}" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-xs text-slate-900 block">فحص بيئي وانبعاثات</span>
                        <span class="text-[10px] text-slate-400 font-en">Environmental Assessment</span>
                    </div>
                </label>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3">
                
                <!-- Equipment Identifier / Serial / Chassis -->
                <div>
                    <label for="equipment_identifier" class="block text-xs font-bold text-slate-700 mb-1">
                        رقم الشاسيه / الرقم التعريفي للمعدة (ID / Chassis No)
                    </label>
                    <input type="text" 
                           name="equipment_identifier" 
                           id="equipment_identifier" 
                           value="{{ old('equipment_identifier') }}" 
                           dir="ltr"
                           placeholder="مثال: CRN-2024-8890 / VIN: 98124" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden font-en transition text-left">
                </div>

                <!-- Equipment Description / Specs -->
                <div>
                    <label for="equipment_description" class="block text-xs font-bold text-slate-700 mb-1">
                        وصف وموديل المعدة وقدرتها (Description &amp; Capacity)
                    </label>
                    <input type="text" 
                           name="equipment_description" 
                           id="equipment_description" 
                           value="{{ old('equipment_description') }}" 
                           placeholder="مثال: ونش تادانو هيدروليكي حمولة 50 طن موديل Tadano GR-500" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition">
                </div>

            </div>
        </div>

        <!-- Section 3: Scheduling, Inspector & Priority -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">الجدولة والتكليف والأولوية (Scheduling &amp; Assignment)</h3>
                    <p class="text-xs text-slate-500">تحديد موعد الفحص وتعيين المهندس الفاحص المسئول.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Scheduled Date -->
                <div>
                    <label for="scheduled_date" class="block text-xs font-bold text-slate-700 mb-1">
                        تاريخ الفحص الميداني (Inspection Date) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="scheduled_date" 
                           id="scheduled_date" 
                           value="{{ old('scheduled_date', date('Y-m-d')) }}" 
                           required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden font-mono transition">
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-xs font-bold text-slate-700 mb-1">
                        تاريخ أقصى لإصدار التقرير (Due Date)
                    </label>
                    <input type="date" 
                           name="due_date" 
                           id="due_date" 
                           value="{{ old('due_date', date('Y-m-d', strtotime('+3 days'))) }}" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden font-mono transition">
                </div>

                <!-- Assigned Inspector -->
                <div>
                    <label for="assigned_inspector_id" class="block text-xs font-bold text-slate-700 mb-1">
                        المهندس الفاحص المكلف (Inspector)
                    </label>
                    <select name="assigned_inspector_id" 
                            id="assigned_inspector_id" 
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition bg-white">
                        <option value="">-- اختر الفاحص المسؤول --</option>
                        @foreach($inspectors as $insp)
                            <option value="{{ $insp->id }}" {{ old('assigned_inspector_id') == $insp->id ? 'selected' : '' }}>
                                {{ $insp->name }} ({{ $insp->jobTitle('ar') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-xs font-bold text-slate-700 mb-1">
                        درجة الأولوية (Priority) <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" 
                            id="priority" 
                            required 
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition bg-white">
                        <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>عادية (Normal)</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية ومستعجلة (High)</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>طارئة وفورية (Urgent)</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة (Low)</option>
                    </select>
                </div>

            </div>

            <!-- Notes -->
            <div class="pt-2">
                <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">
                    توجيهات وملاحظات الفحص والسلامة (Safety Instructions &amp; Notes)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          placeholder="تعليمات دخول الموقع، مهمات الوقاية المطلوبة، متطلبات وزن الاختبار..." 
                          class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#0E4DA4] outline-hidden transition">{{ old('notes') }}</textarea>
            </div>
        </div>

    </div>
</form>
@endsection

