<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - Safero ERP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Poppins', system-ui, sans-serif;
        }
        .font-en {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F4F6F8] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-block p-3 bg-white rounded-2xl shadow-md border border-slate-100 mb-3">
                <img src="{{ asset('branding/safero_logo.jpg') }}" alt="Safero Logo" class="h-16 w-auto mx-auto object-contain">
            </div>
            <h1 class="text-2xl font-black text-[#0E4DA4] tracking-tight">SAFERO SERVICES</h1>
            <p class="text-xs text-slate-500 font-medium mt-1">نظام إدارة الفحص والمعايرة وشهادات ISO المعتمدة</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 backdrop-blur-sm">
            
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-slate-900">تسجيل الدخول للنظام</h2>
                <p class="text-xs text-slate-500 mt-0.5">أدخل بيانات الحساب للوصول إلى لوحة المديولات</p>
            </div>

            @if($errors->any())
                <div class="p-3 mb-5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">البريد الإلكتروني</label>
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', 'admin@safero-eg.com') }}" 
                               required 
                               autocomplete="email"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none transition text-sm text-slate-900 bg-slate-50/50 focus:bg-white"
                               placeholder="name@safero-eg.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-slate-700">كلمة المرور</label>
                        <span class="text-[11px] text-slate-400">الافتراضية: password</span>
                    </div>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               value="password"
                               required 
                               autocomplete="current-password"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-[#0E4DA4] focus:ring-2 focus:ring-[#0E4DA4]/20 outline-none transition text-sm text-slate-900 bg-slate-50/50 focus:bg-white"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0E4DA4] focus:ring-[#0E4DA4] border-slate-300">
                        <span class="text-slate-600 font-medium">تذكر تسجيل دخولي</span>
                    </label>
                    <a href="{{ route('certificates.verify.lookup') }}" class="text-[#0E4DA4] hover:underline font-bold">
                        أداة التحقق من الشهادات &larr;
                    </a>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-[#0E4DA4] hover:bg-[#0a3a7d] text-white font-bold text-sm shadow-lg shadow-[#0E4DA4]/20 transition duration-200 flex items-center justify-center gap-2">
                    <span>دخول إلى لوحة المديولات</span>
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Quick Demo Credentials Selector -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 text-center">
                    حسابات تجريبية سريعة (Demo Accounts)
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            onclick="setDemo('admin@safero-eg.com', 'password')"
                            class="p-2 rounded-lg border border-slate-200 hover:border-[#0E4DA4] hover:bg-[#E6F2FF] text-right transition text-xs">
                        <span class="block font-bold text-slate-800">مسؤول النظام</span>
                        <span class="block text-[10px] text-slate-500 font-en truncate">admin@safero-eg.com</span>
                    </button>
                    <button type="button" 
                            onclick="setDemo('eng.fathy@safero-eg.com', 'password')"
                            class="p-2 rounded-lg border border-slate-200 hover:border-[#22A447] hover:bg-[#E8F7EC] text-right transition text-xs">
                        <span class="block font-bold text-slate-800">مهندس الفحص</span>
                        <span class="block text-[10px] text-slate-500 font-en truncate">eng.fathy@safero-eg.com</span>
                    </button>
                </div>
            </div>

        </div>

        <div class="text-center mt-6 text-xs text-slate-400 font-en">
            Safero Inspection & Consultations &copy; {{ date('Y') }}
        </div>

    </div>

    <script>
        function setDemo(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>

