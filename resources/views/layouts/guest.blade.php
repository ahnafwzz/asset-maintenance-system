<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AMMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 selection:bg-blue-500 selection:text-white">
        
        <!-- Tombol Kembali Melayang di Kiri Atas -->
        <div class="absolute top-6 left-6 sm:top-8 sm:left-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors group">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-gray-300 group-hover:shadow transition-all">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </div>
                <span class="hidden sm:block">Kembali ke Beranda</span>
            </a>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-16 sm:pt-0 px-4">
            <!-- Logo di Atas Form -->
            <div class="mb-6 transform transition hover:scale-105 duration-300">
                <a href="/">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-800 to-black text-white rounded-2xl flex items-center justify-center text-3xl shadow-xl border border-gray-700">
                        ⌘
                    </div>
                </a>
            </div>

            <!-- Kotak Form Utama -->
            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl sm:rounded-3xl border border-gray-100 overflow-hidden relative">
                <!-- Efek Blur Tipis di Background Form -->
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>