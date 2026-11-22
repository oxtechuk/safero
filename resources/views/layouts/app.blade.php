<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Safero ERP') }} - @yield('title', 'نظام الفحص والشهادات المعتمدة')</title>

    @php
        $favicon = setting('favicon_path') ?: setting('logo_path', '/branding/safero_logo.jpg');
        $appIcon = setting('app_icon_path') ?: $favicon;
    @endphp

    <!-- Dynamic Favicon & Mobile App Icons from Settings -->
    <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
    <link rel="shortcut icon" href="{{ asset($favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset($appIcon) }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ setting('company_name_en', 'Safero ERP') }}">
    <meta name="theme-color" content="#0E4DA4">

    <!-- Google Fonts: Cairo & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Cairo', 'Poppins', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }

        /* Pure CSS zero-delay hover fallback for Odoo Navbar Dropdowns */
        .menu-item-group:hover > .menu-popup-box {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        .menu-popup-box {
            min-width: 288px !important;
            box-sizing: border-box !important;
        }

        .menu-popup-box > div {
            min-width: 288px !important;
            box-sizing: border-box !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-[#006837] selection:text-white bg-[#F8FAFC]">

    <!-- Top IsoOx 17/18 Enterprise Navbar (Crisp, High-Density, with Apps Return Button) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 text-slate-800 shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13">
                
                <!-- Left: 9-Dots Apps Drawer Button + Active Module Brand + Dropdown Sub-menus -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Back to Apps Launcher Button (IsoOx Return Pattern) -->
                    <a href="{{ route('apps') }}" 
                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-100/90 hover:bg-slate-200 text-slate-700 hover:text-slate-900 border border-slate-200 transition-all duration-150 shadow-2xs group" 
                       title="الرجوع إلى شاشة التطبيقات الرئيسية (Apps Launcher)">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-800 transition transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <img src="{{ asset('branding/odoo-icons/apps.svg') }}" class="w-4 h-4 object-contain shrink-0" alt="Apps">
                        <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 hidden md:inline">الرئيسية</span>
                    </a>

                    <!-- Current Active Module Brand / Icon -->
                    <div class="flex items-center gap-2 pr-2 border-r border-slate-200">
                        @if(request()->routeIs('settings.*'))
                            <div class="w-6 h-6 rounded bg-purple-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-purple-200">
                                <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-4 h-4 object-contain" alt="Settings">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">الإعدادات (Settings)</span>
                        @elseif(request()->routeIs('inspections.*'))
                            <div class="w-6 h-6 rounded bg-emerald-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-emerald-200">
                                <img src="{{ asset('branding/odoo-icons/survey.svg') }}" class="w-4 h-4 object-contain" alt="Field Inspections">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">بدء فحص ميداني</span>
                        @elseif(request()->routeIs('invoices.*'))
                            <div class="w-6 h-6 rounded bg-blue-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-blue-200">
                                <img src="{{ asset('branding/odoo-icons/account.svg') }}" class="w-4 h-4 object-contain" alt="Invoices">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">الفواتير والحسابات</span>
                        @elseif(request()->routeIs('reports.*'))
                            <div class="w-6 h-6 rounded bg-amber-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-amber-200">
                                <img src="{{ asset('branding/odoo-icons/point_of_sale.svg') }}" class="w-4 h-4 object-contain" alt="Reports">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">تقارير الأرباح</span>
                        @elseif(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
                            <div class="w-6 h-6 rounded bg-emerald-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-emerald-200">
                                <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-4 h-4 object-contain" alt="Training">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">شهادات التدريب</span>
                        @elseif(request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*'))
                            <div class="w-6 h-6 rounded bg-blue-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-blue-200">
                                <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-4 h-4 object-contain" alt="Inspection">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">شهادات الفحص</span>
                        @elseif(request()->routeIs('customers.*'))
                            <div class="w-6 h-6 rounded bg-amber-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-amber-200">
                                <img src="{{ asset('branding/odoo-icons/crm.svg') }}" class="w-4 h-4 object-contain" alt="Customers">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">العملاء </span>
                        @elseif(request()->routeIs('users.*') || request()->routeIs('roles.*'))
                            <div class="w-6 h-6 rounded bg-purple-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-purple-200">
                                <img src="{{ asset('branding/odoo-icons/hr.svg') }}" class="w-4 h-4 object-contain" alt="Users">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">المستخدمون</span>
                        @elseif(request()->routeIs('tasks.*'))
                            <div class="w-6 h-6 rounded bg-blue-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-blue-200">
                                <img src="{{ asset('branding/odoo-icons/timer.svg') }}" class="w-4 h-4 object-contain" alt="Tasks">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">مهام الفحص </span>
                        @else
                            <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" alt="{{ setting('company_name_en', 'Safero') }}" class="h-5 w-auto object-contain">
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">{{ setting('company_name_en', 'Safero ERP') }}</span>
                        @endif
                    </div>

                    <!-- IsoOx Enterprise Submenu Navigation with Grouped Cascading Dropdowns (Hidden on Mobile) -->
                    <nav class="hidden md:flex items-center gap-0.5 sm:gap-1 flex-nowrap" 
                         x-data="{ 
                             activeMenu: null, 
                             timer: null, 
                             enter(name) { 
                                 clearTimeout(this.timer); 
                                 this.activeMenu = name; 
                             }, 
                             leave() { 
                                 this.timer = setTimeout(() => { 
                                     this.activeMenu = null; 
                                 }, 180); 
                             } 
                         }" 
                         @click.away="activeMenu = null">

                        @if(request()->routeIs('settings.*'))
                            <!-- IsoOx Enterprise Settings Sub-navigation Bar (Image 1 Exact Match) -->
                            <a href="{{ route('settings.index', ['tab' => 'general']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab', 'general') === 'general' ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span>الإعدادات العام</span>
                            </a>

                            <a href="{{ route('settings.index', ['tab' => 'users']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab') === 'users' ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span>المستخدمون</span>
                            </a>

                            <a href="{{ route('settings.index', ['tab' => 'localization']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab') === 'localization' ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span>اللغات والترجمة</span>
                            </a>

                            <a href="{{ route('settings.index', ['tab' => 'google_sheets']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab') === 'google_sheets' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="w-2 h-2 rounded-full {{ setting('google_sheets_webhook_url') ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <span>ربط Google Sheets</span>
                            </a>

                            <!-- Technical Dropdown (Image 1 Highlight: Menu Items, Database, Automation) -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('technical')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'technical' ? null : 'technical')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ in_array(request('tab'), ['technical', 'saas']) ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>الخيارات التقنية (Technical)</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'technical' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'technical'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 288px; width: 288px;">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100" style="min-width: 288px; width: 288px;">
                                        <!-- User Interface Section (Image 1 Highlight: Menu Items) -->
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                واجهة المستخدم (User Interface)
                                            </div>
                                            <a href="{{ route('settings.index', ['tab' => 'technical']) }}" 
                                               class="flex items-center justify-between px-3.5 py-2 hover:bg-purple-50 hover:text-[#714B67] text-slate-700 transition font-bold {{ request('tab') === 'technical' ? 'bg-purple-50 text-[#714B67]' : '' }}">
                                                <span>عناصر القوائم (Menu Items)</span>
                                                <span class="text-[9px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-bold font-mono">Image 1 Highlight</span>
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'branding']) }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                قوالب الشهادات والشعار (Branding &amp; Assets)
                                            </a>
                                        </div>

                                        <!-- Database Structure & Modular Core -->
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                البنية ونظام SaaS (Architecture)
                                            </div>
                                            <a href="{{ route('settings.index', ['tab' => 'saas']) }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition font-bold {{ request('tab') === 'saas' ? 'bg-purple-50 text-[#714B67]' : '' }}">
                                                منصة SaaS والمديولات مفتوحة المصدر
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'certificates']) }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                معايير الاعتماد الهندسي (ISO 17020)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- Standard App Modules Navigation -->

                            <!-- 1. Inspection Certificates Dropdown (Odoo Style Tree Menu) -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('inspection')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'inspection' ? null : 'inspection')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ (request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*')) && !request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>شهادات الفحص</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'inspection' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                
                                <!-- Odoo Tree Menu Dropdown (Zero-gap, 180ms hover buffer, Direct type links) -->
                                <div x-show="activeMenu === 'inspection'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 288px; width: 288px;">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100" style="min-width: 288px; width: 288px;">
                                        <!-- Field Inspection (بدء فحص) Highlight -->
                                        <div class="py-1 bg-emerald-50/40">
                                            <div class="px-3 py-1 text-[10px] font-bold text-emerald-800 uppercase tracking-wider flex items-center justify-between">
                                                <span>الفحص الميداني والشيك ليست</span>
                                                <span class="text-[9px] bg-emerald-200 text-emerald-800 px-1.5 py-0.2 rounded font-bold">جديد</span>
                                            </div>
                                            <a href="{{ route('inspections.start') }}" 
                                               class="flex items-center justify-between px-3.5 py-1.5 hover:bg-emerald-100/60 text-emerald-950 font-black transition text-xs">
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    <span>بدء فحص ميداني جديد (شيك ليست)</span>
                                                </span>
                                            </a>
                                            <a href="{{ route('inspections.index') }}" 
                                               class="block px-3.5 py-1 text-slate-700 hover:text-emerald-800 text-[11px] font-semibold transition">
                                                سجل الفحوصات الميدانية
                                            </a>
                                        </div>

                                        <!-- Tree Branch 1: نماذج الفحص المعتمدة -->
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                نماذج الفحص المعتمدة
                                            </div>
                                            <a href="{{ route('certificates.create', ['type' => 'crane']) }}" 
                                               class="flex flex-col px-3.5 py-2 hover:bg-[#E6F2FF] hover:text-[#0E4DA4] text-slate-700 transition group {{ (request()->routeIs('certificates.create') && request('type', 'crane') === 'crane') ? 'bg-blue-50/70 font-bold text-[#0E4DA4]' : '' }}">
                                                <span class="font-bold text-xs group-hover:text-[#0E4DA4]">فحص الأوناش والروافع</span>
                                                <span class="text-[10px] text-slate-400 font-en">Lifting Appliances (Cranes - LOLER)</span>
                                            </a>
                                            <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" 
                                               class="flex flex-col px-3.5 py-2 hover:bg-[#E6F2FF] hover:text-[#0E4DA4] text-slate-700 transition group {{ (request()->routeIs('certificates.create') && request('type') === 'accessory') ? 'bg-blue-50/70 font-bold text-[#0E4DA4]' : '' }}">
                                                <span class="font-bold text-xs group-hover:text-[#0E4DA4]">مهمات الرفع والحبال</span>
                                                <span class="text-[10px] text-slate-400 font-en">Lifting Accessories (Slings / Shackles)</span>
                                            </a>
                                            <a href="{{ route('certificates.create', ['type' => 'general']) }}" 
                                               class="flex flex-col px-3.5 py-2 hover:bg-[#E6F2FF] hover:text-[#0E4DA4] text-slate-700 transition group {{ (request()->routeIs('certificates.create') && request('type') === 'general') ? 'bg-blue-50/70 font-bold text-[#0E4DA4]' : '' }}">
                                                <span class="font-bold text-xs group-hover:text-[#0E4DA4]">معدات عامة وباليتات</span>
                                                <span class="text-[10px] text-slate-400 font-en">General Cargo Units &amp; Pallets</span>
                                            </a>
                                        </div>

                                        <!-- Tree Branch: ستوديو قوالب الفحص (Studio) -->
                                        <div class="py-1 bg-slate-50/50">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center justify-between">
                                                <span>ستوديو شهادات الفحص (Studio)</span>
                                                <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.2 rounded font-bold font-mono">CMS</span>
                                            </div>
                                            <a href="{{ route('certificate-templates.index') }}" 
                                               class="flex items-center justify-between px-3.5 py-1.5 hover:bg-slate-100 text-slate-800 hover:text-[#0E4DA4] transition font-bold {{ request()->routeIs('certificate-templates.index') ? 'bg-blue-50 text-[#0E4DA4]' : '' }}">
                                                <span class="flex items-center gap-1.5">
                                                    <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-3.5 h-3.5 object-contain" alt="">
                                                    <span>ستوديو قوالب الفحص</span>
                                                </span>
                                                <span class="text-[10px] text-slate-500 font-medium font-en">Templates</span>
                                            </a>
                                            <a href="{{ route('certificate-templates.create') }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100 text-slate-700 hover:text-[#0E4DA4] transition text-xs {{ request()->routeIs('certificate-templates.create') ? 'bg-blue-50 font-bold text-[#0E4DA4]' : '' }}">
                                                + تصميم وتخصيص قالب جديد
                                            </a>
                                        </div>

                                        <!-- Tree Branch 2: سجل الشهادات والعمليات -->
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                سجل الشهادات والعمليات
                                            </div>
                                            <a href="{{ route('certificates.index') }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition font-bold {{ (request()->routeIs('certificates.index') && !request('status')) ? 'bg-blue-50/70 text-[#0E4DA4]' : '' }}">
                                                سجل الشهادات (جميع الشهادات)
                                            </a>
                                            <a href="{{ route('certificates.index', ['status' => 'issued']) }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition {{ request('status') === 'issued' ? 'bg-blue-50/70 font-bold text-[#0E4DA4]' : '' }}">
                                                الشهادات السارية المعتمدة
                                            </a>
                                            <a href="{{ route('certificates.expiring') }}" 
                                               class="flex items-center justify-between px-3.5 py-1.5 hover:bg-slate-100 text-slate-700 hover:text-[#0E4DA4] transition font-bold {{ request()->routeIs('certificates.expiring') ? 'bg-blue-50 text-[#0E4DA4]' : '' }}">
                                                <span class="flex items-center gap-1.5">
                                                    <img src="{{ asset('branding/IsoOx-icons/timer.svg') }}" class="w-3.5 h-3.5 object-contain" alt="">
                                                    <span>الشهادات القريبة من الانتهاء</span>
                                                </span>
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            </a>
                                            <a href="{{ route('certificates.index', ['status' => 'draft']) }}" 
                                               class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition {{ request('status') === 'draft' ? 'bg-blue-50/70 font-bold text-[#0E4DA4]' : '' }}">
                                                المسودات قيد المراجعة
                                            </a>
                                        </div>

                                        <!-- Tree Branch 3: التحقق والامتثال -->
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                التحقق والامتثال
                                            </div>
                                            <a href="{{ route('certificates.verify.lookup') }}" 
                                               class="block px-3.5 py-1.5 hover:bg-purple-50 text-slate-700 hover:text-purple-700 transition {{ request()->routeIs('certificates.verify.*') ? 'bg-purple-50/70 font-bold text-purple-700' : '' }}">
                                                فحص رمز الاستجابة QR Code
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Training Certificates Dropdown -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('training')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'training' ? null : 'training')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>شهادات التدريب</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'training' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'training'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 288px; width: 288px;">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100" style="min-width: 288px; width: 288px;">
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                شهادات المتدربين
                                            </div>
                                            <a href="{{ route('training-certificates.create') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ request()->routeIs('training-certificates.create') ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                                إصدار شهادة تدريب جديدة
                                            </a>
                                            <a href="{{ route('training-certificates.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ (request()->routeIs('training-certificates.index') && !request('status')) ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                                سجل شهادات التدريب المعتمدة
                                            </a>
                                            <a href="{{ route('training-certificates.index', ['status' => 'issued']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ request('status') === 'issued' ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                                الشهادات السارية المعتمدة
                                            </a>
                                        </div>

                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                إدارة المتدربين
                                            </div>
                                            <a href="{{ route('trainees.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ request()->routeIs('trainees.index') ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                                قاعدة بيانات المتدربين
                                            </a>
                                            <a href="{{ route('trainees.create') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ request()->routeIs('trainees.create') ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                                تسجيل متدرب جديد
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Customers Dropdown -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('customers')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'customers' ? null : 'customers')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('customers.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>العملاء </span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'customers' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'customers'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 260px; width: 260px;">
                                    
                                    <div class="w-64 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5" style="min-width: 260px; width: 260px;">
                                        <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                            إدارة العملاء
                                        </div>
                                        <a href="{{ route('customers.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition {{ request()->routeIs('customers.index') ? 'bg-blue-50/60 font-bold text-[#0E4DA4]' : '' }}">
                                            دليل الشركات والمصانع
                                        </a>
                                        <a href="{{ route('customers.create') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition {{ request()->routeIs('customers.create') ? 'bg-blue-50/60 font-bold text-[#0E4DA4]' : '' }}">
                                            إضافة عميل أو شركة جديدة
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 3.5 Invoices & Accounts Dropdown (Requested Module) -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('invoices')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'invoices' ? null : 'invoices')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('invoices.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>الفواتير والحسابات</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'invoices' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'invoices'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 260px; width: 260px;">
                                    
                                    <div class="w-64 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5" style="min-width: 260px; width: 260px;">
                                        <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                            فواتير الفحص والمعايرة
                                        </div>
                                        <a href="{{ route('invoices.create') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition font-bold {{ request()->routeIs('invoices.create') ? 'bg-emerald-50/60 text-[#006837]' : '' }}">
                                            + إنشاء فاتورة جديدة للعميل
                                        </a>
                                        <a href="{{ route('invoices.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition {{ request()->routeIs('invoices.index') && !request('status') ? 'bg-emerald-50/60 font-bold text-[#006837]' : '' }}">
                                            سجل الفواتير الصادرة
                                        </a>
                                        <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-red-600 transition font-bold">
                                            الفواتير غير المدفوعة / المعلقة
                                        </a>
                                        <a href="{{ route('reports.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-emerald-700 transition">
                                            تقرير الأرباح والتحصيل السريع
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Reporting Dropdown -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('reporting')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'reporting' ? null : 'reporting')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('dashboard') || request()->routeIs('reports.*') ? 'bg-slate-100 text-amber-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <span>التقارير </span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'reporting' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'reporting'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 260px; width: 260px;">
                                    
                                    <div class="w-64 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5" style="min-width: 260px; width: 260px;">
                                        <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                            التحليلات والمؤشرات والأرباح
                                        </div>
                                        <a href="{{ route('reports.index') }}" class="block px-3.5 py-1.5 hover:bg-emerald-50 text-slate-900 hover:text-emerald-700 font-extrabold transition {{ request()->routeIs('reports.index') ? 'bg-emerald-50 text-emerald-800' : '' }}">
                                             تقرير الفحص
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition {{ request()->routeIs('dashboard') ? 'bg-amber-50/60 font-bold text-amber-600' : '' }}">
                                            لوحة المؤشرات الشاملة
                                        </a>
                                        <a href="{{ route('certificates.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition">
                                            تقرير فحص الأوناش والمعدات
                                        </a>
                                        <a href="{{ route('training-certificates.index') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition">
                                            تقرير شهادات التدريب
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Configuration & Settings Dropdown -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('settings')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'settings' ? null : 'settings')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition flex items-center gap-1.5">
                                    <span> والإعدادات</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': activeMenu === 'settings' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="activeMenu === 'settings'" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs"
                                     style="min-width: 288px; width: 288px;">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100" style="min-width: 288px; width: 288px;">
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                إعدادات النظام والشركة
                                            </div>
                                            <a href="{{ route('settings.index') }}" class="block px-3.5 py-1.5 hover:bg-purple-50 text-slate-700 hover:text-[#714B67] transition font-bold">
                                                الإعدادات العامة (General Settings)
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'users']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                المستخدمون والشركات (Users &amp; Companies)
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'branding']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                الشعار والأختام والقوالب (Branding)
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'technical']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                عناصر القوائم (Menu Items)
                                            </a>
                                            <a href="{{ route('settings.index', ['tab' => 'saas']) }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                منصة SaaS والمديولات (Architecture)
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                                أدوات المنظومة
                                            </div>
                                            <a href="{{ route('apps') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                شاشة التطبيقات (Apps Launcher)
                                            </a>
                                            <a href="{{ route('certificates.verify.lookup') }}" class="block px-3.5 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                                أداة التحقق من الشهادات
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                    </nav>

                </div>

                <!-- Right Utilities (Exact Match to Image 1: Clock + Chat + Company + Avatar + Logout) -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    
                    <!-- Quick Tool 1: Chat / Communication Bubble -->
                    <button type="button" 
                            class="p-1.5 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition hidden sm:inline-flex relative" 
                            title="الرسائل والملاحظات (Activities &amp; Messages)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </button>

                    <!-- Quick Tool 2: Clock / Activities (Image 1 Match) -->
                    <button type="button" 
                            class="p-1.5 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition hidden sm:inline-flex" 
                            title="الأنشطة المجدولة وجداول الفحص (Activities)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>



                    <!-- User Initials Box (Image 1 Plum Square / Rounded 'A Administrator') -->
                    <div class="flex items-center gap-1.5 pr-1 sm:pr-2 border-r border-slate-200">
                        <div class="w-7 h-7 rounded bg-[#714B67] text-white flex items-center justify-center font-bold text-xs shadow-2xs font-en uppercase" title="{{ Auth::user()->name ?? 'Administrator' }}">
                            {{ mb_substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>

                        <div class="text-right hidden sm:block">
                            <span class="block text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-1.5 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 transition" 
                                    title="تسجيل الخروج">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Hamburger Toggle -->
                    <button type="button" 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="md:hidden p-1.5 rounded-md text-slate-600 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                </div>

            </div>
        </div>

        <!-- Mobile Drawer Navigation (Slide-down with High-Density IsoOx Categorization) -->
        <div x-show="mobileMenuOpen" 
             x-cloak 
             @click.away="mobileMenuOpen = false" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-slate-200 bg-white/95 backdrop-blur-md px-4 py-4 space-y-3 text-xs shadow-xl max-h-[80vh] overflow-y-auto">
            
            <!-- Quick Action: Start Inspection Button on Mobile Drawer -->
            <a href="{{ route('inspections.start') }}" class="flex items-center justify-between p-3 rounded-xl bg-[#006837] hover:bg-[#00502a] text-white font-extrabold shadow-sm active:scale-98 transition">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>بدء فحص ميداني جديد (شيك ليست)</span>
                </span>
                <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-mono">Mobile First</span>
            </a>

            <!-- Section 1: الفحص والشهادات -->
            <div class="space-y-1.5">
                <div class="font-bold text-slate-400 uppercase text-[10px] tracking-wider flex items-center justify-between">
                    <span>الفحص والشهادات المعتمدة</span>
                    <span class="text-[#0E4DA4] font-mono">ISO 17020</span>
                </div>
                <div class="grid grid-cols-2 gap-2 font-bold">
                    <a href="{{ route('inspections.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/survey.svg') }}" class="w-4 h-4">
                        <span>سجل الفحوصات</span>
                    </a>
                    <a href="{{ route('certificates.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/quality_control.svg') }}" class="w-4 h-4">
                        <span>سجل الشهادات</span>
                    </a>
                    <a href="{{ route('certificate-templates.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-4 h-4">
                        <span>القوالب الـ 28</span>
                    </a>
                    <a href="{{ route('certificates.verify.lookup') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/approvals.svg') }}" class="w-4 h-4">
                        <span>فحص QR Code</span>
                    </a>
                </div>
            </div>

            <!-- Section 2: الفواتير والحسابات + الأرباح -->
            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                <div class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">الحسابات والمالية</div>
                <div class="grid grid-cols-2 gap-2 font-bold">
                    <a href="{{ route('invoices.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/account.svg') }}" class="w-4 h-4">
                        <span>الفواتير والتحصيل</span>
                    </a>
                    <a href="{{ route('invoices.create') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#0E4DA4] transition flex items-center gap-2 shadow-2xs">
                        <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>إنشاء فاتورة</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/point_of_sale.svg') }}" class="w-4 h-4">
                        <span>تقرير الأرباح</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/crm.svg') }}" class="w-4 h-4">
                        <span>دليل العملاء</span>
                    </a>
                </div>
            </div>

            <!-- Section 3: الإعدادات وتثبيت التطبيق -->
            <div class="pt-2 border-t border-slate-100 space-y-1.5">
                <div class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">النظام والتطبيق</div>
                <div class="grid grid-cols-2 gap-2 font-bold">
                    <a href="{{ route('settings.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition flex items-center gap-2 shadow-2xs">
                        <img src="{{ asset('branding/IsoOx-icons/settings.svg') }}" class="w-4 h-4">
                        <span>الإعدادات العامة</span>
                    </a>
                    <a href="{{ route('settings.index', ['tab' => 'branding']) }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#0E4DA4] transition flex items-center gap-2 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>تثبيت التطبيق</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    @if((request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*')) && !request()->routeIs('certificates.verify.*') && !request()->routeIs('certificates.sticker') && !request()->routeIs('certificates.batch_stickers') && !request()->routeIs('inspections.sticker'))
        <div class="print:hidden bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <!-- Left: Subheader Navigation -->
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1 scrollbar-none">
                        <a href="{{ route('certificates.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition shrink-0 whitespace-nowrap {{ (request()->routeIs('certificates.index') && !request('status')) ? 'bg-blue-50 text-[#0E4DA4] border border-blue-200/80 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <img src="{{ asset('branding/IsoOx-icons/documents.svg') }}" class="w-3.5 h-3.5">
                            <span>سجل الشهادات</span>
                        </a>

                        <a href="{{ route('certificates.index', ['status' => 'issued']) }}" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-medium transition shrink-0 whitespace-nowrap {{ request('status') === 'issued' ? 'bg-blue-50 text-[#0E4DA4] font-bold border border-blue-200/80' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>السارية</span>
                        </a>

                        <a href="{{ route('certificates.expiring') }}" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-medium transition shrink-0 whitespace-nowrap {{ request()->routeIs('certificates.expiring') ? 'bg-blue-50 text-[#0E4DA4] font-bold border border-blue-200/80 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <img src="{{ asset('branding/IsoOx-icons/timer.svg') }}" class="w-3.5 h-3.5 object-contain" alt="">
                            <span>قريبة من الانتهاء</span>
                        </a>

                        <span class="text-slate-300 shrink-0">|</span>

                        <a href="{{ route('certificate-templates.index') }}" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-bold transition shrink-0 whitespace-nowrap {{ request()->routeIs('certificate-templates.*') ? 'bg-[#0E4DA4] text-white shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <img src="{{ asset('branding/IsoOx-icons/web_studio.svg') }}" class="w-3.5 h-3.5 object-contain" alt="">
                            <span>ستوديو قوالب الفحص</span>
                        </a>
                    </div>

                    <!-- Right: Quick Filters & Actions -->
                    <div class="hidden md:flex items-center gap-2.5 text-slate-500 text-[11px]">
                        <a href="{{ route('certificates.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-[#0E4DA4] hover:bg-blue-800 text-white font-bold transition shadow-2xs">
                            <span>+ إصدار شهادة</span>
                        </a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('certificates.verify.lookup') }}" 
                           class="hover:text-purple-700 transition flex items-center gap-1 text-slate-500 hover:underline">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>فحص QR</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('training-certificates.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('training-certificates.index') && !request('status')) ? 'bg-emerald-50 text-[#006837] border border-emerald-200/80 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <svg class="w-3.5 h-3.5 text-[#006837]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>سجل شهادات التدريب</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('training-certificates.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('training-certificates.create') ? 'bg-[#006837] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#006837] hover:bg-emerald-50/70' }}">
                            <span>إصدار شهادة تدريب</span>
                        </a>

                        <a href="{{ route('trainees.index') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('trainees.index') ? 'bg-[#006837] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#006837] hover:bg-emerald-50/70' }}">
                            <span>قاعدة بيانات المتدربين</span>
                        </a>

                        <a href="{{ route('trainees.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('trainees.create') ? 'bg-[#006837] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#006837] hover:bg-emerald-50/70' }}">
                            <span>تسجيل متدرب جديد</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center gap-2.5 text-slate-500 text-[11px]">
                        <a href="{{ route('training-certificates.index', ['status' => 'issued']) }}" 
                           class="hover:text-emerald-700 transition flex items-center gap-1 px-2 py-0.5 rounded {{ request('status') === 'issued' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                            <span>الشهادات السارية المعتمدة</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('users.*') || request()->routeIs('roles.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ request()->routeIs('users.index') ? 'bg-purple-100 text-[#714B67] border border-purple-200 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <svg class="w-3.5 h-3.5 text-[#714B67]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>سجل المستخدمين</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('users.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('users.create') ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>إضافة مستخدم جديد</span>
                        </a>

                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('roles.*') ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>أدوار النظام ومصفوفة الصلاحيات</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'users']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium text-slate-700 hover:text-[#714B67] hover:bg-purple-50">
                            <span>إعدادات الشركات والولوج</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('settings.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('settings.index', ['tab' => 'general']) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ request('tab', 'general') === 'general' ? 'bg-purple-100 text-[#714B67] border border-purple-200 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <svg class="w-3.5 h-3.5 text-[#714B67]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>الإعدادات العامة</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('settings.index', ['tab' => 'users']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'users' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>المستخدمون والشركات</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'localization']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'localization' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>اللغات والترجمة</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'google_sheets']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'google_sheets' ? 'bg-[#006837] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-emerald-50' }}">
                            <span>ربط Google Sheets</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'branding']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'branding' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>الشعار والأختام والقوالب</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'technical']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'technical' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>عناصر القوائم (Menu Items)</span>
                        </a>

                        <a href="{{ route('settings.index', ['tab' => 'saas']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('tab') === 'saas' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#714B67] hover:bg-purple-50' }}">
                            <span>منصة SaaS والمديولات</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('tasks.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs overflow-x-auto py-1">
                    <div class="flex items-center gap-1.5 flex-nowrap">
                        <a href="{{ route('tasks.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('tasks.index') && !request('status')) ? 'bg-blue-50 text-[#0E4DA4] border border-blue-200 shadow-2xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100' }}">
                            <svg class="w-3.5 h-3.5 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>أوامر الشغل ومهام الفحص</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('tasks.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('tasks.create') ? 'bg-[#0E4DA4] text-white font-bold shadow-xs' : 'text-slate-700 hover:text-[#0E4DA4] hover:bg-blue-50/70' }}">
                            <span>+ إضافة أمر شغل جديد</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('status') === 'in_progress' ? 'bg-amber-50 text-amber-800 font-bold border border-amber-200' : 'text-slate-700 hover:text-amber-800 hover:bg-amber-50/70' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>قيد الفحص الميداني</span>
                        </a>

                        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request('status') === 'completed' ? 'bg-emerald-50 text-emerald-800 font-bold border border-emerald-200' : 'text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/70' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                            <span>مكتملة الفحص</span>
                        </a>
                    </div>

                    <!-- Right: Quick Google Sheets Sync -->
                    <div class="flex items-center gap-2 flex-nowrap">
                        <form method="POST" action="{{ route('tasks.sync_all') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs flex items-center gap-1.5 transition active:scale-95" 
                                    title="مزامنة فورية مع Google Sheets">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>مزامنة Google Sheets</span>
                            </button>
                        </form>
                        <a href="{{ route('settings.index', ['tab' => 'google_sheets']) }}" 
                           class="text-slate-400 hover:text-slate-700 p-1" title="إعدادات الربط">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('customers.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('customers.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition bg-amber-50 text-amber-900 border border-amber-200 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>دليل العملاء والشركات</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('customers.create') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium transition {{ request()->routeIs('customers.create') ? 'bg-amber-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-amber-800 hover:bg-amber-50' }}">
                            <span>إضافة عميل جديد</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request()->routeIs('dashboard'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition bg-blue-50 text-[#0E4DA4] border border-blue-200 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>لوحة المؤشرات والتقارير</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <a href="{{ route('certificates.index') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium text-slate-700 hover:text-[#0E4DA4] hover:bg-blue-50">
                            <span>مؤشرات شهادات الفحص</span>
                        </a>

                        <a href="{{ route('training-certificates.index') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50">
                            <span>مؤشرات شهادات التدريب</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 w-full">
        @if(session('success'))
            <div class="flex items-center gap-3 p-3 mb-3 rounded-lg bg-[#E8F7EC] border border-[#22A447]/30 text-emerald-900 text-xs shadow-xs">
                <svg class="w-5 h-5 text-[#22A447] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="font-bold">{{ session('success') }}</div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3 mb-3 rounded-lg bg-red-50 border border-red-200 text-red-900 text-xs shadow-xs">
                <div class="font-bold mb-1">يرجى تصحيح الأخطاء التالية:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main View Content -->
    <main class="flex-1 pb-20 md:pb-10">
        @yield('content')
    </main>

    <!-- Mobile Quick-Access Floating Bottom Bar (IsoOx Mobile Ergonomic Pattern) -->
    @unless(View::hasSection('hide_bottom_nav'))
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 px-3 py-1.5 shadow-2xl flex items-center justify-around text-slate-500 transition-all select-none">
        <!-- 1. Apps Home -->
        <a href="{{ route('apps') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition {{ request()->routeIs('apps') ? 'text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-900' }}">
            <img src="{{ asset('branding/IsoOx-icons/apps.svg') }}" class="w-5 h-5 object-contain" alt="Apps">
            <span class="text-[10px]">الرئيسية</span>
        </a>

        <!-- 2. Start Field Inspection (Highlighted Center Action) -->
        <a href="{{ route('inspections.start') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition group">
            <div class="w-9 h-9 -mt-4 rounded-full bg-[#006837] text-white shadow-md flex items-center justify-center border-2 border-white transform active:scale-95 transition group-hover:bg-[#00502a]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="text-[10px] font-bold text-slate-800">بدء فحص</span>
        </a>

        <!-- 3. Certificates -->
        <a href="{{ route('certificates.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition {{ request()->routeIs('certificates.*') ? 'text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-900' }}">
            <img src="{{ asset('branding/IsoOx-icons/quality_control.svg') }}" class="w-5 h-5 object-contain" alt="Certificates">
            <span class="text-[10px]">الشهادات</span>
        </a>

        <!-- 4. Invoices -->
        <a href="{{ route('invoices.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition {{ request()->routeIs('invoices.*') ? 'text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-900' }}">
            <img src="{{ asset('branding/IsoOx-icons/account.svg') }}" class="w-5 h-5 object-contain" alt="Invoices">
            <span class="text-[10px]">الفواتير</span>
        </a>

        <!-- 5. Reports & Profits -->
        <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition {{ request()->routeIs('reports.*') ? 'text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-900' }}">
            <img src="{{ asset('branding/IsoOx-icons/point_of_sale.svg') }}" class="w-5 h-5 object-contain" alt="Reports">
            <span class="text-[10px]">الأرباح</span>
        </a>

        <!-- 6. Settings -->
        <a href="{{ route('settings.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition {{ request()->routeIs('settings.*') ? 'text-[#0E4DA4] font-bold' : 'text-slate-500 hover:text-slate-900' }}">
            <img src="{{ asset('branding/IsoOx-icons/settings.svg') }}" class="w-5 h-5 object-contain" alt="Settings">
            <span class="text-[10px]">الإعدادات</span>
        </a>
    </nav>
    @endunless

    <!-- Global PWA Service Worker & Install Listener -->
    <script>
        // Track PWA install prompt globally
        window.deferredPwaPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPwaPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-ready'));
        });
    </script>

</body>
</html>
