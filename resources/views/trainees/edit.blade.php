@extends('layouts.app')

@section('title', 'تعديل بيانات المتدرب - '.$trainee->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#0E4DA4]">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('trainees.index') }}" class="hover:text-[#0E4DA4]">سجل المتدربين</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">تعديل</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">تعديل بيانات المتدرب</h1>
        </div>
        <a href="{{ route('trainees.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
            عودة
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm">
            <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('trainees.update', $trainee) }}" class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
                اسم المتدرب بالكامل (Certificate Holder) <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $trainee->name) }}" 
                   required 
                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none text-xs sm:text-sm font-semibold">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    الشركة / جهة العمل (Company)
                </label>
                <input type="text" 
                       name="company" 
                       value="{{ old('company', $trainee->company) }}" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    المسمى الوظيفي (Job Title)
                </label>
                <input type="text" 
                       name="job_title" 
                       value="{{ old('job_title', $trainee->job_title) }}" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    الرقم القومي / جواز السفر
                </label>
                <input type="text" 
                       name="national_id" 
                       value="{{ old('national_id', $trainee->national_id) }}" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    رقم الهاتف (Phone)
                </label>
                <input type="text" 
                       name="phone" 
                       value="{{ old('phone', $trainee->phone) }}" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-mono" dir="ltr">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
                البريد الإلكتروني (Email)
            </label>
            <input type="email" 
                   name="email" 
                   value="{{ old('email', $trainee->email) }}" 
                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs sm:text-sm font-mono" dir="ltr">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
                ملاحظات إضافية (Notes)
            </label>
            <textarea name="notes" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-[#0E4DA4] outline-none text-xs">{{ old('notes', $trainee->notes) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <a href="{{ route('trainees.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs sm:text-sm">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-xs sm:text-sm shadow-sm transition">
                حفظ التعديلات
            </button>
        </div>

    </form>

</div>
@endsection

