@extends('layouts.app')

@section('title', __('إصدار شهادات التدريب المعتمدة'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    mode: '{{ old('mode', $mode ?? 'single') }}',
    customInstructor: {{ (old('instructor_name') && !in_array(old('instructor_name'), array_column($instructors, 'name'))) ? 'true' : 'false' }},
    searchTrainee: '',
    selectedCount: 0,
    rawText: '{{ old('raw_trainee_names', '') }}',
    updateCount() {
        const checkboxes = document.querySelectorAll('.trainee-checkbox:checked');
        let count = checkboxes.length;
        if (this.rawText.trim()) {
            const lines = this.rawText.split('\n').filter(l => l.trim().length > 0);
            count += lines.length;
        }
        this.selectedCount = count;
    },
    selectAllTrainees(check) {
        document.querySelectorAll('.trainee-checkbox').forEach(cb => {
            cb.checked = check;
        });
        this.updateCount();
    },
    onInstructorSelect(e) {
        const val = e.target.value;
        if (val === 'custom') {
            this.customInstructor = true;
            document.getElementById('instructor_name_input').value = '';
            document.getElementById('instructor_id_hidden').value = '';
            $nextTick(() => document.getElementById('instructor_name_input').focus());
        } else if (val) {
            this.customInstructor = false;
            const opt = e.target.selectedOptions[0];
            document.getElementById('instructor_name_input').value = opt.dataset.name;
            document.getElementById('instructor_id_hidden').value = opt.dataset.userId || '';
        } else {
            this.customInstructor = false;
            document.getElementById('instructor_name_input').value = 'Dr. Hany Nabil';
            document.getElementById('instructor_id_hidden').value = '';
        }
    }
}" x-init="updateCount()">

    <!-- Header Breadcrumb & Mode Selector -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#0E4DA4]">{{ __('الرئيسية') }}</a>
                <span>/</span>
                <a href="{{ route('training-certificates.index') }}" class="hover:text-[#0E4DA4]">{{ __('شهادات التدريب') }}</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">{{ __('إصدار شهادات') }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">{{ __('إصدار شهادات التدريب المعتمدة') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ __('اختر بين إصدار شهادة فردية لمتدرب واحد أو إصدار جماعي لعدة شهادات دفعة واحدة') }}</p>
        </div>
        <a href="{{ route('training-certificates.index') }}" 
           class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
            {{ __('عودة للقائمة') }}
        </a>
    </div>

    <!-- Mode Switcher Tabs (شهادة واحدة أو عدة شهادات) -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-2xs flex items-center gap-2">
        <button type="button" 
                @click="mode = 'single'" 
                :class="mode === 'single' ? 'bg-[#0E4DA4] text-white shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                class="flex-1 py-3 px-4 rounded-xl font-black text-xs sm:text-sm transition flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>{{ __('إصدار شهادة فردية (Single Certificate)') }}</span>
        </button>

        <button type="button" 
                @click="mode = 'batch'" 
                :class="mode === 'batch' ? 'bg-[#006837] text-white shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                class="flex-1 py-3 px-4 rounded-xl font-black text-xs sm:text-sm transition flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>{{ __('إصدار عدة شهادات دفعة واحدة (Batch Multiple Certificates)') }}</span>
        </button>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm">
            <span class="font-bold block mb-1">{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('training-certificates.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="mode" :value="mode">

        <!-- ================= 1. TRAINEE SECTION ================= -->
        <!-- A) SINGLE TRAINEE MODE -->
        <div x-show="mode === 'single'" class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#22A447] rounded-full inline-block"></span>
                    {{ __('بيانات المتدرب (Certificate Holder)') }}
                </h2>
                <a href="{{ route('trainees.create') }}" target="_blank" class="text-xs text-[#0E4DA4] hover:underline font-bold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('إضافة متدرب جديد في السجل') }}</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('اختيار من سجل المتدربين المسجلين (اختياري)') }}
                    </label>
                    <select name="trainee_id" id="trainee_select" 
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm bg-white">
                        <option value="">{{ __('-- متدرب جديد / إدخال يدوي --') }}</option>
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
                        {{ __('اسم المتدرب المعتمد في الشهادة (Certificate Holder Name)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="trainee_name" 
                           id="trainee_name_input"
                           value="{{ old('trainee_name') }}"
                           :required="mode === 'single'"
                           placeholder="مثال: Mohamed Mahmoud Mahran"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-semibold">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">{{ __('الاسم الذي سيظهر بالخط العريض في منتصف الشهادة') }}</span>
                </div>
            </div>
        </div>

        <!-- B) BATCH MULTI-TRAINEES MODE -->
        <div x-show="mode === 'batch'" class="bg-white p-6 rounded-2xl border border-emerald-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                <div>
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>
                        <span>{{ __('تحديد قائمة المتدربين لإصدار الشهادات الجماعية') }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('سيتم توليد شهادة منفصلة لكل متدرب محدد برقم مسلسل تلقائي فريد') }}</p>
                </div>
                <div class="px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-black">
                    {{ __('العدد الإجمالي:') }} <span class="text-sm font-mono" x-text="selectedCount">0</span> {{ __('شهادة سيتم إصدارها') }}
                </div>
            </div>

            <!-- Trainees from Registry (Multi-Checkboxes) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs font-bold text-slate-700">{{ __('1. اختيار من سجل المتدربين المسجلين:') }}</span>
                    <div class="flex items-center gap-3 text-xs">
                        <button type="button" @click="selectAllTrainees(true)" class="text-[#0E4DA4] font-bold hover:underline cursor-pointer">
                            {{ __('+ تحديد الكل') }}
                        </button>
                        <span class="text-slate-300">|</span>
                        <button type="button" @click="selectAllTrainees(false)" class="text-slate-500 font-bold hover:underline cursor-pointer">
                            {{ __('إلغاء التحديد') }}
                        </button>
                    </div>
                </div>

                <!-- Live Filter Input -->
                <input type="text" 
                       x-model="searchTrainee" 
                       placeholder="{{ __('بحث في المتدربين بالاسم أو الشركة...') }}" 
                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none">

                <div class="max-h-56 overflow-y-auto border border-slate-200 rounded-xl p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 bg-slate-50/50">
                    @forelse($trainees as $t)
                        <label class="flex items-center gap-2 p-2 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 transition cursor-pointer select-none"
                               x-show="!searchTrainee || '{{ addslashes(strtolower($t->name.' '.$t->company)) }}'.includes(searchTrainee.toLowerCase())">
                            <input type="checkbox" 
                                   name="batch_trainee_ids[]" 
                                   value="{{ $t->id }}" 
                                   @change="updateCount()"
                                   class="trainee-checkbox w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                            <div class="truncate text-xs">
                                <span class="font-bold text-slate-800 block truncate">{{ $t->name }}</span>
                                @if($t->company)
                                    <span class="text-[10px] text-slate-400 block truncate">{{ $t->company }}</span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="col-span-3 text-center py-4 text-xs text-slate-400">
                            {{ __('لا يوجد متدربون مسجلون حالياً') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Add Extra Trainees via Direct Text Lines -->
            <div class="pt-3 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('2. أو كتابة أسماء متدربين جدد مباشرة (اسم كل متدرب في سطر جديد):') }}
                </label>
                <textarea name="raw_trainee_names" 
                          x-model="rawText"
                          @input="updateCount()"
                          rows="3" 
                          placeholder="Mohamed Ahmed Hassan&#10;Ibrahim Ali Mahmoud&#10;Mostafa Taha" 
                          class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono outline-none"></textarea>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                    {{ __('سيتم إنشاء شهادة معتمدة لكل اسم مكتوب في سطر تلقائياً بنفس بيانات الدورة أدناه.') }}
                </span>
            </div>
        </div>

        <!-- ================= 2. COURSE & PROGRAM DETAILS ================= -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="w-2 h-5 bg-[#0E4DA4] rounded-full inline-block"></span>
                {{ __('تفاصيل البرنامج التدريبي (Training Details)') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Serial Number (Single mode only) -->
                <div x-show="mode === 'single'">
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('رقم مسلسل الشهادة (Certificate Serial No)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="certificate_serial" 
                           value="{{ old('certificate_serial', $defaultSerial) }}"
                           :required="mode === 'single'"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-bold font-en text-[#0E4DA4]">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">{{ __('يتم توليده تلقائياً أو كتابة رقم مخصص') }}</span>
                </div>

                <!-- Serial Range Preview (Batch mode only) -->
                <div x-show="mode === 'batch'" class="bg-emerald-50/70 p-3 rounded-xl border border-emerald-200">
                    <span class="text-xs font-bold text-emerald-900 block mb-1">{{ __('الأرقام المسلسلة المتوقعة:') }}</span>
                    <span class="font-mono font-bold text-emerald-700 text-xs block">
                        {{ $defaultSerial }} &rarr; ... ({{ __('تسلسلي تلقائي') }})
                    </span>
                    <span class="text-[10.5px] text-emerald-600 block mt-1">
                        {{ __('سيتم تخصيص رقم مسلسل فريد لكل شهادة تباعاً فور الحفظ.') }}
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('مجال التدريب (Training Scope)') }} <span class="text-red-500">*</span>
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
                        {{ __('عنوان الدورة التدريبية (Program Title)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="program_title" 
                           value="{{ old('program_title', 'Defensive Driving') }}"
                           required 
                           placeholder="مثال: Rigging & Slinger Competence"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('عدد الساعات التدريبية (Hours)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="hours" 
                           value="{{ old('hours', 12) }}"
                           min="1" max="1000"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('تاريخ الإصدار (Issue Date)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="issue_date" 
                           value="{{ old('issue_date', $today->toDateString()) }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('تاريخ انتهاء الصلاحية (Expiry Date - اختياري)') }}
                    </label>
                    <input type="date" 
                           name="expiry_date" 
                           value="{{ old('expiry_date', $expiryDate->toDateString()) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-mono">
                </div>
            </div>
        </div>

        <!-- ================= 3. TRAINER LIST & SIGNATURES ================= -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="w-2 h-5 bg-amber-500 rounded-full inline-block"></span>
                {{ __('الاعتمادات والتوقيعات وقائمة المدربين (Trainer List & Signatures)') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Trainer / Instructor Selection Dropdown -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700">
                            {{ __('المدرب المعتمد (Trainer)') }} <span class="text-red-500">*</span>
                        </label>
                        <button type="button" class="text-[10px] text-[#0E4DA4] font-bold hover:underline cursor-pointer" @click="customInstructor = !customInstructor">
                            <span x-show="!customInstructor">{{ __('+ مدرب آخر') }}</span>
                            <span x-show="customInstructor">{{ __('قائمة المدربين') }}</span>
                        </button>
                    </div>

                    <input type="hidden" name="instructor_id" id="instructor_id_hidden" value="{{ old('instructor_id', '') }}">

                    <!-- Dropdown List of Certified Trainers -->
                    <div x-show="!customInstructor">
                        <select id="instructor_select" @change="onInstructorSelect($event)" 
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm bg-white font-bold">
                            <option value="">{{ __('الافتراضي (Dr. Hany Nabil)') }}</option>
                            <optgroup label="{{ __('قائمة المدربين المعتمدين') }}">
                                @foreach($instructors as $inst)
                                    <option value="{{ $inst['name'] }}" 
                                            data-name="{{ $inst['name'] }}"
                                            {{ old('instructor_name', 'Dr. Hany Nabil') === $inst['name'] ? 'selected' : '' }}>
                                        {{ $inst['name'] }} &mdash; {{ $inst['title_ar'] }}
                                    </option>
                                @endforeach
                            </optgroup>
                            @if(isset($users) && $users->isNotEmpty())
                                <optgroup label="{{ __('فريق العمل / المستخدمين') }}">
                                    @foreach($users as $u)
                                        <option value="{{ $u->name }}" data-name="{{ $u->name }}" data-user-id="{{ $u->id }}">
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <option value="custom">&#9998; {{ __('+ مدرب آخر (إدخال يدوي)') }}</option>
                        </select>
                    </div>

                    <!-- Direct Text Input for Custom Trainer -->
                    <div :class="customInstructor ? 'mt-1' : 'hidden'">
                        <input type="text" 
                               name="instructor_name" 
                               id="instructor_name_input"
                               value="{{ old('instructor_name', 'Dr. Hany Nabil') }}"
                               required 
                               placeholder="{{ __('اكتب اسم المدرب المعتمد...') }}"
                               class="w-full px-3 py-2.5 rounded-xl border border-amber-300 bg-amber-50/50 outline-none text-xs sm:text-sm font-semibold">
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">{{ __('الاسم الذي سيوقع على الشهادة كمدرب معتمد') }}</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('مدير التدريب (Training Manager)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="training_manager_name" 
                           value="{{ old('training_manager_name', 'Gehan Mahmoud') }}"
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('حالة الشهادة (Status)') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm bg-white font-bold">
                        <option value="issued" {{ old('status', 'issued') === 'issued' ? 'selected' : '' }}>{{ __('معتمدة ومصدرة (Issued)') }}</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('مسودة (Draft)') }}</option>
                        <option value="revoked" {{ old('status') === 'revoked' ? 'selected' : '' }}>{{ __('ملغاة (Revoked)') }}</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('نص الاعتماد والجهات المانحة (Accreditation Statement)') }}
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
                {{ __('إلغاء') }}
            </a>
            <button type="submit" 
                    class="px-8 py-3 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-black text-sm shadow-lg shadow-[#22A447]/30 transition duration-200 flex items-center gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span x-show="mode === 'single'">{{ __('إصدار الشهادة وحفظها') }}</span>
                <span x-show="mode === 'batch'">{{ __('توليد وإصدار الشهادات الجماعية دفعة واحدة') }}</span>
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
