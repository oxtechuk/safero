---
name: odoo-ui-system
description: "Guidelines and reference implementation for the Odoo 17/18 Enterprise UI design system in Safero ERP. Covers top navbar dropdown sub-menus, control panels, dual list/kanban views, compact form sheets, and the official Odoo vector icon library in public/branding/odoo-icons."
---

# Odoo Enterprise UI Design System for Safero ERP

This skill provides comprehensive standards, architectural patterns, and reusable Blade components for implementing the **Odoo 17/18 Enterprise** visual theme across the Safero application.

---

## 1. Icon Library Guide (`public/branding/odoo-icons/`)

The application includes the official Odoo SVG vector icon library extracted directly from Odoo 17/18 Enterprise located at `public/branding/odoo-icons/` (with tiled icon variants in `public/branding/odoo-icons/tiles/`).

### Icon to Module / Model Mapping

| Icon File | Module / Entity | Purpose |
| :--- | :--- | :--- |
| `quality_control.svg` | **شهادات الفحص** (Inspection Certificates) | Primary module icon, inspection reports, thorough examination |
| `industry_certification.svg` | **شهادات التدريب** (Training Certificates) | Accredited training certificates, qualifications |
| `contacts.svg` | **العملاء والمتدربون** (Customers & Trainees) | Companies, contacts, trainees directory |
| `fleet.svg` | **الأوناش والروافع** (Cranes & Heavy Lifting) | Mobile telescopic cranes, lifting appliances |
| `maintenance.svg` | **مهمات الرفع والحبال** (Lifting Accessories) | Slings, shackles, webbing belts, rigging hardware |
| `repair.svg` | **العيوب والإصلاحات** (Defects & Repairs) | Remedial actions, maintenance tracking |
| `survey.svg` | **قوائم الفحص** (Inspection Checklists) | 28-point crane inspection checklist, LOLER questions |
| `documents.svg` | **التقارير والمستندات** (Reports & Exports) | PDF generation, documentation logs |
| `approvals.svg` | **الاعتماد والتحقق** (Verification & Authority) | QR Code scanner, authentication status |
| `sign.svg` | **التوقيعات الرقمية** (Signatures & Stamp) | Inspector signature, managing director seal |
| `settings.svg` | **الإعدادات والتهيئة** (Configuration) | System settings, user permissions |
| `apps.svg` | **شاشة التطبيقات** (Apps Launcher) | 9-dots app drawer button |

### Usage in Blade Views

```blade
<!-- Standalone SVG Icon -->
<img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-5 h-5" alt="Inspection">

<!-- Tiled Icon (For Apps Launcher & Kanban Cards) -->
<div class="w-12 h-12 rounded-xl bg-white p-2 shadow-xs border border-slate-200/80 flex items-center justify-center">
    <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-8 h-8" alt="Inspection">
</div>
```

---

## 2. Top Navigation & Dropdown Sub-menus (Odoo Enterprise Pattern)

As demonstrated in Odoo Enterprise settings and technical menus, the top header consists of:
1. **App Drawer Launcher (9-Dots)**: Always navigates to `/apps`.
2. **Current Active Module Brand**: Name and icon of the active module.
3. **Primary Menu Tabs with Categorized Dropdowns**:
   Hovering or clicking a menu tab opens an absolute dropdown menu featuring categorized sub-headers and action items.

### Blade Dropdown Pattern (Alpine.js / Tailwind)

```blade
<div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
    <!-- Trigger Tab -->
    <button type="button" 
            class="px-3 py-1.5 rounded-md text-xs font-bold text-slate-700 hover:bg-slate-100 flex items-center gap-1 transition">
        <span>شهادات الفحص</span>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 top-full mt-1 w-64 bg-white border border-slate-200 rounded-lg shadow-xl py-2 z-50 text-xs divide-y divide-slate-100"
         style="display: none;">
        
        <!-- Category Section -->
        <div class="py-1">
            <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">إصدار نموذج فحص</span>
            <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] font-medium transition">
                <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-4 h-4">
                <span>فحص الأوناش والروافع (Cranes)</span>
            </a>
            <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] font-medium transition">
                <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-4 h-4">
                <span>مهمات الرفع والحبال (Accessories)</span>
            </a>
            <a href="{{ route('certificates.create', ['type' => 'general']) }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] font-medium transition">
                <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-4 h-4">
                <span>معدات عامة وباليتات (General Cargo)</span>
            </a>
        </div>

        <!-- Operations Section -->
        <div class="py-1">
            <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">السجلات والعمليات</span>
            <a href="{{ route('certificates.index') }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-50 text-slate-700 font-medium">
                <img src="{{ asset('branding/odoo-icons/documents.svg') }}" class="w-4 h-4">
                <span>جميع الشهادات (All Certificates)</span>
            </a>
        </div>
    </div>
</div>
```

---

## 3. Odoo Control Panel (`x-odoo-control-panel`)

The Control Panel provides the standard Odoo header for list and kanban views:
- **Left**: Primary Action button (`New`), Page Title, Settings Cog.
- **Center**: Search Bar with integrated Filter/Group-by dropdown popover.
- **Right**: Pager (`1-15 / 142`) with navigation arrows, and dual view switchers (List, Kanban).

---

## 4. Compact ERP Form View Guidelines

1. **Top Button Box**: Action buttons (`Save`, `Discard`, `Print PDF`, `Verify`) positioned at the top of the form for quick execution without scrolling.
2. **Dense Form Fields**:
   - Label: `text-xs font-bold text-slate-700 mb-1`.
   - Input: `px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs`.
   - Grid layout: 3 to 4 columns on desktop, 2 on tablet, 1 on mobile.
3. **No Emojis**: Always use SVG icons or vector assets from `public/branding/odoo-icons/`.
4. **Fast Actions**: Provide batch and "Mark All" capabilities (e.g. `Pass All` button for crane checklists).
