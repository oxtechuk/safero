@extends('layouts.app')

@section('title', 'أدوار النظام ومصفوفة الصلاحيات - Roles & Permissions')

@section('content')
<div class="space-y-6">

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        :title="__('أدوار النظام ومصفوفة الصلاحيات (Roles & Access Matrix)')"
        :search-placeholder="__('البحث في الأدوار والصلاحيات...')"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Informational Banner -->
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#714B67] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-xs text-purple-950 space-y-1">
                <span class="font-bold block">نظام الأدوار والصلاحيات المفتوح القابل للتوسع (Dynamic RBAC Engine)</span>
                <p class="text-purple-900 leading-relaxed">
                    يتم ربط كل دور بمصفوفة صلاحيات تحدد الإجراءات المسموحة (عرض، إضافة، تعديل، حذف، اعتماد، وتصدير). عند إضافة أي مديول جديد مستقبلاً في منصة SaaS، يقوم النظام تلقائياً بتوليد الصلاحيات التابعة له وإتاحتها للتخصيص فوراً.
                </p>
            </div>
        </div>

        <!-- Roles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($roles as $role)
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider block">{{ $role->name }}</span>
                                <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-[#714B67] transition">{{ $role->label() }}</h3>
                                <span class="text-[11px] text-slate-400 font-en block">{{ $role->label_en }}</span>
                            </div>
                            @if($role->is_system)
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 uppercase font-en">
                                    System Role
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $role->description() }}
                        </p>

                        <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <span class="text-[10px] text-slate-400 block">الصلاحيات الممنوحة:</span>
                                <span class="font-extrabold text-[#714B67] font-en text-sm">{{ $role->permissions->count() }}</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <span class="text-[10px] text-slate-400 block">المستخدمين المرتبطين:</span>
                                <span class="font-extrabold text-slate-800 font-en text-sm">{{ $role->users->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                        <a href="{{ route('users.index', ['role' => $role->name]) }}" class="text-[11px] text-slate-500 hover:text-slate-800 transition">
                            عرض المستخدمين
                        </a>

                        <a href="{{ route('roles.edit', $role) }}" 
                           class="px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-[#714B67] text-[#714B67] hover:text-white font-bold text-xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>تعديل الصلاحيات</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection

