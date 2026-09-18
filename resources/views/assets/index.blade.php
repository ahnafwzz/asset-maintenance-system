<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Aset') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success Apple Style -->
            @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Card Utama -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    
                    <!-- Header & Tombol Tambah -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Seluruh Aset</h3>
                            <p class="text-xs text-gray-500 mt-1">Pantau, kendalikan, dan kelola seluruh inventaris perangkat perusahaan di sini.</p>
                        </div>
                        
                        <!-- GEMBOK TOMBOL TAMBAH -->
                        @unlessrole('Viewer')
                        <a href="{{ route('assets.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Aset Baru
                        </a>
                        @endunlessrole
                    </div>
                    
                    <!-- Tabel Data -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                                    <th class="py-3 px-4 font-bold">Kode Aset</th>
                                    <th class="py-3 px-4 font-bold">Detail Aset</th>
                                    <th class="py-3 px-4 font-bold">Penempatan</th>
                                    <th class="py-3 px-4 font-bold">Status</th>
                                    
                                    <!-- GEMBOK HEADER AKSI -->
                                    @unlessrole('Viewer')
                                    <th class="py-3 px-4 font-bold text-center w-32">Aksi</th>
                                    @endunlessrole
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($assets as $asset)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        <!-- Kode Aset bergaya struk/mono -->
                                        <td class="py-4 px-4">
                                            <span class="font-mono text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">
                                                {{ $asset->asset_code }}
                                            </span>
                                        </td>
                                        
                                        <!-- Nama & Kategori diringkas dalam 1 kolom agar lebih rapi -->
                                        <td class="py-4 px-4">
                                            <div class="font-bold text-gray-900">{{ $asset->name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                {{ $asset->category->name ?? 'Tanpa Kategori' }}
                                            </div>
                                        </td>
                                        
                                        <!-- Lokasi & Departemen -->
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-800">{{ $asset->location->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $asset->department->name ?? '-' }}</div>
                                        </td>
                                        
                                        <!-- Status dengan Pil Warna ala iOS -->
                                        <td class="py-4 px-4">
                                            @if($asset->status == 'active')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 font-bold rounded-full text-xs shadow-sm">Aktif</span>
                                            @elseif($asset->status == 'maintenance')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 font-bold rounded-full text-xs shadow-sm">Maintenance</span>
                                            @elseif($asset->status == 'broken')
                                                <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-full text-xs shadow-sm">Rusak</span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-200 text-gray-700 font-bold rounded-full text-xs shadow-sm border border-gray-300">Pensiun</span>
                                            @endif
                                        </td>
                                        
                                        <!-- GEMBOK TOMBOL EDIT & HAPUS -->
                                        @unlessrole('Viewer')
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('assets.edit', $asset->id) }}" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                                    Edit
                                                </a>
                                                <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini secara permanen? Data yang dihapus tidak dapat dikembalikan.');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endunlessrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-500">
                                            <div class="text-4xl mb-3 text-gray-300">
                                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                                            </div>
                                            <p class="font-medium">Belum ada data aset perusahaan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>