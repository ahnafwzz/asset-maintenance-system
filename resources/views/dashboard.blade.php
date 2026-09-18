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

            <!-- LAYOUT BAWAH (65 : 35) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div class="lg:col-span-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 h-full flex flex-col">
                        <div class="p-8 flex-1 flex flex-col">
                            
                            <!-- Header Kolom Kiri -->
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 tracking-tight">Laporan Kerusakan Terbaru</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Informasi kerusakan aset terkini dari seluruh pengguna.</p>
                                </div>
                                
                                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-full transition-all hover:shadow-sm shrink-0">
                                    Lihat Semua 
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                            
                            @php
                                // MENGAMBIL 1 LAPORAN TERBARU (Global) BESERTA RELASI USER DAN ASET
                                $latestRequest = \App\Models\MaintenanceRequest::with(['asset', 'user'])
                                                ->latest()
                                                ->first();
                            @endphp

                            @if(!$latestRequest)
                                <!-- Empty State -->
                                <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200 flex-1 flex flex-col justify-center items-center">
                                    <p class="text-gray-500 font-medium text-sm">Belum ada laporan kerusakan di sistem.</p>
                                </div>
                            @else
                                <!-- Kartu Hero Laporan Terbaru -->
                                <div onclick="openReportModal()" class="block bg-gradient-to-br from-gray-50 to-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm flex-1 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 hover:shadow-md transition-all duration-300 cursor-pointer">
                                    
                                    <!-- Efek Latar Apple (Glow biru) -->
                                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:opacity-100 group-hover:bg-blue-100 transition-all duration-500"></div>

                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start mb-5">
                                            <div class="pr-4">
                                                <h4 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight group-hover:text-blue-700 transition-colors">{{ $latestRequest->asset->name ?? 'Aset Dihapus' }}</h4>
                                                <p class="text-xs font-medium text-gray-400 mt-1 uppercase tracking-wider">KODE: {{ $latestRequest->asset->asset_code ?? '-' }}</p>
                                            </div>
                                            <span class="px-3 py-1 bg-white rounded-xl shadow-sm text-xs font-bold text-gray-700 border border-gray-200 shrink-0">
                                                {{ $latestRequest->status }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                                            <div class="flex-1 pr-8">
                                                <p class="text-base font-bold text-gray-800 mb-1">{{ $latestRequest->title }}</p>
                                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">{{ $latestRequest->description ?? 'Tidak ada detail.' }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-xl text-xs font-bold shrink-0 shadow-sm
                                                {{ $latestRequest->priority == 'Critical' ? 'bg-red-100 text-red-700' : 
                                                  ($latestRequest->priority == 'High' ? 'bg-orange-100 text-orange-700' : 
                                                  'bg-blue-100 text-blue-700') }}">
                                                {{ $latestRequest->priority }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="relative z-10 mt-auto pt-5 border-t border-gray-100 flex justify-between items-center pr-10">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-200">
                                                {{ strtoupper(substr($latestRequest->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Dilaporkan Oleh</p>
                                                <p class="text-sm font-bold text-gray-900">{{ $latestRequest->user->name ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Tanggal</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $latestRequest->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>

                                    <!-- Ikon Panah Interaktif -->
                                    <div class="absolute bottom-8 right-6 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 z-20">
                                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- POP-UP MODAL (Awalnya disembunyikan) -->
                                <div id="reportModal" class="fixed inset-0 z-50 hidden items-center justify-center opacity-0 transition-opacity duration-300">
                                    <!-- Background Blur Gelap -->
                                    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm cursor-pointer" onclick="closeReportModal()"></div>
                                    
                                    <!-- Kotak Putih Modal -->
                                    <div id="reportModalContent" class="relative bg-white w-full max-w-2xl mx-4 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300">
                                        
                                        <!-- Header Modal -->
                                        <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                            <h3 class="text-lg font-bold text-gray-900">Detail Lengkap Laporan</h3>
                                            <button onclick="closeReportModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Isi Modal -->
                                        <div class="p-8">
                                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                                                <div>
                                                    <h4 class="text-2xl font-extrabold text-gray-900">{{ $latestRequest->asset->name ?? 'Aset Dihapus' }}</h4>
                                                    <p class="text-sm font-medium text-gray-500 mt-1 uppercase tracking-wider">KODE: {{ $latestRequest->asset->asset_code ?? '-' }}</p>
                                                </div>
                                                <div class="flex gap-2">
                                                    <span class="px-3 py-1 rounded-xl text-xs font-bold shadow-sm {{ $latestRequest->priority == 'Critical' ? 'bg-red-100 text-red-700' : ($latestRequest->priority == 'High' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                                        {{ $latestRequest->priority }}
                                                    </span>
                                                    <span class="px-3 py-1 bg-gray-100 rounded-xl text-xs font-bold text-gray-700 border border-gray-200">
                                                        {{ $latestRequest->status }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-8">
                                                <p class="text-base font-bold text-gray-800 mb-2">Kerusakan: {{ $latestRequest->title }}</p>
                                                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                                                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $latestRequest->description ?? 'Tidak ada rincian kerusakan yang dicantumkan.' }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex justify-between items-center pt-5 border-t border-gray-100">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-200">
                                                        {{ strtoupper(substr($latestRequest->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Dilaporkan Oleh</p>
                                                        <p class="text-sm font-bold text-gray-900">{{ $latestRequest->user->name ?? 'Unknown' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-0.5">Waktu Laporan</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $latestRequest->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Script Pengendali Pop-up -->
                                <script>
                                    function openReportModal() {
                                        const modal = document.getElementById('reportModal');
                                        const content = document.getElementById('reportModalContent');
                                        
                                        // Tampilkan elemennya dulu
                                        modal.classList.remove('hidden');
                                        modal.classList.add('flex');
                                        
                                        // Beri jeda sepersekian detik biar animasinya jalan
                                        setTimeout(() => {
                                            modal.classList.remove('opacity-0');
                                            content.classList.remove('scale-95');
                                        }, 10);
                                    }

                                    function closeReportModal() {
                                        const modal = document.getElementById('reportModal');
                                        const content = document.getElementById('reportModalContent');
                                        
                                        // Tarik animasinya mundur
                                        modal.classList.add('opacity-0');
                                        content.classList.add('scale-95');
                                        
                                        // Sembunyikan elemen setelah animasi selesai (300ms)
                                        setTimeout(() => {
                                            modal.classList.add('hidden');
                                            modal.classList.remove('flex');
                                        }, 300);
                                    }
                                </script>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 h-full flex flex-col justify-center">
                        <div class="p-8 text-center sm:text-left">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-5 mx-auto sm:mx-0 shadow-sm border border-blue-100">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Halo, {{ auth()->user()->name }}! 👋</h3>
                            
                            <p class="text-sm text-gray-500 leading-relaxed mb-6">
                                Selamat datang kembali. Anda saat ini masuk dengan hak akses sebagai <span class="font-semibold text-blue-600">{{ auth()->user()->roles->first()->name ?? 'Pengguna' }}</span>. Pantau aset dan kendalikan inventaris Anda dari sini.
                            </p>

                            <a href="{{ route('profile.edit') }}" class="inline-block w-full text-center bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-3 rounded-xl transition-colors border border-gray-200">
                                Kelola Profil Saya
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>