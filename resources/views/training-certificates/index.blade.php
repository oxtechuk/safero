@extends('layouts.app')

@section('title', 'شهادات التدريب المعتمدة')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#E8F7EC] text-[#22A447] text-xs font-bold mb-1">
                <span class="w-2 h-2 rounded-full bg-[#22A447] animate-pulse"></span>
                أكاديمية سافيرو للتدريب والاستشارات (Training & Consultation)
            </div>
            <h1 class="text-2xl font-black text-slate-900">سجل شهادات التدريب المعتمدة</h1>
            <p class="text-xs text-slate-500 mt-0.5">إصدار وإدارة شهادات الدورات التدريبية المعتمدة للمتدربين مع رمز QR المباشر والربط بموقع الشركة</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('training-certificates.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>إصدار شهادة تدريب</span>
            </a>
            <a href="{{ route('trainees.index') }}" 
               class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>سجل المتدربين</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('training-certificates.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="بحث باسم المتدرب، رقم المسلسل، عنوان البرنامج أو المجال..."
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
                    <a href="{{ route('training-certificates.index') }}" class="py-2 px-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                        إعادة ضبط
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Training Certificates Table matching requested columns -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="p-3.5">اسم المتدرب (Certificate Holder)</th>
                        <th class="p-3.5">رقم المسلسل (Serial No)</th>
                        <th class="p-3.5">مجال التدريب (Scope)</th>
                        <th class="p-3.5">عنوان البرنامج (Program Title)</th>
                        <th class="p-3.5 text-center">الساعات (Hours)</th>
                        <th class="p-3.5">تاريخ الشهادة (Date)</th>
                        <th class="p-3.5">الانتهاء (Expiry)</th>
                        <th class="p-3.5 text-center">الحالة</th>
                        <th class="p-3.5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-[#E6F2FF] text-[#0E4DA4] flex items-center justify-center font-bold font-en text-xs shrink-0">
                                        {{ mb_substr($cert->trainee_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('training-certificates.show', $cert) }}" class="font-bold text-slate-900 hover:text-[#0E4DA4] transition">
                                            {{ $cert->trainee_name }}
                                        </a>
                                        @if($cert->trainee?->company)
                                            <span class="block text-[11px] text-slate-500">{{ $cert->trainee->company }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-[#0E4DA4] font-en tracking-wide">{{ $cert->certificate_serial }}</span>
                            </td>
                            <td class="p-3.5 text-slate-700 font-medium">
                                {{ $cert->training_scope }}
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-800">{{ $cert->program_title }}</span>
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-700 font-en">
                                {{ $cert->hours }} hrs
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->issue_date->format('d/M/Y') }}
                            </td>
                            <td class="p-3.5 font-en">
                                @if($cert->expiry_date)
                                    <span class="{{ $cert->expiry_date->isPast() ? 'text-red-600 font-bold' : 'text-slate-600' }}">
                                        {{ $cert->expiry_date->format('d/M/Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                @if($cert->status === 'issued')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                        معتمدة
                                    </span>
                                @elseif($cert->status === 'draft')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600">
                                        مسودة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-600">
                                        ملغاة
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('training-certificates.show', $cert) }}" 
                                       class="p-1.5 rounded-lg text-slate-600 hover:text-[#0E4DA4] hover:bg-slate-100 transition" 
                                       title="عرض الشهادة">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition" 
                                       title="تحميل PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.edit', $cert) }}" 
                                       class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('training-certificates.destroy', $cert) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشهادة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">
                                لا توجد شهادات تدريب مسجلة بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

