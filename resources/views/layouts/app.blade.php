<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Safero ERP') }} - @yield('title', 'نظام الفحص والشهادات المعتمدة')</title>

    <!-- Google Fonts: Cairo & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js Core (High Reliability CDN + Local Build) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

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
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-[#006837] selection:text-white bg-[#F8FAFC]">

    <!-- Top IsoOx 17/18 Enterprise Navbar (Crisp, High-Density, with Apps Return Button) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 text-slate-800 shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-13">
                
                <!-- Left: 9-Dots Apps Drawer Button + Active Module Brand + Dropdown Sub-menus -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- 9-Dots Button to return to Apps (/apps) -->
                    <a href="{{ route('apps') }}" 
                       class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-slate-100 text-slate-600 hover:text-black transition-all duration-150 relative group" 
                       title="الرجوع إلى شاشة التطبيقات والمديولات (Apps Launcher)">
                        <img src="{{ asset('branding/odoo-icons/apps.svg') }}" class="w-5 h-5 object-contain" alt="Apps">
                        <span class="sr-only">Apps Launcher</span>
                    </a>

                    <!-- Current Active Module Brand / Icon -->
                    <div class="flex items-center gap-2 pr-2 border-r border-slate-200">
                        @if(request()->routeIs('settings.*'))
                            <div class="w-6 h-6 rounded bg-purple-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-purple-200">
                                <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-4 h-4 object-contain" alt="Settings">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">الإعدادات (Settings)</span>
                        @elseif(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
                            <div class="w-6 h-6 rounded bg-emerald-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-emerald-200">
                                <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-4 h-4 object-contain" alt="Training">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">شهادات التدريب</span>
                        @elseif(request()->routeIs('certificates.*'))
                            <div class="w-6 h-6 rounded bg-blue-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-blue-200">
                                <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-4 h-4 object-contain" alt="Inspection">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">شهادات الفحص</span>
                        @elseif(request()->routeIs('customers.*'))
                            <div class="w-6 h-6 rounded bg-amber-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-amber-200">
                                <img src="{{ asset('branding/odoo-icons/crm.svg') }}" class="w-4 h-4 object-contain" alt="Customers">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">العملاء والشركات</span>
                        @elseif(request()->routeIs('users.*') || request()->routeIs('roles.*'))
                            <div class="w-6 h-6 rounded bg-purple-50 text-white flex items-center justify-center p-0.5 shadow-2xs border border-purple-200">
                                <img src="{{ asset('branding/odoo-icons/hr.svg') }}" class="w-4 h-4 object-contain" alt="Users">
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">المستخدمون والصلاحيات</span>
                        @else
                            <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" alt="{{ setting('company_name_en', 'Safero') }}" class="h-5 w-auto object-contain">
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">{{ setting('company_name_en', 'Safero ERP') }}</span>
                        @endif
                    </div>

                    <!-- IsoOx Enterprise Submenu Navigation with Grouped Cascading Dropdowns -->
                    <nav class="hidden lg:flex items-center gap-0.5" 
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
                                <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-3.5 h-3.5 object-contain" alt="General Settings">
                                <span>الإعدادات العامة (General Settings)</span>
                            </a>

                            <a href="{{ route('settings.index', ['tab' => 'users']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab') === 'users' ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/contacts.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Users">
                                <span>المستخدمون والشركات (Users &amp; Companies)</span>
                            </a>

                            <a href="{{ route('settings.index', ['tab' => 'localization']) }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request('tab') === 'localization' ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/website.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Translations">
                                <span>اللغات والترجمة (Translations)</span>
                            </a>

                            <!-- Technical Dropdown (Image 1 Highlight: Menu Items, Database, Automation) -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('technical')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'technical' ? null : 'technical')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ in_array(request('tab'), ['technical', 'saas']) ? 'bg-purple-100 text-[#714B67]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <img src="{{ asset('branding/odoo-icons/board.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Technical">
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100">
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
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('certificates.*') && !request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Inspection">
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-72 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100">
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
                                    <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Training">
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-64 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100">
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
                                    <img src="{{ asset('branding/odoo-icons/crm.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Customers">
                                    <span>العملاء والشركات</span>
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-60 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5">
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

                            <!-- 4. Reporting Dropdown -->
                            <div class="relative py-2 -my-2 menu-item-group" 
                                 @mouseenter="enter('reporting')" 
                                 @mouseleave="leave()">
                                
                                <button type="button" 
                                        @click="activeMenu = (activeMenu === 'reporting' ? null : 'reporting')"
                                        class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-amber-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <img src="{{ asset('branding/odoo-icons/board.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Reporting">
                                    <span>التقارير والمؤشرات</span>
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-60 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5">
                                        <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                            التحليلات والمؤشرات
                                        </div>
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
                                    <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Settings">
                                    <span>التهيئة والإعدادات</span>
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
                                     class="menu-popup-box absolute right-0 top-full pt-1 z-50 text-xs">
                                    
                                    <div class="w-68 bg-white border border-slate-200 rounded-lg shadow-2xl py-1.5 divide-y divide-slate-100">
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
                            class="lg:hidden p-1.5 rounded-md text-slate-600 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                </div>

            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" 
             x-cloak 
             @click.away="mobileMenuOpen = false" 
             class="lg:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-2 text-xs shadow-lg"
             style="display: none;">
            
            <div class="font-bold text-slate-400 uppercase text-[10px]">شهادات الفحص</div>
            <div class="grid grid-cols-2 gap-1.5">
                <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>فحص أوناش</span>
                </a>
                <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>مهمات رفع</span>
                </a>
                <a href="{{ route('certificates.create', ['type' => 'general']) }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>معدات عامة</span>
                </a>
                <a href="{{ route('certificates.index') }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>سجل الشهادات</span>
                </a>
            </div>

            <div class="font-bold text-slate-400 uppercase text-[10px] pt-2 border-t border-slate-100">شهادات التدريب</div>
            <div class="grid grid-cols-2 gap-1.5">
                <a href="{{ route('training-certificates.create') }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>إصدار تدريب</span>
                </a>
                <a href="{{ route('trainees.index') }}" class="p-2 rounded bg-slate-50 font-bold text-slate-700 text-center">
                    <span>المتدربين</span>
                </a>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('customers.index') }}" class="font-bold text-slate-700">دليل العملاء</a>
                <a href="{{ route('dashboard') }}" class="font-bold text-amber-600">لوحة المؤشرات</a>
                <a href="{{ route('apps') }}" class="font-bold text-[#0E4DA4]">جميع التطبيقات</a>
            </div>
        </div>
    </header>

    <!-- Secondary Module Sub-Navbar (Odoo Tree Menu Bar - Image 1 Exact Match) -->
    @if(request()->routeIs('certificates.*') && !request()->routeIs('certificates.verify.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-3 text-xs">
                   

                    <!-- Right: Quick Filters & Actions -->
                    <div class="hidden md:flex items-center gap-2.5 text-slate-500 text-[11px]">
                        <a href="{{ route('certificates.index', ['status' => 'issued']) }}" 
                           class="hover:text-emerald-700 transition flex items-center gap-1 px-2 py-0.5 rounded {{ request('status') === 'issued' ? 'bg-emerald-50 text-emerald-800 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
                            <span>الشهادات السارية المعتمدة</span>
                        </a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('certificates.index', ['status' => 'draft']) }}" 
                           class="hover:text-sky-700 transition flex items-center gap-1 px-2 py-0.5 rounded {{ request('status') === 'draft' ? 'bg-sky-50 text-sky-800 font-bold' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                            <span>المسودات</span>
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
    <main class="flex-1 pb-10">
        @yield('content')
    </main>

</body>
</html>
