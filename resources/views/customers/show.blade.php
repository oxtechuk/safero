@extends('layouts.app')

@section('title', __('بيانات العميل') . ' - ' . $customer->name)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('customers.index') }}" class="hover:text-[#0E4DA4]">{{ __('العملاء') }}</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">{{ __('ملف العميل') }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">{{ $customer->name }}</h1>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('inspections.start', ['customer_id' => $customer->id]) }}" 
               class="px-3.5 py-2 rounded-xl bg-[#006837] hover:bg-[#00522c] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>{{ __('بدء فحص ميداني') }}</span>
            </a>
            <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" 
               class="px-3.5 py-2 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                <span>{{ __('إنشاء فاتورة') }}</span>
            </a>
            <a href="{{ route('certificates.create', ['customer_id' => $customer->id]) }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <span>{{ __('شهادة فحص مباشرة') }}</span>
            </a>
            <a href="{{ route('customers.edit', $customer) }}" 
               class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition flex items-center gap-1.5">
                <span>{{ __('تعديل') }}</span>
            </a>
        </div>
    </div>

    <!-- Customer Info Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-7">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100">
            {{ __('المعلومات الأساسية وموقع الفحص') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
            <div>
                <span class="text-slate-400 block mb-1">{{ __('الشخص المسؤول') }}</span>
                <span class="font-bold text-slate-800 text-sm block">{{ $customer->contact_person ?? __('غير محدد') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">{{ __('الهاتف / الواتساب') }}</span>
                <span class="font-bold text-slate-800 text-sm block font-en">{{ $customer->phone ?? __('غير محدد') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">{{ __('البريد الإلكتروني') }}</span>
                <span class="font-bold text-slate-800 text-sm block font-en">{{ $customer->email ?? __('غير محدد') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">{{ __('السجل التجاري / البطاقة الضريبية') }}</span>
                <span class="font-bold text-slate-800 text-sm block font-en">{{ $customer->tax_id ?? __('غير محدد') }}</span>
            </div>
            <div class="md:col-span-2">
                <span class="text-slate-400 block mb-1">{{ __('عنوان الفحص وموقع العمل') }}</span>
                <span class="font-bold text-slate-800 text-sm block">{{ $customer->address ?? __('غير محدد') }}</span>
            </div>
        </div>

        @if($customer->notes)
            <div class="mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-slate-400 block mb-1">{{ __('ملاحظات فنية:') }}</span>
                <p class="text-slate-700 leading-relaxed">{{ $customer->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Customer Certificates -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">{{ __('سجل شهادات الفحص الصادرة للعميل') }}</h3>
                <p class="text-xs text-slate-500">{{ __('كافة تقارير الفحص الشامل الصادرة لمعدات هذا العميل') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#E6F2FF] text-[#0E4DA4]">
                {{ $customer->certificates->count() }} {{ __('شهادة') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4 font-bold">{{ __('رقم الشهادة') }}</th>
                        <th class="p-4 font-bold">{{ __('المعدة المفحوصة') }}</th>
                        <th class="p-4 font-bold">{{ __('الحمل الآمن (SWL)') }}</th>
                        <th class="p-4 font-bold">{{ __('الكمية') }}</th>
                        <th class="p-4 font-bold">{{ __('تاريخ الفحص') }}</th>
                        <th class="p-4 font-bold">{{ __('تاريخ الاستحقاق') }}</th>
                        <th class="p-4 font-bold">{{ __('الحالة') }}</th>
                        <th class="p-4 font-bold text-center">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->certificates as $cert)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-bold text-[#0E4DA4] font-en">
                                <a href="{{ route('certificates.show', $cert) }}" class="hover:underline">
                                    {{ $cert->certificate_number }}
                                </a>
                            </td>
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $cert->equipment_description }}
                            </td>
                            <td class="p-4 font-bold text-slate-900 font-en">
                                {{ $cert->swl }}
                            </td>
                            <td class="p-4 font-en">
                                {{ $cert->quantity }}
                            </td>
                            <td class="p-4 text-slate-600 font-en">
                                {{ $cert->thorough_examination_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 text-slate-600 font-en">
                                {{ $cert->next_examination_due_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4">
                                @if($cert->is_safe_to_operate)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#E8F7EC] text-[#22A447]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                                        {{ __('مقبول') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        {{ __('مرفوض') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('certificates.show', $cert) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-[#E6F2FF] text-slate-700 hover:text-[#0E4DA4] transition" 
                                       title="{{ __('معاينة') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('certificates.pdf', $cert) }}" 
                                       target="_blank"
                                       class="p-1.5 rounded-lg bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white transition" 
                                       title="{{ __('تحميل PDF') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                {{ __('لم تصدر أي شهادات فحص لهذا العميل حتى الآن.') }}
                                <a href="{{ route('certificates.create', ['customer_id' => $customer->id]) }}" class="text-[#0E4DA4] font-bold hover:underline mr-1">
                                    {{ __('اضغط هنا لإصدار أول شهادة') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
