@extends('layouts.app')

@section('title', __('فاتورة ضريبية معتمدة: ') . $invoice->invoice_number)
@section('hide_bottom_nav', '1')

@section('content')
<div class="space-y-4 pb-12 print:p-0 print:m-0 print:space-y-0">

    <!-- ================= SCREEN-ONLY ACTIONS TOOLBAR ================= -->
    <div class="print:hidden no-print bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="max-w-5xl mx-auto flex items-center justify-between flex-wrap gap-3">
            
            <!-- Left Info & Navigation -->
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.index') }}" 
                   class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition shadow-2xs flex items-center justify-center"
                   title="{{ __('الرجوع لسجل الفواتير') }}">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-black font-mono bg-[#0E4DA4]/10 text-[#0E4DA4] border border-[#0E4DA4]/20">
                            {{ $invoice->invoice_number }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">{{ __('معاينة وطباعة الفاتورة الضريبية المعتمدة') }}</h1>
                        
                        @if($invoice->payment_status === 'paid')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                {{ __('مدفوع بالكامل') }}
                            </span>
                        @elseif($invoice->payment_status === 'partial')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
                                {{ __('سداد جزئي') }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                                {{ __('مستحقة السداد') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ __('العميل:') }} <strong class="text-slate-800">{{ $invoice->customer->name }}</strong> 
                        &bull; {{ __('تاريخ الإصدار:') }} <span class="font-mono">{{ $invoice->invoice_date->format('d/m/Y') }}</span>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.edit', $invoice) }}" 
                   class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>{{ __('تعديل الفاتورة') }}</span>
                </a>

                <button type="button" onclick="window.print()" 
                        class="px-5 py-2 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs sm:text-sm font-extrabold transition flex items-center gap-2 shadow-md hover:shadow-lg cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>{{ __('طباعة الفاتورة (A4 Print / PDF)') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= THE EXECUTIVE A4 INVOICE SHEET ================= -->
    <div class="print:p-0 print:m-0 flex justify-center py-4 sm:py-8 px-2 sm:px-4 bg-slate-200/50 print:bg-white min-h-screen">
        
        <div class="invoice-sheet bg-white border border-slate-300 print:border-0 rounded-2xl print:rounded-none p-6 sm:p-10 shadow-xl print:shadow-none w-full max-w-[210mm] relative overflow-hidden text-slate-800 box-border">
            
            <!-- Top Elegant Accent Stripe (Corporate Brand Signature) -->
            <div class="absolute top-0 left-0 right-0 h-2.5 bg-gradient-to-r from-[#0E4DA4] via-[#0284c7] to-[#006837]"></div>

            <!-- ================= 1. HEADER & ACCREDITATION LETTERHEAD ================= -->
            <div class="flex items-start justify-between flex-wrap gap-4 pb-5 border-b-2 border-slate-800">
                
                <!-- Right: Company Info & Official Logo -->
                <div class="flex items-start gap-4">
                    <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" 
                         alt="{{ setting('company_name_en', 'Safero') }}" 
                         class="h-18 w-auto object-contain shrink-0 border border-slate-100 rounded-lg p-1 bg-white shadow-2xs">
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-snug">
                            {{ setting('company_name_ar', 'شركة سافيرو للخدمات والاستشارات الهندسية والتفتيش') }}
                        </h2>
                       
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-blue-50 border border-blue-200/80 text-[10.5px] font-bold text-[#0E4DA4] mt-1.5">
                            <svg class="w-3.5 h-3.5 text-[#006837]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ __('هيئة تفتيش هندسي معتمدة طبقاً للمواصفة ISO/IEC 17020:2012') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-[10.5px] text-slate-500 font-mono mt-1 flex-wrap">
                            <span>{{ __('ب.ض:') }} <strong class="text-slate-800">{{ setting('company_tax_id', '714-220-918') }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ __('س.ت:') }} <strong class="text-slate-800">{{ setting('company_cr_no', '182941') }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ __('الترخيص:') }} <strong class="text-slate-800">EGAC / LOLER / ASME</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Left: Title, Invoice No & Digital QR Verification -->
                <div class="flex items-start gap-4">
                    <!-- Dynamic Live QR Code -->
                    @if(isset($qrCodeSvg))
                        <div class="flex flex-col items-center bg-slate-50 p-2 rounded-xl border border-slate-200 shrink-0 shadow-2xs">
                            <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="Verify QR" class="w-16 h-16 object-contain">
                            <span class="text-[7.5px] font-black text-slate-500 font-mono mt-1 uppercase tracking-wider text-center">
                                {{ __('امسح للتحقق الرقمي') }}<br>SCAN TO VERIFY
                            </span>
                        </div>
                    @endif

                    <div class="text-left rtl:text-right space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#0E4DA4] text-white text-xs font-black font-mono tracking-wider shadow-2xs">
                            <span>TAX INVOICE</span>
                            <span>|</span>
                            <span>{{ __('فاتورة ضريبية معتمدة') }}</span>
                        </div>

                        <div class="font-mono text-xl sm:text-2xl font-black text-[#0E4DA4] tracking-tight">
                            # {{ $invoice->invoice_number }}
                        </div>

                        <div class="text-xs text-slate-600 font-mono">
                            <span class="font-bold text-slate-400 uppercase">{{ __('تاريخ الإصدار / Date:') }}</span> 
                            <strong class="text-slate-900">{{ $invoice->invoice_date->format('Y-m-d') }}</strong>
                        </div>

                        <div class="text-xs text-slate-600 font-mono">
                            <span class="font-bold text-slate-400 uppercase">{{ __('الاستحقاق / Due:') }}</span> 
                            <strong class="text-slate-900">{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : __('عند الاستلام') }}</strong>
                        </div>

                        <div class="pt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-black uppercase font-mono {{ $invoice->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($invoice->payment_status === 'partial' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-rose-100 text-rose-800 border border-rose-300') }}">
                                @if($invoice->payment_status === 'paid')
                                    &#10003; {{ __('مدفوع بالكامل / PAID IN FULL') }}
                                @elseif($invoice->payment_status === 'partial')
                                    &#9684; {{ __('سداد جزئي / PARTIALLY PAID') }}
                                @else
                                    &#9888; {{ __('مستحقة السداد / PAYMENT DUE') }}
                                @endif
                            </span>
                        </div>
                    </div>

                </div>

            </div>


            <!-- ================= 2. CLIENT & BILLING INFORMATION CARDS ================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-5">
                
                <!-- Card 1: Billed To / Client -->
                <div class="bg-slate-50/90 rounded-xl p-4 border border-slate-200 text-right space-y-1">
                    <div class="flex items-center justify-between pb-1 mb-1 border-b border-slate-200">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>{{ __('فاتورة إلى (BILLED TO / CLIENT)') }}</span>
                        </span>
                        <span class="text-[9px] font-mono text-slate-400">ID: #{{ $invoice->customer->id }}</span>
                    </div>

                    <h3 class="text-sm sm:text-base font-black text-slate-900 leading-snug">
                        {{ $invoice->customer->name }}
                    </h3>

                    @if($invoice->customer->tax_id)
                        <div class="text-xs text-slate-700 font-mono">
                            <span class="text-slate-400">{{ __('البطاقة الضريبية / Tax ID:') }}</span> 
                            <strong>{{ $invoice->customer->tax_id }}</strong>
                        </div>
                    @endif

                    @if($invoice->customer->address)
                        <div class="text-xs text-slate-600 flex items-start gap-1">
                            <span class="text-slate-400 shrink-0">{{ __('العنوان / Address:') }}</span>
                            <span>{{ $invoice->customer->address }}</span>
                        </div>
                    @endif

                    @if($invoice->customer->phone)
                        <div class="text-xs text-slate-600 font-mono">
                            <span class="text-slate-400">{{ __('الهاتف / Phone:') }}</span> 
                            <span dir="ltr">{{ $invoice->customer->phone }}</span>
                        </div>
                    @endif
                </div>

                <!-- Card 2: Payment & Transaction Details -->
                <div class="bg-slate-50/90 rounded-xl p-4 border border-slate-200 text-right flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-1 mb-1 border-b border-slate-200">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-[#006837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>{{ __('شروط السداد والتحصيل (PAYMENT TERMS)') }}</span>
                            </span>
                            <span class="text-[9px] font-mono text-slate-400">{{ __('مطالبة رسمية') }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-700 mt-2">
                            <div>
                                <span class="text-slate-400 block text-[10px]">{{ __('طريقة السداد / Method:') }}</span>
                                <strong class="text-slate-900">
                                    {{ match($invoice->payment_method) {
                                        'cash' => __('نقداً (Cash)'),
                                        'bank_transfer' => __('تحويل بنكي (Bank Transfer)'),
                                        'cheque' => __('شيك مصرفي (Cheque)'),
                                        default => $invoice->payment_method ?: __('نقداً (Cash)')
                                    } }}
                                </strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">{{ __('العملة / Currency:') }}</span>
                                <strong class="text-slate-900 font-mono">{{ __('الجنيه المصري (EGP)') }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">{{ __('مرجع الفحص / Inspection Ref:') }}</span>
                                <strong class="text-slate-900 font-mono">INSP-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">{{ __('محرر الفاتورة / Issued By:') }}</span>
                                <strong class="text-slate-900">{{ $invoice->creator ? $invoice->creator->name : __('إدارة الحسابات والاعتماد') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200 mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-500">{{ __('حالة المطالبة المالية:') }}</span>
                        <span class="font-black {{ $invoice->payment_status === 'paid' ? 'text-emerald-700' : ($invoice->payment_status === 'partial' ? 'text-amber-700' : 'text-rose-700') }}">
                            {{ match($invoice->payment_status) {
                                'paid' => __('خالصة ومسددة بالكامل'),
                                'partial' => __('مسددة جزئياً'),
                                default => __('مستحقة السداد')
                            } }}
                        </span>
                    </div>
                </div>

            </div>


            <!-- ================= 3. INVOICE ITEMS & INSPECTION TABLE ================= -->
            <div class="border border-slate-300 rounded-xl overflow-hidden my-5">
                <table class="w-full text-right text-xs border-collapse">
                    <thead class="bg-[#0E4DA4] text-white font-bold select-none">
                        <tr>
                            <th class="py-3 px-3 w-10 text-center text-blue-200">#</th>
                            <th class="py-3 px-4">{{ __('بيان خدمة الفحص / الشهادة الهندسية المعتمدة (Inspection & Service Description)') }}</th>
                            <th class="py-3 px-3 text-center w-24">{{ __('الكمية (Qty)') }}</th>
                            <th class="py-3 px-4 text-left rtl:text-left w-36">{{ __('سعر الوحدة (Unit)') }}</th>
                            <th class="py-3 px-4 text-left rtl:text-left w-36">{{ __('الإجمالي (Total EGP)') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @forelse($invoice->items as $idx => $item)
                            <tr class="{{ $loop->even ? 'bg-slate-50/60' : 'bg-white' }} hover:bg-blue-50/20 transition">
                                <td class="py-3 px-3 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-black text-slate-900 text-xs sm:text-sm">{{ $item->description }}</div>
                                    @if($item->certificate)
                                        <div class="text-[11px] text-[#0E4DA4] font-mono mt-1 flex items-center gap-1.5 flex-wrap">
                                            <span class="px-1.5 py-0.5 rounded bg-blue-50 border border-blue-200 font-bold">
                                                {{ $item->certificate->certificate_number }}
                                            </span>
                                            <span class="text-slate-600 font-normal">
                                                ({{ $item->certificate->equipment_type }} &bull; SWL: <strong>{{ $item->certificate->swl }}</strong>)
                                            </span>
                                            <span class="text-[10px] px-1 py-0.2 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                ISO/IEC 17020 Certified
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-sm">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 text-left rtl:text-left font-mono font-bold text-slate-700">
                                    {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="py-3 px-4 text-left rtl:text-left font-mono font-black text-slate-900 text-sm">
                                    {{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 text-xs">
                                    {{ __('لا توجد بنود مسجلة في هذه الفاتورة') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <!-- ================= 4. TOTALS, TAFQEET & BANK DETAILS ================= -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start pt-1">
                
                <!-- Left: Payment Notes, Terms & Banking Details (7 cols) -->
                <div class="md:col-span-7 space-y-3.5 text-right">
                    
                    <!-- Tafqeet (Amount in Words) Banner -->
                    <div class="bg-blue-50/70 border border-blue-200/90 rounded-xl p-3 text-xs">
                        <span class="text-[10px] font-bold text-[#0E4DA4] uppercase tracking-wider block mb-0.5">
                            {{ __('المبلغ الإجمالي بالحروف والتفقيط المعتمد:') }}
                        </span>
                        <div class="font-extrabold text-[#0E4DA4] text-xs sm:text-sm">
                            {{ \App\Helpers\Tafqeet::inArabic($invoice->total_amount) }}
                        </div>
                    </div>

                    <!-- Bank Wire Details -->
                    <div class="bg-slate-50/90 p-3.5 rounded-xl border border-slate-200 space-y-1.5 text-xs">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5 pb-1 border-b border-slate-200">
                            <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>{{ __('بيانات التحويل البنكي والحساب الرسمي (Bank Wire Details):') }}</span>
                        </span>
                        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 font-mono">
                            <div>{{ __('البنك:') }} <strong class="text-slate-800">{{ setting('bank_name', 'البنك الأهلي المصري (NBE)') }}</strong></div>
                            <div>{{ __('كود السويفت:') }} <strong class="text-slate-800">{{ setting('bank_swift', 'NBEGEGCX') }}</strong></div>
                            <div class="col-span-2">{{ __('اسم الحساب:') }} <strong class="text-slate-800">{{ setting('bank_account_name', 'شركة سافيرو للخدمات والاستشارات الهندسية') }}</strong></div>
                            <div class="col-span-2">{{ __('رقم الآيبان (IBAN):') }} <strong class="text-slate-900">{{ setting('bank_iban', 'EG940003000000000123456789001') }}</strong></div>
                        </div>
                    </div>

                    <!-- Terms & Notes -->
                    <div class="bg-slate-50/70 p-3 rounded-xl border border-slate-200 space-y-1 text-xs text-slate-600">
                        <div class="font-bold text-slate-800 text-[11px]">{{ __('ملاحظات وشروط الفحص والاعتماد:') }}</div>
                        <p class="text-[11px] leading-relaxed">
                            {{ $invoice->notes ?: __('شكراً لتعاملكم مع شركة سافيرو لخدمات الفحص والاعتماد الهندسي والمعايرة.') }}
                        </p>
                        <p class="text-[10.5px] text-slate-500 leading-normal">
                            {{ $invoice->terms ?: __('تعتبر شهادات وتقارير الفحص سارية ونافذة فور استيفاء شروط السداد والاعتماد الفني، وصلاحية هذه الفاتورة 30 يوماً من تاريخ الإصدار.') }}
                        </p>
                    </div>

                    <!-- Signatures & Official Stamp -->
                    <div class="pt-2 flex items-center justify-between gap-4 text-center">
                        <div class="flex-1 p-3 rounded-xl border border-dashed border-slate-300 flex flex-col items-center justify-center min-h-[90px] bg-slate-50/40">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">{{ __('توقيع المستلم / العميل') }}</span>
                            <span class="text-[8.5px] text-slate-400 mt-7 block">{{ __('الاستلام والموافقة على الفحص') }}</span>
                        </div>
                        
                        <div class="flex-1 p-3 rounded-xl border border-dashed border-[#0E4DA4]/50 flex flex-col items-center justify-center min-h-[90px] bg-blue-50/20 relative">
                            <span class="text-[10px] font-bold text-[#0E4DA4] uppercase">{{ __('إدارة الحسابات والاعتماد الفني') }}</span>
                            
                            <!-- Official Digital Stamp Mark -->
                            <div class="mt-1 px-3 py-1 rounded-full border-2 border-[#0E4DA4]/60 text-[#0E4DA4] text-[9.5px] font-black rotate-[-6deg] bg-white/80 shadow-2xs">
                                &#9733; {{ __('معتمد - شركة سافيرو') }} &#9733;
                            </div>
                            <span class="text-[8px] font-mono text-slate-400 mt-1">ISO 17020 ACCREDITED</span>
                        </div>
                    </div>

                </div>

                <!-- Right: Financial Breakdown & Grand Total (5 cols) -->
                <div class="md:col-span-5 bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-300 space-y-2.5 text-xs text-right">
                    
                    <div class="flex justify-between items-center text-slate-700 pb-1 border-b border-slate-200">
                        <span class="font-bold">{{ __('المجموع الفرعي (Subtotal):') }}</span>
                        <span class="font-mono font-bold text-slate-900 text-sm">{{ number_format($invoice->subtotal, 2) }} {{ __('ج.م') }}</span>
                    </div>

                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between items-center text-amber-700 bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200">
                            <span class="font-bold">
                                {{ __('الخصم الممنوح') }} ({{ $invoice->discount_type === 'percentage' ? $invoice->discount_value.'%' : __('مبلغ ثابت') }}):
                            </span>
                            <span class="font-mono font-bold">-{{ number_format($invoice->discount_amount, 2) }} {{ __('ج.م') }}</span>
                        </div>
                    @endif

                    @if($invoice->tax_amount > 0)
                        <div class="flex justify-between items-center text-slate-700 px-1">
                            <span class="font-bold">{{ __('ضريبة القيمة المضافة') }} (VAT {{ $invoice->tax_rate }}%):</span>
                            <span class="font-mono font-bold text-slate-900">+{{ number_format($invoice->tax_amount, 2) }} {{ __('ج.م') }}</span>
                        </div>
                    @endif

                    <!-- Executive Grand Total Banner -->
                    <div class="rounded-xl bg-[#0E4DA4] text-white p-3.5 flex justify-between items-center shadow-sm">
                        <div>
                            <span class="text-[10px] text-blue-200 block uppercase font-mono tracking-wider">GRAND TOTAL</span>
                            <span class="text-xs sm:text-sm font-black">{{ __('صافي إجمالي الفاتورة:') }}</span>
                        </div>
                        <div class="font-mono text-xl sm:text-2xl font-black text-white">
                            {{ number_format($invoice->total_amount, 2) }} <span class="text-xs font-bold font-sans">{{ __('ج.م') }}</span>
                        </div>
                    </div>

                    <!-- Paid Amount -->
                    <div class="flex justify-between items-center text-emerald-800 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-200">
                        <span class="font-bold">{{ __('المبلغ المدفوع (Paid):') }}</span>
                        <span class="font-mono font-black text-sm">{{ number_format($invoice->paid_amount, 2) }} {{ __('ج.م') }}</span>
                    </div>

                    <!-- Remaining Balance Due -->
                    <div class="flex justify-between items-center px-3 py-2.5 rounded-xl border {{ $invoice->remaining_balance > 0 ? 'bg-rose-50 text-rose-800 border-rose-300' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                        <span class="font-black">{{ __('المتبقي المستحق (Balance Due):') }}</span>
                        <span class="font-mono font-black text-base">{{ number_format($invoice->remaining_balance, 2) }} {{ __('ج.م') }}</span>
                    </div>

                </div>

            </div>


            <!-- ================= 5. OFFICIAL FOOTER & VERIFICATION METADATA ================= -->
            <div class="mt-8 pt-4 border-t-2 border-slate-200 text-center text-[10px] text-slate-500 space-y-1">
                <p class="font-bold text-slate-800">
                    {{ setting('company_address', 'المقر الرئيسي: الإسكندرية، جمهورية مصر العربية') }} 
                    &bull; {{ __('هاتف / واتساب:') }} <span class="font-mono">{{ setting('company_phone', '01508367666') }}</span> 
                    &bull; {{ __('البريد الإلكتروني:') }} <span class="font-mono">{{ setting('company_email', 'info@safero-eg.com') }}</span>
                </p>
                <p class="text-slate-400 font-mono text-[9px]">
                    Official Engineering Tax Invoice &bull; ISO/IEC 17020 Certified Body &bull; Generated electronically by Safero ERP &bull; {{ setting('company_website', 'www.safero-eg.com') }}
                </p>
            </div>

        </div>

    </div>

</div>

<!-- Strict Print Stylesheet to enforce single clean A4 Sheet without Web UI -->
<style>
@media print {
    /* Hide EVERY web interface element */
    header, 
    nav, 
    footer, 
    aside, 
    .print\:hidden, 
    .no-print, 
    #mobile-bottom-bar,
    .menu-item-group,
    .sub-header-bar {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body, html, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    .invoice-sheet {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 8mm 10mm !important;
        margin: 0 auto !important;
        width: 100% !important;
        max-width: 100% !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @page {
        size: A4 portrait;
        margin: 6mm 8mm;
    }
}
</style>
@endsection
