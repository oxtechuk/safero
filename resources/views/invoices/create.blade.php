@extends('layouts.app')

@section('title', 'إنشاء فاتورة فحص جديدة - New Invoice')

@section('content')
<div class="space-y-4" x-data="invoiceBuilder()">

    <!-- Header & Breadcrumb -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0E4DA4] border border-blue-200 font-mono">
                            {{ $nextNumber }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">إنشاء فاتورة وحسابات فحص جديدة</h1>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">إضافة شهادات الفحص للعميل، تحديد الأسعار، احتساب الخصم (% أو ج.م)، وتسجيل المبالغ المدفوعة</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                    إلغاء
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-2">
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm" class="space-y-6">
            @csrf

            <!-- Client & Dates Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-5 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Customer Selection -->
                <div class="md:col-span-1">
                    <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        العميل (Customer) <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required 
                            @change="onCustomerChange($event.target.value)"
                            class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2.5 bg-white">
                        <option value="">-- اختر العميل --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (old('customer_id', $selectedCustomerId) == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->certificates->count() }} شهادة)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Invoice Date -->
                <div>
                    <label for="invoice_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                        تاريخ الفاتورة (Invoice Date) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="invoice_date" id="invoice_date" required value="{{ old('invoice_date', $today) }}"
                           class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                        تاريخ الاستحقاق (Due Date)
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $dueDate) }}"
                           class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-[#0E4DA4] focus:ring-[#0E4DA4] py-2">
                </div>
            </div>

            <!-- Client's Available Certificates Quick-Picker -->
            <div class="bg-blue-50/50 border border-blue-200/80 rounded-2xl p-4 sm:p-5 space-y-3" x-show="customerCertificates.length > 0">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-xs font-extrabold text-[#0E4DA4]">شهادات الفحص المتاحة للعميل (اختر لإضافتها فوراً للفاتورة):</h3>
                    </div>
                    <button type="button" @click="addAllCertificates()" class="text-[11px] font-bold text-[#0E4DA4] hover:underline">
                        + إضافة جميع الشهادات المتاحة
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    <template x-for="cert in customerCertificates" :key="cert.id">
                        <div class="bg-white border border-blue-200 rounded-xl p-2.5 flex items-center justify-between gap-2 shadow-2xs hover:border-[#0E4DA4] transition">
                            <div class="min-w-0">
                                <span class="font-mono text-[11px] font-bold text-[#0E4DA4] block truncate" x-text="cert.certificate_number"></span>
                                <span class="text-[11px] text-slate-700 font-semibold block truncate" x-text="cert.equipment_description"></span>
                                <span class="text-[10px] text-slate-400 font-mono block" x-text="cert.thorough_examination_date"></span>
                            </div>
                            <button type="button" 
                                    @click="addCertificateItem(cert)"
                                    :disabled="isCertAdded(cert.id)"
                                    :class="isCertAdded(cert.id) ? 'bg-slate-100 text-slate-400' : 'bg-blue-600 hover:bg-blue-700 text-white'"
                                    class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition shrink-0">
                                <span x-text="isCertAdded(cert.id) ? 'تمت الإضافة' : '+ إضافة'"></span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <h2 class="text-sm font-extrabold text-slate-900">بنود وشهادات الفحص المفوترة (Invoice Line Items)</h2>
                    </div>

                    <button type="button" @click="addCustomItem()" 
                            class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ إضافة بند خدمة يدوي</span>
                    </button>
                </div>

                <div class="p-4 sm:p-5 overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="text-slate-500 font-bold border-b border-slate-200 pb-2">
                            <tr>
                                <th class="pb-2 w-10 text-center">#</th>
                                <th class="pb-2">وصف الخدمة / شهادة الفحص</th>
                                <th class="pb-2 w-24 text-center">الكمية</th>
                                <th class="pb-2 w-32 text-center">سعر الفحص (ج.م)</th>
                                <th class="pb-2 w-32 text-center">الإجمالي (ج.م)</th>
                                <th class="pb-2 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 text-center font-mono text-slate-400 font-bold" x-text="index + 1"></td>
                                    <td class="py-2.5 pl-2">
                                        <div class="space-y-1">
                                            <input type="text" :name="'items[' + index + '][description]'" 
                                                   x-model="item.description" required
                                                   placeholder="وصف فحص المعدة والشهادة المعتمدة..."
                                                   class="w-full text-xs rounded-lg border-slate-300 py-2">
                                            <input type="hidden" :name="'items[' + index + '][certificate_id]'" :value="item.certificate_id || ''">
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-1 text-center">
                                        <input type="number" :name="'items[' + index + '][quantity]'" 
                                               x-model.number="item.quantity" min="1" required
                                               @input="calculateTotals()"
                                               class="w-20 text-xs text-center font-mono rounded-lg border-slate-300 py-2">
                                    </td>
                                    <td class="py-2.5 px-1 text-center">
                                        <input type="number" step="0.01" :name="'items[' + index + '][unit_price]'" 
                                               x-model.number="item.unit_price" min="0" required
                                               @input="calculateTotals()"
                                               placeholder="0.00"
                                               class="w-28 text-xs text-center font-mono rounded-lg border-slate-300 py-2">
                                    </td>
                                    <td class="py-2.5 px-2 text-center font-mono font-extrabold text-slate-800"
                                        x-text="(item.quantity * item.unit_price).toFixed(2)">
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <button type="button" @click="removeItem(index)" 
                                                class="text-red-400 hover:text-red-600 p-1 transition" title="حذف البند">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financials, Discount & Payment Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Left: Payment details & Notes -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-5 space-y-4">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">تفاصيل السداد وطريقة الدفع</h3>
                    
                    <div>
                        <label for="payment_method" class="block text-xs font-bold text-slate-700 mb-1">طريقة السداد</label>
                        <select name="payment_method" id="payment_method" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                            <option value="cash">نقداً (كاش)</option>
                            <option value="bank_transfer">تحويل بنكي (Bank Transfer)</option>
                            <option value="cheque">شيك بنكي (Cheque)</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">ملاحظات الفاتورة</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="أي شروط أو ملاحظات خاصة بالفاتورة..."
                                  class="w-full text-xs rounded-lg border-slate-300 py-2"></textarea>
                    </div>

                    <div>
                        <label for="terms" class="block text-xs font-bold text-slate-700 mb-1">شروط الدفع والاعتماد</label>
                        <input type="text" name="terms" id="terms" value="تعتبر الشهادات سارية فور استيفاء شروط السداد والاعتماد الفني."
                               class="w-full text-xs rounded-lg border-slate-300 py-2 text-slate-600">
                    </div>
                </div>

                <!-- Right: Financial Breakdown (Live Calculated) -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl shadow-xs p-5 space-y-3.5">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">ملخص الحساب والخصومات</h3>

                    <!-- Subtotal -->
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                        <span>المجموع الفرعي (Subtotal):</span>
                        <span class="font-mono text-sm" x-text="subtotal.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                    </div>

                    <!-- Discount Section (% or Fixed EGP) -->
                    <div class="pt-2 border-t border-slate-200 space-y-2">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-700">نوع الخصم (Discount):</span>
                            
                            <div class="flex items-center gap-1 text-[11px] font-bold">
                                <label class="cursor-pointer px-2 py-1 rounded transition" 
                                       :class="discountType === 'percentage' ? 'bg-[#0E4DA4] text-white' : 'bg-white border text-slate-600'">
                                    <input type="radio" name="discount_type" value="percentage" x-model="discountType" @change="calculateTotals()" class="hidden">
                                    <span>نسبة مئوية (%)</span>
                                </label>
                                <label class="cursor-pointer px-2 py-1 rounded transition" 
                                       :class="discountType === 'fixed' ? 'bg-[#0E4DA4] text-white' : 'bg-white border text-slate-600'">
                                    <input type="radio" name="discount_type" value="fixed" x-model="discountType" @change="calculateTotals()" class="hidden">
                                    <span>مبلغ ثابت (ج.م)</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="number" step="0.01" min="0" name="discount_value" 
                                   x-model.number="discountValue" 
                                   @input="calculateTotals()"
                                   :placeholder="discountType === 'percentage' ? 'مثال: 10%' : 'مثال: 500 ج.م'"
                                   class="w-full text-xs font-mono font-bold rounded-lg border-amber-300 bg-amber-50/30 py-2">
                            <span class="text-xs font-extrabold text-amber-700 shrink-0 font-mono" x-text="'-' + discountAmount.toFixed(2) + ' ج.م'"></span>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-sm font-black text-slate-900">
                        <span>الإجمالي المطلوب (Total Amount):</span>
                        <span class="font-mono text-base text-[#0E4DA4]" x-text="totalAmount.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                    </div>

                    <!-- Amount Paid (دفع كم) -->
                    <div class="pt-2 border-t border-slate-200 space-y-1">
                        <label for="paid_amount" class="block text-xs font-bold text-emerald-800">
                            المبلغ المدفوع كاش / تحويل (دفع كم):
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                                   x-model.number="paidAmount" 
                                   @input="calculateTotals()"
                                   placeholder="0.00"
                                   class="w-full text-xs font-mono font-black text-emerald-900 rounded-lg border-emerald-300 bg-emerald-50/40 py-2.5">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-emerald-700">ج.م</span>
                        </div>
                    </div>

                    <!-- Remaining Balance (المتبقي) & Status Badge -->
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-700 block">المبلغ المتبقي (Balance Due):</span>
                            <span class="text-base font-black font-mono" 
                                  :class="remainingBalance > 0 ? 'text-red-600' : 'text-emerald-600'"
                                  x-text="remainingBalance.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-0.5">حالة الفاتورة:</span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-black inline-block shadow-2xs"
                                  :class="{
                                      'bg-emerald-100 text-emerald-800 border border-emerald-300': paymentStatus === 'paid',
                                      'bg-amber-100 text-amber-800 border border-amber-300': paymentStatus === 'partial',
                                      'bg-red-100 text-red-800 border border-red-300': paymentStatus === 'unpaid'
                                  }"
                                  x-text="paymentStatusLabel">
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" 
                        class="px-8 py-3 rounded-xl bg-[#006837] hover:bg-[#00522c] text-white text-sm font-extrabold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>حفظ وإصدار الفاتورة</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function invoiceBuilder() {
    return {
        customerCertificates: @json($preselectedCertificates),
        discountType: 'percentage',
        discountValue: 0,
        paidAmount: 0,
        subtotal: 0,
        discountAmount: 0,
        totalAmount: 0,
        remainingBalance: 0,
        
        items: [
            { description: 'فحص ومعايرة معدة رفع وفق معايير ISO 17020', certificate_id: null, quantity: 1, unit_price: 1500 }
        ],

        init() {
            this.calculateTotals();
        },

        async onCustomerChange(customerId) {
            if (!customerId) {
                this.customerCertificates = [];
                return;
            }

            try {
                const res = await fetch(`/customers/${customerId}/certificates-json`);
                const data = await res.json();
                if (data.success) {
                    this.customerCertificates = data.certificates || [];
                }
            } catch (e) {
                console.error('Error fetching certificates:', e);
            }
        },

        isCertAdded(certId) {
            return this.items.some(item => item.certificate_id == certId);
        },

        addCertificateItem(cert) {
            if (this.isCertAdded(cert.id)) return;
            
            this.items.push({
                description: `فحص واعتماد: ${cert.equipment_description} - شهادة رقم (${cert.certificate_number})`,
                certificate_id: cert.id,
                quantity: 1,
                unit_price: 1200
            });
            this.calculateTotals();
        },

        addAllCertificates() {
            this.customerCertificates.forEach(cert => {
                if (!this.isCertAdded(cert.id)) {
                    this.addCertificateItem(cert);
                }
            });
        },

        addCustomItem() {
            this.items.push({
                description: '',
                certificate_id: null,
                quantity: 1,
                unit_price: 500
            });
            this.calculateTotals();
        },

        removeItem(index) {
            if (this.items.length <= 1) {
                alert('يجب الإبقاء على بند واحد على الأقل في الفاتورة.');
                return;
            }
            this.items.splice(index, 1);
            this.calculateTotals();
        },

        calculateTotals() {
            this.subtotal = this.items.reduce((sum, item) => sum + ((item.quantity || 1) * (item.unit_price || 0)), 0);
            
            if (this.discountType === 'percentage') {
                this.discountAmount = (this.subtotal * (this.discountValue || 0)) / 100;
            } else {
                this.discountAmount = Math.min(this.subtotal, this.discountValue || 0);
            }

            this.totalAmount = Math.max(0, this.subtotal - this.discountAmount);
            this.remainingBalance = Math.max(0, this.totalAmount - (this.paidAmount || 0));
        },

        get paymentStatus() {
            if (this.paidAmount >= this.totalAmount && this.totalAmount > 0) return 'paid';
            if (this.paidAmount > 0) return 'partial';
            return 'unpaid';
        },

        get paymentStatusLabel() {
            if (this.paymentStatus === 'paid') return 'مدفوع بالكامل';
            if (this.paymentStatus === 'partial') return 'سداد جزئي';
            return 'غير مدفوع (معلق)';
        }
    };
}
</script>
@endsection
