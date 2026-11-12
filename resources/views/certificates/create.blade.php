@extends('layouts.app')

@section('title', 'إصدار شهادة فحص جديدة')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('certificates.index') }}" class="hover:text-[#0E4DA4]">شهادات الفحص</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">نموذج فحص جديد</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">إصدار تقرير وشهادة فحص معدات (LOLER 1998)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Report Thorough Examination of Lifting Equipment &amp; ISO Compliance</p>
        </div>
        <a href="{{ route('certificates.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>إلغاء وعودة</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('certificates.store') }}" class="space-y-8">
            @csrf

            <!-- Section 1: Certificate & Client Details -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-900 text-sm">معلومات الشهادة والعميل وموقع الفحص</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="certificate_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم الشهادة (Certificate Number) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="certificate_number" 
                               id="certificate_number" 
                               value="{{ old('certificate_number', $defaultCertNo) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en font-bold text-[#0E4DA4] bg-slate-50">
                    </div>

                    <div>
                        <label for="order_no" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم أمر الشغل (Order No)
                        </label>
                        <input type="text" 
                               name="order_no" 
                               id="order_no" 
                               value="{{ old('order_no', '100000') }}" 
                               placeholder="100000"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="reference_standard" class="block text-xs font-bold text-slate-700 mb-1.5">
                            المواصفة المرجعية (Reference Standard) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="reference_standard" 
                               id="reference_standard" 
                               value="{{ old('reference_standard', 'ASTM A6') }}" 
                               required 
                               placeholder="ASTM A6 / LOLER 1998"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            اسم العميل / الشركة (Customer Name) <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" 
                                id="customer_id" 
                                required 
                                onchange="updateCustomerAddress(this)"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm bg-white">
                            <option value="">-- اختر العميل --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" 
                                        data-address="{{ $c->address }}"
                                        {{ (old('customer_id', $selectedCustomerId) == $c->id) ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="examination_address" class="block text-xs font-bold text-slate-700 mb-1.5">
                            عنوان موقع الفحص (Examination Address) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="examination_address" 
                               id="examination_address" 
                               value="{{ old('examination_address', 'Om Zoghio - Alexandria') }}" 
                               required 
                               placeholder="Om Zoghio - Alexandria"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="thorough_examination_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            تاريخ الفحص الشامل (Thorough Examination Date) <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="thorough_examination_date" 
                               id="thorough_examination_date" 
                               value="{{ old('thorough_examination_date', $today->format('Y-m-d')) }}" 
                               required 
                               onchange="autoSetNextDue(this.value)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="next_examination_due_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            تاريخ الفحص القادم المستحق (Next Due Date) <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="next_examination_due_date" 
                               id="next_examination_due_date" 
                               value="{{ old('next_examination_due_date', $nextDue->format('Y-m-d')) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>
                </div>
            </div>

            <!-- Section 2: Equipment Specifications -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-900 text-sm">مواصفات وبيانات المعدة المفحوصة (Equipment Description & Identification)</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="equipment_description" class="block text-xs font-bold text-slate-700 mb-1.5">
                            وصف ونوع المعدة (Description of Equipment) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="equipment_description" 
                               id="equipment_description" 
                               value="{{ old('equipment_description', 'Move Pallet') }}" 
                               required 
                               placeholder="Move Pallet / Overhead Crane / Forklift"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
                    </div>

                    <div>
                        <label for="swl" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الحمل الآمن للتشغيل (SWL) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="swl" 
                               id="swl" 
                               value="{{ old('swl', '70  Ton') }}" 
                               required 
                               placeholder="70 Ton / 5 Ton"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en font-bold text-slate-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="dimensions" class="block text-xs font-bold text-slate-700 mb-1.5">الأبعاد (Dimensions)</label>
                        <input type="text" 
                               name="dimensions" 
                               id="dimensions" 
                               value="{{ old('dimensions', 'Length: 0.48  m   Width: 0.22 m     Height: 0.19 m') }}" 
                               placeholder="Length: 0.48 m Width: 0.22 m Height: 0.19 m"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>

                    <div>
                        <label for="quantity" class="block text-xs font-bold text-slate-700 mb-1.5">الكمية (Quantity)</label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               value="{{ old('quantity', 6) }}" 
                               min="1"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="equipment_id_range" class="block text-xs font-bold text-slate-700 mb-1.5">ترقيم المعدات (Equipment ID)</label>
                        <input type="text" 
                               name="equipment_id_range" 
                               id="equipment_id_range" 
                               value="{{ old('equipment_id_range', 'From 01 To 06') }}" 
                               placeholder="From 01 To 06"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="date_of_manufacture" class="block text-xs font-bold text-slate-700 mb-1.5">سنة الصنع (Manufacture Date)</label>
                        <input type="text" 
                               name="date_of_manufacture" 
                               id="date_of_manufacture" 
                               value="{{ old('date_of_manufacture', '2009') }}" 
                               placeholder="2009"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>
                </div>
            </div>

            <!-- Section 3: Legal Checklist Questions -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">3</span>
                    <h2 class="font-bold text-slate-900 text-sm">أسئلة الفحص والمطابقة لمعايير السلامة (Statutory Examination Scheme)</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Q1 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">هل هذا أول فحص بعد التركيب؟ (First exam after install)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="is_first_examination_after_installation" value="1" {{ old('is_first_examination_after_installation') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="is_first_examination_after_installation" value="0" {{ old('is_first_examination_after_installation', '0') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">هل تم الفحص خلال 6 أشهر؟ (Within 6 months)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="was_examination_within_6_months" value="1" {{ old('was_examination_within_6_months', '1') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="was_examination_within_6_months" value="0" {{ old('was_examination_within_6_months') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">هل تم تركيب المعدة بشكل صحيح؟ (Installed correctly)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="has_equipment_installed_correctly" value="1" {{ old('has_equipment_installed_correctly', '1') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="has_equipment_installed_correctly" value="0" {{ old('has_equipment_installed_correctly') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">هل تم الفحص خلال 12 شهراً؟ (Within 12 months)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="was_examination_within_12_months" value="1" {{ old('was_examination_within_12_months') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="was_examination_within_12_months" value="0" {{ old('was_examination_within_12_months', '0') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q5 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">مطابق لخطة الفحص المعتمدة؟ (In Accordance with scheme)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="in_accordance_with_scheme" value="1" {{ old('in_accordance_with_scheme') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="in_accordance_with_scheme" value="0" {{ old('in_accordance_with_scheme', '0') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q6 -->
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <span class="font-semibold text-slate-800">بعد وقوع ظروف استثنائية؟ (Exceptional circumstances)</span>
                        <div class="flex items-center gap-3 font-bold font-en">
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="after_exceptional_circumstances" value="1" {{ old('after_exceptional_circumstances') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="after_exceptional_circumstances" value="0" {{ old('after_exceptional_circumstances', '0') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Defects & Tests -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="defect_identification" class="block text-xs font-bold text-slate-700 mb-1.5">
                            أي أجزاء وجد بها عيب (Defect Identification)
                        </label>
                        <input type="text" 
                               name="defect_identification" 
                               id="defect_identification" 
                               value="{{ old('defect_identification', 'NONE') }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>

                    <div>
                        <label for="repair_particulars" class="block text-xs font-bold text-slate-700 mb-1.5">
                            تفاصيل الإصلاحات أو التعديلات المطلوبة (Repairs required)
                        </label>
                        <input type="text" 
                               name="repair_particulars" 
                               id="repair_particulars" 
                               value="{{ old('repair_particulars', 'NONE') }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>
                </div>

                <div>
                    <label for="tests_carried_out" class="block text-xs font-bold text-slate-700 mb-1.5">
                        تفاصيل الاختبارات والمعايير المطبقة (Particulars of tests carried out) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="tests_carried_out" 
                              id="tests_carried_out" 
                              rows="2" 
                              required
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en font-semibold">{{ old('tests_carried_out', 'Visual Inspection, Function Test Were Carried Out and ACCEPTED.') }}</textarea>
                </div>

                <!-- Final Safe to Operate Decision -->
                <div class="p-4 rounded-2xl bg-[#E8F7EC] border border-[#22A447]/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-black text-[#22A447] text-sm block">القرار النهائي: هل المعدة آمنة للتشغيل؟</span>
                        <span class="text-xs text-slate-600 font-en">Is the Equipment Safe to Operate?</span>
                    </div>
                    <div class="flex items-center gap-4 font-bold">
                        <label class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl bg-white border border-[#22A447] text-[#22A447]">
                            <input type="radio" name="is_safe_to_operate" value="1" {{ old('is_safe_to_operate', '1') == '1' ? 'checked' : '' }} class="text-[#22A447]">
                            <span>نعم - آمنة (YES)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl bg-white border border-red-300 text-red-600">
                            <input type="radio" name="is_safe_to_operate" value="0" {{ old('is_safe_to_operate') == '0' ? 'checked' : '' }} class="text-red-600">
                            <span>لا - غير آمنة (NO)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 4: Signatures & Authentication -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">4</span>
                    <h2 class="font-bold text-slate-900 text-sm">التوقيعات والاعتماد الرسمي والختم</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="issuer_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            مهندس الفحص (Person issuing certificate) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="issuer_name" 
                               id="issuer_name" 
                               value="{{ old('issuer_name', 'Eng. Mohammed fathy') }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="authenticator_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            معتمد الشهادة (Person authenticating certificate) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="authenticator_name" 
                               id="authenticator_name" 
                               value="{{ old('authenticator_name', 'Dr. Hany Nabil') }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">
                            حالة الشهادة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm bg-white">
                            <option value="issued" {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}>معتمدة ومصدرة (Issued)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>مسودة للمراجعة (Draft)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('certificates.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                    إلغاء
                </a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-sm shadow-lg shadow-[#0E4DA4]/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>إصدار الشهادة وحفظها</span>
                </button>
            </div>
        </form>
    </div>

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
</script>
@endpush
@endsection

