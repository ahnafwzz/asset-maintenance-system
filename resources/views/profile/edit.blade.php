<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('status') === 'role-requested')
                <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm transition-all">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium text-sm">Pengajuan hak akses berhasil dikirim dan sedang menunggu persetujuan Super Admin.</span>
                </div>
            @endif

            <div class="p-8 bg-white shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Akses & Peran Sistem</h2>
                        <p class="mt-1 text-sm text-gray-500">Peran Anda saat ini menentukan fitur apa saja yang dapat Anda gunakan di dalam aplikasi.</p>
                    </header>

                    <div class="mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 font-bold text-xl border border-blue-100">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-0.5">Peran Saat Ini</p>
                            <p class="text-base font-bold text-gray-900">{{ auth()->user()->roles->first()->name ?? 'Pengguna Standar (Viewer)' }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->approval_status === 'pending')
                        <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800">Menunggu Persetujuan</p>
                                    <p class="text-sm text-yellow-700 mt-1 leading-relaxed">
                                        Anda telah mengajukan akses sebagai <span class="font-bold">{{ auth()->user()->requested_role }}</span> pada {{ auth()->user()->approval_requested_at ? \Carbon\Carbon::parse(auth()->user()->approval_requested_at)->format('d M Y') : 'beberapa waktu lalu' }}. Mohon tunggu konfirmasi dari Super Admin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif(!auth()->user()->hasRole('Super Admin'))
                        <form method="POST" action="{{ route('profile.request-role') }}" class="mt-6">
                            @csrf
                            <div class="mb-4">
                                <label for="requested_role" class="block text-sm font-medium text-gray-700 mb-2">Ajukan Kenaikan Peran</label>
                                @php
                                    $availableRoles = \Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get();
                                @endphp
                                <select name="requested_role" id="requested_role" class="w-full border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-xl shadow-sm text-sm py-2.5 transition-colors" required>
                                    <option value="" disabled selected>-- Pilih peran yang dibutuhkan --</option>
                                    @foreach($availableRoles as $role)
                                        @if(!auth()->user()->hasRole($role->name))
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-full text-sm font-semibold transition-all shadow-sm">
                                Kirim Pengajuan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="p-8 bg-white shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-sm sm:rounded-3xl border border-red-50">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>