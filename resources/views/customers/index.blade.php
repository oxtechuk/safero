@extends('layouts.app')

@section('title', 'إدارة العملاء')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">سجل العملاء والشركات</h1>
            <p class="text-xs text-slate-500 mt-0.5">إدارة بيانات عملاء الفحص والمعايرة ومواقع العمل الخاصة بهم</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>إضافة عميل جديد</span>
            </a>
        </div>
    </div>

    <!-- Search Box -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="بحث باسم العميل، الهاتف، البريد أو السجل الضريبي..."
                       class="w-full pr-10 pl-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm">
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0E4DA4] text-white font-bold text-xs sm:text-sm hover:bg-[#0a3a7d] transition">
                بحث
            </button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="px-3 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-4 font-bold">اسم الشركة / العميل</th>
                        <th class="p-4 font-bold">الشخص المسؤول</th>
                        <th class="p-4 font-bold">الهاتف</th>
                        <th class="p-4 font-bold">البريد الإلكتروني</th>
                        <th class="p-4 font-bold">العنوان وموقع الفحص</th>
                        <th class="p-4 font-bold text-center">الشهادات الصادرة</th>
                        <th class="p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-bold text-slate-900">
                                <a href="{{ route('customers.show', $customer) }}" class="text-[#0E4DA4] hover:underline block">
                                    {{ $customer->name }}
                                </a>
                                @if($customer->tax_id)
                                    <span class="text-[10px] text-slate-400 font-en block">ضريبي: {{ $customer->tax_id }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-700">
                                {{ $customer->contact_person ?? '—' }}
                            </td>
                            <td class="p-4 text-slate-600 font-en">
                                {{ $customer->phone ?? '—' }}
                            </td>
                            <td class="p-4 text-slate-600 font-en">
                                {{ $customer->email ?? '—' }}
                            </td>
                            <td class="p-4 text-slate-600 max-w-xs truncate">
                                {{ $customer->address ?? '—' }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-[#E6F2FF] text-[#0E4DA4]">
                                    {{ $customer->certificates_count }} شهادة
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('certificates.create', ['customer_id' => $customer->id]) }}" 
                                       class="p-1.5 rounded-lg bg-[#E8F7EC] hover:bg-[#22A447] text-[#22A447] hover:text-white transition" 
                                       title="إصدار شهادة فحص لهذا العميل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </a>
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-[#E6F2FF] text-slate-700 hover:text-[#0E4DA4] transition" 
                                       title="عرض تفاصيل العميل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-600 transition" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                لا يوجد عملاء مسجلين يطابقون البحث.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

