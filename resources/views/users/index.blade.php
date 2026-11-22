@extends('layouts.app')

@section('title', 'إدارة المستخدمين والصلاحيات - Users & Access Control')

@section('content')
<div class="space-y-4" x-data="{ currentView: '{{ request('view', 'list') }}' }">

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        :title="__('المستخدمون والصلاحيات (Users & Access)')"
        new-url="{{ route('users.create') }}"
        :new-text="__('+ مستخدم جديد')"
        new-color="bg-[#714B67]"
        search-action="{{ route('users.index') }}"
        search-value="{{ request('search') }}"
        :search-placeholder="__('البحث بالاسم، البريد الإلكتروني، رقم الهاتف، أو المسمى الوظيفي...')"
        :paginator="$users"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('الدور الوظيفي (Role)') }}</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($roles as $role)
                        <a href="{{ route('users.index', array_merge(request()->except(['role', 'page']), ['role' => $role->name])) }}" 
                           class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('role') === $role->name ? 'bg-[#714B67] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            {{ $role->label() }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('حالة الحساب (Status)') }}</span>
                <div class="flex gap-1.5">
                    <a href="{{ route('users.index', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'active' ? 'bg-[#22A447] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ __('✓ الحسابات النشطة (Active)') }}
                    </a>
                    <a href="{{ route('users.index', array_merge(request()->except(['status', 'page']), ['status' => 'inactive'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('status') === 'inactive' ? 'bg-red-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ __('معطلة (Inactive)') }}
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('users.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    {{ __('إعادة ضبط الفلاتر') }}
                </a>
                <span class="text-[10px] text-slate-400 font-en">Odoo 17/18 RBAC</span>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Top Overview Stats & View Switcher Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex items-center gap-4 sm:gap-6 flex-wrap text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span class="text-slate-500">{{ __('إجمالي المستخدمين:') }}</span>
                    <span class="font-extrabold text-slate-900 font-en">{{ $stats['total'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#22A447]"></span>
                    <span class="text-slate-500">{{ __('نشط وجاهز للتشغيل:') }}</span>
                    <span class="font-extrabold text-[#22A447] font-en">{{ $stats['active'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="text-slate-500">{{ __('مهندسو وفاحصو الرفع:') }}</span>
                    <span class="font-extrabold text-blue-700 font-en">{{ $stats['inspectors'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#714B67]"></span>
                    <span class="text-slate-500">{{ __('مسؤولو النظام (Admins):') }}</span>
                    <span class="font-extrabold text-[#714B67] font-en">{{ $stats['admins'] }}</span>
                </div>
            </div>

            <!-- View Mode Switcher (List vs Kanban) -->
            <div class="flex items-center gap-1 bg-slate-100 p-0.5 rounded-lg self-end sm:self-auto">
                <button type="button" 
                        @click="currentView = 'list'" 
                        :class="currentView === 'list' ? 'bg-white shadow-2xs text-[#714B67] font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="px-2.5 py-1 rounded text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>{{ __('عرض القائمة') }}</span>
                </button>
                <button type="button" 
                        @click="currentView = 'kanban'" 
                        :class="currentView === 'kanban' ? 'bg-white shadow-2xs text-[#714B67] font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="px-2.5 py-1 rounded text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    <span>{{ __('عرض البطاقات') }}</span>
                </button>
            </div>
        </div>

        <!-- 1. Odoo Enterprise List View -->
        <div x-show="currentView === 'list'" class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse">
                    <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                        <tr>
                            <th class="w-12 px-3 py-2.5 text-center">#</th>
                            <th class="px-4 py-2.5 font-bold tracking-tight text-slate-700">{{ __('المستخدم') }}</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">{{ __('المسمى الوظيفي') }}</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">{{ __('الدور الأساسي (Role)') }}</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">{{ __('الصلاحيات') }}</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">{{ __('الحالة') }}</th>
                            <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">{{ __('تاريخ الانضمام') }}</th>
                            <th class="w-24 px-3 py-2.5 text-center text-slate-500">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/90 transition-colors group">
                                <!-- User ID / Avatar Icon -->
                                <td class="px-3 py-2.5 text-center">
                                    <div class="w-8 h-8 mx-auto rounded bg-purple-100 text-[#714B67] flex items-center justify-center font-bold text-xs uppercase shadow-2xs font-en">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                </td>

                                <!-- User Name & Email -->
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <a href="{{ route('users.edit', $user) }}" class="font-bold text-slate-900 group-hover:text-[#714B67] transition">
                                        {{ $user->name }}
                                    </a>
                                    <div class="text-[11px] text-slate-500 font-en flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span>{{ $user->email }}</span>
                                        @if($user->phone)
                                            <span class="text-slate-300">|</span>
                                            <span>{{ $user->phone }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Job Title -->
                                <td class="px-3 py-2.5">
                                    <div class="font-medium text-slate-800">{{ $user->jobTitle('ar') }}</div>
                                    @if($user->job_title_en)
                                        <div class="text-[10px] text-slate-400 font-en">{{ $user->job_title_en }}</div>
                                    @endif
                                </td>

                                <!-- Primary Role Badge -->
                                <td class="px-3 py-2.5">
                                    @if($user->isAdmin())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-100 text-[#714B67] border border-purple-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <span>مسؤول نظام (Super Admin)</span>
                                        </span>
                                    @else
                                        @php $role = $user->roles->first(); @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span>{{ $role ? $role->label() : 'مستخدم قياسي' }}</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Permissions Indicator -->
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-700 font-en">
                                            {{ $user->isAdmin() ? 'Full Access' : $user->allPermissions()->count() . ' صلاحيات' }}
                                        </span>
                                        @if($user->directPermissions->count() > 0)
                                            <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded font-bold" title="صلاحيات استثنائية مخصصة مباشرة">
                                                +{{ $user->directPermissions->count() }} مخصصة
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Active Status -->
                                <td class="px-3 py-2.5 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                            <span>نشط</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            <span>معطل</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Created Date -->
                                <td class="px-3 py-2.5 text-center text-slate-500 font-en text-[11px]">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                <!-- Actions Dropdown / Quick Links -->
                                <td class="px-3 py-2.5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="p-1 rounded text-slate-400 hover:text-[#714B67] hover:bg-purple-50 transition" 
                                           title="تعديل الحساب والصلاحيات">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>

                                        @if($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('users.toggle_status', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-1 rounded text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition" 
                                                        title="{{ $user->is_active ? 'تعطيل الحساب' : 'تفعيل الحساب' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم نهائياً؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition" 
                                                        title="حذف المستخدم">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <p class="font-bold text-slate-700">لا يوجد مستخدمون مطابقون لبحثك</p>
                                        <p class="text-xs text-slate-400">جرب تعديل معايير البحث أو إضافة مستخدم جديد.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- 2. Odoo Enterprise Kanban View -->
        <div x-show="currentView === 'kanban'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" style="display: none;">
            @foreach($users as $user)
                <div class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <!-- Header: Avatar + Status + Edit -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-lg bg-[#714B67] text-white flex items-center justify-center font-bold text-base shadow-2xs font-en uppercase">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('users.edit', $user) }}" class="font-bold text-slate-900 group-hover:text-[#714B67] transition block text-sm">
                                        {{ $user->name }}
                                    </a>
                                    <span class="text-xs text-slate-500 font-medium block">{{ $user->jobTitle('ar') }}</span>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-[#E8F7EC] text-[#22A447]' : 'bg-red-50 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-[#22A447]' : 'bg-red-500' }}"></span>
                                <span>{{ $user->is_active ? 'نشط' : 'معطل' }}</span>
                            </span>
                        </div>

                        <!-- Info Strip -->
                        <div class="space-y-1.5 text-xs text-slate-600 py-2 border-y border-slate-100 my-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-en text-slate-700 truncate">{{ $user->email }}</span>
                            </div>
                            @if($user->phone)
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span class="font-en text-slate-700">{{ $user->phone }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer: Role Badge + Action Button -->
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                            {{ $user->primaryRoleName() }}
                        </span>

                        <a href="{{ route('users.edit', $user) }}" class="text-xs font-bold text-[#714B67] hover:underline flex items-center gap-1">
                            <span>تعديل الصلاحيات</span>
                            <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection

