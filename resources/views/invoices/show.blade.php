@extends('layouts.app')

@section('title', 'فاتورة ضريبية معتمدة: '.$invoice->invoice_number)
@section('hide_bottom_nav', '1')

@section('content')
<div class="space-y-4 pb-12">

    <!-- Screen-Only Actions Toolbar (Hidden on print) -->
    <div class="print:hidden bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="max-w-5xl mx-auto flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.index') }}" 
                   class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition"
                   title="الرجوع لسجل الفواتير">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-black font-mono bg-[#0E4DA4]/10 text-[#0E4DA4] border border-[#0E4DA4]/20">
                            {{ $invoice->invoice_number }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">معاينة وطباعة الفاتورة الضريبية</h1>
                        
                        @if($invoice->payment_status === 'paid')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                مدفوع بالكامل
                            </span>
                        @elseif($invoice->payment_status === 'partial')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
                                سداد جزئي
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                                مستحقة السداد
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">العميل: <strong class="text-slate-800">{{ $invoice->customer->name }}</strong> &bull; تاريخ الإصدار: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.edit', $invoice) }}" 
                   class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>تعديل</span>
                </a>
                <button type="button" onclick="window.print()" 
                        class="px-5 py-2 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-xs sm:text-sm font-extrabold transition flex items-center gap-2 shadow-md hover:shadow-lg cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>طباعة الفاتورة (A4 Print)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= THE PROFESSIONAL INVOICE SHEET ================= -->
    <div class="max-w-5xl mx-auto px-2 sm:px-6 py-2 sm:py-6">
        
        <div class="invoice-sheet bg-white border border-slate-300 rounded-2xl p-6 sm:p-10 shadow-lg relative overflow-hidden font-en select-none">
            
            <!-- Top Elegant Accent Stripe -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#0E4DA4] via-[#0284c7] to-[#006837]"></div>

            <!-- ================= 1. HEADER & BRANDING ================= -->
            <div class="flex items-start justify-between flex-wrap gap-6 pb-6 border-b border-slate-200">
                
                <!-- Right: Company Info & Logo -->
                <div class="flex items-start gap-4">
                    <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" 
                         alt="{{ setting('company_name_en', 'Safero') }}" 
                         class="h-16 w-auto object-contain shrink-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                            {{ setting('company_name_ar', 'شركة سافيرو للخدمات والاستشارات الهندسية والتفتيش') }}
                        </h2>
                        <p class="text-xs font-bold text-[#0E4DA4] font-en tracking-wide mt-0.5">
                            {{ setting('company_name_en', 'SAFERO Services & Consultations') }}
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            اعتماد التفتيش والمعايرة الهندسية ISO/IEC 17020 &amp; LOLER Regulations
                        </p>
                        <div class="flex items-center gap-3 text-[10.5px] text-slate-500 font-mono mt-1 flex-wrap">
                            <span>ب.ض: <strong>{{ setting('company_tax_id', '714-220-918') }}</strong></span>
                            <span>&bull;</span>
                            <span>س.ت: <strong>{{ setting('company_cr_no', '182941') }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Left: Invoice Meta Box & Verification QR -->
                <div class="flex items-start gap-4">
                    <!-- QR Code for Live Verification -->
                    @if(isset($qrCodeSvg))
                        <div class="hidden sm:flex flex-col items-center bg-slate-50 p-2 rounded-xl border border-slate-200 shrink-0">
                            <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="Verify QR" class="w-18 h-18 object-contain">
                            <span class="text-[8px] font-bold text-slate-400 font-mono mt-1 uppercase">VERIFY INVOICE</span>
                        </div>
                    @endif

                    <div class="text-left space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#0E4DA4] text-white text-xs font-black font-mono tracking-wider">
                            <span>TAX INVOICE</span>
                            <span>|</span>
                            <span class="text-[10px]">فاتورة ضريبية</span>
                        </div>

                        <div class="font-mono text-xl font-black text-[#0E4DA4] tracking-tight">
                            {{ $invoice->invoice_number }}
                        </div>

                        <div class="text-xs text-slate-600 font-mono">
                            <span class="font-bold text-slate-400 uppercase">Issue Date:</span> 
                            <strong>{{ $invoice->invoice_date->format('Y-m-d') }}</strong>
                        </div>

                        @if($invoice->due_date)
                            <div class="text-xs text-slate-600 font-mono">
                                <span class="font-bold text-slate-400 uppercase">Due Date:</span> 
                                <strong>{{ $invoice->due_date->format('Y-m-d') }}</strong>
                            </div>
                        @endif

                        <div class="pt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-black uppercase font-mono {{ $invoice->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($invoice->payment_status === 'partial' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-rose-100 text-rose-800 border border-rose-300') }}">
                                {{ match($invoice->payment_status) {
                                    'paid' => 'PAID IN FULL',
                                    'partial' => 'PARTIAL PAYMENT',
                                    default => 'PAYMENT DUE'
                                } }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>


            <!-- ================= 2. BILL TO & PAYMENT INFORMATION ================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
                
                <!-- Billed To Customer -->
                <div class="bg-[#F8FAFC] rounded-xl p-4 border border-slate-200/90 text-right">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                        الجهة المفوتر إليها (Billed To):
                    </span>
                    <h3 class="text-sm sm:text-base font-black text-slate-900">
                        {{ $invoice->customer->name }}
                    </h3>
                    @if($invoice->customer->tax_id)
                        <p class="text-xs text-slate-600 font-mono mt-0.5">
                            البطاقة الضريبية: <strong>{{ $invoice->customer->tax_id }}</strong>
                        </p>
                    @endif
                    @if($invoice->customer->address)
                        <p class="text-xs text-slate-600 mt-0.5">
                            العنوان: {{ $invoice->customer->address }}
                        </p>
                    @endif
                    @if($invoice->customer->phone)
                        <p class="text-xs text-slate-500 font-mono mt-0.5">
                            الهاتف: {{ $invoice->customer->phone }}
                        </p>
                    @endif
                </div>

                <!-- Payment Details -->
                <div class="bg-[#F8FAFC] rounded-xl p-4 border border-slate-200/90 text-right flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                            شروط السداد والتحصيل (Payment Terms):
                        </span>
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-700 mt-1">
                            <div>
                                <span class="text-slate-400 block text-[10px]">طريقة السداد:</span>
                                <strong class="text-slate-900">
                                    {{ match($invoice->payment_method) {
                                        'cash' => 'نقداً (Cash)',
                                        'bank_transfer' => 'تحويل بنكي (Bank Transfer)',
                                        'cheque' => 'شيك مصرفي (Cheque)',
                                        default => $invoice->payment_method ?: 'نقداً'
                                    } }}
                                </strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px]">العملة المعاملة:</span>
                                <strong class="text-slate-900 font-mono">الجنيه المصري (EGP)</strong>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200/60 mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-500">حالة المطالبة:</span>
                        <span class="font-extrabold {{ $invoice->payment_status === 'paid' ? 'text-emerald-700' : ($invoice->payment_status === 'partial' ? 'text-amber-700' : 'text-rose-700') }}">
                            {{ match($invoice->payment_status) {
                                'paid' => 'خالصة ومسددة بالكامل',
                                'partial' => 'مسددة جزئياً',
                                default => 'مستحقة السداد'
                            } }}
                        </span>
                    </div>
                </div>

            </div>


            <!-- ================= 3. INVOICE ITEMS TABLE ================= -->
            <div class="border border-slate-200 rounded-xl overflow-hidden my-6">
                <table class="w-full text-right text-xs border-collapse">
                    <thead class="bg-[#0E4DA4] text-white font-bold select-none">
                        <tr>
                            <th class="py-3 px-3 w-10 text-center text-blue-200">#</th>
                            <th class="py-3 px-4">بيان خدمة الفحص / الشهادة الهندسية المعتمدة</th>
                            <th class="py-3 px-3 text-center w-24">الكمية (Qty)</th>
                            <th class="py-3 px-4 text-left w-36">سعر الوحدة (Unit)</th>
                            <th class="py-3 px-4 text-left w-36">الإجمالي (Total EGP)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @foreach($invoice->items as $idx => $item)
                            <tr class="{{ $loop->even ? 'bg-slate-50/50' : 'bg-white' }} hover:bg-blue-50/30 transition">
                                <td class="py-3.5 px-3 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-black text-slate-900 text-xs sm:text-sm">{{ $item->description }}</div>
                                    @if($item->certificate)
                                        <div class="text-[11px] text-[#0E4DA4] font-mono mt-1 flex items-center gap-1.5 flex-wrap">
                                            <span class="px-1.5 py-0.5 rounded bg-blue-50 border border-blue-200 font-bold">
                                                {{ $item->certificate->certificate_number }}
                                            </span>
                                            <span class="text-slate-500 font-normal">({{ $item->certificate->equipment_type }} &bull; SWL: {{ $item->certificate->swl }})</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center font-mono font-bold text-sm">{{ $item->quantity }}</td>
                                <td class="py-3.5 px-4 text-left font-mono font-bold text-slate-700">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-left font-mono font-black text-slate-900 text-sm">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- ================= 4. TOTALS & SUMMARY ================= -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start pt-2">
                
                <!-- Left: Payment Notes & Bank Details (7 cols) -->
                <div class="md:col-span-7 space-y-4 text-right">
                    
                    <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>ملاحظات وشروط الدفع والتحصيل:</span>
                        </span>
                        <p class="text-slate-600 leading-relaxed">
                            {{ $invoice->notes ?: 'شكراً لتعاملكم مع شركة سافيرو لخدمات الفحص والاعتماد الهندسي والتفتيش.' }}
                        </p>
                        <p class="text-[11px] text-slate-500 leading-normal">
                            {{ $invoice->terms ?: 'تعتبر شهادات وتقارير الفحص سارية ونافذة فور استيفاء شروط السداد والاعتماد الفني.' }}
                        </p>
                    </div>

                    <!-- Company Stamp & Authorized Signatures -->
                    <div class="pt-2 flex items-center justify-between gap-4 text-center">
                        <div class="flex-1 p-3 rounded-xl border border-dashed border-slate-300 flex flex-col items-center justify-center min-h-[90px] bg-slate-50/40">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">توقيع المستلم / العميل</span>
                            <span class="text-[9px] text-slate-400 mt-8 block">التوقيع بالاستلام</span>
                        </div>
                        <div class="flex-1 p-3 rounded-xl border border-dashed border-[#0E4DA4]/40 flex flex-col items-center justify-center min-h-[90px] bg-blue-50/20 relative">
                            <span class="text-[10px] font-bold text-[#0E4DA4] uppercase">إدارة الحسابات والاعتماد الفني</span>
                            <div class="w-16 h-12 rounded-full border border-[#0E4DA4]/30 flex items-center justify-center text-[#0E4DA4] text-[9px] font-bold rotate-[-12deg] mt-1">
                                ختم الشركة
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Financial Breakdown (5 cols) -->
                <div class="md:col-span-5 bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/90 space-y-2.5 text-xs">
                    
                    <div class="flex justify-between items-center text-slate-600">
                        <span class="font-bold">المجموع الفرعي (Subtotal):</span>
                        <span class="font-mono font-bold text-slate-900">{{ number_format($invoice->subtotal, 2) }} ج.م</span>
                    </div>

                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between items-center text-amber-700">
                            <span class="font-bold">الخصم الممنوح ({{ $invoice->discount_type === 'percentage' ? $invoice->discount_value.'%' : 'مبلغ ثابت' }}):</span>
                            <span class="font-mono font-bold">-{{ number_format($invoice->discount_amount, 2) }} ج.م</span>
                        </div>
                    @endif

                    @if($invoice->tax_amount > 0)
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="font-bold">ضريبة القيمة المضافة (VAT {{ $invoice->tax_rate }}%):</span>
                            <span class="font-mono font-bold text-slate-900">+{{ number_format($invoice->tax_amount, 2) }} ج.م</span>
                        </div>
                    @endif

                    <!-- Grand Total Banner -->
                    <div class="rounded-xl bg-[#0E4DA4] text-white p-3 flex justify-between items-center shadow-xs">
                        <div>
                            <span class="text-[11px] text-blue-200 block uppercase font-mono tracking-wider">GRAND TOTAL</span>
                            <span class="text-xs font-black">إجمالي الفاتورة الصافي:</span>
                        </div>
                        <div class="font-mono text-lg sm:text-xl font-black">
                            {{ number_format($invoice->total_amount, 2) }} <span class="text-xs font-bold font-sans">ج.م</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-emerald-800 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-200">
                        <span class="font-bold">المبلغ المدفوع (Paid):</span>
                        <span class="font-mono font-black text-sm">{{ number_format($invoice->paid_amount, 2) }} ج.م</span>
                    </div>

                    <div class="flex justify-between items-center px-3 py-2 rounded-xl border {{ $invoice->remaining_balance > 0 ? 'bg-rose-50 text-rose-800 border-rose-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                        <span class="font-black">المتبقي المستحق (Balance):</span>
                        <span class="font-mono font-black text-sm">{{ number_format($invoice->remaining_balance, 2) }} ج.م</span>
                    </div>

                </div>

            </div>


            <!-- ================= 5. FOOTER & VERIFICATION ================= -->
            <div class="mt-8 pt-4 border-t border-slate-200 text-center text-[10.5px] text-slate-500 space-y-1">
                <p class="font-bold text-slate-700">
                    {{ setting('company_address', 'المقر الرئيسي: الإسكندرية، جمهورية مصر العربية') }} &bull; هاتف: {{ setting('company_phone', '01508367666') }} &bull; البريد: {{ setting('company_email', 'info@safero-eg.com') }}
                </p>
                <p class="text-slate-400 font-mono text-[9.5px]">
                    This official invoice was electronically generated by Safero ERP &mdash; ISO/IEC 17020 Certified Body &mdash; {{ setting('company_website', 'www.safero-eg.com') }}
                </p>
            </div>

        </div>

    </div>

</div>

<!-- Print Styles for Desktop Paper & PDF Export -->
<style>
@media print {
    /* Hide all system layout elements */
    header, footer, nav, aside, .print\:hidden, #main-sidebar, .menu-item-group, .border-b {
        display: none !important;
    }
    body, html, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .invoice-sheet {
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0 !important;
        padding: 10mm 12mm !important;
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
        margin: 8mm 10mm;
    }
}
</style>
@endsection
