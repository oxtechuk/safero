@extends('layouts.app')

@section('title', 'ملف المتدرب - '.$trainee->name)

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#0E4DA4]">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('trainees.index') }}" class="hover:text-[#0E4DA4]">سجل المتدربين</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">{{ $trainee->name }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">{{ $trainee->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">تفاصيل المتدرب وسجل الدورات والشهادات الحاصل عليها</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('training-certificates.create', ['trainee_id' => $trainee->id]) }}" 
               class="px-4 py-2 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>إصدار شهادة جديدة للمتدرب</span>
            </a>
            <a href="{{ route('trainees.edit', $trainee) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
                تعديل
            </a>
        </div>
    </div>

    <!-- Trainee Info Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
        <div class="p-3 bg-slate-50 rounded-xl">
            <span class="text-slate-400 block mb-1">الشركة / جهة العمل:</span>
            <span class="font-bold text-slate-900 text-sm block">{{ $trainee->company ?? 'غير محدد' }}</span>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl">
            <span class="text-slate-400 block mb-1">المسمى الوظيفي:</span>
            <span class="font-bold text-slate-900 text-sm block">{{ $trainee->job_title ?? 'متدرب' }}</span>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl">
            <span class="text-slate-400 block mb-1">الرقم القومي / الجواز:</span>
            <span class="font-bold text-slate-900 font-mono text-sm block">{{ $trainee->national_id ?? '-' }}</span>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl">
            <span class="text-slate-400 block mb-1">الهاتف / البريد:</span>
            <span class="font-bold text-slate-900 font-mono block" dir="ltr">{{ $trainee->phone ?? '-' }}</span>
            <span class="text-[11px] text-slate-500 block">{{ $trainee->email ?? '' }}</span>
        </div>
    </div>

    <!-- Trainee Certificates List -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <span class="w-2 h-5 bg-[#006837] rounded-full inline-block"></span>
                الشهادات المعتمدة الصادرة للمتدرب ({{ $trainee->certificates->count() }})
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="p-3.5">رقم المسلسل</th>
                        <th class="p-3.5">عنوان البرنامج التدريبي</th>
                        <th class="p-3.5">مجال التدريب</th>
                        <th class="p-3.5 text-center">الساعات</th>
                        <th class="p-3.5">تاريخ الإصدار</th>
                        <th class="p-3.5">تاريخ الانتهاء</th>
                        <th class="p-3.5 text-center">الحالة</th>
                        <th class="p-3.5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trainee->certificates as $cert)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold font-en text-[#006837]">
                                <a href="{{ route('training-certificates.show', $cert) }}" class="hover:underline">
                                    {{ $cert->certificate_serial }}
                                </a>
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                {{ $cert->program_title }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $cert->training_scope }}
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-700 font-en">
                                {{ $cert->hours }} hrs
                            </td>
                            <td class="p-3.5 text-slate-600 font-en">
                                {{ $cert->issue_date->format('d/m/Y') }}
                            </td>
                            <td class="p-3.5 font-en text-slate-600">
                                {{ $cert->expiry_date ? $cert->expiry_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                    {{ $cert->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('training-certificates.show', $cert) }}" class="p-1 text-slate-600 hover:text-[#0E4DA4]" title="عرض">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('training-certificates.pdf', $cert) }}" target="_blank" class="p-1 text-red-600" title="PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">
                                لم تصدر شهادات تدريب لهذا المتدرب بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

