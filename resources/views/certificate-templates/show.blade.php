@extends('layouts.app')

@section('title', 'عرض قالب: ' . $template->name . ' - ستوديو قوالب الفحص')

@section('content')
<div class="space-y-4">

    <!-- IsoOx Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="{{ $template->name }}"
        new-url="{{ route('certificate-templates.create') }}"
        new-text="قالب جديد"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('certificate-templates.index') }}"
    >
        <div class="flex items-center gap-2">
            <a href="{{ route('certificate-templates.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">
                العودة إلى ستوديو القوالب
            </a>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-2 max-w-5xl mx-auto space-y-4">

        <!-- Top Action Bar -->
        <div class="bg-white border border-slate-200 rounded-xl p-3 flex items-center justify-between shadow-2xs flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('certificate-templates.index') }}" 
                   class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    ← العودة للقوالب
                </a>
                <span class="text-xs font-bold text-slate-400">|</span>
                <span class="text-xs font-mono font-bold text-slate-500">كود: {{ $template->code ?? 'N/A' }}</span>
                @if($template->is_active)
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-700 font-bold border border-slate-200">نشط وجاهز</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-bold border border-slate-200">مسودة</span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <!-- Use this template button -->
                <a href="{{ route('certificates.create', ['template_id' => $template->id, 'type' => $template->equipment_type]) }}" 
                   class="px-4 py-2 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>استخدام هذا القالب لإصدار شهادة فحص</span>
                </a>

                <a href="{{ route('certificate-templates.edit', $template) }}" 
                   class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition">
                    تعديل القالب
                </a>

                <form action="{{ route('certificate-templates.duplicate', $template) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                        نسخ القالب
                    </button>
                </form>
            </div>
        </div>

        <!-- Template Details Sheet -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $template->equipment_type_label }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">مرات الاستخدام: {{ $template->usage_count }}</span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900">{{ $template->name }}</h1>
                        @if($template->description)
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $template->description }}</p>
                        @endif
                    </div>

                    <div class="text-left bg-slate-50 border border-slate-200 rounded-lg p-3 min-w-[160px]">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">حمل التشغيل الآمن (SWL)</span>
                        <span class="text-lg font-black text-[#0E4DA4] font-mono">{{ $template->swl ?? 'غير محدد' }}</span>
                        <div class="mt-1 pt-1 border-t border-slate-200 text-[11px] text-slate-600 font-medium">
                            صلاحية الفحص: <strong class="font-en text-slate-800">{{ $template->validity_months }} أشهر</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Specifications Grid -->
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 bg-slate-50/50">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 block">المواصفة القياسية المرجعية</span>
                    <span class="text-xs font-mono font-bold text-slate-800">{{ $template->reference_standard ?? 'ASME / BS EN' }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 block">الشركة المصنعة الافتراضية</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $template->default_manufacturer ?? 'غير محدد' }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 block">الموديل الافتراضي</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $template->default_model ?? 'غير محدد' }}</span>
                </div>
            </div>

            <!-- Custom CMS Tools & Equipment Specifications -->
            @if(!empty($template->custom_specifications) && count($template->custom_specifications) > 0)
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#0E4DA4]"></span>
                        <span>معدات الفحص، الأجهزة وأدوات الاختبار المخصصة (CMS Tools & Test Equipment)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($template->custom_specifications as $spec)
                            <div class="border border-slate-200 rounded-lg p-3 bg-white">
                                <span class="text-[11px] font-bold text-slate-500 block">{{ $spec['label'] ?? 'بند' }}</span>
                                <span class="text-xs font-mono font-bold text-slate-800 block mt-0.5">{{ $spec['value'] ?? '-' }}</span>
                                @if(!empty($spec['key']))
                                    <span class="text-[10px] text-slate-400 font-mono block mt-1">#{{ $spec['key'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Inspection Checklist Items -->
            @if(!empty($template->checklist_items) && count($template->checklist_items) > 0)
                <div class="p-6">
                    <h3 class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#0E4DA4]"></span>
                        <span>قائمة بنود الفحص والمعاينة الافتراضية (Inspection Checklist)</span>
                    </h3>
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-bold">
                                <tr>
                                    <th class="w-12 px-3 py-2 text-center">#</th>
                                    <th class="px-3 py-2 w-28">رقم البند/الفقرة</th>
                                    <th class="px-3 py-2">نقطة الفحص والمعاينة</th>
                                    <th class="px-3 py-2">معايير القبول (Acceptance)</th>
                                    <th class="px-3 py-2">معايير الرفض (Rejection)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($template->checklist_items as $index => $item)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-3 py-2 text-center font-mono text-slate-400 font-bold">{{ $item['no'] ?? ($index + 1) }}</td>
                                        <td class="px-3 py-2">
                                            @if(!empty($item['clause_ref']))
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#0E4DA4] border border-blue-200 font-mono">
                                                    Clause {{ $item['clause_ref'] }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 font-mono text-[10px]">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 font-bold text-slate-900">{{ $item['item'] ?? $item['question'] ?? 'بند فحص' }}</td>
                                        <td class="px-3 py-2 text-[11px] text-emerald-800 bg-emerald-50/40">{{ $item['acceptance_criteria'] ?? 'In Order / Accepted' }}</td>
                                        <td class="px-3 py-2 text-[11px] text-red-800 bg-red-50/40">{{ $item['rejection_criteria'] ?? 'Cracks, excessive wear, deformation' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Bottom Actions -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between flex-wrap gap-2">
                <span class="text-xs text-slate-500">تم اعتماد هذا القالب وفقاً للائحة LOLER ومعايير ISO/IEC 17020</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('inspections.start', ['template_id' => $template->id]) }}" 
                       class="px-4 py-2 rounded-lg bg-[#006837] hover:bg-[#00522c] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>بدء فحص ميداني بهذا القالب</span>
                    </a>
                    <a href="{{ route('certificates.create', ['template_id' => $template->id, 'type' => $template->equipment_type]) }}" 
                       class="px-4 py-2 rounded-lg bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <span>إصدار شهادة مباشرة ←</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
