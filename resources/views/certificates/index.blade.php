@extends('layouts.app')

@section('title', 'شهادات وتقارير الفحص')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">سجل شهادات الفحص والمعايرة</h1>
            <p class="text-xs text-slate-500 mt-0.5">تقارير فحص معدات الرفع الشاملة المطابقة لمعايير LOLER 1998 و ISO المعتمدة</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('certificates.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>إصدار شهادة جديدة</span>
            </a>
            <a href="{{ route('certificates.verify.lookup') }}" 
               class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>أداة التحقق</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('certificates.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="بحث برقم الشهادة، أمر الشغل، العميل أو وصف المعدة..."
                       class="w-full pr-10 pl-4 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm bg-white">
                    <option value="">جميع الحالات</option>
                    <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>معتمدة ومصدرة (Issued)</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>ملغاة (Revoked)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-[#0E4DA4] text-white font-bold text-xs sm:text-sm hover:bg-[#0a3a7d] transition">
                    تصفية
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('certificates.index') }}" class="py-2 px-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                        إعادة ضبط
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Certificates Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 font-bold">رقم الشهادة</th>
                        <th class="p-3.5 font-bold">العميل</th>
                        <th class="p-3.5 font-bold">المعدة والمواصفة</th>
                        <th class="p-3.5 font-bold">SWL</th>
                        <th class="p-3.5 font-bold">تاريخ الفحص</th>
                        <th class="p-3.5 font-bold">الفحص القادم</th>
                        <th class="p-3.5 font-bold">القرار الفني</th>
                        <th class="p-3.5 font-bold text-center">الإجراءات والـ PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-[#0E4DA4] font-en">
                                <a href="{{ route('certificates.show', $cert) }}" class="hover:underline flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>{{ $cert->certificate_number }}</span>
                                </a>
                                @if($cert->order_no)
                                    <span class="text-[10px] text-slate-400 font-en block">Order #{{ $cert->order_no }}</span>
                                @endif
                            </td>
                            <td class="p-3.5 font-semibold text-slate-900">
                                <a href="{{ route('customers.show', $cert->customer) }}" class="hover:text-[#0E4DA4]">
                                    {{ $cert->customer?->name }}
                                </a>
                                <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ $cert->examination_address }}</span>
                            </td>
                            <td class="p-3.5 text-slate-700">
                                <span class="font-bold text-slate-900 block">{{ $cert->equipment_description }}</span>
                                <span class="text-[10px] text-slate-400 font-en">{{ $cert->reference_standard }} | عدد: {{ $cert->quantity }}</span>
                            </td>
                            <td class="p-3.5 font-black text-slate-900 font-en">
                                {{ $cert->swl }}
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->thorough_examination_date->format('Y-m-d') }}
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->next_examination_due_date->format('Y-m-d') }}
                            </td>
                            <td class="p-3.5">
                                @if($cert->is_safe_to_operate)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                        آمن للتشغيل
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        غير آمن
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('certificates.show', $cert) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-[#E6F2FF] text-slate-700 hover:text-[#0E4DA4] transition" 
                                       title="معاينة الشهادة والـ QR">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white transition" 
                                       title="تحميل PDF رسمي">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.edit', $cert) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-600 transition" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                لا توجد شهادات مطابقة للبحث.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

