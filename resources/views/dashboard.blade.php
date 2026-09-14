<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Statistik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- BANNER NOTIFIKASI APPROVAL -->
            @if(auth()->user()->approval_status === 'approved')
            <div class="mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white flex flex-col md:flex-row justify-between items-center border border-white/20 backdrop-blur-sm">
                <div class="flex items-center gap-4 mb-4 md:mb-0">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-2xl shadow-inner">
                        🎉
                    </div>
                    <div>
                        <h3 class="text-xl font-bold tracking-tight">Selamat, {{ auth()->user()->name }}!</h3>
                        <p class="text-blue-50 text-sm mt-1">Pengajuan hak akses Anda telah disetujui oleh Super Admin. Kini Anda resmi menjabat sebagai <strong class="text-white">{{ auth()->user()->roles->first()->name }}</strong>.</p>
                    </div>
                </div>
                <form action="{{ route('clear.approval') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white text-indigo-600 px-6 py-2.5 rounded-full font-bold hover:bg-gray-50 transition shadow-md hover:-translate-y-0.5 text-sm">
                        Mulai Bekerja
                    </button>
                </form>
            </div>
            @endif
            
            <!-- Grid Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                
                <!-- Kartu Total Aset -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-semibold uppercase">Total Seluruh Aset</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $totalAssets }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Aset Aktif -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-semibold uppercase">Aset Aktif / Digunakan</p>
                            <h3 class="text-2xl font-bold text-green-600">{{ $activeAssets }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Maintenance -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-semibold uppercase">Dalam Perbaikan (Maintenance)</p>
                            <h3 class="text-2xl font-bold text-yellow-600">{{ $maintenanceAssets }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Rusak -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-semibold uppercase">Aset Rusak</p>
                            <h3 class="text-2xl font-bold text-red-600">{{ $brokenAssets }}</h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pesan Sambutan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Selamat Datang di Sistem Manajemen Aset!</h3>
                    <p class="text-gray-600">Gunakan menu navigasi di atas untuk mengelola data master (Departemen, Lokasi, Kategori) dan mengontrol inventaris aset secara penuh.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>