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
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/>
                        </svg>
                        <span class="sr-only">Apps Launcher</span>
                    </a>

                    <!-- Current Active Module Brand / Icon -->
                    <div class="flex items-center gap-2 pr-2 border-r border-slate-200">
                        @if(request()->routeIs('training-certificates.*') || request()->routeIs('trainees.*'))
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-emerald-600 to-teal-400 text-white flex items-center justify-center shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">شهادات التدريب</span>
                        @elseif(request()->routeIs('certificates.*') || request()->routeIs('customers.*'))
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-[#0E4DA4] to-indigo-500 text-white flex items-center justify-center shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">شهادات الفحص</span>
                        @else
                            <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero" class="h-6 w-auto object-contain">
                            <span class="text-sm font-extrabold text-slate-900 tracking-tight">Safero ERP</span>
                        @endif
                    </div>

                    <!-- Odoo Submenu Navigation Tabs -->
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('training-certificates.index') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('training-certificates.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            شهادات التدريب
                        </a>
                        <a href="{{ route('trainees.index') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('trainees.*') ? 'bg-slate-100 text-[#006837]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            المتدربون
                        </a>
                        <a href="{{ route('certificates.index') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('certificates.index') || request()->routeIs('certificates.show') || request()->routeIs('certificates.edit') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            شهادات الفحص
                        </a>
                        <a href="{{ route('customers.index') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('customers.*') ? 'bg-slate-100 text-[#0E4DA4]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            العملاء
                        </a>
                        <a href="{{ route('certificates.verify.lookup') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('certificates.verify.*') ? 'bg-slate-100 text-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            أداة التحقق
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-amber-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            التقارير
                        </a>
                    </nav>

                </div>

                <!-- Right Utilities: Company Switcher + Clock + Avatar + Logout -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Company Switcher Badge -->
                    <div class="hidden lg:flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                        <svg class="w-3.5 h-3.5 text-[#006837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Safero Services &amp; Consultations</span>
                    </div>

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
