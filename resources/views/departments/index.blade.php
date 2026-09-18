<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Departemen') }}
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
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Departemen</h3>
                            <p class="text-xs text-gray-500 mt-1">Kelola data divisi atau unit kerja perusahaan.</p>
                        </div>
                        
                        <!-- GEMBOK TOMBOL TAMBAH -->
                        @unlessrole('Viewer')
                        <a href="{{ route('departments.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Departemen
                        </a>
                        @endunlessrole
                    </div>
                    
                    <!-- Tabel Data -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                                    <th class="py-3 px-4 font-bold">Nama Departemen</th>
                                    <th class="py-3 px-4 font-bold">Deskripsi</th>
                                    
                                    <!-- GEMBOK HEADER AKSI -->
                                    @unlessrole('Viewer')
                                    <th class="py-3 px-4 font-bold text-center w-32">Aksi</th>
                                    @endunlessrole
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($departments as $department)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-4 font-bold text-gray-900">{{ $department->name }}</td>
                                        <td class="py-4 px-4 text-gray-600">{{ $department->description ?? '-' }}</td>
                                        
                                        <!-- GEMBOK TOMBOL EDIT & HAPUS -->
                                        @unlessrole('Viewer')
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('departments.edit', $department->id) }}" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                                    Edit
                                                </a>
                                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus departemen ini?');" class="inline-block">
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
                                        <td colspan="3" class="py-12 text-center text-gray-500">
                                            <div class="text-4xl mb-3">🏢</div>
                                            <p class="font-medium">Belum ada data departemen.</p>
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