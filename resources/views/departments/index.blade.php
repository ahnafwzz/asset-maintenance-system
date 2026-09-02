<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departments Master Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Sukses -->
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header Tabel & Tombol Tambah -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Departemen</h3>
                        <a href="{{ route('departments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                            + Tambah Departemen
                        </a>
                    </div>
                    
                    <!-- Tabel Data -->
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold">Nama Departemen</th>
                                <th class="p-3 text-sm font-semibold">Deskripsi</th>
                                <th class="p-3 text-sm font-semibold w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $dept)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $dept->name }}</td>
                                    <td class="p-3">{{ $dept->description ?? '-' }}</td>
                                    <td class="p-3 text-sm flex gap-3">
                                        
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('departments.edit', $dept->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                        
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus departemen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-3 text-center text-gray-500">Belum ada data departemen.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>