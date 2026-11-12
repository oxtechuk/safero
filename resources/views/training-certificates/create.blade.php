@extends('layouts.app')

@section('title', 'إصدار شهادة تدريب معتمدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#0E4DA4]">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('training-certificates.index') }}" class="hover:text-[#0E4DA4]">شهادات التدريب</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">إصدار جديد</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">إصدار شهادة تدريب معتمدة جديدة</h1>
            <p class="text-xs text-slate-500 mt-0.5">أدخل بيانات المتدرب والدورة لإصدار الشهادة الرسمية وتوليد رمز QR تلقائياً</p>
        </div>
        <a href="{{ route('training-certificates.index') }}" 
           class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
            عودة للقائمة
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm">
            <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('training-certificates.store') }}" class="space-y-6">
        @csrf

        <!-- 1. Trainee Selection & Data -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#22A447] rounded-full inline-block"></span>
                    بيانات المتدرب (Certificate Holder)
                </h2>
                <a href="{{ route('trainees.create') }}" target="_blank" class="text-xs text-[#0E4DA4] hover:underline font-bold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>إضافة متدرب جديد في السجل</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        اختيار من سجل المتدربين المسجلين (اختياري)
                    </label>
                    <select name="trainee_id" id="trainee_select" 
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm bg-white">
                        <option value="">-- متدرب جديد / إدخال يدوي --</option>
                        @foreach($trainees as $t)
                            <option value="{{ $t->id }}" 
                                    data-name="{{ $t->name }}" 
                                    data-company="{{ $t->company }}"
                                    {{ old('trainee_id', $selectedTraineeId) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} {{ $t->company ? "({$t->company})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        اسم المتدرب المعتمد في الشهادة (Certificate Holder Name) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="trainee_name" 
                           id="trainee_name_input"
                           value="{{ old('trainee_name') }}"
                           required 
                           placeholder="مثال: Mohamed Mahmoud Mahran"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-semibold">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">الاسم الذي سيظهر بالخط العريض في منتصف الشهادة</span>
                </div>
            </div>
        </div>

        <!-- 2. Course & Program Details -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="w-2 h-5 bg-[#0E4DA4] rounded-full inline-block"></span>
                تفاصيل البرنامج التدريبي (Training Details)
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        رقم مسلسل الشهادة (Certificate Serial No) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="certificate_serial" 
                           value="{{ old('certificate_serial', $defaultSerial) }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-bold font-en text-[#0E4DA4]">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">يتم توليده تلقائياً أو كتابة رقم مخصص مثل: 2025112310</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        مجال التدريب (Training Scope) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="training_scope" 
                           value="{{ old('training_scope', 'Safe Lifting & Rigging Operations') }}"
                           required 
                           placeholder="Safe Lifting & Rigging Operations"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        عنوان البرنامج / الدورة (Program Title / Course) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="program_title" 
                           value="{{ old('program_title', 'Defensive Driving') }}"
                           required 
                           placeholder="مثال: Defensive Driving أو Safe Lifting & Rigging"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        عدد الساعات التدريبية (No. of Hours) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="hours" 
                               value="{{ old('hours', 12) }}"
                               required 
                               min="1" 
                               max="1000"
                               class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-bold font-en">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-bold pointer-events-none">Hours</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        تاريخ الشهادة (Certificate Date) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="issue_date" 
                           value="{{ old('issue_date', $today->format('Y-m-d')) }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-en">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        تاريخ انتهاء الصلاحية (Expire on)
                    </label>
                    <input type="date" 
                           name="expiry_date" 
                           value="{{ old('expiry_date', $expiryDate->format('Y-m-d')) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-en">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">افتراضياً عامين من تاريخ الإصدار</span>
                </div>
            </div>
        </div>

        <!-- 3. Signatures & Accreditation -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="w-2 h-5 bg-amber-500 rounded-full inline-block"></span>
                الاعتمادات والتوقيعات (Signatures & Accreditation)
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        اسم المدرب / المحاضر (Instructor) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="instructor_name" 
                           value="{{ old('instructor_name', 'Hany Nabil') }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        مدير التدريب (Training Manager) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="training_manager_name" 
                           value="{{ old('training_manager_name', 'Gehan Mahmoud') }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        حالة الشهادة (Status) <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm bg-white font-bold">
                        <option value="issued" {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}>معتمدة ومصدرة (Issued)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                        <option value="revoked" {{ old('status') === 'revoked' ? 'selected' : '' }}>ملغاة (Revoked)</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        نص الاعتماد والجهات المانحة (Accreditation Statement)
                    </label>
                    <textarea name="accreditation_text" 
                              rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs">{{ old('accreditation_text', "Safero services & consultation is accredited by\nMinistry of Industry\nEgyptian organization for standardization & quality\nNational quality Institute NQIT 347/686") }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('training-certificates.index') }}" 
               class="px-5 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
                إلغاء
            </a>
            <button type="submit" 
                    class="px-8 py-3 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-black text-sm shadow-lg shadow-[#22A447]/30 transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>إصدار الشهادة وحفظها</span>
            </button>
        </div>

    </form>

</div>

<script>
    document.getElementById('trainee_select')?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const nameInput = document.getElementById('trainee_name_input');
        if (selected && selected.dataset.name) {
            nameInput.value = selected.dataset.name;
        }
    });
</script>
@endsection

