<div class="max-w-7xl mx-auto flex flex-col gap-4">
    {{-- Filter bar --}}
    <div class="flex flex-wrap items-center gap-3">
        <input type="text"
               wire:model.debounce.400ms="q"
               placeholder="Cari nama perusahaan atau email..."
               class="w-72 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />

        <select wire:model="verificationStatus"
                class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status Verifikasi</option>
            <option value="pending">Belum Disetujui</option>
            <option value="approved">Disetujui</option>
        </select>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md border border-emerald-500/40 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="relative flex-1 min-h-0 flex flex-col rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
        <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-slate-950/30 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-900/70 border border-slate-700 shadow text-indigo-200">
                <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm">Memuat data…</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-200">
                <thead class="bg-slate-800 text-slate-200">
                <tr>
                    <th class="p-3 text-left">Nama Perusahaan</th>
                    <th class="p-3 text-left">Jenis / Bidang Usaha</th>
                    <th class="p-3 text-left">Domisili Perusahaan</th>
                    <th class="p-3 text-left">Status Verifikasi</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                @forelse($companies as $c)
                    @php
                        $user = $c->user;
                        $isActive = ($user->status ?? 'active') === 'active';
                        $verified = $c->verification_status === 'approved';
                    @endphp
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="p-3 align-top">
                            <div class="font-semibold text-slate-100">
                                {{ $c->nama_perusahaan ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                Terdaftar:
                                {{ optional($user->created_at)->format('d M Y H:i') ?? '-' }}
                            </div>
                        </td>
                        <td class="p-3 align-top">
                            {{ $c->jenis_usaha ?? '-' }}
                        </td>
                        <td class="p-3 align-top text-sm text-slate-200">
                            {{ $c->alamat_lengkap ?? '-' }}<br>
                            @if($c->kecamatan || $c->kabupaten || $c->provinsi)
                                <span class="text-xs text-slate-400">
                                    {{ $c->kecamatan ?? '' }}{{ $c->kecamatan ? ', ' : '' }}
                                    {{ $c->kabupaten ?? '' }}{{ $c->kabupaten ? ', ' : '' }}
                                    {{ $c->provinsi ?? '' }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3 align-top">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-medium
                                    {{ $verified ? 'bg-emerald-600/90 text-emerald-50' : 'bg-amber-500/90 text-slate-950' }}">
                                    {{ $verified ? 'Disetujui' : 'Belum Disetujui' }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    @if($verified && $c->verified_at)
                                        Disetujui: {{ $c->verified_at->format('d M Y H:i') }}
                                    @else
                                        Menunggu persetujuan admin.
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="p-3 align-top text-center">
                            <div class="flex items-center justify-center">
                                <div class="relative inline-block text-left" x-data="{ open:false }">
                                    <button @click="open = !open"
                                            type="button"
                                            class="rounded-md border border-slate-700 bg-slate-800 p-2 text-white text-sm transition duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="5" r="1"/>
                                            <circle cx="12" cy="12" r="1"/>
                                            <circle cx="12" cy="19" r="1"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         @click.away="open = false"
                                         @keydown.escape.window="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute right-0 mt-2 z-40 w-64 rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800">
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition"
                                                    @click="
                                                        open = false;
                                                        window.dispatchEvent(new CustomEvent('company-detail', {
                                                            detail: {
                                                                url: '{{ route('admin.company.show', $c) }}',
                                                                name: '{{ $c->nama_perusahaan }}',
                                                                email: '{{ $user->email }}'
                                                            }
                                                        }));
                                                    ">
                                                <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">
                                                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z\"/>
                                                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\"/>
                                                </svg>
                                                Detail
                                            </button>

                                            @if(!$verified)
                                                <button type="button"
                                                        class="w-full text-left px-4 py-2 text-sm text-emerald-300 hover:bg-emerald-700/20 flex items-center gap-2 transition"
                                                        onclick="openCompanyApproveModal(this); open = false;"
                                                        data-company-id="{{ $c->id }}"
                                                        data-company-name="{{ $c->nama_perusahaan }}">
                                                    <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">
                                                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 13l4 4L19 7\" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="w-full text-left px-4 py-2 text-sm text-amber-300 hover:bg-amber-700/20 flex items-center gap-2 transition"
                                                        onclick="openCompanyUnapproveModal(this); open = false;"
                                                        data-company-id="{{ $c->id }}"
                                                        data-company-name="{{ $c->nama_perusahaan }}">
                                                    <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">
                                                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M18 12H6\" />
                                                    </svg>
                                                    Batal Setuju
                                                </button>
                                            @endif

                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm {{ $isActive ? 'text-amber-300 hover:bg-amber-700/20' : 'text-emerald-300 hover:bg-emerald-700/20' }} flex items-center gap-2 transition"
                                                    onclick="openCompanyUserStatusModal(this); open = false;"
                                                    data-user-id="{{ $user->id }}"
                                                    data-company-name="{{ $c->nama_perusahaan }}"
                                                    data-user-email="{{ $user->email }}"
                                                    data-user-active="{{ $isActive ? '1' : '0' }}">
                                                <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">
                                                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6v12m6-6H6\" />
                                                </svg>
                                                {{ $isActive ? 'Nonaktifkan User' : 'Aktifkan User' }}
                                            </button>

                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-rose-300 hover:bg-rose-700/20 flex items-center gap-2 transition"
                                                    onclick="openCompanyDeleteModal(this); open = false;"
                                                    data-user-id="{{ $user->id }}"
                                                    data-company-name="{{ $c->nama_perusahaan }}"
                                                    data-user-email="{{ $user->email }}">
                                                <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">
                                                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"
                                                          d=\"M6 7h12M9 7V4h6v3m-7 4v7m4-7v7m4-7v7M5 7l1 13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-13\"/>
                                                </svg>
                                                Hapus User
                                            </button>
                                        </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">
                            Belum ada perusahaan terdaftar.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $companies->links() }}
        </div>
    </div>
    
    {{-- Modal detail perusahaan --}}
    <div x-data="{ open:false, html:'', loading:false }"
         @company-detail.window="
            open = true; loading = true; html = '';
            fetch($event.detail.url, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(r => r.text())
                .then(t => { html = t; })
                .catch(() => { html = '<div class=\'p-6 text-red-300\'>Gagal memuat detail perusahaan.</div>'; })
                .finally(() => { loading = false; });
         ">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
             @keydown.escape.window="open = false">
            <div @click.outside="open = false"
                 class="bg-slate-900 w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden border border-slate-800">
                <div class="flex items-center justify-between px-6 py-3 border-b border-slate-800 sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Detail Perusahaan
                    </h3>
                    <button class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700" @click="open = false">Tutup</button>
                </div>
                <div class="max-h-[80vh] overflow-y-auto">
                    <template x-if="loading">
                        <div class="p-6 text-slate-300">Memuat...</div>
                    </template>
                    <div class="p-6" x-html="html"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi verifikasi --}}
    <x-modal name="confirm-company-verify" :show="false" maxWidth="md" animation="slide-up">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-100" id="companyVerifyTitle">Konfirmasi</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyVerifySubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-verify'}))"
                    class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed" id="companyVerifyBody"></p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-verify'}))"
                        class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmCompanyVerifyBtn"
                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 transition text-sm font-semibold text-white">
                    Lanjutkan
                </button>
            </div>
        </div>
    </x-modal>

    {{-- Modal konfirmasi status akun --}}
    <x-modal name="confirm-company-user-status" :show="false" maxWidth="md" animation="slide-up">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-100" id="companyUserStatusTitle">Konfirmasi</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyUserStatusSubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-user-status'}))"
                    class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed" id="companyUserStatusBody"></p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-user-status'}))"
                        class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmCompanyUserStatusBtn"
                        class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 transition text-sm font-semibold text-white">
                    Lanjutkan
                </button>
            </div>
        </div>
    </x-modal>

    {{-- Modal konfirmasi hapus --}}
    <x-modal name="confirm-company-delete" :show="false" maxWidth="md" animation="slide-up">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-100">Hapus Akun Perusahaan</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyDeleteSubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-delete'}))"
                    class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed">
                Hapus akun perusahaan ini beserta seluruh data terkait? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-delete'}))"
                        class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmCompanyDeleteBtn"
                        class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 transition text-sm font-semibold text-white">
                    Hapus
                </button>
            </div>
        </div>
    </x-modal>

    @once
        @push('scripts')
            <script>
                window.dropdownMenu = window.dropdownMenu || function () {
                    return {
                        open: false,
                        dropUp: false,
                        style: '',
                        width: 256,
                        init() {
                            window.addEventListener('close-dropdowns', () => { this.open = false; });
                        },
                        toggle(e) {
                            this.open = !this.open;
                            if (this.open) {
                                const rect = e.currentTarget.getBoundingClientRect();
                                const spaceBelow = window.innerHeight - rect.bottom;
                                this.dropUp = spaceBelow < 260;
                                let left = rect.right - this.width;
                                left = Math.max(8, Math.min(left, window.innerWidth - this.width - 8));
                                let top = this.dropUp ? rect.top - 8 : rect.bottom + 8;
                                this.style = `left:${left}px;top:${top}px`;
                            }
                        },
                        close() { this.open = false; }
                    }
                };

                let currentCompanyId = null;
                let currentUserId = null;

                window.openCompanyApproveModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-verify' }));

                    currentCompanyId = button.getAttribute('data-company-id');
                    const name = button.getAttribute('data-company-name') || '';

                    document.getElementById('companyVerifyTitle').textContent = 'Setujui Perusahaan';
                    document.getElementById('companyVerifyBody').textContent =
                        'Setujui profil perusahaan ini? Status verifikasi akan menjadi Disetujui.';
                    document.getElementById('companyVerifySubtitle').textContent = name;

                    const btn = document.getElementById('confirmCompanyVerifyBtn');
                    if (btn) {
                        btn.textContent = 'Setujui';
                        btn.classList.remove('bg-amber-600','hover:bg-amber-700');
                        btn.classList.add('bg-emerald-600','hover:bg-emerald-700');
                        btn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).approve(currentCompanyId);
                            }
                            window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-verify'}));
                        };
                    }
                };

                window.openCompanyUnapproveModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-verify' }));

                    currentCompanyId = button.getAttribute('data-company-id');
                    const name = button.getAttribute('data-company-name') || '';

                    document.getElementById('companyVerifyTitle').textContent = 'Batalkan Persetujuan';
                    document.getElementById('companyVerifyBody').textContent =
                        'Batalkan persetujuan perusahaan ini? Status verifikasi akan dikembalikan menjadi pending.';
                    document.getElementById('companyVerifySubtitle').textContent = name;

                    const btn = document.getElementById('confirmCompanyVerifyBtn');
                    if (btn) {
                        btn.textContent = 'Batal Setuju';
                        btn.classList.remove('bg-emerald-600','hover:bg-emerald-700');
                        btn.classList.add('bg-amber-600','hover:bg-amber-700');
                        btn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).unapprove(currentCompanyId);
                            }
                            window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-verify'}));
                        };
                    }
                };

                window.openCompanyUserStatusModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-user-status' }));

                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-company-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const isActive = button.getAttribute('data-user-active') === '1';

                    document.getElementById('companyUserStatusTitle').textContent = isActive
                        ? 'Nonaktifkan Akun Perusahaan'
                        : 'Aktifkan Akun Perusahaan';
                    document.getElementById('companyUserStatusBody').textContent = isActive
                        ? 'Nonaktifkan akun perusahaan ini? Mereka tidak dapat login sampai diaktifkan kembali.'
                        : 'Aktifkan kembali akun perusahaan ini? Mereka akan dapat login kembali.';
                    document.getElementById('companyUserStatusSubtitle').textContent =
                        email ? `${name} · ${email}` : name;

                    const btn = document.getElementById('confirmCompanyUserStatusBtn');
                    if (btn) {
                        btn.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                        btn.classList.remove('bg-emerald-600','hover:bg-emerald-700','bg-amber-600','hover:bg-amber-700');
                        btn.classList.add(isActive ? 'bg-amber-600','hover:bg-amber-700' : 'bg-emerald-600','hover:bg-emerald-700');
                        btn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).toggleUserStatus(currentUserId);
                            }
                            window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-user-status'}));
                        };
                    }
                };

                window.openCompanyDeleteModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-delete' }));

                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-company-name') || '';
                    const email = button.getAttribute('data-user-email') || '';

                    document.getElementById('companyDeleteSubtitle').textContent =
                        email ? `${name} · ${email}` : name;

                    const btn = document.getElementById('confirmCompanyDeleteBtn');
                    if (btn) {
                        btn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deleteUser(currentUserId);
                            }
                            window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-delete'}));
                        };
                    }
                };
            </script>
        @endpush
    @endonce
</div>
