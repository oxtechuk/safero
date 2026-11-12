@extends('layouts.app')

@section('title', 'تعديل بيانات العميل')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">تعديل بيانات: {{ $customer->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">تحديث معلومات الاتصال وموقع الفحص</p>
        </div>
        <a href="{{ route('customers.show', $customer) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>إلغاء وعودة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    اسم الشركة / العميل بالكامل <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $customer->name) }}" 
                       required 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_person" class="block text-xs font-bold text-slate-700 mb-1.5">الشخص المسؤول / جهة الاتصال</label>
                    <input type="text" 
                           name="contact_person" 
                           id="contact_person" 
                           value="{{ old('contact_person', $customer->contact_person) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
                </div>

                <div>
                    <label for="tax_id" class="block text-xs font-bold text-slate-700 mb-1.5">السجل التجاري / البطاقة الضريبية</label>
                    <input type="text" 
                           name="tax_id" 
                           id="tax_id" 
                           value="{{ old('tax_id', $customer->tax_id) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف / الواتساب</label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone', $customer->phone) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $customer->email) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">العنوان وموقع الفحص</label>
                <textarea name="address" 
                          id="address" 
                          rows="2" 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات إضافية</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="2" 
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">{{ old('notes', $customer->notes) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" 
                        onclick="if(confirm('هل أنت متأكد من حذف هذا العميل؟ سيتم حذف الشهادات المرتبطة به.')) { document.getElementById('delete-form').submit(); }"
                        class="text-xs text-red-600 hover:text-red-800 font-bold">
                    حذف العميل نهائياً
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('customers.show', $customer) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                        إلغاء
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-sm transition">
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" method="POST" action="{{ route('customers.destroy', $customer) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection

