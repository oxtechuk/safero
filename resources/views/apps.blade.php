<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تطبيقات ومديولات النظام &mdash; Safero ERP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Kalam:wght@700&family=Cairo:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Poppins', system-ui, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .font-script {
            font-family: 'Kalam', 'Caveat', cursive, sans-serif;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }

        /* Marker Highlighter Effect for 'one platform.' */
        .brush-highlight {
            position: relative;
            display: inline-block;
            padding: 2px 14px;
            color: #1a1a1a;
            z-index: 1;
        }
        .brush-highlight::before {
            content: "";
            position: absolute;
            inset: 2px -4px;
            background: linear-gradient(135deg, #FFB830, #FFA31A);
            border-radius: 8px 12px 10px 14px;
            transform: rotate(-1.5deg);
            z-index: -1;
            box-shadow: 0 2px 8px rgba(255, 163, 26, 0.35);
        }

        /* Brush Underline Effect for 'affordable!' */
        .brush-underline {
            position: relative;
            display: inline-block;
        }
        .brush-underline::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -6px;
            height: 6px;
            background: #2563eb;
            border-radius: 4px;
            transform: rotate(-0.8deg);
        }

        /* Subtle curved dome / arch separating the top from apps */
        .curved-arch {
            position: relative;
            background-color: #F8FAFC;
            border-top-left-radius: 50% 40px;
            border-top-right-radius: 50% 40px;
            padding-top: 50px;
            padding-bottom: 70px;
            min-height: calc(100vh - 240px);
        }

        @media (min-width: 1024px) {
            .curved-arch {
                border-top-left-radius: 50% 60px;
                border-top-right-radius: 50% 60px;
                padding-top: 60px;
            }
        }

        /* Odoo App Card */
        .app-card-btn {
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .app-card-btn:hover {
            transform: translateY(-8px) scale(1.04);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.12), 0 10px 15px -5px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col justify-between selection:bg-[#0E4DA4] selection:text-white">

    <!-- Top Minimal Navigation Bar -->
    <header class="w-full bg-white border-b border-slate-100 py-3 px-6 sm:px-10 z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            
            <!-- Left: Logo & Search -->
            <div class="flex items-center gap-6">
                <a href="{{ route('apps') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-10 w-auto object-contain">
                    
                </a>

                <!-- Instant Search / Filter Box -->
                <div class="relative w-48 sm:w-64">
                    <input type="text" 
                           id="appSearchInput" 
                           placeholder="بحث في المديولات..." 
                           class="w-full pr-8 pl-3 py-1.5 rounded-full border border-slate-200 bg-slate-50 text-xs focus:bg-white focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/15 outline-none transition">
                    <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Right: Quick Dashboard link, User Info & Logout -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-[#0E4DA4] hover:bg-[#E6F2FF] text-slate-700 hover:text-[#0E4DA4] text-xs font-bold transition">
                    <svg class="w-4 h-4 text-[#0E4DA4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>لوحة التقارير</span>
                </a>

                <div class="flex items-center gap-2 pr-2 border-r border-slate-200">
                    <div class="text-left hidden lg:block">
                        <span class="block text-xs font-bold text-slate-900">{{ Auth::user()->name ?? 'مدير النظام' }}</span>
                        <span class="block text-[10px] text-slate-500">{{ Auth::user()->email ?? '' }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition" 
                                title="تسجيل الخروج">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">

        <!-- Top Slogan / Hero (Matching the exact wording and style from the image) -->
        <div class="pt-10 pb-8 px-4 text-center select-none" dir="ltr">
            <h1 class="font-script text-3xl sm:text-5xl lg:text-[54px] font-bold text-slate-900 tracking-tight leading-snug">
                All your business on <span class="brush-highlight">one platform.</span>
            </h1>
            <p class="font-script text-2xl sm:text-4xl lg:text-[42px] font-bold text-slate-800 mt-2">
                Simple, efficient, yet <span class="brush-underline">affordable!</span>
            </p>
            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-3 font-sans" dir="rtl">
                منصة شركة سافيرو (Safero) الموحدة لإدارة الفحص الشامل، شهادات التدريب المعتمدة، وسجلات المتدربين والعملاء.
            </p>
        </div>

        <!-- Curved Arch Section containing the Apps Grid -->
        <div class="curved-arch">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- The Apps Grid (6 columns on desktop, exactly like Odoo Enterprise) -->
                <div id="appsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 sm:gap-8 justify-items-center">

                    <!-- 1. Training Certificates (شهادات التدريب) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="training certificates شهادات التدريب دورات">
                        <a href="{{ route('training-certificates.index') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center relative p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/industry_certification.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Training Certificates">
                            <span class="absolute -top-1.5 -right-1.5 px-2 py-0.5 rounded-full text-[10px] font-black bg-[#22A447] text-white shadow-sm font-en">
                                {{ $stats['total_training_certificates'] }}
                            </span>
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#006837] transition">
                            شهادات التدريب
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Training Certs</span>
                    </div>

                    <!-- 2. Trainees CRM (سجل المتدربين) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="trainees متدربين متدرب crm">
                        <a href="{{ route('trainees.index') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center relative p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/contacts.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Trainees CRM">
                            <span class="absolute -top-1.5 -right-1.5 px-2 py-0.5 rounded-full text-[10px] font-black bg-[#0E4DA4] text-white shadow-sm font-en">
                                {{ $stats['total_trainees'] }}
                            </span>
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">
                            سجل المتدربين
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Trainees CRM</span>
                    </div>

                    <!-- 3. Inspection Certificates (شهادات الفحص) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="inspection certificates فحص معايرة معدات">
                        <a href="{{ route('certificates.index') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center relative p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/quality_control.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Inspection Certificates">
                            <span class="absolute -top-1.5 -right-1.5 px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-600 text-white shadow-sm font-en">
                                {{ $stats['total_certificates'] }}
                            </span>
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">
                            شهادات الفحص
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Inspection Certs</span>
                    </div>

                    <!-- 4. Customers CRM (إدارة العملاء) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="customers crm عملاء شركات مصانع">
                        <a href="{{ route('customers.index') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center relative p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/crm.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Customers CRM">
                            <span class="absolute -top-1.5 -right-1.5 px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white shadow-sm font-en">
                                {{ $stats['total_customers'] }}
                            </span>
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-rose-600 transition">
                            إدارة العملاء
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Customers CRM</span>
                    </div>

                    <!-- 5. Issue Crane Inspection (فحص الأوناش والروافع) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="crane inspection أوناش روافع فحص">
                        <a href="{{ route('certificates.create', ['type' => 'crane']) }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/fleet.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Cranes Inspection">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">
                            فحص الأوناش
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Cranes LOLER</span>
                    </div>

                    <!-- 6. Issue Accessories Inspection (مهمات الرفع والحبال) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="slings accessories مهمات رفع حبال سلاسل">
                        <a href="{{ route('certificates.create', ['type' => 'accessory']) }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/maintenance.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Lifting Accessories">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-[#0E4DA4] transition">
                            مهمات الرفع
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Accessories</span>
                    </div>

                    <!-- 7. Verify Tool (أداة التحقق) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="verify check qr كود تحقق شهادة">
                        <a href="{{ route('certificates.verify.lookup') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/approvals.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Verification Tool">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-purple-600 transition">
                            أداة التحقق
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Verify QR</span>
                    </div>

                    <!-- 8. Dashboard Analytics (لوحة التقارير) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="dashboard analytics احصائيات تقارير لوحة">
                        <a href="{{ route('dashboard') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/board.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Dashboard">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-amber-600 transition">
                            لوحة الإحصائيات
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Analytics</span>
                    </div>

                    <!-- 9. Issue Training Cert (إصدار تدريب جديد) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="new training cert اصدار شهادة تدريب جديدة">
                        <a href="{{ route('training-certificates.create') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/industry_driving_school.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="New Training">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight group-hover:text-emerald-600 transition">
                            إصدار تدريب
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">New Training</span>
                    </div>

                    <!-- 10. Technical Consultations (الاستشارات الفنية والمعايير) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="consultations استشارات سلامة ايزو standard iso">
                        <a href="javascript:void(0)" onclick="alert('قسم المعايير الهندسية (ISO 17020 / LOLER 1998) مدمج تلقائياً في فحص ومراجعة الشهادات.');" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/industry_surveyor.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Engineering Standards">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight">
                            معايير الفحص
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">ISO 17020</span>
                    </div>

                    <!-- 11. Environmental Studies (الدراسات البيئية) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32 opacity-85" data-name="environmental بصمة كربونية بيئة دراسات">
                        <a href="javascript:void(0)" onclick="alert('موديول الدراسات البيئية وحساب البصمة الكربونية قيد التجهيز وسيتم تفعيله قريباً.');" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/industry_environmental_agency.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Environmental">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight">
                            دراسات بيئية
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Environmental</span>
                    </div>

                    <!-- 12. Settings & System (إعدادات النظام) -->
                    <div class="app-item flex flex-col items-center group cursor-pointer text-center w-28 sm:w-32" data-name="settings إعدادات النظام تهيئة">
                        <a href="{{ route('dashboard') }}" class="app-card-btn w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.06)] border border-slate-100 flex items-center justify-center p-2 sm:p-2.5">
                            <img src="{{ asset('branding/odoo-icons/settings.svg') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain transition-transform duration-200 group-hover:scale-110" alt="Settings">
                        </a>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 mt-2.5 block leading-tight">
                            الإعدادات
                        </span>
                        <span class="text-[11px] text-slate-400 font-en block mt-0.5">Settings</span>
                    </div>

                </div>

            </div>
        </div>

    </main>

    <!-- Bottom Footer -->
    <footer class="bg-[#F8FAFC] py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 font-en">
        Safero Services &amp; Consultations &copy; {{ date('Y') }} &mdash; All Rights Reserved.
    </footer>

    <!-- Instant Search Script -->
    <script>
        const searchInput = document.getElementById('appSearchInput');
        const appItems = document.querySelectorAll('.app-item');

        searchInput?.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            appItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                if (!query || name.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>

