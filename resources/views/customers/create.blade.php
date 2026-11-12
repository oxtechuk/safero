@extends('layouts.app')

@section('title', 'إضافة عميل جديد')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">إضافة عميل / شركة جديدة</h1>
            <p class="text-xs text-slate-500 mt-0.5">تسجيل بيانات العميل لإصدار شهادات الفحص باسمه</p>
        </div>
        <a href="{{ route('customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>العودة للعملاء</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('customers.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    اسم الشركة / العميل بالكامل <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="مثال: Morgan international transport & marine"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_person" class="block text-xs font-bold text-slate-700 mb-1.5">الشخص المسؤول / جهة الاتصال</label>
                    <input type="text" 
                           name="contact_person" 
                           id="contact_person" 
                           value="{{ old('contact_person') }}" 
                           placeholder="مثال: م. أحمد عبد الله"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">
                </div>

                <div>
                    <label for="tax_id" class="block text-xs font-bold text-slate-700 mb-1.5">السجل التجاري / البطاقة الضريبية</label>
                    <input type="text" 
                           name="tax_id" 
                           id="tax_id" 
                           value="{{ old('tax_id') }}" 
                           placeholder="مثال: 100-234-567"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف / الواتساب</label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="+20 100 000 0000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           placeholder="contact@company.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm font-en">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">العنوان وموقع الفحص الافتراضي</label>
                <textarea name="address" 
                          id="address" 
                          rows="2" 
                          placeholder="مثال: Om Zoghio - Alexandria, Egypt"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">{{ old('address') }}</textarea>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات إضافية</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="2" 
                          placeholder="أي تفاصيل فنية خاصة بطبيعة معدات العميل..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-sm">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('customers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#22A447] hover:bg-[#1b8539] text-white font-bold text-xs sm:text-sm shadow-sm transition">
                    حفظ العميل
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

