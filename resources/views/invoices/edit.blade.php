@extends('layouts.app')

@section('title', 'تعديل الفاتورة: '.$invoice->invoice_number)

@section('content')
<div class="space-y-4" x-data="invoiceEditor()">

    <!-- Header & Breadcrumb -->
    <div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3.5 shadow-2xs">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.show', $invoice) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                            {{ $invoice->invoice_number }}
                        </span>
                        <h1 class="text-base sm:text-lg font-black text-slate-900">تعديل الفاتورة وبيانات السداد</h1>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.show', $invoice) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                    إلغاء
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-2">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Client & Dates Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-5 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label for="customer_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        العميل (Customer) <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required 
                            class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2.5 bg-white">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (old('customer_id', $invoice->customer_id) == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="invoice_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                        تاريخ الفاتورة <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="invoice_date" id="invoice_date" required 
                           value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                           class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                </div>

                <div>
                    <label for="due_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                        تاريخ الاستحقاق
                    </label>
                    <input type="date" name="due_date" id="due_date" 
                           value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}"
                           class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between flex-wrap gap-2">
                    <h2 class="text-sm font-extrabold text-slate-900">بنود الفاتورة والشهادات</h2>
                    <button type="button" @click="addCustomItem()" 
                            class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1">
                        + إضافة بند جديد
                    </button>
                </div>

                <div class="p-4 sm:p-5 overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="text-slate-500 font-bold border-b border-slate-200 pb-2">
                            <tr>
                                <th class="pb-2 w-10 text-center">#</th>
                                <th class="pb-2">وصف الخدمة / شهادة الفحص</th>
                                <th class="pb-2 w-24 text-center">الكمية</th>
                                <th class="pb-2 w-32 text-center">السعر (ج.م)</th>
                                <th class="pb-2 w-32 text-center">الإجمالي (ج.م)</th>
                                <th class="pb-2 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="py-2.5 text-center font-mono text-slate-400 font-bold" x-text="index + 1"></td>
                                    <td class="py-2.5 pl-2">
                                        <input type="text" :name="'items[' + index + '][description]'" 
                                               x-model="item.description" required
                                               class="w-full text-xs rounded-lg border-slate-300 py-2">
                                        <input type="hidden" :name="'items[' + index + '][certificate_id]'" :value="item.certificate_id || ''">
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
                                               class="w-28 text-xs text-center font-mono rounded-lg border-slate-300 py-2">
                                    </td>
                                    <td class="py-2.5 px-2 text-center font-mono font-extrabold text-slate-800"
                                        x-text="(item.quantity * item.unit_price).toFixed(2)">
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <button type="button" @click="removeItem(index)" 
                                                class="text-red-400 hover:text-red-600 p-1 transition">
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
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-5 space-y-4">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">تفاصيل السداد</h3>
                    <div>
                        <label for="payment_method" class="block text-xs font-bold text-slate-700 mb-1">طريقة السداد</label>
                        <select name="payment_method" id="payment_method" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                            <option value="cash" {{ $invoice->payment_method === 'cash' ? 'selected' : '' }}>نقداً (كاش)</option>
                            <option value="bank_transfer" {{ $invoice->payment_method === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="cheque" {{ $invoice->payment_method === 'cheque' ? 'selected' : '' }}>شيك بنكي</option>
                            <option value="other" {{ $invoice->payment_method === 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">ملاحظات الفاتورة</label>
                        <textarea name="notes" id="notes" rows="2" class="w-full text-xs rounded-lg border-slate-300 py-2">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <div>
                        <label for="terms" class="block text-xs font-bold text-slate-700 mb-1">شروط الدفع</label>
                        <input type="text" name="terms" id="terms" value="{{ old('terms', $invoice->terms) }}"
                               class="w-full text-xs rounded-lg border-slate-300 py-2 text-slate-600">
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl shadow-xs p-5 space-y-3.5">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">ملخص الحساب والخصومات</h3>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                        <span>المجموع الفرعي:</span>
                        <span class="font-mono text-sm" x-text="subtotal.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                    </div>

                    <div class="pt-2 border-t border-slate-200 space-y-2">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-700">نوع الخصم:</span>
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
                                   class="w-full text-xs font-mono font-bold rounded-lg border-amber-300 bg-amber-50/30 py-2">
                            <span class="text-xs font-extrabold text-amber-700 shrink-0 font-mono" x-text="'-' + discountAmount.toFixed(2) + ' ج.م'"></span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-sm font-black text-slate-900">
                        <span>الإجمالي المطلوب:</span>
                        <span class="font-mono text-base text-[#0E4DA4]" x-text="totalAmount.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                    </div>

                    <div class="pt-2 border-t border-slate-200 space-y-1">
                        <label for="paid_amount" class="block text-xs font-bold text-emerald-800">
                            المبلغ المدفوع (دفع كم):
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                                   x-model.number="paidAmount" 
                                   @input="calculateTotals()"
                                   class="w-full text-xs font-mono font-black text-emerald-900 rounded-lg border-emerald-300 bg-emerald-50/40 py-2.5">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-emerald-700">ج.م</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-700 block">المبلغ المتبقي:</span>
                            <span class="text-base font-black font-mono" 
                                  :class="remainingBalance > 0 ? 'text-red-600' : 'text-emerald-600'"
                                  x-text="remainingBalance.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-0.5">حالة السداد:</span>
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

            <div class="flex items-center justify-between gap-3 pt-2">
                <button type="submit" 
                        class="px-8 py-3 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white text-sm font-extrabold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <span>حفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function invoiceEditor() {
    return {
        discountType: '{{ $invoice->discount_type }}',
        discountValue: {{ (float) $invoice->discount_value }},
        paidAmount: {{ (float) $invoice->paid_amount }},
        subtotal: {{ (float) $invoice->subtotal }},
        discountAmount: {{ (float) $invoice->discount_amount }},
        totalAmount: {{ (float) $invoice->total_amount }},
        remainingBalance: {{ (float) $invoice->remaining_balance }},
        
        items: @json($invoice->items->map(fn($i) => [
            'description' => $i->description,
            'certificate_id' => $i->certificate_id,
            'quantity' => $i->quantity,
            'unit_price' => (float) $i->unit_price
        ])),

        init() {
            this.calculateTotals();
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
                alert('يجب الإبقاء على بند واحد على الأقل.');
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
