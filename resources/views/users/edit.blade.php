@extends('layouts.app')

@section('title', 'تعديل بيانات وصلاحيات المستخدم - ' . $user->name)

@section('content')
<form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6" x-data="{
    selectedRole: '{{ old('role_id', $user->roles->first()?->id) }}',
    toggleModule(moduleKey, check) {
        document.querySelectorAll('.perm-module-' + moduleKey).forEach(el => el.checked = check);
    }
}">
    @csrf
    @method('PUT')

    <!-- Odoo Enterprise Form Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13">
                
                <!-- Left: Breadcrumb & Title -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        المستخدمون والصلاحيات
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">
                        {{ $user->name }}
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $user->is_active ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-red-50 text-red-600' }}">
                        {{ $user->is_active ? 'نشط' : 'معطل' }}
                    </span>
                </div>

                <!-- Right: Save / Discard Actions -->
                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="px-4 py-1.5 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ التعديلات (Save)</span>
                    </button>
                    <a href="{{ route('users.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition">
                        إلغاء (Discard)
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Form Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Section 1: User Account & Profile Information -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">البيانات الأساسية للحساب (General Information)</h3>
                    <p class="text-xs text-slate-500">تعديل معلومات الحساب الشخصية وبيانات التواصل.</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#714B67] text-white flex items-center justify-center font-bold text-base shadow-2xs font-en uppercase">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                        الاسم الكامل (Full Name) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden transition">
                    @error('name')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                        البريد الإلكتروني (Email Address) <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           dir="ltr"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden font-en transition text-left">
                    @error('email')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Optional on update) -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                        كلمة المرور الجديدة (New Password)
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           dir="ltr"
                           placeholder="اتركه فارغاً للإبقاء على كلمة المرور الحالية" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden transition text-left">
                    <span class="text-[10px] text-slate-400 mt-1 block">اتركه فارغاً ما لم ترغب في تعيين كلمة مرور جديدة.</span>
                    @error('password')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                        تأكيد كلمة المرور الجديدة (Confirm Password)
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           dir="ltr"
                           placeholder="••••••••" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden transition text-left">
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                        رقم الهاتف / الجوال (Phone Number)
                    </label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           dir="ltr"
                           placeholder="+20 100 000 0000" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden font-en transition text-left">
                </div>

                <!-- Account Active Status -->
                <div class="flex items-center pt-5">
                    <label class="relative flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                               class="rounded border-slate-300 text-[#714B67] focus:ring-[#714B67] w-4 h-4 cursor-pointer">
                        <span class="text-xs font-bold text-slate-800">الحساب مفعل وجاهز للتشغيل والولوج الفوري (Active Account)</span>
                    </label>
                </div>

                <!-- Job Title Arabic -->
                <div>
                    <label for="job_title_ar" class="block text-xs font-bold text-slate-700 mb-1">
                        المسمى الوظيفي بالعربية (Job Title - Arabic)
                    </label>
                    <input type="text" 
                           name="job_title_ar" 
                           id="job_title_ar" 
                           value="{{ old('job_title_ar', $user->job_title_ar) }}" 
                           placeholder="مثال: فاحص معتمد للأوناش والروافع" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden transition">
                </div>

                <!-- Job Title English -->
                <div>
                    <label for="job_title_en" class="block text-xs font-bold text-slate-700 mb-1">
                        المسمى الوظيفي بالإنجليزية (Job Title - English)
                    </label>
                    <input type="text" 
                           name="job_title_en" 
                           id="job_title_en" 
                           value="{{ old('job_title_en', $user->job_title_en) }}" 
                           dir="ltr"
                           placeholder="e.g. Certified LOLER Lifting Inspector" 
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] focus:border-[#714B67] outline-hidden font-en transition text-left">
                </div>

            </div>
        </div>

        <!-- Section 2: Primary Role Selection -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">الدور الوظيفي الرئيسي (Predefined System Role)</h3>
                    <p class="text-xs text-slate-500">اختر دوراً قياسياً لتطبيق حزمة الصلاحيات القياسية تلقائياً، مع إمكانية التخصيص الإضافي أدناه.</p>
                </div>
                <a href="{{ route('roles.index') }}" target="_blank" class="text-xs font-bold text-[#714B67] hover:underline flex items-center gap-1">
                    <span>إدارة حزم الأدوار</span>
                    <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($roles as $role)
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none"
                           :class="selectedRole == '{{ $role->id }}' ? 'border-[#714B67] bg-purple-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        
                        <div class="flex items-center justify-between mb-1.5">
                            <input type="radio" 
                                   name="role_id" 
                                   value="{{ $role->id }}" 
                                   x-model="selectedRole" 
                                   class="text-[#714B67] focus:ring-[#714B67]">
                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">{{ $role->name }}</span>
                        </div>

                        <span class="font-bold text-xs text-slate-900 block mb-0.5">{{ $role->label() }}</span>
                        <span class="text-[10px] text-slate-400 font-en block mb-2">{{ $role->label_en }}</span>
                        <p class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed">{{ $role->description() }}</p>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Dynamic Permissions Matrix (Auto-discovered Modules) -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">مصفوفة الصلاحيات المتقدمة (Dynamic Permissions Matrix)</h3>
                    <p class="text-xs text-slate-500">صلاحيات مخصصة مباشرة لهذا المستخدم (Direct Overrides). الصلاحيات المحددة تم تفعيلها للمستخدم.</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="text-slate-400 text-[11px]">المديولات المكتشفة: {{ count($matrix) }} مديول</span>
                </div>
            </div>

            <div class="space-y-6">
                @php
                    $userDirectIds = $user->directPermissions->pluck('id')->toArray();
                @endphp

                @foreach($matrix as $moduleKey => $module)
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                        
                        <!-- Module Header Strip -->
                        <div class="p-3.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-slate-100 p-1 flex items-center justify-center shrink-0 border border-slate-200">
                                    <img src="{{ asset($module['icon']) }}" alt="{{ $module['name_en'] }}" class="w-4 h-4 object-contain">
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-slate-900">{{ $module['name_ar'] }}</span>
                                    <span class="text-[10px] text-slate-400 font-en block">{{ $module['name_en'] }}</span>
                                </div>
                            </div>

                            <!-- Module Quick Select / Deselect All -->
                            <div class="flex items-center gap-2 text-xs">
                                <button type="button" 
                                        @click="toggleModule('{{ $moduleKey }}', true)" 
                                        class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">
                                    تحديد الكل
                                </button>
                                <button type="button" 
                                        @click="toggleModule('{{ $moduleKey }}', false)" 
                                        class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">
                                    إلغاء التحديد
                                </button>
                            </div>
                        </div>

                        <!-- Permissions Grid for This Module -->
                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 bg-white">
                            @foreach($module['permissions'] as $perm)
                                @php
                                    $isChecked = in_array($perm['id'], old('permissions', $userDirectIds)) || $perm['has_direct'];
                                @endphp
                                <label class="flex items-start gap-2.5 p-2.5 rounded-lg border {{ $perm['is_granted'] ? 'border-purple-200 bg-purple-50/30' : 'border-slate-100' }} hover:border-slate-300 hover:bg-slate-50/80 transition cursor-pointer select-none">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $perm['id'] }}" 
                                           {{ $isChecked ? 'checked' : '' }}
                                           class="perm-module-{{ $moduleKey }} mt-0.5 rounded border-slate-300 text-[#714B67] focus:ring-[#714B67] w-4 h-4 cursor-pointer">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800 leading-tight">{{ $perm['label_ar'] }}</span>
                                        <span class="block text-[10px] text-slate-400 font-en mt-0.5">{{ $perm['label_en'] }}</span>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="font-mono text-[9px] text-slate-400 bg-slate-100 px-1.5 py-0.2 rounded">{{ $perm['name'] }}</span>
                                            @if($perm['is_granted'] && !$perm['has_direct'])
                                                <span class="text-[9px] text-emerald-700 bg-emerald-50 px-1 rounded font-bold font-en">via Role</span>
                                            @elseif($perm['has_direct'])
                                                <span class="text-[9px] text-purple-700 bg-purple-50 px-1 rounded font-bold font-en">Direct Override</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</form>
@endsection
