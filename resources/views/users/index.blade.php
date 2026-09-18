<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna & Approval') }}
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

            <!-- 1. KARTU SUMMARY (Menggunakan Variabel dari Controller) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pegawai -->
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total Pegawai</p>
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalUsers ?? 0 }}</h3> 
                    </div>
                </div>

                <!-- Menunggu Approval -->
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-2xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-0.5">Menunggu</p>
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $pendingUsers ?? 0 }}</h3>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-0.5">Disetujui</p>
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $approvedUsers ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card Utama Tabel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    
                    <div class="flex flex-col mb-8 gap-1">
                        <h3 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Pegawai Terdaftar</h3>
                        <p class="text-xs text-gray-500">Kelola akses, role, dan persetujuan akun pegawai di sini.</p>
                    </div>
                    
                    <!-- SEARCH BAR & FILTER LENGKAP -->
                    <div class="flex flex-col lg:flex-row justify-between items-center mb-6 gap-4 bg-gray-50 p-2 rounded-2xl border border-gray-100">
                        
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('users.index') }}" id="searchForm" class="w-full lg:w-1/2">
                            <div class="relative w-full">
                                <input type="text" name="search" id="searchInput" oninput="liveSearch()" placeholder="🔍 Cari nama atau username..." 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-transparent bg-white text-sm focus:border-blue-500 focus:ring focus:ring-blue-100 shadow-sm transition-all"
                                       value="{{ request('search') }}">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                            @if(request('role')) <input type="hidden" name="role" value="{{ request('role') }}"> @endif
                        </form>

                        <!-- Filter Status & Role -->
                        <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-2">
                            <form method="GET" action="{{ route('users.index') }}" id="filterForm" class="flex flex-col sm:flex-row gap-2 w-full">
                                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                
                                <!-- Dropdown Status -->
                                <select name="status" onchange="document.getElementById('filterForm').submit();" class="w-full sm:w-44 bg-white border-transparent text-sm rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-100 shadow-sm font-medium text-gray-700">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Status: Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                                </select>

                                <!-- Dropdown Role -->
                                <select name="role" onchange="document.getElementById('filterForm').submit();" class="w-full sm:w-44 bg-white border-transparent text-sm rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-100 shadow-sm font-medium text-gray-700">
                                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Role: Semua</option>
                                    @foreach($roles ?? [] as $r)
                                        <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tabel Data -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                                    <th class="py-4 px-4 font-bold">Pegawai</th>
                                    <th class="py-4 px-4 font-bold">Role Saat Ini</th>
                                    <th class="py-4 px-4 font-bold">Role Diminta</th>
                                    <th class="py-4 px-4 font-bold">Tgl. Pengajuan</th>
                                    <th class="py-4 px-4 font-bold">Status</th>
                                    <th class="py-4 px-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($users as $user)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-200 shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500 font-mono">{{ '@'.$user->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold border border-gray-200">
                                                {{ $user->roles->first()->name ?? 'Tanpa Role' }}
                                            </span>
                                        </td>

                                        <td class="py-4 px-4">
                                            @if($user->approval_status === 'pending' && $user->requested_role)
                                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">
                                                    {{ $user->requested_role }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-bold text-lg">-</span>
                                            @endif
                                        </td>
                                        
                                        <!-- TANGGAL PENGAJUAN (Menggunakan kolom khusus yang lebih akurat) -->
                                        <td class="py-4 px-4">
                                            @if($user->approval_status === 'pending' && $user->approval_requested_at)
                                                <div class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($user->approval_requested_at)->format('d M Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($user->approval_requested_at)->format('H:i') }}</div>
                                            @else
                                                <span class="text-gray-400 font-bold text-lg">-</span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-4">
                                            @if($user->approval_status === 'pending')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold flex items-center gap-1 w-max">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                                                </span>
                                            @elseif($user->approval_status === 'approved')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold flex items-center gap-1 w-max">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Approved
                                                </span>
                                            @elseif($user->approval_status === 'rejected')
                                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold flex items-center gap-1 w-max">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold">Unregistered</span>
                                            @endif
                                        </td>
                                        
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                
                                                <!-- Jika Pending: Muncul Approve & Reject -->
                                                @if($user->approval_status === 'pending')
                                                    <form action="{{ route('users.approve', $user->id) }}" method="POST" onsubmit="return confirm('Setujui permintaan akses {{ $user->requested_role }} untuk {{ $user->name }}?');">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded-lg shadow-sm transition-colors" title="Setujui">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('users.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan {{ $user->name }}?');">
                                                        @csrf
                                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 p-1.5 rounded-lg transition-colors border border-red-200" title="Tolak">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Jika BUKAN Pending: Muncul Edit Manual -->
                                                    <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Manual">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                @endif
                                                
                                                <!-- Tombol Hapus Akun (Selalu ada) -->
                                                @if(auth()->id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $user->name }} secara permanen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Pengguna">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-gray-500">
                                            <div class="text-4xl mb-3 text-gray-300">
                                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="font-medium">Belum ada user yang terdaftar atau sesuai pencarian.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        @if(method_exists($users, 'links'))
                            {{ $users->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Live Search -->
    <script>
        let typingTimer;                
        const doneTypingInterval = 500; 
        const searchInput = document.getElementById('searchInput');

        function liveSearch() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, doneTypingInterval);
        }

        window.onload = function() {
            if (searchInput.value.length > 0) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
    </script>
</x-app-layout>