<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

       <!-- Role sebagai radio -->
        <div class="mt-4">
            <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">Daftar sebagai</span>

            <div class="flex items-center space-x-6 mt-1">
                <label class="flex items-center">
                    <input type="radio" name="role" value="pencaker"
                        class="mr-2 role-choice"
                        {{ old('role', 'pencaker') === 'pencaker' ? 'checked' : '' }}>
                    <span>Pencari Kerja</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" name="role" value="perusahaan"
                        class="mr-2 role-choice"
                        {{ old('role') === 'perusahaan' ? 'checked' : '' }}>
                    <span>Perusahaan</span>
                </label>
            </div>
        </div>



        <!-- ============================== -->
        <!-- FORM PENCAKER -->
        <!-- ============================== -->
        <div id="form-pencaker">

            <!-- NIK -->
            <div class="mt-4">
                <x-input-label for="nik" value="NIK" />
                <x-text-input id="nik" class="block mt-1 w-full" type="text" 
                              name="nik" :value="old('nik')" maxlength="16" 
                              inputmode="numeric" pattern="[0-9]*" 
                              placeholder="Masukkan 16 digit NIK" />
                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            </div>

            <!-- Nama Lengkap -->
            <div class="mt-4">
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" 
                              name="name" :value="old('name')" autofocus 
                              autocomplete="name" placeholder="Nama sesuai KTP" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

        </div>

        <!-- ============================== -->
        <!-- FORM PERUSAHAAN -->
        <!-- ============================== -->
        <div id="form-perusahaan" class="hidden">

            <!-- Nama Perusahaan -->
            <div class="mt-4">
                <x-input-label for="company_name" value="Nama Perusahaan" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text"
                              name="company_name" :value="old('company_name')"
                              placeholder="Masukkan nama perusahaan" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

        </div>

        <!-- Email (dipakai kedua role) -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" 
                          name="email" :value="old('email')" required 
                          autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" 
                          name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" 
                          type="password" name="password_confirmation" required 
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>

    <!-- SCRIPT: TOGGLE FORM -->
    <script>
    function toggleForms() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;

        const formPencaker = document.getElementById('form-pencaker');
        const formPerusahaan = document.getElementById('form-perusahaan');

        if (selectedRole === 'perusahaan') {
            formPencaker.classList.add('hidden');
            formPerusahaan.classList.remove('hidden');
        } else {
            formPerusahaan.classList.add('hidden');
            formPencaker.classList.remove('hidden');
        }
    }

    // Event listener untuk semua radio button role
    document.querySelectorAll('input[name="role"]').forEach((radio) => {
        radio.addEventListener('change', toggleForms);
    });

    // Trigger saat halaman pertama kali dibuka (untuk old value saat validation error)
    window.addEventListener('load', toggleForms);
</script>


</x-guest-layout>
