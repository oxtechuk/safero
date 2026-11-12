@extends('layouts.app')

@section('title', 'تعديل شهادة الفحص - ' . $certificate->certificate_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('certificates.index') }}" class="hover:text-[#0E4DA4]">شهادات الفحص</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">تعديل الشهادة</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">تعديل شهادة: {{ $certificate->certificate_number }}</h1>
        </div>
        <a href="{{ route('certificates.show', $certificate) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>إلغاء وعودة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('certificates.update', $certificate) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1 -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">1</span>
                    <h2 class="font-bold text-slate-900 text-sm">معلومات الشهادة والعميل وموقع الفحص</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="certificate_number" class="block text-xs font-bold text-slate-700 mb-1.5">رقم الشهادة</label>
                        <input type="text" 
                               name="certificate_number" 
                               id="certificate_number" 
                               value="{{ old('certificate_number', $certificate->certificate_number) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en font-bold text-[#0E4DA4] bg-slate-50">
                    </div>

                    <div>
                        <label for="order_no" class="block text-xs font-bold text-slate-700 mb-1.5">رقم أمر الشغل</label>
                        <input type="text" 
                               name="order_no" 
                               id="order_no" 
                               value="{{ old('order_no', $certificate->order_no) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="reference_standard" class="block text-xs font-bold text-slate-700 mb-1.5">المواصفة المرجعية</label>
                        <input type="text" 
                               name="reference_standard" 
                               id="reference_standard" 
                               value="{{ old('reference_standard', $certificate->reference_standard) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1.5">اسم العميل</label>
                        <select name="customer_id" id="customer_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm bg-white">
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (old('customer_id', $certificate->customer_id) == $c->id) ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="examination_address" class="block text-xs font-bold text-slate-700 mb-1.5">عنوان موقع الفحص</label>
                        <input type="text" 
                               name="examination_address" 
                               id="examination_address" 
                               value="{{ old('examination_address', $certificate->examination_address) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="thorough_examination_date" class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الفحص</label>
                        <input type="date" 
                               name="thorough_examination_date" 
                               id="thorough_examination_date" 
                               value="{{ old('thorough_examination_date', $certificate->thorough_examination_date->format('Y-m-d')) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="next_examination_due_date" class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الاستحقاق القادم</label>
                        <input type="date" 
                               name="next_examination_due_date" 
                               id="next_examination_due_date" 
                               value="{{ old('next_examination_due_date', $certificate->next_examination_due_date->format('Y-m-d')) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">2</span>
                    <h2 class="font-bold text-slate-900 text-sm">مواصفات وبيانات المعدة</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="equipment_description" class="block text-xs font-bold text-slate-700 mb-1.5">وصف المعدة</label>
                        <input type="text" 
                               name="equipment_description" 
                               id="equipment_description" 
                               value="{{ old('equipment_description', $certificate->equipment_description) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm">
                    </div>

                    <div>
                        <label for="swl" class="block text-xs font-bold text-slate-700 mb-1.5">الحمل الآمن (SWL)</label>
                        <input type="text" 
                               name="swl" 
                               id="swl" 
                               value="{{ old('swl', $certificate->swl) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="dimensions" class="block text-xs font-bold text-slate-700 mb-1.5">الأبعاد</label>
                        <input type="text" 
                               name="dimensions" 
                               id="dimensions" 
                               value="{{ old('dimensions', $certificate->dimensions) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>

                    <div>
                        <label for="quantity" class="block text-xs font-bold text-slate-700 mb-1.5">الكمية</label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               value="{{ old('quantity', $certificate->quantity) }}" 
                               min="1"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="equipment_id_range" class="block text-xs font-bold text-slate-700 mb-1.5">ترقيم المعدات</label>
                        <input type="text" 
                               name="equipment_id_range" 
                               id="equipment_id_range" 
                               value="{{ old('equipment_id_range', $certificate->equipment_id_range) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="date_of_manufacture" class="block text-xs font-bold text-slate-700 mb-1.5">سنة الصنع</label>
                        <input type="text" 
                               name="date_of_manufacture" 
                               id="date_of_manufacture" 
                               value="{{ old('date_of_manufacture', $certificate->date_of_manufacture) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-sm font-en">
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">3</span>
                    <h2 class="font-bold text-slate-900 text-sm">أسئلة الفحص والقرار الفني</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="defect_identification" class="block text-xs font-bold text-slate-700 mb-1.5">الأجزاء المعيبة</label>
                        <input type="text" name="defect_identification" id="defect_identification" value="{{ old('defect_identification', $certificate->defect_identification) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>

                    <div>
                        <label for="repair_particulars" class="block text-xs font-bold text-slate-700 mb-1.5">الإصلاحات المطلوبة</label>
                        <input type="text" name="repair_particulars" id="repair_particulars" value="{{ old('repair_particulars', $certificate->repair_particulars) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en">
                    </div>
                </div>

                <div>
                    <label for="tests_carried_out" class="block text-xs font-bold text-slate-700 mb-1.5">الاختبارات المنفذة</label>
                    <textarea name="tests_carried_out" id="tests_carried_out" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs font-en font-semibold">{{ old('tests_carried_out', $certificate->tests_carried_out) }}</textarea>
                </div>

                <div class="p-4 rounded-2xl bg-[#E8F7EC] border border-[#22A447]/40 flex items-center justify-between">
                    <div>
                        <span class="font-black text-[#22A447] text-sm block">هل المعدة آمنة للتشغيل؟</span>
                    </div>
                    <div class="flex items-center gap-4 font-bold">
                        <label class="inline-flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-xl bg-white border border-[#22A447] text-[#22A447]">
                            <input type="radio" name="is_safe_to_operate" value="1" {{ old('is_safe_to_operate', $certificate->is_safe_to_operate ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span>نعم (YES)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-xl bg-white border border-red-300 text-red-600">
                            <input type="radio" name="is_safe_to_operate" value="0" {{ old('is_safe_to_operate', $certificate->is_safe_to_operate ? '1' : '0') == '0' ? 'checked' : '' }}>
                            <span>لا (NO)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-[#E6F2FF] text-[#0E4DA4] font-bold text-xs flex items-center justify-center">4</span>
                    <h2 class="font-bold text-slate-900 text-sm">الاعتماد والمسؤولين</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="issuer_name" class="block text-xs font-bold text-slate-700 mb-1.5">مهندس الفحص</label>
                        <input type="text" name="issuer_name" id="issuer_name" value="{{ old('issuer_name', $certificate->issuer_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="authenticator_name" class="block text-xs font-bold text-slate-700 mb-1.5">معتمد الشهادة</label>
                        <input type="text" name="authenticator_name" id="authenticator_name" value="{{ old('authenticator_name', $certificate->authenticator_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 outline-none text-sm font-en">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">حالة الشهادة</label>
                        <select name="status" id="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 outline-none text-sm bg-white">
                            <option value="issued" {{ old('status', $certificate->status) === 'issued' ? 'selected' : '' }}>معتمدة ومصدرة (Issued)</option>
                            <option value="draft" {{ old('status', $certificate->status) === 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                            <option value="revoked" {{ old('status', $certificate->status) === 'revoked' ? 'selected' : '' }}>ملغاة (Revoked)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" 
                        onclick="if(confirm('هل أنت متأكد من حذف هذه الشهادة نهائياً؟')) { document.getElementById('delete-cert-form').submit(); }"
                        class="text-xs text-red-600 hover:text-red-800 font-bold">
                    حذف الشهادة
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('certificates.show', $certificate) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                        إلغاء
                    </a>
                    <button type="submit" class="px-7 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-sm transition">
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-cert-form" method="POST" action="{{ route('certificates.destroy', $certificate) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection

