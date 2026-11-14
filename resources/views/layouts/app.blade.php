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

    <!-- Top Odoo 17/18 Enterprise Navbar (White, Crisp, with Apps Return Button) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 text-slate-800 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                
                <!-- Left: 9-Dots Apps Drawer Button + Active Module + Sub-menus -->
                <div class="flex items-center gap-3 sm:gap-5">
                    
                    <!-- 9-Dots Button to return to Apps (/app) -->
                    <a href="{{ route('apps') }}" 
                       class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-slate-100 text-slate-600 hover:text-black transition-all duration-150 relative group" 
                       title="الرجوع إلى شاشة التطبيقات والمديولات (Apps)">
                        <img src="{{ asset('branding/odoo-icons/apps.svg') }}" class="w-5 h-5 object-contain" alt="Apps">
                        <span class="sr-only">Apps Launcher</span>
                    </a>

                    <!-- Current Active Module Brand / Icon -->
                    <div class="flex items-center gap-2 pr-2 border-r border-slate-200">
                        @if(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-white flex items-center justify-center p-1 shadow-2xs border border-emerald-200">
                                <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-5 h-5 object-contain" alt="Training">
                            </div>
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">شهادات التدريب</span>
                        @elseif(request()->routeIs('certificates.*') || request()->routeIs('customers.*'))
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-white flex items-center justify-center p-1 shadow-2xs border border-blue-200">
                                <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-5 h-5 object-contain" alt="Inspection">
                            </div>
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">شهادات الفحص</span>
                        @else
                            <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero" class="h-6 w-auto object-contain">
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">Safero ERP</span>
                        @endif
                    </div>

                    <!-- Odoo Enterprise Submenu Navigation with Categorized Dropdowns -->
                    <nav class="hidden md:flex items-center gap-1">
                        
                        <!-- 1. Inspection Certificates Dropdown (Odoo Style) -->
                        <div class="relative group">
                            <a href="{{ route('certificates.index') }}" 
                               class="px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('certificates.*') && !request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-4 h-4 object-contain">
                                <span>شهادات الفحص</span>
                                <svg class="w-3 h-3 text-slate-400 group-hover:rotate-180 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            
                            <!-- Odoo Sub-menu Dropdown Popup -->
                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute right-0 top-full mt-1 w-64 bg-white border border-slate-200 rounded-lg shadow-xl py-2 z-50 text-xs divide-y divide-slate-100">
                                <div class="py-1">
                                    <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">إصدار نموذج فحص (New Inspection)</span>
                                    <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="flex items-center gap-2.5 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] transition">
                                        <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-4 h-4 object-contain">
                                        <div>
                                            <span class="font-bold block">فحص الأوناش والروافع</span>
                                            <span class="text-[10px] text-slate-400 font-en">Lifting Appliances (Cranes)</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="flex items-center gap-2.5 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] transition">
                                        <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-4 h-4 object-contain">
                                        <div>
                                            <span class="font-bold block">مهمات الرفع والحبال</span>
                                            <span class="text-[10px] text-slate-400 font-en">Lifting Accessories (Slings)</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('certificates.create', ['type' => 'general']) }}" class="flex items-center gap-2.5 px-3 py-1.5 hover:bg-blue-50 text-slate-700 hover:text-[#0E4DA4] transition">
                                        <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-4 h-4 object-contain">
                                        <div>
                                            <span class="font-bold block">معدات عامة وباليتات</span>
                                            <span class="text-[10px] text-slate-400 font-en">General Cargo Units</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="py-1">
                                    <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">السجلات والعمليات</span>
                                    <a href="{{ route('certificates.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 hover:bg-slate-50 text-slate-700">
                                        <img src="{{ asset('branding/odoo-icons/documents.svg') }}" class="w-4 h-4 object-contain">
                                        <span>جميع الشهادات (All Certificates)</span>
                                    </a>
                                    <a href="{{ route('certificates.index', ['status' => 'issued']) }}" class="flex items-center gap-2.5 px-3 py-1.5 hover:bg-slate-50 text-slate-700">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#22A447] flex items-center justify-center font-bold text-[10px]">✓</span>
                                        <span>الشهادات السارية المعتمدة</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Training Certificates Dropdown -->
                        <div class="relative group">
                            <a href="{{ route('training-certificates.index') }}" 
                               class="px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-4 h-4 object-contain">
                                <span>شهادات التدريب</span>
                                <svg class="w-3 h-3 text-slate-400 group-hover:rotate-180 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>

                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute right-0 top-full mt-1 w-60 bg-white border border-slate-200 rounded-lg shadow-xl py-2 z-50 text-xs divide-y divide-slate-100">
                                <div class="py-1">
                                    <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">شهادات المتدربين</span>
                                    <a href="{{ route('training-certificates.create') }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-emerald-50 text-slate-700 hover:text-[#006837]">
                                        <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-4 h-4 object-contain">
                                        <span>إصدار شهادة تدريب جديدة</span>
                                    </a>
                                    <a href="{{ route('training-certificates.index') }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-50 text-slate-700">
                                        <img src="{{ asset('branding/odoo-icons/documents.svg') }}" class="w-4 h-4 object-contain">
                                        <span>سجل شهادات التدريب</span>
                                    </a>
                                </div>
                                <div class="py-1">
                                    <span class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">إدارة المتدربين</span>
                                    <a href="{{ route('trainees.index') }}" class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-50 text-slate-700">
                                        <img src="{{ asset('branding/odoo-icons/contacts.svg') }}" class="w-4 h-4 object-contain">
                                        <span>قاعدة بيانات المتدربين</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Customers Link -->
                        <a href="{{ route('customers.index') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('customers.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <img src="{{ asset('branding/odoo-icons/contacts.svg') }}" class="w-4 h-4 object-contain">
                            <span>العملاء</span>
                        </a>

                        <!-- 4. Verification Link -->
                        <a href="{{ route('certificates.verify.lookup') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <img src="{{ asset('branding/odoo-icons/approvals.svg') }}" class="w-4 h-4 object-contain">
                            <span>أداة التحقق</span>
                        </a>

                        <!-- 5. Reports Link -->
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-amber-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <img src="{{ asset('branding/odoo-icons/board.svg') }}" class="w-4 h-4 object-contain">
                            <span>لوحة المؤشرات</span>
                        </a>
                    </nav>

                </div>

                <!-- Right Utilities: Company Switcher + Clock + Avatar + Logout -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                 

                    <!-- User Avatar & Quick Actions -->
                    <div class="flex items-center gap-2">
                        <!-- User Initials Circle (Odoo Style) -->
                        <div class="w-8 h-8 rounded-full bg-[#714B67] text-white flex items-center justify-center font-bold text-xs shadow-xs font-en uppercase" title="{{ Auth::user()->name ?? 'User' }}">
                            {{ mb_substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>

                        <div class="text-right hidden sm:block">
                            <span class="block text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'المسؤول' }}</span>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" 
                                    title="تسجيل الخروج">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>

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
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type', 'crane') === 'crane') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Cranes">
                            <span>فحص الأوناش والروافع</span>
                        </a>

                        <!-- Type 2: Slings / Accessories -->
                        <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type') === 'accessory') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-3.5 h-3.5 object-contain" alt="Accessories">
                            <span>مهمات الرفع والحبال</span>
                        </a>

                        <!-- Type 3: General Equipment -->
                        <a href="{{ route('certificates.create', ['type' => 'general']) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md font-bold transition {{ (request()->routeIs('certificates.create') && request('type') === 'general') ? 'bg-[#0E4DA4] text-white shadow-xs' : 'text-slate-600 hover:text-[#0E4DA4] hover:bg-blue-50/50' }}">
                            <img src="{{ asset('branding/odoo-icons/repair.svg') }}" class="w-3.5 h-3.5 object-contain" alt="General">
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
