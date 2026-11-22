@extends('layouts.app')

@php
    $item = $inspection ?? $certificate ?? null;
    $isCert = isset($certificate) && $certificate;
    
    // Normalize data
    $equipmentId = $item->doc_number 
        ?? ($item->asset_tag 
        ?? ($item->equipment_id_range 
        ?? ($item->serial_no 
        ?? ($item->chassis_no 
        ?? ($item->inspection_number ?? ($item->certificate_number ?? 'EQ-001'))))));

    $equipmentType = $item->equipment_name 
        ?? ($item->equipment_type 
        ?? ($item->equipment_description ?? 'Lifting Equipment'));

    $inspectionDate = $item->inspection_date 
        ? \Carbon\Carbon::parse($item->inspection_date)->format('d/m/Y')
        : ($item->examination_date ? \Carbon\Carbon::parse($item->examination_date)->format('d/m/Y') : ($item->thorough_examination_date ? \Carbon\Carbon::parse($item->thorough_examination_date)->format('d/m/Y') : date('d/m/Y')));

    $nextDate = $item->next_inspection_date 
        ? \Carbon\Carbon::parse($item->next_inspection_date)->format('d/m/Y')
        : ($item->next_examination_due_date ? \Carbon\Carbon::parse($item->next_examination_due_date)->format('d/m/Y') : date('d/m/Y', strtotime('+1 year')));

    $isSafe = $isCert 
        ? ($certificate->is_safe_to_operate ?? true)
        : (($inspection->overall_condition ?? 'safe') === 'safe');

    $resultText = $isSafe ? 'PASS' : 'FAIL';
    $resultColor = $isSafe ? 'text-[#16A34A]' : 'text-[#DC2626]';

    $templateImg = setting('sticker_template_path', '/branding/sticker_template.png');
@endphp

@section('title', 'استيكر فحص المعدة: '.$equipmentId)
@section('hide_bottom_nav', '1')

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-6 py-4 space-y-4" x-data="{ layout: 'single' }">

    <!-- Top Action Toolbar (Hidden when printing) -->
    <div class="print:hidden bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between flex-wrap gap-4">
        
        <!-- Left: Back & Title -->
        <div class="flex items-center gap-3">
            <a href="{{ $isCert ? route('certificates.show', $certificate) : route('inspections.inspect', $inspection) }}" 
               class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition"
               title="الرجوع">
                <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 font-mono tracking-wider">
                        EQUIPMENT INSPECTION
                    </span>
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900">
                        استيكر وكارت فحص المعدة (Equipment Inspection Label)
                    </h1>
                </div>
                <p class="text-xs text-slate-500">
                    كود المعدة: <span class="font-bold text-[#0E4DA4] font-mono">{{ $equipmentId }}</span> &mdash; الحالة: <span class="font-bold {{ $isSafe ? 'text-emerald-700' : 'text-rose-700' }}">{{ $resultText }}</span>
                </p>
            </div>
        </div>

        <!-- Right Controls -->
        <div class="flex items-center gap-2 flex-wrap">
            
            <!-- Layout Switcher: Single (Default) vs A4 4-Pack -->
            <div class="flex items-center p-1 bg-slate-100 rounded-xl text-xs font-bold border border-slate-200">
                <button type="button" 
                        @click="layout = 'single'"
                        :class="layout === 'single' ? 'bg-[#006837] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                    <span>🏷️</span>
                    <span>استيكر فردي (افتراضي)</span>
                </button>
                <button type="button" 
                        @click="layout = 'a4_grid'"
                        :class="layout === 'a4_grid' ? 'bg-[#006837] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                    <span>📄</span>
                    <span>ورقة A4 (4 استيكرات 2×2)</span>
                </button>
            </div>

            <!-- Direct Print Button -->
            <button type="button" onclick="window.print()" 
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#006837] to-[#15803d] hover:from-[#00502a] hover:to-[#166534] text-white text-xs sm:text-sm font-extrabold transition flex items-center gap-2 shadow-md hover:shadow-lg cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة الآن (Print)</span>
            </button>
        </div>
    </div>

    <!-- Instruction Bar (Hidden on Print) -->
    <div class="print:hidden bg-blue-50/80 border border-blue-200/80 p-3 rounded-xl flex items-center justify-between text-xs text-blue-950 flex-wrap gap-2">
        <div class="flex items-center gap-2">
            <span class="text-base">💡</span>
            <span>
                <strong x-show="layout === 'single'">وضع الطباعة الفردية (افتراضي):</strong>
                <strong x-show="layout === 'a4_grid'">وضع الطباعة المجمعة:</strong>
                <span x-show="layout === 'single'">استيكر منفرد ملائم تماماً للطابعات الحرارية والباركود الرول (80×120 مم) أو الطابعات المكتبية المباشرة.</span>
                <span x-show="layout === 'a4_grid'">ورقة A4 تحتوي على 4 كروت مطابقة وموزعة بنظام 2×2 مع خطوط قص واضحة لوضعها على أجزاء المعدة المختلفة.</span>
            </span>
        </div>
        <span class="font-mono font-bold text-[#0E4DA4]">
            {{ $isCert ? 'Official Certificate Linked' : 'Field Inspection Verified' }}
        </span>
    </div>

    <!-- ================= STICKER PRINT CONTAINER ================= -->
    <div class="print-container">

        <!-- =================== OPTION 1: SINGLE STICKER (DEFAULT) =================== -->
        <div x-show="layout === 'single'" class="single-sheet flex justify-center py-4">
            
            <div class="relative w-[380px] sm:w-[420px]">

                <div class="sticker-card relative bg-white w-full rounded-[24px] border-[3px] border-[#0E4DA4] shadow-xl overflow-hidden font-en select-none text-left"
                     dir="ltr"
                     style="aspect-ratio: 1 / 1.5; box-sizing: border-box; direction: ltr; text-align: left;">
                    
                    <!-- Background Template Artwork -->
                    <img src="{{ asset($templateImg) }}" alt="Sticker Background" class="absolute inset-0 w-full h-full object-cover pointer-events-none">

                    <!-- Row 1 Value (Aligned to left under label) -->
                    <div class="absolute text-left" dir="ltr" style="left: 23%; top: 40.5%; width: 31%; text-align: left;">
                        <span class="text-[10px] sm:text-[11.5px] font-black text-slate-900 truncate block font-mono text-left" dir="ltr" title="{{ $equipmentId }}">
                            {{ $equipmentId }}
                        </span>
                    </div>

                    <!-- Row 2 Value (Aligned to left under label) -->
                    <div class="absolute text-left" dir="ltr" style="left: 23%; top: 49.2%; width: 31%; text-align: left;">
                        <span class="text-[9.5px] sm:text-[11px] leading-tight font-black text-slate-900 truncate block text-left" dir="ltr" title="{{ $equipmentType }}">
                            {{ $equipmentType }}
                        </span>
                    </div>

                    <!-- Row 3 Value (Aligned to left under label) -->
                    <div class="absolute text-left" dir="ltr" style="left: 23%; top: 57.5%; width: 31%; text-align: left;">
                        <span class="text-[10px] sm:text-[11.5px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                            {{ $inspectionDate }}
                        </span>
                    </div>

                    <!-- Row 4 Value (Aligned to left under label) -->
                    <div class="absolute text-left" dir="ltr" style="left: 23%; top: 66.2%; width: 31%; text-align: left;">
                        <span class="text-[10px] sm:text-[11.5px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                            {{ $nextDate }}
                        </span>
                    </div>

                    <!-- Row 5 Result Value (Aligned to left under label) -->
                    <div class="absolute text-left" dir="ltr" style="left: 23%; top: 72.8%; width: 31%; text-align: left;">
                        <span class="text-xs sm:text-sm font-black {{ $resultColor }} uppercase font-mono tracking-wider block text-left" dir="ltr">
                            {{ $resultText }}
                        </span>
                    </div>

                    <!-- QR Code inside QR Box -->
                    <div class="absolute flex items-center justify-center p-1.5" style="left: 56%; top: 44.5%; width: 38%; height: 29.5%;">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-full h-full object-contain">
                    </div>

                </div>

            </div>

        </div>


        <!-- =================== OPTION 2: A4 BATCH GRID (4 COPIES 2x2) =================== -->
        <div x-show="layout === 'a4_grid'" 
             class="a4-sheet bg-white p-3 sm:p-5 rounded-2xl border border-slate-300 shadow-md mx-auto max-w-[850px]">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 justify-items-center a4-grid-inner">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="relative w-full max-w-[370px] sm:max-w-[390px] sticker-tile p-1 border border-dashed border-slate-300 rounded-[26px]">
                        <!-- Cut Guide Tag -->
                        <span class="print:hidden absolute -top-2.5 right-4 px-2 py-0.5 rounded bg-slate-200 text-slate-600 text-[9px] font-mono font-bold z-20">
                            ✂ خط القص #{{ $i }}
                        </span>

                        <div class="sticker-card relative bg-white w-full rounded-[22px] border-[2.5px] border-[#0E4DA4] shadow-sm overflow-hidden font-en select-none text-left"
                             dir="ltr"
                             style="aspect-ratio: 1 / 1.5; box-sizing: border-box; direction: ltr; text-align: left;">
                            
                            <!-- Background Artwork -->
                            <img src="{{ asset($templateImg) }}" alt="Sticker Background" class="absolute inset-0 w-full h-full object-cover pointer-events-none">

                            <!-- Row 1 Value (Aligned to left under label) -->
                            <div class="absolute text-left" dir="ltr" style="left: 23%; top: 40.5%; width: 31%; text-align: left;">
                                <span class="text-[9.5px] sm:text-[10.5px] font-black text-slate-900 truncate block font-mono text-left" dir="ltr" title="{{ $equipmentId }}">
                                    {{ $equipmentId }}
                                </span>
                            </div>

                            <!-- Row 2 Value (Aligned to left under label) -->
                            <div class="absolute text-left" dir="ltr" style="left: 23%; top: 49.2%; width: 31%; text-align: left;">
                                <span class="text-[9px] sm:text-[10px] leading-tight font-black text-slate-900 truncate block text-left" dir="ltr" title="{{ $equipmentType }}">
                                    {{ $equipmentType }}
                                </span>
                            </div>

                            <!-- Row 3 Value (Aligned to left under label) -->
                            <div class="absolute text-left" dir="ltr" style="left: 23%; top: 57.5%; width: 31%; text-align: left;">
                                <span class="text-[9.5px] sm:text-[10.5px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                                    {{ $inspectionDate }}
                                </span>
                            </div>

                            <!-- Row 4 Value (Aligned to left under label) -->
                            <div class="absolute text-left" dir="ltr" style="left: 23%; top: 66.2%; width: 31%; text-align: left;">
                                <span class="text-[9.5px] sm:text-[10.5px] font-black text-slate-900 font-mono block text-left" dir="ltr">
                                    {{ $nextDate }}
                                </span>
                            </div>

                            <!-- Row 5 Result Value (Aligned to left under label) -->
                            <div class="absolute text-left" dir="ltr" style="left: 23%; top: 72.8%; width: 31%; text-align: left;">
                                <span class="text-[11px] sm:text-xs font-black {{ $resultColor }} uppercase font-mono tracking-wider block text-left" dir="ltr">
                                    {{ $resultText }}
                                </span>
                            </div>

                            <!-- QR Code inside QR Box -->
                            <div class="absolute flex items-center justify-center p-1" style="left: 56%; top: 44.5%; width: 38%; height: 29.5%;">
                                <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" alt="QR Code" class="w-full h-full object-contain">
                            </div>

                        </div>
                    </div>
                @endfor
            </div>

        </div>

    </div>

</div>

<!-- Print Styles for Desktop Paper & Label Printers -->
<style>
@media print {
    /* Hide all layout wrappers */
    header, footer, nav, aside, .print\:hidden, #main-sidebar, .menu-item-group, .border-b {
        display: none !important;
    }
    body, html, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    /* Single Sticker Print Layout (Default) */
    .single-sheet {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        min-height: 98vh !important;
        padding: 0 !important;
        margin: 0 auto !important;
    }
    .single-sheet .sticker-card {
        width: 90mm !important;
        max-width: 90mm !important;
        height: 135mm !important;
        border-width: 1.5px !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* A4 4-Pack Mode Print Layout */
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
        border-width: 1.5px !important;
        box-shadow: none !important;
        margin: 0 auto !important;
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
