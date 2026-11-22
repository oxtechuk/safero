@extends('layouts.app')

@section('title', 'طباعة مجمعة لاستيكرات فحص المعدات (A4)')
@section('hide_bottom_nav', '1')

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-6 py-4 space-y-4" x-data="{ layout: 'a4_grid' }">

    <!-- Top Action Toolbar (Hidden when printing) -->
    <div class="print:hidden bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between flex-wrap gap-4">
        
        <!-- Left: Back & Batch Info -->
        <div class="flex items-center gap-3">
            <a href="{{ route('certificates.index') }}" 
               class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition"
               title="الرجوع لسجل الشهادات">
                <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 font-mono">
                        BATCH A4 PRINT
                    </span>
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900">
                        طباعة مجمعة لاستيكرات فحص المعدات (4 استيكرات في ورقة A4)
                    </h1>
                </div>
                <p class="text-xs text-slate-500">
                    تم تحديد <span class="font-bold text-[#0E4DA4] font-mono">{{ $stickers->count() }}</span> شهادات مختلفة موزعة على <span class="font-bold text-emerald-700 font-mono">{{ $sheets->count() }}</span> صفحة A4 بنظام 2×2
                </p>
            </div>
        </div>

        <!-- Right Controls -->
        <div class="flex items-center gap-2 flex-wrap">
            
            <!-- Layout Switcher -->
            <div class="flex items-center p-1 bg-slate-100 rounded-xl text-xs font-bold border border-slate-200">
                <button type="button" 
                        @click="layout = 'a4_grid'"
                        :class="layout === 'a4_grid' ? 'bg-[#006837] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                    <span>📄</span>
                    <span>ورقة A4 (4 استيكرات 2×2)</span>
                </button>
                <button type="button" 
                        @click="layout = 'single'"
                        :class="layout === 'single' ? 'bg-[#006837] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                    <span>🏷️</span>
                    <span>فردي متتالي</span>
                </button>
            </div>

            <!-- Direct Print Button -->
            <button type="button" onclick="window.print()" 
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#006837] to-[#15803d] hover:from-[#00502a] hover:to-[#166534] text-white text-xs sm:text-sm font-extrabold transition flex items-center gap-2 shadow-md hover:shadow-lg cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة جميع الصفحات (Print)</span>
            </button>
        </div>
    </div>

    <!-- Sizing Information -->
    <div class="print:hidden bg-blue-50/80 border border-blue-200/80 p-3 rounded-xl flex items-center justify-between text-xs text-blue-950 flex-wrap gap-2">
        <div class="flex items-center gap-2">
            <span class="text-base">💡</span>
            <span>
                كل كارت يحتوي تلقائياً على <strong>بيانات الشهادة المحددة، كود المعدة الفعلي، وكود QR الخاص بها</strong> للتحقق الفوري.
                مقسم بنظام 4 استيكرات في ورقة A4 واحدة (2×2) مع خطوط قص واضحة.
            </span>
        </div>
        <span class="font-mono font-bold text-[#0E4DA4]">
            Total: {{ $stickers->count() }} Stickers ({{ $sheets->count() }} Pages A4)
        </span>
    </div>

    <!-- ================= SHEETS CONTAINER ================= -->
    <div class="print-container space-y-6">

        @foreach($sheets as $sheetIndex => $sheet)
            <!-- Single A4 Sheet Wrapper -->
            <div class="a4-sheet bg-white p-3 sm:p-4 rounded-2xl border border-slate-300 shadow-md mx-auto max-w-[850px] {{ !$loop->last ? 'print-sheet-break' : '' }}">
                
                <!-- On-Screen Sheet Badge -->
                <div class="print:hidden pb-2 mb-3 border-b border-slate-200 text-xs font-bold text-slate-500 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>ورقة A4 رقم {{ $sheetIndex + 1 }} من إجمالي {{ $sheets->count() }} صفحة</span>
                    </span>
                    <span class="font-mono text-slate-400">4 Stickers / Page</span>
                </div>

                <!-- 2x2 Grid for 4 Stickers -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 justify-items-center a4-grid-inner">
                    @foreach($sheet as $idx => $stk)
                        <div class="relative w-full max-w-[360px] sm:max-w-[370px] sticker-tile p-1 border border-dashed border-slate-300 rounded-[22px]">
                            <!-- Cut Guide Tag -->
                            <span class="print:hidden absolute -top-2.5 right-4 px-2 py-0.5 rounded bg-slate-200 text-slate-600 text-[9px] font-mono font-bold z-20">
                                ✂ خط القص #{{ ($sheetIndex * 4) + $loop->iteration }}
                            </span>

                            <!-- TEMPLATE OVERLAY CARD -->
                            <div class="sticker-card relative bg-white w-full rounded-[18px] border-[2px] border-[#0E4DA4] shadow-sm overflow-hidden font-en select-none text-left"
                                 dir="ltr"
                                 style="aspect-ratio: 1 / 1.5; box-sizing: border-box; direction: ltr; text-align: left;">
                                
                                <img src="{{ asset($templateImg) }}" alt="Sticker Background" class="absolute inset-0 w-full h-full object-cover pointer-events-none">

                                <!-- Row 1 Value (Aligned left under label) -->
                                <div class="absolute text-left" dir="ltr" style="left: 23%; top: 40.5%; width: 31%; text-align: left;">
                                    <span class="text-[9px] sm:text-[10px] font-black text-slate-900 truncate block font-mono text-left" dir="ltr" title="{{ $stk['equipmentId'] }}">
                                        {{ $stk['equipmentId'] }}
                                    </span>
                                </div>

                                <!-- Row 2 Value (Aligned left under label) -->
                                <div class="absolute text-left" dir="ltr" style="left: 23%; top: 49.2%; width: 31%; text-align: left;">
                                    <span class="text-[8.5px] sm:text-[9.5px] leading-tight font-black text-slate-900 truncate block text-left" dir="ltr" title="{{ $stk['equipmentType'] }}">
                                        {{ $stk['equipmentType'] }}
                                    </span>
                                </div>

                                <!-- Row 3 Value (Aligned left under label) -->
                                <div class="absolute text-left" dir="ltr" style="left: 23%; top: 57.5%; width: 31%; text-align: left;">
                                    <span class="text-[9px] sm:text-[10px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                                        {{ $stk['inspectionDate'] }}
                                    </span>
                                </div>

                                <!-- Row 4 Value (Aligned left under label) -->
                                <div class="absolute text-left" dir="ltr" style="left: 23%; top: 66.2%; width: 31%; text-align: left;">
                                    <span class="text-[9px] sm:text-[10px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                                        {{ $stk['nextDate'] }}
                                    </span>
                                </div>

                                <!-- Row 5 Result Value (Aligned left under label) -->
                                <div class="absolute text-left" dir="ltr" style="left: 23%; top: 72.8%; width: 31%; text-align: left;">
                                    <span class="text-[10.5px] sm:text-[11.5px] font-black {{ $stk['resultColor'] }} uppercase font-mono tracking-wider block text-left" dir="ltr">
                                        {{ $stk['resultText'] }}
                                    </span>
                                </div>

                                <!-- QR Code inside QR Box -->
                                <div class="absolute flex items-center justify-center p-1" style="left: 56%; top: 44.5%; width: 38%; height: 29.5%;">
                                    <img src="data:image/svg+xml;base64,{{ $stk['qrCodeSvg'] }}" alt="QR Code" class="w-full h-full object-contain">
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach

    </div>

</div>

<!-- Print Styles for Desktop Paper & Label Printers -->
<style>
@media print {
    header, footer, nav, aside, .print\:hidden, #main-sidebar, .menu-item-group, .border-b {
        display: none !important;
    }
    body, html, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .print-sheet-break {
        page-break-after: always !important;
        break-after: page !important;
    }
    .a4-sheet {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 auto !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 282mm !important;
        max-height: 282mm !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        overflow: hidden !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    .a4-grid-inner {
        display: grid !important;
        grid-template-columns: 90mm 90mm !important;
        grid-template-rows: 135mm 135mm !important;
        column-gap: 6mm !important;
        row-gap: 4mm !important;
        width: 186mm !important;
        height: 274mm !important;
        margin: 0 auto !important;
        justify-content: center !important;
        align-content: center !important;
    }
    .sticker-tile {
        width: 90mm !important;
        height: 135mm !important;
        max-width: 90mm !important;
        max-height: 135mm !important;
        border: 1px dashed #94a3b8 !important;
        border-radius: 10px !important;
        padding: 0 !important;
        margin: 0 !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        overflow: hidden !important;
    }
    .sticker-card {
        width: 100% !important;
        height: 100% !important;
        border-width: 1.5px !important;
        box-shadow: none !important;
        margin: 0 !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    @page {
        size: A4 portrait;
        margin: 6mm;
    }
}
</style>
@endsection
