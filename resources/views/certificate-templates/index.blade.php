@extends('layouts.app')

@section('title', 'ستوديو قوالب شهادات الفحص - Certificate Studio')

@section('content')
<div class="space-y-4">

    <!-- IsoOx Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="ستوديو قوالب شهادات الفحص"
        new-url="{{ route('certificate-templates.create') }}"
        new-text="إنشاء قالب جديد"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('certificate-templates.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث باسم القالب، نوع المعدة، المواصفة المرجعية..."
        :paginator="$templates"
    >
        <!-- Filter Popover Content -->
        <div class="space-y-3">
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">نوع المعدة (Equipment Type)</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('certificate-templates.index') }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ !request('type') ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        الكل (28+ قالب)
                    </a>
                    <a href="{{ route('certificate-templates.index', array_merge(request()->except(['type', 'page']), ['type' => 'crane'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'crane' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        الأوناش (Cranes)
                    </a>
                    <a href="{{ route('certificate-templates.index', array_merge(request()->except(['type', 'page']), ['type' => 'hoist'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'hoist' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        روافع وسلاسل (Hoists & Winches)
                    </a>
                    <a href="{{ route('certificate-templates.index', array_merge(request()->except(['type', 'page']), ['type' => 'accessory'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'accessory' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        مهمات الرفع والحبال (Accessories & Slings)
                    </a>
                    <a href="{{ route('certificate-templates.index', array_merge(request()->except(['type', 'page']), ['type' => 'mewp_forklift'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'mewp_forklift' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        شوكات ومان ليفت (Forklifts & MEWPs)
                    </a>
                    <a href="{{ route('certificate-templates.index', array_merge(request()->except(['type', 'page']), ['type' => 'general'])) }}" 
                       class="px-2.5 py-1 rounded text-xs font-semibold transition {{ request('type') === 'general' ? 'bg-[#0E4DA4] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        كمرات وكلابات وتخزين (Beams & Storage)
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('certificate-templates.index') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">
                    إعادة ضبط الفلاتر
                </a>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 space-y-4">

        <!-- Secondary Sub-Menu Tabs Navigation (Requested in User Prompt) -->
        <div class="bg-white border border-slate-200 rounded-xl p-1.5 flex items-center justify-between gap-2 shadow-2xs flex-wrap">
            <div class="flex items-center gap-1 flex-wrap">
                <a href="{{ route('certificates.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <img src="{{ asset('branding/IsoOx-icons/documents.svg') }}" class="w-4 h-4">
                    <span>جميع الشهادات</span>
                </a>
                <a href="{{ route('certificates.index', ['status' => 'issued']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>السارية المعتمدة</span>
                </a>
                <a href="{{ route('certificates.expiring') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-600 hover:bg-slate-100">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>قريبة من الانتهاء</span>
                </a>
                <a href="{{ route('certificate-templates.index') }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-[#0E4DA4] text-white shadow-xs">
                    <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-4 h-4 brightness-0 invert">
                    <span>ستوديو قوالب الفحص</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20 font-mono">{{ $stats['total'] }}</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('certificate-templates.create') }}" 
                   class="px-3 py-1.5 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>إنشاء قالب فحص جديد</span>
                </a>
            </div>
        </div>

        <!-- Studio Quick KPI Cards (Unified Serene Theme) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">إجمالي القوالب الجاهزة</span>
                    <span class="text-xl font-black text-[#0E4DA4] font-en">{{ $stats['total'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/survey.svg') }}" class="w-full h-full object-contain opacity-80">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">قوالب الأوناش والروافع</span>
                    <span class="text-xl font-black text-[#0E4DA4] font-en">{{ $stats['crane'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/fleet.svg') }}" class="w-full h-full object-contain opacity-80">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">قوالب مهمات الرفع</span>
                    <span class="text-xl font-black text-[#0E4DA4] font-en">{{ $stats['accessory'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/maintenance.svg') }}" class="w-full h-full object-contain opacity-80">
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 block">قوالب المعدات العامة</span>
                    <span class="text-xl font-black text-[#0E4DA4] font-en">{{ $stats['general'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('branding/IsoOx-icons/repair.svg') }}" class="w-full h-full object-contain opacity-80">
                </div>
            </div>
        </div>

        <!-- Studio Templates Grid (CMS Cards View) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($templates as $template)
                <div class="bg-white border border-slate-200 hover:border-[#0E4DA4]/50 rounded-2xl p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between group relative overflow-hidden">
                    
                    <!-- Top Ribbon Indicator -->
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center p-1.5 border border-slate-200">
                                <img src="{{ asset('branding/IsoOx-icons/' . ($template->equipment_type === 'crane' ? 'fleet.svg' : ($template->equipment_type === 'accessory' ? 'maintenance.svg' : 'repair.svg'))) }}" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-700">
                                    {{ $template->equipment_type_label }}
                                </span>
                                @if($template->code)
                                    <span class="text-[10px] font-mono text-slate-400 mr-1.5">{{ $template->code }}</span>
                                @endif
                            </div>
                        </div>

                        @if($template->is_default)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                قالب معتمد
                            </span>
                        @endif
                    </div>

                    <!-- Template Title & Description -->
                    <div class="space-y-2 mb-4">
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-[#0E4DA4] transition line-clamp-1">
                            {{ $template->name }}
                        </h3>
                        <p class="text-xs text-slate-500 font-en line-clamp-2 leading-relaxed">
                            {{ $template->equipment_description ?: 'No detailed description' }}
                        </p>
                    </div>

                    <!-- Specs Pills & Metas -->
                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 space-y-2 text-xs mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 text-[11px]">الحمل الآمن (SWL):</span>
                            <strong class="font-en text-[#0E4DA4] font-bold">{{ $template->swl ?: '—' }}</strong>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 text-[11px]">المواصفة المرجعية:</span>
                            <span class="font-en text-slate-700 font-semibold">{{ $template->reference_standard ?: 'ASTM / EN' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 text-[11px]">دورة الفحص القادم:</span>
                            <span class="text-slate-700 font-bold">كل {{ $template->validity_months }} أشهر</span>
                        </div>

                        <!-- CMS Custom Tools / Specs Counter -->
                        @php
                            $specsCount = is_array($template->custom_specifications) ? count($template->custom_specifications) : 0;
                            $checkCount = is_array($template->checklist_items) ? count($template->checklist_items) : 0;
                        @endphp
                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $specsCount }} أداة ومعدة</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <span>{{ $checkCount }} بنود فحص</span>
                            </span>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <!-- Primary CTA: Use Template for New Certificate -->
                        <a href="{{ route('certificates.create', ['type' => $template->equipment_type, 'template_id' => $template->id]) }}" 
                           class="flex-1 px-3 py-2 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>استخدام القالب</span>
                        </a>

                        <!-- Actions Dropdown / Quick Buttons -->
                        <div class="flex items-center gap-1">
                            <!-- Duplicate Button -->
                            <form method="POST" action="{{ route('certificate-templates.duplicate', $template) }}">
                                @csrf
                                <button type="submit" title="نسخ القالب (Duplicate)" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('certificate-templates.edit', $template) }}" title="تعديل القالب في الاستوديو" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-[#0E4DA4] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('certificate-templates.destroy', $template) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا القالب؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="حذف القالب" class="p-2 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center space-y-3">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 mx-auto flex items-center justify-center">
                        <img src="{{ asset('branding/IsoOx-icons/survey.svg') }}" class="w-8 h-8 opacity-60">
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">لا توجد قوالب فحص مسجلة حتى الآن</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        ابدأ الآن بإنشاء أول قالب فحص مخصص عبر استوديو القوالب لتسريع إنشاء شهادات الفحص وإعادة استخدام بيانات المعدات وأدوات الفحص.
                    </p>
                    <a href="{{ route('certificate-templates.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0E4DA4] text-white text-xs font-bold hover:bg-[#0a3a7d] transition">
                        <span>+ إنشاء قالب جديد الآن</span>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $templates->links() }}
        </div>

    </div>
</div>
@endsection
