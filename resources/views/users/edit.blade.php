<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-3xl border border-gray-100">
                <header class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Pengguna</h2>
                    <p class="text-sm text-gray-500">Perbarui profil dan hak akses pengguna di sini.</p>
                </header>

                <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Alamat Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Peran Sistem')" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm py-2.5">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">{{ __('Simpan Perubahan') }}</x-primary-button>
                        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>