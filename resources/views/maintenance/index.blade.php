<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pusat Laporan Perbaikan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Card Utama -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    
                    <!-- Header, Tab, dan Search Bar -->
                    <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
                        
                        <!-- Navigasi Tab (Sekarang terkoneksi dengan form) -->
                        <div class="flex bg-gray-100 p-1 rounded-full w-full lg:w-auto">
                            <button type="button" onclick="document.getElementById('tabInput').value='diproses'; document.getElementById('searchForm').submit();"
                               class="flex-1 lg:flex-none text-center px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 {{ $tab !== 'selesai' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                Sedang Diproses
                            </button>
                            <button type="button" onclick="document.getElementById('tabInput').value='selesai'; document.getElementById('searchForm').submit();"
                               class="flex-1 lg:flex-none text-center px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 {{ $tab === 'selesai' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                Riwayat Selesai
                            </button>
                        </div>

                        <!-- Area Kanan: Tombol Baru & Kolom Pencarian -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                            
                            <!-- Tombol Buat Laporan Baru (Sebelah kiri Search) -->
                            <a href="{{ route('maintenance.create') }}" class="whitespace-nowrap text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-5 py-2.5 rounded-full transition-colors flex-shrink-0">
                                + Buat Laporan Baru
                            </a>

                            <!-- Kolom Pencarian -->
                            <form method="GET" action="{{ route('maintenance.index') }}" id="searchForm" class="w-full sm:w-72">
                                <input type="hidden" name="tab" id="tabInput" value="{{ $tab }}">
                                
                                <!-- WRAPPER BARU: Mengunci ikon di dalam kotak -->
                                <div class="relative w-full">
                                    <input type="text" name="search" value="{{ $search }}" id="searchInput" oninput="liveSearch()"
                                           placeholder="Cari aset atau nama pelapor..." 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-full border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all shadow-sm">
                                    
                                    <!-- Ikon Kaca Pembesar -->
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Data Laporan -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                                    <th class="py-3 px-4 font-bold">Aset & Kerusakan</th>
                                    <th class="py-3 px-4 font-bold">Pelapor</th>
                                    <th class="py-3 px-4 font-bold">Prioritas</th>
                                    <th class="py-3 px-4 font-bold">Status</th>
                                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($requests as $request)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        
                                        <!-- Kolom Aset -->
                                        <td class="py-4 px-4">
                                            <div class="font-bold text-gray-900">{{ $request->asset->name ?? 'Aset Dihapus' }} <span class="text-xs text-gray-400 font-normal">({{ $request->asset->asset_code ?? '-' }})</span></div>
                                            <div class="text-gray-600 mt-0.5">{{ $request->title }}</div>
                                            <div class="text-xs text-gray-400 mt-1">{{ $request->created_at->format('d M Y, H:i') }}</div>
                                        </td>

                                        <!-- Kolom Pelapor & Teknisi -->
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-900">Oleh: {{ $request->user->name ?? 'Unknown' }}</div>
                                            @if($request->technician_id)
                                                <div class="text-xs text-blue-600 mt-1 font-bold">Teknisi: {{ $request->technician->name }}</div>
                                            @else
                                                <div class="text-xs text-orange-500 mt-1 font-semibold">Belum Ditugaskan</div>
                                            @endif
                                        </td>

                                        <!-- Kolom Prioritas -->
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                                {{ $request->priority == 'Critical' ? 'bg-red-100 text-red-700' : 
                                                  ($request->priority == 'High' ? 'bg-orange-100 text-orange-700' : 
                                                  'bg-blue-100 text-blue-700') }}">
                                                {{ $request->priority }}
                                            </span>
                                        </td>

                                        <!-- Kolom Status -->
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                                {{ $request->status }}
                                            </span>
                                        </td>

                                        <!-- Kolom Aksi Dinamis -->
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                
                                                @php $hasAction = false; @endphp

                                                <!-- Tombol Batal: Muncul jika statusnya Reported & (milik user sendiri ATAU Super Admin) -->
                                                @if($request->status === 'Reported' && (auth()->id() === $request->user_id || auth()->user()->hasRole('Super Admin')))
                                                    <form method="POST" action="{{ route('maintenance.update', $request->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan laporan ini?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="Cancelled">
                                                        <button type="submit" class="text-xs font-semibold text-orange-600 bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg transition-colors">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                    @php $hasAction = true; @endphp
                                                @endif

                                                <!-- Tombol Proses (Gol 2): Untuk Asset Manager / Maintenance Staff -->
                                                @hasanyrole('Asset Manager|Maintenance Staff|Super Admin')
                                                    @if($request->status !== 'Resolved' && $request->status !== 'Closed' && $request->status !== 'Cancelled' && $request->status !== 'Rejected')
                                                        
                                                        @php 
                                                            $canEdit = true;
                                                            // Jika user hanya teknisi, dan laporan sudah dipegang orang lain, sembunyikan tombolnya!
                                                            if(auth()->user()->hasRole('Maintenance Staff') && !auth()->user()->hasAnyRole('Super Admin|Asset Manager')) {
                                                                if($request->technician_id !== null && $request->technician_id !== auth()->id()) {
                                                                    $canEdit = false;
                                                                }
                                                            }
                                                        @endphp

                                                        @if($canEdit)
                                                            <a href="{{ route('maintenance.edit', $request->id) }}" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                                                Tindak Lanjuti
                                                            </a>
                                                            @php $hasAction = true; @endphp
                                                        @endif

                                                    @endif
                                                @endhasanyrole

                                                <!-- Tombol Hapus (Gol 1): HANYA Super Admin -->
                                                @hasrole('Super Admin')
                                                    <form method="POST" action="{{ route('maintenance.destroy', $request->id) }}" onsubmit="return confirm('Hapus laporan ini secara permanen? Data tidak dapat dikembalikan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors ml-1">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                    @php $hasAction = true; @endphp
                                                @endhasrole

                                                <!-- Fallback Visual: Jika tidak ada tombol yang muncul -->
                                                @if(!$hasAction)
                                                    <span class="text-gray-300 font-bold text-lg cursor-not-allowed" title="Tidak ada akses aksi untuk laporan ini">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-500">
                                            <div class="text-4xl mb-3">📁</div>
                                            <p class="font-medium">Tidak ada laporan yang ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Live Search -->
    <script>
        let typingTimer;                // Timer identifier
        const doneTypingInterval = 500; // Waktu tunggu 0.5 detik
        const searchInput = document.getElementById('searchInput');

        // Fungsi dipanggil setiap kali ada huruf yang diketik
        function liveSearch() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, doneTypingInterval);
        }

        // Trik UX: Mengembalikan fokus kursor ke dalam kotak pencarian setelah halaman mereload hasil
        window.onload = function() {
            if (searchInput.value.length > 0) {
                searchInput.focus();
                // Taruh kursor di huruf paling belakang
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
    </script>
</x-app-layout>