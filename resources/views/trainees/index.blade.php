@extends('layouts.app')

@section('title', 'سجل المتدربين')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">سجل المتدربين المعتمدين</h1>
            <p class="text-xs text-slate-500 mt-0.5">قاعدة بيانات المتدربين والشركات التابعين لها وسجل الشهادات التدريبية الصادرة لهم</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('trainees.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>إضافة متدرب جديد</span>
            </a>
            <a href="{{ route('training-certificates.create') }}" 
               class="px-3.5 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>إصدار شهادة تدريب</span>
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('trainees.index') }}" class="flex items-center gap-3">
            <div class="flex-1 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="بحث باسم المتدرب، الشركة، الرقم القومي أو الهاتف..."
                       class="w-full pr-10 pl-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0E4DA4] text-white font-bold text-xs sm:text-sm hover:bg-[#0a3a7d] transition">
                بحث
            </button>
            @if(request('search'))
                <a href="{{ route('trainees.index') }}" class="py-2.5 px-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <!-- Trainees Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="p-3.5">اسم المتدرب (Holder)</th>
                        <th class="p-3.5">الشركة / جهة العمل</th>
                        <th class="p-3.5">المسمى الوظيفي</th>
                        <th class="p-3.5">الرقم القومي / الجواز</th>
                        <th class="p-3.5">الهاتف</th>
                        <th class="p-3.5 text-center">الشهادات الصادرة</th>
                        <th class="p-3.5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trainees as $trainee)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5">
                                <a href="{{ route('trainees.show', $trainee) }}" class="font-bold text-slate-900 hover:text-[#0E4DA4] transition">
                                    {{ $trainee->name }}
                                </a>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $trainee->company ?? '-' }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $trainee->job_title ?? '-' }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-600">
                                {{ $trainee->national_id ?? '-' }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-600" dir="ltr">
                                {{ $trainee->phone ?? '-' }}
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full font-bold {{ $trainee->certificates_count > 0 ? 'bg-[#E6F2FF] text-[#0E4DA4]' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $trainee->certificates_count }} شهادة
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('training-certificates.create', ['trainee_id' => $trainee->id]) }}" 
                                       class="px-2 py-1 rounded bg-[#E8F7EC] text-[#22A447] hover:bg-[#22A447] hover:text-white font-bold transition text-[11px]" 
                                       title="إصدار شهادة">
                                        + إصدار شهادة
                                    </a>
                                    <a href="{{ route('trainees.edit', $trainee) }}" 
                                       class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('trainees.destroy', $trainee) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف المتدرب؟');">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                لا يوجد متدربون مسجلون بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trainees->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $trainees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

