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

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Poppins', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-[#006837] selection:text-white bg-[#F8FAFC]">

    <!-- Top Odoo 17/18 Enterprise Navbar (Crisp, High-Density, with Apps Return Button) -->
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
                        @if(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
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
                        @else
                            <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero" class="h-5 w-auto object-contain">
                            <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">Safero ERP</span>
                        @endif
                    </div>

                    <!-- Odoo Enterprise Submenu Navigation with Grouped Cascading Dropdowns -->
                    <nav class="hidden lg:flex items-center gap-0.5">
                        
                        <!-- 1. Inspection Certificates Dropdown (Odoo Style) -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false">
                            <a href="{{ route('certificates.index') }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('certificates.*') && !request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-3.5 h-3.5 object-contain">
                                <span>شهادات الفحص</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            
                            <!-- Odoo Cascading Sub-menu Dropdown (Text-only, No Icons, Closed by default, Opens on hover) -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-0.5 w-64 bg-white border border-slate-200 rounded-md shadow-2xl py-1.5 z-50 text-xs"
                                 style="display: none;">
                                
                                <!-- Section 1: Types -->
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    نماذج الفحص المعتمدة
                                </div>
                                <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    <span class="font-bold block text-xs">فحص الأوناش والروافع</span>
                                    <span class="text-[10px] text-slate-400 font-en">Lifting Appliances (Cranes - LOLER)</span>
                                </a>
                                <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    <span class="font-bold block text-xs">مهمات الرفع والحبال</span>
                                    <span class="text-[10px] text-slate-400 font-en">Lifting Accessories (Slings / Shackles)</span>
                                </a>
                                <a href="{{ route('certificates.create', ['type' => 'general']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    <span class="font-bold block text-xs">معدات عامة وباليتات</span>
                                    <span class="text-[10px] text-slate-400 font-en">General Cargo Units</span>
                                </a>

                                <!-- Section 2: Records -->
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block border-t border-slate-100 mt-1">
                                    السجلات والعمليات
                                </div>
                                <a href="{{ route('certificates.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    جميع الشهادات (All Certificates)
                                </a>
                                <a href="{{ route('certificates.index', ['status' => 'issued']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    الشهادات السارية المعتمدة
                                </a>
                                <a href="{{ route('certificates.index', ['status' => 'draft']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    المسودات قيد المراجعة
                                </a>

                                <!-- Section 3: Compliance -->
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block border-t border-slate-100 mt-1">
                                    التحقق والامتثال
                                </div>
                                <a href="{{ route('certificates.verify.lookup') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-purple-700 transition">
                                    فحص رمز الاستجابة QR Code
                                </a>
                            </div>
                        </div>

                        <!-- 2. Training Certificates Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false">
                            <a href="{{ route('training-certificates.index') }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-3.5 h-3.5 object-contain">
                                <span>شهادات التدريب</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>

                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-0.5 w-60 bg-white border border-slate-200 rounded-md shadow-2xl py-1.5 z-50 text-xs"
                                 style="display: none;">
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    شهادات المتدربين
                                </div>
                                <a href="{{ route('training-certificates.create') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition">
                                    إصدار شهادة تدريب جديدة
                                </a>
                                <a href="{{ route('training-certificates.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition">
                                    سجل شهادات التدريب المعتمدة
                                </a>
                                <a href="{{ route('training-certificates.index', ['status' => 'issued']) }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition">
                                    الشهادات السارية المعتمدة
                                </a>

                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block border-t border-slate-100 mt-1">
                                    إدارة المتدربين
                                </div>
                                <a href="{{ route('trainees.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition">
                                    قاعدة بيانات المتدربين
                                </a>
                                <a href="{{ route('trainees.create') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#006837] transition">
                                    تسجيل متدرب جديد
                                </a>
                            </div>
                        </div>

                        <!-- 3. Customers Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false">
                            <a href="{{ route('customers.index') }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('customers.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/crm.svg') }}" class="w-3.5 h-3.5 object-contain">
                                <span>العملاء والشركات</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>

                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-0.5 w-56 bg-white border border-slate-200 rounded-md shadow-2xl py-1.5 z-50 text-xs"
                                 style="display: none;">
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    إدارة العملاء
                                </div>
                                <a href="{{ route('customers.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    دليل الشركات والمصانع
                                </a>
                                <a href="{{ route('customers.create') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-[#0E4DA4] transition">
                                    إضافة عميل أو شركة جديدة
                                </a>
                            </div>
                        </div>

                        <!-- 4. Reporting (Image 1 'Reporting') -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false">
                            <a href="{{ route('dashboard') }}" 
                               class="px-2.5 py-1.5 rounded text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-amber-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/board.svg') }}" class="w-3.5 h-3.5 object-contain">
                                <span>التقارير والمؤشرات</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>

                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-0.5 w-56 bg-white border border-slate-200 rounded-md shadow-2xl py-1.5 z-50 text-xs"
                                 style="display: none;">
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    التحليلات والمؤشرات
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition">
                                    لوحة المؤشرات الشاملة
                                </a>
                                <a href="{{ route('certificates.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition">
                                    تقرير فحص الأوناش والمعدات
                                </a>
                                <a href="{{ route('training-certificates.index') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-amber-600 transition">
                                    تقرير شهادات التدريب
                                </a>
                            </div>
                        </div>

                        <!-- 5. Configuration / Technical (Image 1 'Configuration' & Image 3 'Technical') -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false">
                            <button type="button" 
                                    class="px-2.5 py-1.5 rounded text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition flex items-center gap-1.5">
                                <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-3.5 h-3.5 object-contain">
                                <span>التهيئة والإعدادات</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-0.5 w-60 bg-white border border-slate-200 rounded-md shadow-2xl py-1.5 z-50 text-xs"
                                 style="display: none;">
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    إعدادات النظام
                                </div>
                                <a href="{{ route('apps') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                    شاشة التطبيقات (Apps Launcher)
                                </a>
                                <a href="{{ route('certificates.verify.lookup') }}" class="block px-3 py-1.5 hover:bg-slate-100/80 text-slate-700 hover:text-slate-900 transition">
                                    أداة التحقق من الشهادات
                                </a>
                                <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block border-t border-slate-100 mt-1">
                                    المعايير الهندسية
                                </div>
                                <div class="px-3 py-1 text-[11px] text-slate-500 flex items-center justify-between">
                                    <span>ISO/IEC 17020</span>
                                    <span class="text-[9px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-mono font-bold">Standard</span>
                                </div>
                                <div class="px-3 py-1 text-[11px] text-slate-500 flex items-center justify-between">
                                    <span>LOLER 1998 / PUWER</span>
                                    <span class="text-[9px] bg-blue-50 text-[#0E4DA4] px-1.5 py-0.5 rounded font-mono font-bold">Lifting</span>
                                </div>
                            </div>
                        </div>

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

                    <!-- Company Selector (Image 1 'My Company') -->
                    <div class="hidden md:flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="truncate max-w-[130px]">Safero Inspection</span>
                    </div>

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

    <!-- Secondary Sub-Navbar for Inspection Certificates Module -->
    @if(request()->routeIs('certificates.*'))
        <div class="bg-white border-b border-slate-200 shadow-2xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 gap-4 text-xs">
                    <!-- Left: 3 Certificate Types Sub-Tabs -->
                    <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                        <a href="{{ route('certificates.index') }}" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-bold transition {{ request()->routeIs('certificates.index') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>سجل الشهادات</span>
                        </a>

                        <span class="text-slate-300">|</span>

                        <span class="text-slate-400 font-semibold text-[11px] hidden sm:inline">إصدار نموذج:</span>

                        <!-- Type 1: Crane -->
                        <a href="{{ route('certificates.create', ['type' => 'crane']) }}" 
                           class="inline-flex items-center px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type', 'crane') === 'crane') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <span>فحص الأوناش والروافع</span>
                        </a>

                        <!-- Type 2: Slings / Accessories -->
                        <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" 
                           class="inline-flex items-center px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type') === 'accessory') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <span>مهمات الرفع والحبال</span>
                        </a>

                        <!-- Type 3: General Equipment -->
                        <a href="{{ route('certificates.create', ['type' => 'general']) }}" 
                           class="inline-flex items-center px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type') === 'general') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <span>معدات عامة وباليتات</span>
                        </a>
                    </div>

                    <!-- Right: Quick Status -->
                    <div class="hidden md:flex items-center gap-2 text-slate-400 text-[11px]">
                        <span>نظام الفحص الهندسي المعتمد (ISO 17020)</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22A447]"></span>
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
