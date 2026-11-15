@extends('layouts.app')

@section('title', 'ملف المستخدم والصلاحيات - ' . $user->name)

@section('content')
<div class="space-y-6">

    <!-- Odoo Enterprise Form Control Panel -->
    <div class="bg-white border-b border-slate-200 sticky top-12 z-30 shadow-2xs">
       
    </div>

    <!-- Main Profile Sheet -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- User Summary Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-[#714B67] text-white flex items-center justify-center font-bold text-2xl shadow-md font-en uppercase">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-black text-slate-900">{{ $user->name }}</h2>
                        @if($user->isAdmin())
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-[#714B67] border border-purple-200 font-en">
                                Super Administrator
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600 font-medium">{{ $user->jobTitle('ar') }}</p>
                    <div class="flex items-center gap-4 text-xs text-slate-500 font-en">
                        <span>{{ $user->email }}</span>
                        @if($user->phone)
                            <span>| {{ $user->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Badge -->
            <div class="text-left md:text-right bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 block uppercase">الدور الوظيفي الأساسي</span>
                <span class="text-xs font-extrabold text-slate-900 block mt-0.5">{{ $user->primaryRoleName() }}</span>
                <span class="text-[11px] text-slate-500 block mt-1 font-en">Joined: {{ $user->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Permissions Matrix Overview -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">مصفوفة الصلاحيات المفعلة للمستخدم</h3>
                    <p class="text-xs text-slate-500">استعراض الصلاحيات الممنوحة من خلال الدور الوظيفي أو الصلاحيات المباشرة الاستثنائية.</p>
                </div>
                <span class="text-xs font-bold font-en text-[#714B67]">{{ $user->allPermissions()->count() }} Permissions Active</span>
            </div>

            <div class="space-y-5">
                @foreach($matrix as $moduleKey => $module)
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                        <div class="p-3 bg-white border-b border-slate-200 flex items-center gap-3">
                           
                            <div>
                                <span class="text-xs font-bold text-slate-900">{{ $module['name_ar'] }}</span>
                                <span class="text-[10px] text-slate-400 font-en">({{ $module['name_en'] }})</span>
                            </div>
                        </div>

                        <div class="p-3.5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 bg-white">
                            @foreach($module['permissions'] as $perm)
                                <div class="p-2 rounded-lg border {{ $perm['is_granted'] ? 'border-emerald-200 bg-emerald-50/40 text-emerald-950' : 'border-slate-100 bg-slate-50/30 text-slate-400' }} flex items-start justify-between gap-2">
                                    <div>
                                        <span class="text-xs font-bold block">{{ $perm['label_ar'] }}</span>
                                        <span class="text-[10px] font-en block opacity-75">{{ $perm['label_en'] }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold font-en px-1.5 py-0.5 rounded {{ $perm['is_granted'] ? 'bg-emerald-100 text-[#22A447]' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $perm['is_granted'] ? 'Granted' : 'Denied' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

