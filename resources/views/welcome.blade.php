<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AMMS - Enterprise Asset Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-blue-500 selection:text-white flex flex-col min-h-screen">
    
    <!-- Navbar Sederhana -->
    <header class="w-full px-6 py-4 flex justify-between items-center max-w-6xl mx-auto">
        <div class="flex items-center gap-2 font-bold text-xl tracking-tight text-gray-900">
            <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm shadow-sm">⌘</div>
            AMMS
        </div>
        <div class="text-sm font-medium text-gray-500">
            Sistem Manajemen Aset
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 flex flex-col items-center justify-center px-6 text-center max-w-4xl mx-auto mt-[-5vh]">
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-gray-900 mb-6">
            Kelola inventaris perusahaan <br class="hidden sm:block"> dengan lebih cerdas.
        </h1>
        
        <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
            Tinggalkan pencatatan manual. Pantau kondisi aset, jadwalkan perawatan, dan kelola hak akses tim Anda dalam satu platform yang rapi dan terstruktur.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 justify-center w-full sm:w-auto">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-full bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-colors shadow-sm">
                    Masuk ke Sistem
                </a>
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-white text-gray-700 font-semibold border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                    Daftar Akun
                </a>
            @endauth
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-6 text-sm text-gray-400">
        &copy; {{ date('Y') }} AMMS Enterprise.
    </footer>

</body>
</html>