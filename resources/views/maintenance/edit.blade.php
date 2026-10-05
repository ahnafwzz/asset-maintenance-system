<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail & Proses Laporan</h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-3xl border border-gray-100">
                
                <!-- Info Laporan -->
                <div class="mb-8 pb-6 border-b border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $request->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $request->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded-xl">
                        <div><span class="text-gray-500 block text-xs uppercase">Aset</span> <span class="font-bold">{{ $request->asset->name }}</span></div>
                        <div><span class="text-gray-500 block text-xs uppercase">Pelapor</span> <span class="font-bold">{{ $request->user->name }}</span></div>
                        <div><span class="text-gray-500 block text-xs uppercase">Prioritas</span> <span class="font-bold text-orange-600">{{ $request->priority }}</span></div>
                        <div><span class="text-gray-500 block text-xs uppercase">Status Saat Ini</span> <span class="font-bold">{{ $request->status }}</span></div>
                    </div>
                </div>

                <!-- Form Tindak Lanjut -->
                <form method="POST" action="{{ route('maintenance.update', $request->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="status" value="Ubah Status Perbaikan" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="Reviewed" {{ $request->status == 'Reviewed' ? 'selected' : '' }}>Reviewed (Sudah Dicek)</option>
                            <option value="Assigned" {{ $request->status == 'Assigned' ? 'selected' : '' }}>Assigned (Ditugaskan)</option>
                            <option value="In Progress" {{ $request->status == 'In Progress' ? 'selected' : '' }}>In Progress (Sedang Dikerjakan)</option>
                            <option value="Resolved" {{ $request->status == 'Resolved' ? 'selected' : '' }}>Resolved (Selesai)</option>
                        </select>
                    </div>

                    @hasanyrole('Super Admin|Asset Manager')
                    <div>
                        <x-input-label for="technician_id" value="Tugaskan Kepada Teknisi" />
                        <select id="technician_id" name="technician_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Teknisi (Opsional) --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $request->technician_id == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Jika dikosongkan, teknisi yang mengklik "In Progress" akan otomatis ditugaskan.</p>
                    </div>
                    @endhasanyrole

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">Simpan Perubahan</x-primary-button>
                        <a href="{{ route('maintenance.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>