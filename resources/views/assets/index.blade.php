<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Aset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Seluruh Aset</h3>
                        <a href="{{ route('assets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                            + Tambah Aset Baru
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold">Kode</th>
                                    <th class="p-3 text-sm font-semibold">Nama Aset</th>
                                    <th class="p-3 text-sm font-semibold">Kategori</th>
                                    <th class="p-3 text-sm font-semibold">Lokasi</th>
                                    <th class="p-3 text-sm font-semibold">Departemen</th>
                                    <th class="p-3 text-sm font-semibold">Status</th>
                                    <th class="p-3 text-sm font-semibold w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $asset)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm font-mono text-gray-600">{{ $asset->asset_code }}</td>
                                        <td class="p-3 text-sm font-medium">{{ $asset->name }}</td>
                                        <td class="p-3 text-sm">{{ $asset->category->name ?? '-' }}</td>
                                        <td class="p-3 text-sm">{{ $asset->location->name ?? '-' }}</td>
                                        <td class="p-3 text-sm">{{ $asset->department->name ?? '-' }}</td>
                                        <td class="p-3 text-sm">
                                            @if($asset->status == 'active')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aktif</span>
                                            @elseif($asset->status == 'maintenance')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Maintenance</span>
                                            @elseif($asset->status == 'broken')
                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Rusak</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">Pensiun</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm flex gap-3">
                                            <a href="{{ route('assets.edit', $asset->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-3 text-center text-gray-500">Belum ada data aset.</td>
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