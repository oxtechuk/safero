@extends('layouts.app')

@section('title', __('تسجيل سند مصروف جديد'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#0E4DA4]">{{ __('الرئيسية') }}</a>
                <span>/</span>
                <a href="{{ route('expenses.index') }}" class="hover:text-[#0E4DA4]">{{ __('المصروفات') }}</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">{{ __('سند جديد') }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">{{ __('تسجيل سند صرف ونفقة جديدة') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ __('تسجيل نفقات التشغيل والفحص الميداني لحساب الأرباح الحقيقية بدقة') }}</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
            {{ __('عودة للقائمة') }}
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm">
            <span class="font-bold block mb-1">{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('expenses.store') }}" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 text-xs">
        @csrf

        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <span class="text-xs font-bold text-slate-500">{{ __('الرقم المقترح للسند:') }}</span>
            <span class="font-mono font-black text-rose-600 text-sm bg-rose-50 px-2.5 py-0.5 rounded border border-rose-200">{{ $nextNumber }}</span>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">{{ __('بيان المصروف (Description)') }} <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="{{ __('مثال: وقود وانتقالات فحص أوناش الإسكندرية') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 outline-none font-semibold text-xs sm:text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('التصنيف (Category)') }} <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-white outline-none font-bold">
                    @foreach($categories as $k => $c)
                        <option value="{{ $k }}" {{ old('category') === $k ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? $c['en'] : $c['ar'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('المبلغ (ج.م) *') }}</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" min="0.01" required placeholder="0.00" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-mono font-black text-sm text-rose-600 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('تاريخ الصرف') }} <span class="text-red-500">*</span></label>
                <input type="date" name="expense_date" value="{{ old('expense_date', $today) }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-mono outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('طريقة الدفع') }} <span class="text-red-500">*</span></label>
                <select name="payment_method" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-white outline-none">
                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('نقداً (كاش)') }}</option>
                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('تحويل بنكي') }}</option>
                    <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>{{ __('شيك') }}</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('المدفوع له (المستلم / الجهة)') }}</label>
                <input type="text" name="paid_to" value="{{ old('paid_to') }}" placeholder="{{ __('اسم المفتش أو الشركة أو المحطة...') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('رقم الإيصال / الفاتورة الخارجية') }}</label>
                <input type="text" name="reference_no" value="{{ old('reference_no') }}" placeholder="REC-00123" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-mono outline-none">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">{{ __('ملاحظات وتفاصيل إضافية') }}</label>
            <textarea name="notes" rows="3" placeholder="{{ __('أي تفاصيل تتعلق بأمر الفحص أو المحطة...') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold transition">
                {{ __('إلغاء') }}
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-md transition cursor-pointer">
                {{ __('حفظ واعتماد المصروف') }}
            </button>
        </div>

    </form>

</div>
@endsection
