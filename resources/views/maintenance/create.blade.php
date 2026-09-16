<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lapor Kerusakan Aset') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-10">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Formulir Maintenance</h3>
                        <p class="text-sm text-gray-500 mt-1">Silakan isi detail kerusakan aset untuk ditindaklanjuti oleh teknisi.</p>
                    </div>

                    <form action="{{ route('maintenance.store') }}" method="POST">
                        @csrf
                        
                        <!-- Pilihan Aset -->
                        <div class="mb-6">
                            <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Aset <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="asset_id" id="asset_id" required class="appearance-none w-full rounded-2xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all duration-200 bg-white py-3 pl-4 pr-10 text-gray-700 cursor-pointer">
                                    <option value="" disabled selected>-- Ketuk untuk memilih aset --</option>
                                    @foreach($assets as $asset)
                                        @if($asset->status === 'maintenance')
                                            <!-- Tampilan Lembut untuk Aset yang Sedang Diperbaiki -->
                                            <option value="{{ $asset->id }}" disabled class="text-gray-400 bg-gray-50">
                                                🔒 {{ $asset->name }} ({{ $asset->asset_code }}) - Sedang Perbaikan
                                            </option>
                                        @else
                                            <!-- Tampilan Standar untuk Aset Aktif -->
                                            <option value="{{ $asset->id }}" class="text-gray-900">
                                                {{ $asset->name }} ({{ $asset->asset_code }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                
                                <!-- Panah Kustom ala iOS -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Teks Bantuan Halus di Bawah Kotak -->
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Aset dengan ikon 🔒 tidak dapat dipilih karena sedang ditangani oleh teknisi.
                            </p>
                        </div>

                        <!-- Judul Laporan -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Laporan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required placeholder="Contoh: Layar Monitor Bergaris" class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50">
                        </div>

                        <!-- Tingkat Prioritas -->
                        <div class="mb-6">
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Prioritas <span class="text-red-500">*</span></label>
                            <select name="priority" id="priority" required class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50">
                                <option value="Low">Low (Bisa ditunda)</option>
                                <option value="Medium" selected>Medium (Mengganggu pekerjaan)</option>
                                <option value="High">High (Harus segera diperbaiki)</option>
                                <option value="Critical">Critical (Sistem lumpuh total)</option>
                            </select>
                        </div>

                        <!-- Deskripsi Kerusakan -->
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Detail Kerusakan <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="4" required placeholder="Jelaskan secara kronologis bagaimana kerusakan terjadi..." class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50 resize-none"></textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('maintenance.index') }}" class="text-gray-500 hover:text-gray-800 font-medium mr-6 transition-colors">Batal</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>