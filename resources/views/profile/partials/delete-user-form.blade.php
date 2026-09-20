<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-600 tracking-tight flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Hapus Akun
        </h2>

        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus, harap unduh data atau informasi yang ingin Anda simpan. Aksi ini tidak dapat dibatalkan.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-6 py-2.5 rounded-full text-sm font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                Tindakan ini sangat fatal. Setelah dihapus, semua data akan hilang selamanya. Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" placeholder="Masukkan Sandi Anda" 
                    class="block w-full sm:w-3/4 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-red-50 focus:border-red-500 focus:bg-white transition-all outline-none" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-bold transition-colors">
                    Batal
                </button>

                <button type="submit" 
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
                    Ya, Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>