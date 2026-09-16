<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Statistik') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
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
                        <p class="text-blue-50 text-sm mt-1">Pengajuan hak akses Anda telah disetujui oleh Super Admin. Kini Anda resmi menjabat sebagai <strong class="text-white">{{ auth()->user()->roles->first()->name ?? 'Pengguna' }}</strong>.</p>
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

            <!-- Alert Sukses ala iOS -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-100 text-green-800 rounded-2xl p-4 flex items-center shadow-sm transition-all duration-300">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 text-xl shrink-0">
                        ✅
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Grid Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Kartu Total Aset -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500 transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Seluruh Aset</p>
                            <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalAssets ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Aset Aktif -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500 transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Aset Aktif / Digunakan</p>
                            <h3 class="text-3xl font-extrabold text-green-600">{{ $activeAssets ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Maintenance -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-500 transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Dalam Perbaikan</p>
                            <h3 class="text-3xl font-extrabold text-yellow-600">{{ $maintenanceAssets ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Kartu Rusak -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-red-500 transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Aset Rusak</p>
                            <h3 class="text-3xl font-extrabold text-red-600">{{ $brokenAssets ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sorotan Laporan Terbaru (Ringkas untuk Dashboard) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 mb-6">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 tracking-tight">Status Laporan Terakhir Anda</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Ringkasan aktivitas pelaporan kerusakan aset Anda.</p>
                            </div>
                            
                            <!-- Tombol ke Pusat Laporan Lengkap -->
                            <a href="{{ route('maintenance.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-full transition-all hover:shadow-sm">
                                Lihat Semua Laporan 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        
                        @php
                            // Mengambil HANYA 1 atau 3 laporan terbaru milik pegawai yang sedang login
                            $latestRequests = \App\Models\MaintenanceRequest::with('asset')
                                            ->where('user_id', auth()->id())
                                            ->latest()
                                            ->take(3) // Batasi hanya 3 data agar tetap clean
                                            ->get();
                        @endphp

                        @if($latestRequests->isEmpty())
                            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <p class="text-gray-500 font-medium text-sm">Belum ada laporan kerusakan yang Anda buat.</p>
                                <a href="{{ route('maintenance.create') }}" class="inline-block mt-3 text-xs font-semibold text-blue-600 hover:underline">
                                    + Buat laporan baru sekarang
                                </a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($latestRequests as $req)
                                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 hover:border-blue-100 transition-all flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                                                    {{ $req->priority == 'Critical' ? 'bg-red-100 text-red-700' : 
                                                    ($req->priority == 'High' ? 'bg-orange-100 text-orange-700' : 
                                                    'bg-blue-100 text-blue-700') }}">
                                                    {{ $req->priority }}
                                                </span>
                                                <span class="text-xs text-gray-400 font-medium">{{ $req->created_at->format('d M Y') }}</span>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $req->asset->name ?? 'Aset Dihapus' }}</h4>
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $req->title }}</p>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t border-gray-200/60 flex justify-between items-center">
                                            <span class="text-xs font-semibold px-2.5 py-1 bg-white rounded-lg shadow-2xs text-gray-700 border border-gray-100">
                                                {{ $req->status }}
                                            </span>
                                            <a href="{{ route('maintenance.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                                Detail &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Kolom Kanan: Pesan Sambutan & Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 mb-6">
                        <div class="p-8">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                                👋
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Selamat Datang!</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Anda berada di pusat kendali Sistem Manajemen Aset. Gunakan menu navigasi di atas untuk mengelola data master, melacak lokasi, dan mengontrol inventaris perusahaan secara penuh.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>