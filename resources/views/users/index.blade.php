<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna & Approval') }}
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
                    <h3 class="text-lg font-bold mb-4">Daftar Pegawai Terdaftar</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="p-3 text-sm font-semibold">Nama / Username</th>
                                    <th class="p-3 text-sm font-semibold">Role Saat Ini</th>
                                    <th class="p-3 text-sm font-semibold">Status Approval</th>
                                    <th class="p-3 text-sm font-semibold w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">
                                            <div class="font-medium text-sm">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ '@'.$user->username }}</div>
                                        </td>
                                        <td class="p-3 text-sm">
                                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs font-semibold">
                                                {{ $user->roles->first()->name ?? 'Tidak Ada' }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm">
                                            @if($user->approval_status === 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-300">
                                                    Meminta: {{ $user->requested_role }}
                                                </span>
                                            @elseif($user->approval_status === 'approved')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Telah Disetujui</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">Standar (Viewer)</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm">
                                            @if($user->approval_status === 'pending')
                                                <form action="{{ route('users.approve', $user->id) }}" method="POST" onsubmit="return confirm('Setujui kenaikan role untuk {{ $user->name }}?');">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs shadow-sm font-semibold">
                                                        ✓ Approve
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Tidak ada aksi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-3 text-center text-gray-500">Belum ada user terdaftar.</td>
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