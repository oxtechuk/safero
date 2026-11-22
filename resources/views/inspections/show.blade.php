@extends('layouts.app')

@section('title', 'تقرير فحص: '.$inspection->inspection_number)

@section('content')
<div class="space-y-4">

    <!-- Header & Breadcrumb -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('inspections.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold font-mono bg-blue-50 text-[#0E4DA4] border border-blue-200">
                            {{ $inspection->inspection_number }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">تقرير استمارة الفحص الميداني المعتمدة</h1>
                        @if($inspection->overall_condition === 'safe')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                آمن للاستخدام (SAFE TO USE)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-300">
                                غير آمن / به عيوب (UNSAFE)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('inspections.inspect', $inspection) }}" 
                   class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>فتح الشيك ليست الميداني</span>
                </a>

                @if(!$inspection->certificate_id)
                    <form action="{{ route('inspections.convert', $inspection) }}" method="POST" class="inline" onsubmit="return confirm('هل تريد إصدار شهادة الفحص المعتمدة فوراً؟');">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-1.5 rounded-lg bg-[#006837] hover:bg-[#00522c] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>المرحلة 2: إنشاء الشهادة المعتمدة</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('certificates.show', $inspection->certificate_id) }}" 
                       class="px-4 py-1.5 rounded-lg bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>عرض الشهادة: {{ $inspection->certificate->certificate_number }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-2 space-y-5">
        
        <!-- Asset & Inspection Overview -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 flex-wrap gap-2">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">اسم ووصف المعدة</span>
                    <h2 class="text-base font-black text-slate-900">{{ $inspection->equipment_name }}</h2>
                    <span class="text-xs text-slate-500 font-mono">{{ $inspection->reference_standard }}</span>
                </div>
                <div class="text-left">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">العميل والموقع</span>
                    <div class="text-xs font-extrabold text-[#0E4DA4]">{{ $inspection->customer->name }}</div>
                    <div class="text-[11px] text-slate-500">{{ $inspection->location ?: 'ساحة العميل' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold">رقم الأصل / التاج:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $inspection->asset_tag ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold">الرقم المسلسل / الشاسيه:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $inspection->serial_no ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold">حمولة التشغيل الآمنة (SWL):</span>
                    <span class="font-bold text-emerald-700">{{ $inspection->swl ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold">تاريخ المعاينة:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $inspection->inspection_date->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>

        <!-- Checklist Results -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">نتائج بنود الفحص الميداني (Checklist Items)</h3>
                @php $summary = $inspection->checklistSummary(); @endphp
                <div class="flex items-center gap-2 text-xs font-mono font-bold">
                    <span class="text-emerald-700">✓ {{ $summary['pass'] }} مطابق</span>
                    <span class="text-red-600">✗ {{ $summary['fail'] }} مرفوض</span>
                    <span class="text-slate-500">- {{ $summary['na'] }} N/A</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 text-[11px]">
                        <tr>
                            <th class="py-2.5 px-3 w-10 text-center">#</th>
                            <th class="py-2.5 px-3 w-28">الفقرة القياسية</th>
                            <th class="py-2.5 px-3">نقطة الفحص</th>
                            <th class="py-2.5 px-3 text-center w-24">النتيجة</th>
                            <th class="py-2.5 px-3">ملاحظات / العيوب المرصودة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach(($inspection->checklist_results ?? []) as $item)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400 font-bold">{{ $item['no'] ?? 1 }}</td>
                                <td class="py-2.5 px-3 font-mono text-[10px] font-bold text-[#0E4DA4]">
                                    {{ !empty($item['clause_ref']) ? 'Clause '.$item['clause_ref'] : '—' }}
                                </td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">{{ $item['item'] ?? '' }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    @if(($item['status'] ?? 'pass') === 'pass')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            مطابق (P)
                                        </span>
                                    @elseif(($item['status'] ?? '') === 'fail')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            مرفوض (F)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                            غير منطبق
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-[11px] {{ ($item['status'] ?? '') === 'fail' ? 'text-red-600 font-bold' : 'text-slate-500' }}">
                                    {{ $item['remarks'] ?: '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Overall Declaration Box -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">الإقرار الفني النهائي (Declaration & Findings)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="font-bold text-slate-700 block">العيوب المرصودة:</span>
                    <p class="text-slate-600">{{ $inspection->defects_identified ?: 'لا توجد عيوب مرصودة أثناء المعاينة.' }}</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="font-bold text-slate-700 block">الإجراءات التصحيحية المطلوبة:</span>
                    <p class="text-slate-600">{{ $inspection->corrective_actions ?: 'لا توجد إجراءات إضافية مطلوبة.' }}</p>
                </div>
            </div>
            <div class="pt-2 text-xs text-slate-500 flex items-center justify-between">
                <span>اسم الفاحص المعتمد: <strong class="text-slate-800">{{ $inspection->inspector_name }}</strong></span>
                <span>رقم الاعتماد: <strong class="text-slate-800 font-mono">{{ $inspection->inspector_cert_ref }}</strong></span>
            </div>
        </div>
    </div>
</div>
@endsection
