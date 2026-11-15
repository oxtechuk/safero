@extends('layouts.app')

@section('title', 'تعديل صلاحيات الدور - ' . $role->label())

@section('content')
<form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6" x-data="{
    toggleModule(moduleKey, check) {
        document.querySelectorAll('.perm-module-' + moduleKey).forEach(el => el.checked = check);
    }
}">
    @csrf
    @method('PUT')

    <!-- Odoo Enterprise Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13">
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('roles.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        الأدوار والصلاحيات
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">
                        {{ $role->label() }}
                    </span>
                    <span class="text-[10px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded uppercase">
                        {{ $role->name }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="px-4 py-1.5 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ الصلاحيات (Save)</span>
                    </button>
                    <a href="{{ route('roles.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition">
                        إلغاء (Discard)
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Form Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Role Metadata -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-2">بيانات الدور الوظيفي (Role Metadata)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="label_ar" class="block text-xs font-bold text-slate-700 mb-1">اسم الدور بالعربية <span class="text-red-500">*</span></label>
                    <input type="text" name="label_ar" id="label_ar" value="{{ old('label_ar', $role->label_ar) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] outline-hidden">
                </div>
                <div>
                    <label for="label_en" class="block text-xs font-bold text-slate-700 mb-1">اسم الدور بالإنجليزية <span class="text-red-500">*</span></label>
                    <input type="text" name="label_en" id="label_en" value="{{ old('label_en', $role->label_en) }}" dir="ltr" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] outline-hidden font-en text-left">
                </div>
                <div class="md:col-span-2">
                    <label for="description_ar" class="block text-xs font-bold text-slate-700 mb-1">الوصف التفصيلي للدور</label>
                    <textarea name="description_ar" id="description_ar" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-[#714B67] outline-hidden">{{ old('description_ar', $role->description_ar) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Permissions Matrix -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">مصفوفة الصلاحيات الممنوحة للدور (Assigned Permissions)</h3>
                    <p class="text-xs text-slate-500">حدد الصلاحيات التي يرثها كل مستخدم ينضم لهذا الدور.</p>
                </div>
                <span class="text-xs font-bold text-[#714B67] font-en">{{ $role->permissions->count() }} Active Permissions</span>
            </div>

            <div class="space-y-6">
                @php
                    $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                @endphp

                @foreach($modules as $moduleKey => $module)
                    @php
                        $modPerms = $permissionsByModule->get($moduleKey, collect());
                    @endphp

                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                        <div class="p-3.5 bg-white border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-slate-100 p-1 flex items-center justify-center shrink-0 border border-slate-200">
                                    <img src="{{ asset($module['icon'] ?? 'branding/odoo-icons/base_icon.svg') }}" class="w-4 h-4 object-contain">
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-slate-900">{{ $module['name_ar'] }}</span>
                                    <span class="text-[10px] text-slate-400 font-en block">{{ $module['name_en'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-xs">
                                <button type="button" @click="toggleModule('{{ $moduleKey }}', true)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold">تحديد الكل</button>
                                <button type="button" @click="toggleModule('{{ $moduleKey }}', false)" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold">إلغاء الكل</button>
                            </div>
                        </div>

                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 bg-white">
                            @foreach($modPerms as $perm)
                                <label class="flex items-start gap-2.5 p-2.5 rounded-lg border border-slate-100 hover:border-slate-300 hover:bg-slate-50/80 transition cursor-pointer select-none">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $perm->id }}" 
                                           {{ in_array($perm->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                           class="perm-module-{{ $moduleKey }} mt-0.5 rounded border-slate-300 text-[#714B67] focus:ring-[#714B67] w-4 h-4 cursor-pointer">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800 leading-tight">{{ $perm->label_ar }}</span>
                                        <span class="block text-[10px] text-slate-400 font-en mt-0.5">{{ $perm->label_en }}</span>
                                        <span class="inline-block mt-1 font-mono text-[9px] text-slate-400 bg-slate-100 px-1.5 py-0.2 rounded">{{ $perm->name }}</span>
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
