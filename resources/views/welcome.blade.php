<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AMMS - Enterprise Asset Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-blue-500 selection:text-white">
    <!-- Background estetik Apple-style -->
    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>

        <!-- Glassmorphism Card -->
        <div class="relative z-10 text-center px-8 py-14 max-w-3xl mx-auto backdrop-blur-md bg-white/40 rounded-3xl border border-white/60 shadow-2xl">
            <!-- Logo Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-16 h-16 bg-gray-900 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg">
                    ⌘
                </div>
            </div>
            
            <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 mb-5">
                Manajemen Aset Terpadu.
            </h1>
            <p class="text-lg text-gray-600 mb-10 leading-relaxed font-medium px-4">
                Tinggalkan pencatatan manual. Kelola, pantau, dan rawat seluruh inventaris perusahaan secara efisien dalam satu platform yang elegan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3 rounded-full bg-gray-900 text-white font-semibold hover:bg-gray-800 transition shadow-lg hover:-translate-y-0.5">
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg hover:-translate-y-0.5">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 rounded-full bg-white/80 text-gray-800 font-semibold border border-gray-300 hover:bg-white transition shadow-sm hover:-translate-y-0.5">
                        Register Akun Baru
                    </a>
                @endauth
            </div>
        </div>
        
        <div class="absolute bottom-6 text-sm text-gray-400 font-medium">
            &copy; {{ date('Y') }} AMMS Enterprise.
        </div>
    </div>
</body>
</html>