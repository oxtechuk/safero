@extends('layouts.app')

@section('title', 'إعدادات المنظومة والشركة - Settings')

@section('content')
<div x-data="{ 
    activeTab: '{{ $activeTab ?? 'general' }}',
    searchQuery: '',
    matches(section) {
        if (!this.searchQuery) return true;
        return section.toLowerCase().includes(this.searchQuery.toLowerCase());
    }
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-4">

    <!-- Top Sticky IsoOx Enterprise Control Panel for Settings (Image 1 Exact Match) -->
    <div class="bg-white rounded-xl border border-slate-200 px-4 sm:px-6 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs sticky top-14 z-30">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-[#714B67]/10 flex items-center justify-center p-1.5 border border-[#714B67]/20 text-[#714B67]">
                <img src="{{ asset('branding/IsoOx-icons/settings.svg') }}" class="w-6 h-6 object-contain" alt="Settings">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-tight">الإعدادات (Settings)</h1>
                    <span class="text-[10px] font-bold bg-purple-50 text-[#714B67] border border-purple-200 px-2 py-0.5 rounded-full font-mono">IsoOx v .1</span>
                </div>
                <p class="text-xs text-slate-500 font-en">Company Profile, Users, Branding, Localization &amp; SaaS Configuration</p>
            </div>
        </div>

        <!-- Action Buttons: Save & Discard (Image 1 Layout) -->
        <div class="flex items-center gap-2">
            <button type="submit" 
                    form="settingsForm"
                    class="px-4 py-2 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs sm:text-sm shadow-xs transition flex items-center gap-1.5 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>حفظ (Save)</span>
            </button>

            <a href="{{ route('apps') }}" 
               class="px-3.5 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm transition">
                تراجع (Discard)
            </a>

            <!-- Quick Settings Search Box (Image 1 top right) -->
            <div class="relative hidden md:block w-48">
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="بحث في الإعدادات..." 
                       class="w-full pr-7 pl-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Settings Two-Column Layout (IsoOx Enterprise Pattern from Image 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Sidebar: Settings Navigation Tabs (Image 1 Match: General Settings, Sales, Invoicing, Technical) -->
        <div class="lg:col-span-3 bg-white rounded-xl border border-slate-200 shadow-xs p-2 space-y-1 sticky top-32">
            <span class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                أقسام التهيئة والتحكم (Configuration)
            </span>

            <!-- Tab 1: General Settings (Image 1 Top Tab) -->
            <button type="button" 
                    @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/settings.svg') }}" class="w-4 h-4 object-contain">
                <span>الإعدادات العامة (General Settings)</span>
            </button>

            <!-- Tab 2: Company Profile -->
            <button type="button" 
                    @click="activeTab = 'company'"
                    :class="activeTab === 'company' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/crm.svg') }}" class="w-4 h-4 object-contain">
                <span>بيانات وهوية الشركة (Company)</span>
            </button>

            <!-- Tab 3: Branding & Assets (Logo, Stamp, BG Image 5) -->
            <button type="button" 
                    @click="activeTab = 'branding'"
                    :class="activeTab === 'branding' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/sign.svg') }}" class="w-4 h-4 object-contain">
                <span>الشعار والأختام والقوالب (Branding)</span>
            </button>

            <!-- Tab 4: Inspection & Certificates (Quality) -->
            <button type="button" 
                    @click="activeTab = 'certificates'"
                    :class="activeTab === 'certificates' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/quality_control.svg') }}" class="w-4 h-4 object-contain">
                <span>الفحص ومعايير الاعتماد (Inspection)</span>
            </button>

            <!-- Tab 5: Training & Accreditations -->
            <button type="button" 
                    @click="activeTab = 'training'"
                    :class="activeTab === 'training' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/industry_certification.svg') }}" class="w-4 h-4 object-contain">
                <span>شهادات التدريب (Training)</span>
            </button>

            <!-- Tab 6: SaaS & Extensibility -->
            <button type="button" 
                    @click="activeTab = 'saas'"
                    :class="activeTab === 'saas' ? 'bg-[#714B67] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs text-right transition">
                <img src="{{ asset('branding/IsoOx-icons/apps.svg') }}" class="w-4 h-4 object-contain">
                <span>منصة SaaS والمديولات (Architecture)</span>
            </button>

            <!-- Tab 7: Menu Items (Image 1 Red Highlight: Technical -> Menu Items) -->
            <button type="button" 
                    @click="activeTab = 'technical'"
                    :class="activeTab === 'technical' ? 'bg-[#714B67] text-white font-bold shadow-xs ring-2 ring-red-400' : 'text-slate-700 hover:bg-slate-100 font-medium'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-xs text-right transition">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('branding/IsoOx-icons/board.svg') }}" class="w-4 h-4 object-contain">
                    <span>عناصر القوائم (Menu Items)</span>
                </div>
                <span class="text-[9px] bg-red-100 text-red-700 px-1 py-0.5 rounded font-mono font-bold">Image 1</span>
            </button>

            <div class="pt-3 border-t border-slate-100 mt-2 px-3 text-[11px] text-slate-400 font-en">
                <span class="font-bold text-slate-600 block">Safero ERP Core v2.4</span>
                <span class="block text-[10px]">Open-Source &amp; Multi-Tenant SaaS</span>
            </div>
        </div>

        <!-- Right Main Panel: Settings Form Content -->
        <div class="lg:col-span-9 space-y-6">
            
            <!-- Global Form for Persistent Settings -->
            <form id="settingsForm" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_tab" :value="activeTab">

                <!-- ================= TAB 1: General Settings (Image 1 Exact Layout: Users, Companies, Languages) ================= -->
                <div x-show="activeTab === 'general'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">
                    
                    <!-- 1. Users Section (Matches Image 1 "Users") -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900">المستخدمون وصلاحيات النظام (Users)</h2>
                                <p class="text-xs text-slate-500">إدارة حسابات المهندسين والمفتشين ودعوة أعضاء فريق الفحص الجدد.</p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-400">IsoOx / Users</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <!-- Left Sub-col: Active Users Count & Manage Link (Image 1 Match) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-base font-extrabold text-slate-900">{{ $userCount ?? 3 }} مستخدمين نشطين</span>
                                            <span class="text-xs text-slate-400 font-en">({{ $userCount ?? 3 }} Active Users)</span>
                                        </div>
                                        <span class="text-[11px] text-slate-500 block">مشرفين، مدراء جودة، ومهندسي فحص معتمدين.</span>
                                    </div>
                                </div>

                                <!-- Manage Users & Roles Action Buttons -->
                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between gap-2">
                                    <a href="{{ route('users.index') }}" 
                                       class="text-xs font-bold text-[#714B67] hover:underline flex items-center gap-1">
                                        <span>إدارة المستخدمين (Manage Users)</span>
                                        <span>&rarr;</span>
                                    </a>
                                    <a href="{{ route('users.create') }}" 
                                       class="px-2.5 py-1 rounded-md bg-[#714B67] text-white font-bold text-[11px] hover:bg-[#5b3c53] transition">
                                        + مستخدم جديد
                                    </a>
                                </div>
                            </div>

                            <!-- Right Sub-col: Invite New Users (Image 1 Match) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                                <span class="text-xs font-bold text-slate-900 block">دعوة مستخدم جديد (Invite New Users)</span>
                                
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <input type="email" 
                                               id="inviteEmailInput"
                                               name="invite_email"
                                               placeholder="Enter e-mail address (أدخل البريد الإلكتروني)" 
                                               class="flex-1 px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] outline-none bg-white">
                                        
                                        <button type="button" 
                                                onclick="submitInviteUser()"
                                                class="px-4 py-2 rounded-lg bg-[#714B67] hover:bg-[#5b3c53] text-white font-bold text-xs transition shrink-0 active:scale-95">
                                            دعوة (Invite)
                                        </button>
                                    </div>

                                    <div class="text-[11px] text-slate-400 flex items-center justify-between pt-1">
                                        <span>الدعوات المعلقة (Pending Invitations):</span>
                                        <span class="font-mono text-slate-500">demo@safero-eg.com</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Companies Section (Matches Image 1 "Companies") -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900">الشركات والكيانات التابعة (Companies)</h2>
                                <p class="text-xs text-slate-500">بيانات المقر الرئيسي للشركة المشغلة للمنصة والفروع المرخصة.</p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-400">IsoOx / Companies</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <!-- Left Sub-col: Primary Company Details (Image 1 Match: YourCompany) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-bold text-sm text-slate-900">{{ setting('company_name_ar', 'شركة سافيرو للخدمات والاستشارات الهندسية') }}</h3>
                                        <p class="text-xs text-slate-500 font-en font-medium">{{ setting('company_name_en', 'SAFERO Services & Consultations') }}</p>
                                    </div>
                                    <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded font-bold border border-emerald-200">الشركة الرئيسية (Primary)</span>
                                </div>

                                <p class="text-xs text-slate-600 font-en leading-relaxed">
                                    {{ setting('company_address', '215- Sporting - Port Said Main Street - Sidi Gaber - Alexandria-Egypt') }}
                                </p>

                                <div class="text-[11px] text-slate-500 space-y-1 pt-1 font-mono">
                                    <div>السجل التجاري (CR): <strong class="text-slate-800">{{ setting('company_cr', '145892') }}</strong></div>
                                    <div>البطاقة الضريبية (Tax ID): <strong class="text-slate-800">{{ setting('company_tax_id', '741-982-310') }}</strong></div>
                                    <div>الهاتف: <strong class="text-slate-800" dir="ltr">{{ setting('company_phone', '+2-01508367666') }}</strong></div>
                                </div>

                                <div class="pt-2 border-t border-slate-200/60">
                                    <button type="button" 
                                            @click="activeTab = 'company'"
                                            class="text-xs font-bold text-[#714B67] hover:underline flex items-center gap-1">
                                        <span>تحديث بيانات الشركة (Update Info)</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Right Sub-col: Multi-Company Info (Image 1 Match) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900">سجل الكيانات المرخصة</span>
                                    <span class="text-xs font-mono font-bold text-slate-600">1 Company</span>
                                </div>

                                <p class="text-xs text-slate-500">
                                    يتيح نظام Safero ERP تشغيل عدة شركات وفروع مستقلة تحت نفس المنصة السحابية مع مشاركة الفنيين وقواعد البيانات.
                                </p>

                                <div class="p-3 rounded-lg bg-white border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-[#714B67] text-white flex items-center justify-center font-bold text-xs">S</div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">المقر الرئيسي - الإسكندرية</span>
                                            <span class="text-[10px] text-slate-400">Headquarters - Alex Branch</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-bold">نشط</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Languages Section (Matches Image 1 "Languages") -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900">اللغات والموقع الجغرافي (Languages &amp; Translations)</h2>
                                <p class="text-xs text-slate-500">ضبط اللغة الافتراضية للواجهة والتقارير الرسمية والمنطقة الزمنية والعملة.</p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-400">IsoOx / Languages</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <!-- Left Sub-col: Active Languages (Image 1 Match) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900">اللغات المفعلة بالنظام</span>
                                    <span class="text-xs font-mono font-bold text-slate-600">2 Languages</span>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-2.5 rounded bg-white border border-slate-200 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span class="font-bold text-slate-800">العربية (Arabic)</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">الافتراضية RTL</span>
                                    </div>

                                    <div class="flex items-center justify-between p-2.5 rounded bg-white border border-slate-200 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            <span class="font-bold text-slate-800">English (الإنجليزية)</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">مفعلة LTR</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Sub-col: Select Default Locale & Timezone -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">اللغة الافتراضية للنظام</label>
                                    <select name="app_locale" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-[#714B67] outline-none bg-white">
                                        <option value="ar" {{ setting('app_locale', 'ar') === 'ar' ? 'selected' : '' }}>العربية (Arabic - RTL)</option>
                                        <option value="en" {{ setting('app_locale', 'ar') === 'en' ? 'selected' : '' }}>English (الإنجليزية - LTR)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">المنطقة الزمنية (Timezone)</label>
                                    <select name="app_timezone" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] outline-none bg-white">
                                        <option value="Africa/Cairo" {{ setting('app_timezone', 'Africa/Cairo') === 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo (توقيت القاهرة GMT+2)</option>
                                        <option value="Asia/Riyadh" {{ setting('app_timezone') === 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh (توقيت الرياض GMT+3)</option>
                                        <option value="Asia/Dubai" {{ setting('app_timezone') === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (توقيت دبي GMT+4)</option>
                                        <option value="UTC" {{ setting('app_timezone') === 'UTC' ? 'selected' : '' }}>UTC (توقيت جرينتش)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">العملة الافتراضية</label>
                                    <select name="app_currency" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] outline-none bg-white">
                                        <option value="EGP" {{ setting('app_currency', 'EGP') === 'EGP' ? 'selected' : '' }}>EGP - جنيه مصري</option>
                                        <option value="USD" {{ setting('app_currency') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="SAR" {{ setting('app_currency') === 'SAR' ? 'selected' : '' }}>SAR - ريال سعودي</option>
                                        <option value="AED" {{ setting('app_currency') === 'AED' ? 'selected' : '' }}>AED - درهم إماراتي</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================= TAB 2: Company Profile Section ================= -->
                <div x-show="activeTab === 'company'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-slate-900">بيانات وهوية الشركة القانونية (Company Profile)</h2>
                        <p class="text-xs text-slate-500">المعلومات القانونية والتجارية للشركة المستخدمة في الشهادات والمستندات الرسمية والفوترة.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Arabic Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">اسم الشركة (بالعربية) *</label>
                            <input type="text" 
                                   name="company_name_ar" 
                                   value="{{ setting('company_name_ar', 'شركة سافيرو للخدمات والاستشارات الهندسية') }}" 
                                   required
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>

                        <!-- English Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">اسم الشركة (بالإنجليزية) *</label>
                            <input type="text" 
                                   name="company_name_en" 
                                   value="{{ setting('company_name_en', 'SAFERO Services & Consultations') }}" 
                                   required
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>

                        <!-- Tagline -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">الشعار والوصف القانوني المعتمد</label>
                            <input type="text" 
                                   name="company_tagline" 
                                   value="{{ setting('company_tagline', 'Accredited Inspection Body (ISO/IEC 17020) & Lifting Equipment Certification') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">العنوان والفرع الرئيسي *</label>
                            <input type="text" 
                                   name="company_address" 
                                   value="{{ setting('company_address', '215- Sporting - Port Said Main Street - Sidi Gaber - Alexandria-Egypt') }}" 
                                   required
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف / الواتساب الرسمي *</label>
                            <input type="text" 
                                   name="company_phone" 
                                   value="{{ setting('company_phone', '+2-01508367666') }}" 
                                   required
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none" dir="ltr">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">البريد الإلكتروني الرسمي *</label>
                            <input type="email" 
                                   name="company_email" 
                                   value="{{ setting('company_email', 'info@safero-eg.com') }}" 
                                   required
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none" dir="ltr">
                        </div>

                        <!-- Website -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الموقع الإلكتروني</label>
                            <input type="url" 
                                   name="company_website" 
                                   value="{{ setting('company_website', 'https://safero-eg.com') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none" dir="ltr">
                        </div>

                        <!-- Commercial Registration -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رقم السجل التجاري (CR)</label>
                            <input type="text" 
                                   name="company_cr" 
                                   value="{{ setting('company_cr', '145892') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>

                        <!-- Tax ID -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رقم البطاقة الضريبية (Tax ID)</label>
                            <input type="text" 
                                   name="company_tax_id" 
                                   value="{{ setting('company_tax_id', '741-982-310') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] focus:ring-1 focus:ring-[#714B67] outline-none">
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 3: Branding & Assets Section (Logo, Stamp, BG Image 5) ================= -->
                <div x-show="activeTab === 'branding'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-slate-900">الشعار، الأختام، والقوالب الرسمية (Branding &amp; Assets)</h2>
                        <p class="text-xs text-slate-500">رفع وإدارة شعار الشركة، الختم الدائري المعتمد، ورقة القالب الخلفية للشهادات، والتوقيعات الرقمية.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. Company Logo -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900">شعار الشركة (Company Logo)</span>
                                <span class="text-[10px] text-slate-400 font-en">PNG, JPG, SVG</span>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shrink-0 shadow-2xs">
                                    <img src="{{ asset(setting('logo_path', '/branding/safero_logo.jpg')) }}" alt="Logo" class="max-h-16 max-w-full object-contain">
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" 
                                           name="logo_file" 
                                           accept="image/*"
                                           class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#714B67] file:text-white hover:file:bg-[#5b3c53] cursor-pointer">
                                    <span class="text-[10px] text-slate-400 block">يظهر في الهيدر، الفواتير، وبوابة التحقق QR.</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Official Circular Stamp -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900">الختم الرسمي الدائري (Official Stamp)</span>
                                <span class="text-[10px] text-slate-400 font-en">SVG, PNG</span>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shrink-0 shadow-2xs">
                                    <img src="{{ asset(setting('stamp_path', '/branding/stamp.svg')) }}" alt="Stamp" class="max-h-16 max-w-full object-contain">
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" 
                                           name="stamp_file" 
                                           accept="image/*"
                                           class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#714B67] file:text-white hover:file:bg-[#5b3c53] cursor-pointer">
                                    <span class="text-[10px] text-slate-400 block">يُطبع رسمياً في شهادات الفحص وتقارير الـ PDF.</span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Certificate Letterhead Background (Image 5 Match) -->
                        <div class="md:col-span-2 p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">ورقة القالب الرسمي لشهادات الفحص (Letterhead Paper Background)</span>
                                    <span class="text-[11px] text-slate-500">الصورة الخلفية لشهادات الفحص بنسبة أبعاد A4 (شعار الشركة + كود الاعتماد EGAC + العلامة المائية الأمنية).</span>
                                </div>
                                <span class="text-[10px] bg-blue-50 text-[#0E4DA4] px-2 py-0.5 rounded font-mono font-bold">A4 1414x2000 (Image 5)</span>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-1">
                                <div class="w-24 h-32 rounded-lg bg-white border border-slate-300 p-1 flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
                                    <img src="{{ asset(setting('letterhead_bg_path', '/branding/cert_inspect_bg.jpg')) }}" alt="Letterhead BG" class="w-full h-full object-cover rounded">
                                </div>
                                <div class="flex-1 space-y-2">
                                    <input type="file" 
                                           name="letterhead_bg_file" 
                                           accept="image/jpeg,image/png,image/webp"
                                           class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#714B67] file:text-white hover:file:bg-[#5b3c53] cursor-pointer">
                                    <p class="text-[11px] text-slate-500">
                                        تستخدم تلقائياً كخلفية لورقة الشهادة عند العرض والطباعة وتصدير PDF المعتمد.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Engineer Signature -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <span class="text-xs font-bold text-slate-900 block">توقيع مهندس الفحص الرقمي (Engineer Signature)</span>
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 rounded bg-white border border-slate-200 p-1 flex items-center justify-center shrink-0">
                                    <img src="{{ asset(setting('engineer_signature_path', '/branding/eng_signature.svg')) }}" class="max-h-8 max-w-full object-contain">
                                </div>
                                <input type="file" 
                                       name="eng_sign_file" 
                                       accept="image/*"
                                       class="w-full text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:bg-slate-200 file:text-slate-700 cursor-pointer">
                            </div>
                        </div>

                        <!-- 5. Director Signature -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                            <span class="text-xs font-bold text-slate-900 block">توقيع معتمد الشهادة / المدير الفني (Director Signature)</span>
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 rounded bg-white border border-slate-200 p-1 flex items-center justify-center shrink-0">
                                    <img src="{{ asset(setting('director_signature_path', '/branding/director_signature.svg')) }}" class="max-h-8 max-w-full object-contain">
                                </div>
                                <input type="file" 
                                       name="dir_sign_file" 
                                       accept="image/*"
                                       class="w-full text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:bg-slate-200 file:text-slate-700 cursor-pointer">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ================= TAB 4: Certificates & Accreditation Section ================= -->
                <div x-show="activeTab === 'certificates'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-slate-900">شهادات الفحص والاعتماد الهندسي (Inspection &amp; LOLER Standards)</h2>
                        <p class="text-xs text-slate-500">إعدادات جهات الاعتماد الدولية (EGAC)، صلاحية الفحص الدوري، والترقيم التسلسلي التلقائي.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Accreditation Body -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">جهة الاعتماد الرسمية</label>
                            <input type="text" 
                                   name="accreditation_body" 
                                   value="{{ setting('accreditation_body', 'EGAC & ilac-MRA') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] outline-none">
                        </div>

                        <!-- Accreditation Number -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رقم شهادة اعتماد جهاز التفتيش (CAB Number)</label>
                            <input type="text" 
                                   name="accreditation_number" 
                                   value="{{ setting('accreditation_number', 'CAB # 0326001') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] outline-none">
                        </div>

                        <!-- Certificate Prefix -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">بادئة الترقيم التسلسلي للشهادات</label>
                            <input type="text" 
                                   name="certificate_prefix" 
                                   value="{{ setting('certificate_prefix', 'SAF') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono font-bold focus:border-[#714B67] outline-none">
                            <span class="text-[10px] text-slate-400 block mt-0.5">مثال: SAF/2026/001</span>
                        </div>

                        <!-- Validity Months -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">صلاحية الفحص الدوري الافتراضية (بالشهور)</label>
                            <select name="certificate_validity_months" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-[#714B67] outline-none bg-white">
                                <option value="6" {{ setting('certificate_validity_months', 12) == 6 ? 'selected' : '' }}>6 شهور (فحص نصف سنوي - معدات الرفع للأشخاص)</option>
                                <option value="12" {{ setting('certificate_validity_months', 12) == 12 ? 'selected' : '' }}>12 شهر (فحص سنوي قياسي - LOLER 1998)</option>
                                <option value="24" {{ setting('certificate_validity_months', 12) == 24 ? 'selected' : '' }}>24 شهر (سنتين)</option>
                            </select>
                        </div>

                        <!-- Default Inspector -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">مهندس الفحص الافتراضي (Default Inspector)</label>
                            <input type="text" 
                                   name="default_inspector_name" 
                                   value="{{ setting('default_inspector_name', 'Eng. Mohamed Fathy') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] outline-none">
                        </div>

                        <!-- Default Authenticator -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">معتمد الشهادات / المدير الفني (Default Authenticator)</label>
                            <input type="text" 
                                   name="default_authenticator_name" 
                                   value="{{ setting('default_authenticator_name', 'Dr. Hany Taher') }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-en focus:border-[#714B67] outline-none">
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 5: Training Module Section ================= -->
                <div x-show="activeTab === 'training'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-slate-900">شهادات التدريب وتأهيل الأفراد (Training &amp; Competency)</h2>
                        <p class="text-xs text-slate-500">معايير دورات تدريب مشغلي الأوناش والسلامة المهنية وإصدار شهادات الكفاءة.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50 space-y-2">
                            <span class="text-xs font-bold text-emerald-900 block">شهادات التدريب المعتمدة</span>
                            <span class="text-2xl font-black text-emerald-700 font-mono">100%</span>
                            <p class="text-[11px] text-emerald-800">شهادات متوافقة مع معايير OSHA و LOLER للأفراد والمشغلين.</p>
                            <a href="{{ route('training-certificates.index') }}" class="text-xs font-bold text-emerald-700 hover:underline block pt-1">سجل شهادات التدريب &rarr;</a>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2">
                            <span class="text-xs font-bold text-slate-900 block">قاعدة بيانات المتدربين</span>
                            <span class="text-2xl font-black text-slate-700 font-mono">CRM</span>
                            <p class="text-[11px] text-slate-500">إدارة السجلات المدنية وأرقام الهويات الوطنية والشركات التابعة.</p>
                            <a href="{{ route('trainees.index') }}" class="text-xs font-bold text-[#0E4DA4] hover:underline block pt-1">دليل المتدربين &rarr;</a>
                        </div>

                        <div class="p-4 rounded-xl border border-purple-200 bg-purple-50/50 space-y-2">
                            <span class="text-xs font-bold text-purple-900 block">فحص الشهادات بالـ QR</span>
                            <span class="text-2xl font-black text-purple-700 font-mono">Instant</span>
                            <p class="text-[11px] text-purple-800">التحقق اللحظي عبر مسح الباركود على بطاقة المتدرب.</p>
                            <a href="{{ route('certificates.verify.lookup') }}" class="text-xs font-bold text-purple-700 hover:underline block pt-1">أداة الفحص &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 6: SaaS & Extensibility Section ================= -->
                <div x-show="activeTab === 'saas'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-extrabold text-slate-900">بنية منصة SaaS وقابلية التوسع (SaaS &amp; Open-Source ERP)</h2>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SaaS Ready</span>
                        </div>
                        <p class="text-xs text-slate-500">جاهزية النظام ليعمل كمنصة سحابية متقدمة (Multi-tenant SaaS) أو كنظام مفتوح المصدر قابل لتركيب وتوسعة المديولات.</p>
                    </div>

                    <!-- SaaS Toggles -->
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-xs text-slate-900 block">وضع الشركات المتعددة (Multi-Company SaaS Mode)</span>
                                <span class="text-[11px] text-slate-500">السماح بتشغيل عدة شركات ومستأجرين منفصلين بقواعد بيانات ومعايير مستقلة.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="saas_enabled" value="1" {{ setting('saas_enabled') ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22A447]"></div>
                            </label>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-xs text-slate-900 block">التسجيل الذاتي للشركات والمصانع (Self-service Portal)</span>
                                <span class="text-[11px] text-slate-500">إتاحة بوابة للعملاء لطلب شهادات الفحص والاطلاع على سجلات معداتهم وفحصها ذاتياً.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="saas_allow_registration" value="1" {{ setting('saas_allow_registration') ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22A447]"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Active Modules Registry -->
                    <div class="pt-4 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-900 block mb-2">سجل المديولات المثبتة في النواة (Installed Modules Registry)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            
                            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/quality_control.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-800">شهادات الفحص</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">مفعل v2.4</span>
                            </div>

                            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/industry_certification.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-800">شهادات التدريب</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">مفعل v2.1</span>
                            </div>

                            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/contacts.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-800">سجل المتدربين</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">مفعل v1.8</span>
                            </div>

                            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/crm.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-800">دليل العملاء CRM</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">مفعل v1.5</span>
                            </div>

                            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/approvals.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-800">بوابة التحقق الذكي QR</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-white px-1.5 py-0.5 rounded border border-emerald-200">مفعل v2.0</span>
                            </div>

                            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-between opacity-80">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('branding/IsoOx-icons/industry_environmental_agency.svg') }}" class="w-5 h-5">
                                    <span class="text-xs font-bold text-slate-700">الدراسات البيئية</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 bg-white px-1.5 py-0.5 rounded border border-slate-200">قيد التطوير</span>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ================= TAB 7: Technical -> Menu Items (Image 1 Red Box Exact Feature) ================= -->
                <div x-show="activeTab === 'technical'" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold text-slate-900">عناصر القوائم (Menu Items)</h2>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 font-mono">Image 1 Highlight</span>
                            </div>
                            <p class="text-xs text-slate-500">إدارة شجرة القوائم والمديولات، الترتيب التسلسلي، ومسارات التنقل في Safero ERP.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-mono font-bold text-slate-500">{{ count($menuItems ?? []) }} Menu Items</span>
                        </div>
                    </div>

                    <!-- Menu Items Table (IsoOx 17 Technical List View) -->
                    <div class="overflow-x-auto border border-slate-200 rounded-lg">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-[11px]">
                                <tr>
                                    <th class="py-2.5 px-3">#</th>
                                    <th class="py-2.5 px-3">اسم القائمة</th>
                                    <th class="py-2.5 px-3">Menu Name (EN)</th>
                                    <th class="py-2.5 px-3">الموديل الأب (Parent)</th>
                                    <th class="py-2.5 px-3">المسار (Route)</th>
                                    <th class="py-2.5 px-3 text-center">الأيقونة</th>
                                    <th class="py-2.5 px-3 text-center">الترتيب</th>
                                    <th class="py-2.5 px-3 text-center">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($menuItems ?? [] as $m)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-2 px-3 font-mono text-slate-400 font-bold">{{ $m['id'] }}</td>
                                        <td class="py-2 px-3 font-bold text-slate-800">
                                            <a href="{{ $m['url'] }}" class="hover:text-[#714B67] hover:underline flex items-center gap-2">
                                                <span>{{ $m['name_ar'] }}</span>
                                            </a>
                                        </td>
                                        <td class="py-2 px-3 font-en text-slate-600">{{ $m['name_en'] }}</td>
                                        <td class="py-2 px-3">
                                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-medium">
                                                {{ $m['parent'] }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-3 font-mono text-[11px] text-slate-500" dir="ltr">
                                            {{ $m['route'] }}
                                        </td>
                                        <td class="py-2 px-3 text-center">
                                            <div class="w-6 h-6 rounded bg-slate-100 p-1 mx-auto flex items-center justify-center">
                                                <img src="{{ asset($m['icon']) }}" class="w-4 h-4 object-contain">
                                            </div>
                                        </td>
                                        <td class="py-2 px-3 text-center font-mono text-slate-600 font-bold">
                                            {{ $m['sequence'] }}
                                        </td>
                                        <td class="py-2 px-3 text-center">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                مفعل (Active)
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-6 text-center text-slate-400">لا توجد عناصر قوائم معرفة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Google Sheets & Task Sync Integration Box -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">ربط وتزامن Google Sheets (Google Sheets Sync)</h3>
                                    <p class="text-xs text-slate-500">تحديث أوامر الشغل وجداول الفحص اليومية تلقائياً في جداول بيانات جوجل.</p>
                                </div>
                            </div>
                            <a href="{{ route('tasks.index') }}" class="text-xs font-bold text-[#0E4DA4] hover:underline flex items-center gap-1">
                                <span>شاشة المهام</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="google_sheets_webhook_url" class="block text-xs font-bold text-slate-700 mb-1">
                                    رابط Google Apps Script Webhook URL
                                </label>
                                <input type="url" 
                                       name="google_sheets_webhook_url" 
                                       id="google_sheets_webhook_url" 
                                       value="{{ setting('google_sheets_webhook_url') }}" 
                                       dir="ltr"
                                       placeholder="https://script.google.com/macros/s/.../exec" 
                                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] outline-hidden text-left bg-white">
                                <span class="text-[10px] text-slate-400 mt-1 block">رابط Webhook لتحديث الصفوف في Google Sheet فورياً وبشكل مجدول يومياً.</span>
                            </div>

                            <div>
                                <label for="google_sheet_id" class="block text-xs font-bold text-slate-700 mb-1">
                                    معرف ملف الشيت (Google Spreadsheet ID)
                                </label>
                                <input type="text" 
                                       name="google_sheet_id" 
                                       id="google_sheet_id" 
                                       value="{{ setting('google_sheet_id') }}" 
                                       dir="ltr"
                                       placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms" 
                                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-mono focus:border-[#714B67] outline-hidden text-left bg-white">
                                <span class="text-[10px] text-slate-400 mt-1 block">معرف جدول البيانات المستخرج من الرابط العام.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Meta Card -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 text-xs text-slate-600 space-y-1 font-mono">
                        <div class="flex items-center justify-between">
                            <span>Framework: Laravel {{ app()->version() }} | PHP {{ phpversion() }}</span>
                            <span class="text-emerald-700 font-bold">Database: MySQL (Connected)</span>
                        </div>
                        <div>IsoOx Engine: IsoOx v .1 Web Components Emulation</div>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

<!-- Hidden Form to submit User Invitation (Matches Image 1) -->
<form id="inviteUserForm" method="POST" action="{{ route('settings.invite_user') }}" class="hidden">
    @csrf
    <input type="hidden" name="email" id="inviteFormEmail">
</form>

<script>
    function submitInviteUser() {
        const emailInput = document.getElementById('inviteEmailInput');
        if (!emailInput || !emailInput.value) {
            alert('يرجى إدخال البريد الإلكتروني للمستخدم المراد دعوته.');
            return;
        }
        document.getElementById('inviteFormEmail').value = emailInput.value;
        document.getElementById('inviteUserForm').submit();
    }
</script>
@endsection
