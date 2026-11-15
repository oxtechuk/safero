@extends('layouts.app')

@section('title', 'العملاء والشركات - Customers Directory')

@section('content')
<div class="space-y-4">

    <!-- Odoo Enterprise Control Panel -->
    <x-odoo-control-panel 
        title="العملاء والشركات"
        new-url="{{ route('customers.create') }}"
        new-text="New"
        new-color="bg-[#0E4DA4]"
        search-action="{{ route('customers.index') }}"
        search-value="{{ request('search') }}"
        search-placeholder="البحث بالاسم، جهة الاتصال، الهاتف، البريد، السجل الضريبي..."
        :paginator="$customers"
    >
        <div class="space-y-2 text-xs">
            <span class="block text-[11px] font-bold text-slate-500 uppercase">تجميع وتصفية سريعة</span>
            <div class="flex flex-col gap-1 text-slate-700">
                <a href="{{ route('customers.index') }}" class="p-1.5 rounded hover:bg-slate-50 flex items-center justify-between">
                    <span>جميع العملاء والشركات</span>
                    <span class="font-en text-slate-400">{{ $customers->total() }}</span>
                </a>
            </div>
        </div>
    </x-odoo-control-panel>

    <div class="px-4 sm:px-6 lg:px-8 py-3 space-y-3">

        <!-- Active Filters -->
    @if(request('search'))
        <div class="flex items-center gap-2 text-xs px-1">
            <span class="text-slate-400">الفلاتر النشطة:</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 text-xs">
                بحث: "{{ request('search') }}"
                <a href="{{ route('customers.index') }}" class="hover:text-red-600 font-bold">×</a>
            </span>
            <a href="{{ route('customers.index') }}" class="text-[#0E4DA4] hover:underline text-xs font-semibold mr-1">مسح الكل</a>
        </div>
    @endif

    <!-- 1. IsoOx Data Table View -->
    <div id="IsoOxListView" class="bg-white border border-slate-200 rounded-lg shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-[#F8FAFC] text-slate-600 border-b border-slate-200 select-none">
                    <tr>
                        <th class="w-10 px-3 py-2.5 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-[#0E4DA4] focus:ring-[#0E4DA4] w-3.5 h-3.5 cursor-pointer">
                        </th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Company / Customer</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Contact Person</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Phone</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Email</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700">Address / Site</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Certificates</th>
                        <th class="px-3 py-2.5 font-bold tracking-tight text-slate-700 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/90 transition-colors group">
                            <td class="w-10 px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $customer->id }}" class="rounded border-slate-300 text-[#0E4DA4] focus:ring-[#0E4DA4] w-3.5 h-3.5 cursor-pointer">
                            </td>

                            <!-- Company Name with Avatar -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-[#0E4DA4] flex items-center justify-center font-bold font-en text-xs shrink-0">
                                        {{ mb_substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}" class="font-bold text-slate-900 group-hover:text-[#0E4DA4] transition">
                                            {{ $customer->name }}
                                        </a>
                                        @if($customer->tax_id)
                                            <span class="block text-[10px] text-slate-400 font-en">Tax ID: {{ $customer->tax_id }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Person -->
                            <td class="px-3 py-2.5 text-slate-700">
                                {{ $customer->contact_person ?: '—' }}
                            </td>

                            <!-- Phone -->
                            <td class="px-3 py-2.5 font-en text-slate-600" dir="ltr">
                                {{ $customer->phone ?: '—' }}
                            </td>

                            <!-- Email -->
                            <td class="px-3 py-2.5 font-en text-slate-600">
                                {{ $customer->email ?: '—' }}
                            </td>

                            <!-- Address -->
                            <td class="px-3 py-2.5 text-slate-600 max-w-xs truncate">
                                {{ $customer->address ?: '—' }}
                            </td>

                            <!-- Certificates Count -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                @if($customer->certificates_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E6F2FF] text-[#0E4DA4] border border-blue-200">
                                        {{ $customer->certificates_count }} شهادة
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-400">
                                        0
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 opacity-80 group-hover:opacity-100 transition">
                                    <a href="{{ route('certificates.create', ['customer_id' => $customer->id]) }}" 
                                       class="px-2 py-1 rounded bg-[#E6F2FF] hover:bg-[#0E4DA4] text-[#0E4DA4] hover:text-white font-bold transition text-[11px] flex items-center gap-1"
                                       title="إصدار شهادة فحص لهذا العميل">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>إصدار شهادة</span>
                                    </a>
                                    <a href="{{ route('customers.show', $customer) }}" class="p-1 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="عرض السجل">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="p-1 rounded text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="تعديل">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                لا يوجد عملاء مسجلون حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. IsoOx Kanban View -->
    <div id="IsoOxKanbanView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($customers as $customer)
            <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-[#0E4DA4] flex items-center justify-center font-bold font-en text-sm shrink-0">
                            {{ mb_substr($customer->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">
                                <a href="{{ route('customers.show', $customer) }}" class="hover:text-[#0E4DA4]">
                                    {{ $customer->name }}
                                </a>
                            </h3>
                            <p class="text-[11px] text-slate-400">{{ $customer->contact_person ?: 'مسؤول الشركة' }}</p>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-600 bg-slate-50 p-2.5 rounded border border-slate-100">
                        <div class="flex justify-between">
                            <span class="text-slate-400">الهاتف:</span>
                            <span class="font-mono" dir="ltr">{{ $customer->phone ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">الموقع:</span>
                            <span class="truncate max-w-[180px]">{{ $customer->address ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">الشهادات:</span>
                            <span class="font-bold text-[#0E4DA4]">{{ $customer->certificates_count }} شهادة</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('certificates.create', ['customer_id' => $customer->id]) }}" 
                       class="text-xs font-bold text-[#0E4DA4] hover:underline flex items-center gap-1">
                        + إصدار شهادة فحص
                    </a>
                    <a href="{{ route('customers.show', $customer) }}" class="text-xs text-slate-500 hover:text-slate-800">
                        عرض الملف &larr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-slate-400">
                لا يوجد عملاء
            </div>
        @endforelse
    </div>

    </div>
</div>
@endsection
