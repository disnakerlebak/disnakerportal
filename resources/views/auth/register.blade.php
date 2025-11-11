<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- NIK -->
        <div>
            <x-input-label for="nik" value="NIK" />
            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" maxlength="16" inputmode="numeric" pattern="[0-9]*" placeholder="Masukkan 16 digit NIK" />
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <!-- Nama Lengkap -->
        <div class="mt-4">
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama sesuai KTP" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Daftar sebagai</label>
            <select id="role" name="role" required class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="pencaker" @selected(old('role')==='pencaker')>Pencari Kerja</option>
                <option value="perusahaan" @selected(old('role')==='perusahaan')>Perusahaan</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
