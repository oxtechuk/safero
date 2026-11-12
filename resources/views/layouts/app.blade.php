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
            background-color: #F4F6F8;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-[#0E4DA4] selection:text-white">

    <!-- Top Odoo-Style Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#0E4DA4] text-white shadow-md border-b border-[#0a3a7d]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left: App Launcher & Logo -->
                <div class="flex items-center gap-4">
                    <!-- Odoo-Style Apps Drawer Button -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-200 text-white" 
                       title="شاشة التطبيقات والمديولات (Apps)">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/>
                        </svg>
                    </a>

                    <!-- Safero Brand -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-10 w-auto bg-white p-1 rounded-lg shadow-sm">
                        <div class="hidden sm:block">
                            <span class="text-lg font-black tracking-wide text-white block leading-tight font-en">SAFERO ERP</span>
                            <span class="text-xs text-[#E8F7EC] font-medium block">الفحص والمعايرة وشهادات ISO</span>
                        </div>
                    </a>

                    <!-- Top Menu Links -->
                    <nav class="hidden md:flex items-center gap-1 mr-6">
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                            الرئيسية
                        </a>
                        <a href="{{ route('certificates.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('certificates.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                            شهادات الفحص
                        </a>
                        <a href="{{ route('customers.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('customers.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                            العملاء
                        </a>
                        <a href="{{ route('certificates.verify.lookup') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('certificates.verify.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                            أداة التحقق
                        </a>
                    </nav>
                </div>

                <!-- Right: Quick Actions & User Menu -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('certificates.create') }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#22A447] hover:bg-[#1b8539] text-white text-xs sm:text-sm font-bold shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>إصدار شهادة</span>
                    </a>

                    <div class="flex items-center gap-2 pr-2 border-r border-white/20">
                        <div class="text-left hidden lg:block">
                            <span class="block text-xs font-bold text-white">{{ Auth::user()->name ?? 'مستخدم النظام' }}</span>
                            <span class="block text-[11px] text-[#E8F7EC]">{{ Auth::user()->title ?? 'مسؤول معتمد' }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-2 rounded-lg text-blue-100 hover:text-white hover:bg-red-500/20 transition" 
                                    title="تسجيل الخروج">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 mb-4 rounded-xl bg-[#E8F7EC] border border-[#22A447]/30 text-emerald-900 shadow-sm">
                <svg class="w-6 h-6 text-[#22A447] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="font-semibold text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-900 shadow-sm">
                <div class="font-bold text-sm mb-1">يرجى تصحيح الأخطاء التالية:</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                جميع الحقوق محفوظة &copy; {{ date('Y') }} <span class="font-bold text-[#0E4DA4]">شركة سافيرو (Safero Services & Consultations)</span>
            </div>
            <div class="text-slate-400 font-en">
                Inspection & ISO Certification ERP Portal
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

