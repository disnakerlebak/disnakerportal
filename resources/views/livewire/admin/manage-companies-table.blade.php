<div class="max-w-7xl mx-auto flex flex-col gap-4">
    <!-- <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-100">Kelola Perusahaan</h1>
        <p class="text-sm text-slate-300">Daftar perusahaan yang terdaftar di Disnaker Portal.</p>
    </div> -->
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

        <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-company-admin' }))"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-3 py-2 text-sm font-semibold text-white shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Admin Perusahaan
        </button>

        {{-- Actions bulk (mirip pencaker) --}}
        <div class="ml-auto relative" x-data="{
            open:false,
            selected: @entangle('selected'),
            get count(){ return (this.selected || []).length; },
            openConfirm(action){
                if(this.count === 0){ return; }
                if(action === 'approve'){
                    $wire.bulkApprove();
                } else if(action === 'activate-user'){
                    $wire.bulkActivateUsers();
                } else if(action === 'deactivate-user'){
                    $wire.bulkDeactivateUsers();
                } else if(action === 'delete'){
                    $wire.bulkDelete();
                }
                this.open = false;
            }
        }" @click.stop>
            <button type="button"
                    @click="open = !open"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-700 bg-slate-800/70 px-3 py-1.5 text-sm font-semibold text-slate-100 hover:bg-slate-700">
                Actions
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open"
                 @click.outside="open=false"
                 x-transition
                 class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-700 bg-slate-800 shadow-lg z-50">
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-emerald-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('approve')"
                        :disabled="count === 0">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    Setujui Perusahaan
                </button>
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-emerald-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('activate-user')"
                        :disabled="count === 0">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    Aktifkan User
                </button>
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-amber-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('deactivate-user')"
                        :disabled="count === 0">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
                    Nonaktifkan User
                </button>
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('delete')"
                        :disabled="count === 0">
                    <svg class="w-4 h-4 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m-3 0h12" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
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
                    <th class="p-3 w-10">
                        <input type="checkbox"
                               @click="event.target.checked ? $wire.set('selected', {{ $companies->pluck('id') }}) : $wire.set('selected', [])"
                               class="rounded border-slate-700 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                    </th>
                    <th class="p-3 text-left">Nama Perusahaan</th>
                    <th class="p-3 text-left">Jenis / Bidang Usaha</th>
                    <th class="p-3 text-left">Domisili Perusahaan</th>
                    <th class="p-3 text-left">Status Verifikasi</th>
                    <th class="p-3 text-left">Status User</th>
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
                            <input type="checkbox" wire:model="selected" value="{{ $c->id }}"
                                   class="rounded border-slate-700 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                        </td>
                        <td class="p-3 align-top">
                            <div class="font-semibold text-slate-100">
                                {{ $c->nama_perusahaan ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ $user->email ?? '-' }}
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
                                <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full text-[11px] font-semibold
                                    {{ $verified ? 'bpill-emerald' : 'bpill-amber' }}">
                                    <span class="h-2 w-2 rounded-full {{ $verified ? 'bg-emerald-300' : 'bg-amber-400' }}"></span>
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
                        <td class="p-3 align-top">
                            @php
                                $userStatusLabel = $isActive ? 'Aktif' : 'Tidak Aktif';
                                $userStatusClass = $isActive
                                    ? 'bg-emerald-600/90 text-emerald-50'
                                    : 'bg-slate-600/90 text-slate-100';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $userStatusClass }}">
                                {{ $userStatusLabel }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center">
                                <x-dropdown :id="'company-actions-'.$c->id">
                                    <x-dropdown-item class="text-blue-300 hover:text-blue-100"
                                                     onclick="openCompanyDetail('{{ route('admin.company.show', $c) }}', '{{ $c->nama_perusahaan }}', '{{ $user->email }}')">
                                        Detail
                                    </x-dropdown-item>
                                    @if(!$verified)
                                        <x-dropdown-item class="text-emerald-300 hover:text-emerald-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         onclick="openCompanyApproveModal(this)">
                                            Setujui
                                        </x-dropdown-item>
                                        <x-dropdown-item class="text-rose-300 hover:text-rose-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         data-user-id="{{ $user->id }}"
                                                         onclick="openCompanyRejectModal(this)">
                                            Tolak
                                        </x-dropdown-item>
                                    @else
                                        <x-dropdown-item class="text-amber-300 hover:text-amber-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         onclick="openCompanyUnapproveModal(this)">
                                            Batalkan Verifikasi
                                        </x-dropdown-item>
                                    @endif
                                    @if($isActive)
                                        <x-dropdown-item class="text-orange-300 hover:text-orange-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         data-user-id="{{ $user->id }}"
                                                         data-next-status="inactive"
                                                         onclick="openCompanyUserStatus(this)">
                                            Nonaktifkan User
                                        </x-dropdown-item>
                                    @else
                                        <x-dropdown-item class="text-emerald-300 hover:text-emerald-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         data-user-id="{{ $user->id }}"
                                                         data-next-status="active"
                                                         onclick="openCompanyUserStatus(this)">
                                            Aktifkan User
                                        </x-dropdown-item>
                                    @endif
                                    <x-dropdown-item class="text-rose-300 hover:text-rose-100"
                                                     data-user-id="{{ $user->id }}"
                                                     onclick="openCompanyDeleteModal(this)">
                                        Hapus Akun
                                    </x-dropdown-item>
                                    <x-dropdown-item class="text-cyan-300 hover:text-cyan-100"
                                                     onclick="
                                                        window.dispatchEvent(
                                                            new CustomEvent('timeline:open', {
                                                                detail: {
                                                                    id: 'company-timeline',
                                                                    url: '{{ $user ? route('admin.manage.history', $user->id) : '' }}',
                                                                    title: 'Riwayat Perusahaan',
                                                                    name: '{{ $c->nama_perusahaan ?? '-' }}',
                                                                    email: '{{ $user->email ?? '' }}'
                                                                }
                                                            })
                                                        );
                                                     ">
                                        Riwayat
                                    </x-dropdown-item>
                                </x-dropdown>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-400">
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

    {{-- Modal tambah admin perusahaan --}}
    <x-modal id="add-company-admin" size="md" title="Tambah Admin Perusahaan">
        <form wire:submit.prevent="createCompanyAdmin" class="px-1 py-1 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">Nama Perusahaan</label>
                <input type="text" wire:model.defer="newCompanyName"
                       class="w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Masukkan nama perusahaan">
                @error('newCompanyName')
                    <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">Email</label>
                <input type="email" wire:model.defer="newCompanyEmail"
                       class="w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="nama@email.com">
                @error('newCompanyEmail')
                    <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-slate-400">Password awal akan dibuat otomatis dan ditampilkan di notifikasi.</p>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'add-company-admin'}))"
                        class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition text-sm font-semibold text-white">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

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
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center modal-backdrop"
             @keydown.escape.window="open = false">
            <div @click.outside="open = false"
                 class="modal-panel w-full max-w-5xl shadow-lg overflow-hidden">
                <div class="modal-panel-header flex items-center justify-between px-6 py-3 sticky top-0 z-10">
                    <h3 class="text-lg font-semibold text-gray-100">
                        Detail Perusahaan
                    </h3>
                    <button class="px-3 py-1 rounded border border-gray-700 bg-gray-800 hover:bg-gray-700" @click="open = false">Tutup</button>
                </div>
                <div class="max-h-[80vh] overflow-y-auto">
                    <template x-if="loading">
                        <div class="p-6 text-gray-300">Memuat...</div>
                    </template>
                    <div class="p-6" x-html="html"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi verifikasi --}}
    <x-modal id="confirm-company-verify" size="md" title="Konfirmasi Verifikasi">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyVerifySubtitle"></p>
            </div>
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
    <x-modal id="confirm-company-user-status" size="md" title="Konfirmasi Status User">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyUserStatusSubtitle"></p>
            </div>
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
    <x-modal id="confirm-company-delete" size="md" title="Hapus Akun Perusahaan">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyDeleteSubtitle"></p>
            </div>
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

    {{-- Modal approve --}}
    <x-modal id="modal-company-approve" size="md" title="Setujui Perusahaan">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyApproveSubtitle"></p>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">Setujui perusahaan ini? Pastikan data dan informasi perusahaan sudah sesuai sebelum disetujui</p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-approve'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="companyApproveBtn" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 transition text-sm font-semibold text-white">Setujui</button>
            </div>
        </div>
    </x-modal>

    {{-- Modal reject verifikasi --}}
    <x-modal id="modal-company-reject" size="md" title="Tolak Verifikasi">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyRejectSubtitle"></p>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">Tolak verifikasi perusahaan ini? Status akan dikembalikan ke pending.</p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-reject'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="companyRejectBtn" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 transition text-sm font-semibold text-white">Tolak</button>
            </div>
        </div>
    </x-modal>

    {{-- Modal batalkan verifikasi --}}
    <x-modal id="modal-company-unapprove" size="md" title="Batalkan Verifikasi">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="companyUnapproveSubtitle"></p>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">Batalkan persetujuan verifikasi perusahaan ini? Status akan dikembalikan ke pending.</p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-unapprove'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="companyUnapproveBtn" class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 transition text-sm font-semibold text-white">Batalkan</button>
            </div>
        </div>
    </x-modal>

    {{-- Modal Timeline Perusahaan --}}
    <x-timeline.modal id="company-timeline" />

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (detail) => {
            if (typeof Toastify === 'undefined') return;
            Toastify({
                text: detail?.message || 'Berhasil',
                duration: 3500,
                close: true,
                gravity: 'bottom',
                position: 'right',
                backgroundColor: detail?.type === 'error' ? '#dc2626' : '#16a34a',
                stopOnFocus: true,
            }).showToast();
        });
    });

(() => {
    let currentCompanyId = null;
    let currentUserId = null;

    const getWire = () => {
        const comp = document.querySelector('[wire\\:id]');
        return comp ? window.Livewire.find(comp.getAttribute('wire:id')) : null;
    };

    window.openCompanyDetail = function (url, name = '', email = '') {
        window.dispatchEvent(new CustomEvent('company-detail', { detail: { url, name, email } }));
    };

    window.openCompanyApproveModal = function (el) {
        currentCompanyId = el.getAttribute('data-company-id');
        const name = el.getAttribute('data-company-name') || 'Perusahaan';
        const subtitle = document.getElementById('companyApproveSubtitle');
        if (subtitle) subtitle.textContent = name;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-company-approve' }));
    };

    window.openCompanyRejectModal = function (el) {
        currentCompanyId = el.getAttribute('data-company-id');
        const name = el.getAttribute('data-company-name') || 'Perusahaan';
        const subtitle = document.getElementById('companyRejectSubtitle');
        if (subtitle) subtitle.textContent = name;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-company-reject' }));
    };

    window.openCompanyUnapproveModal = function (el) {
        currentCompanyId = el.getAttribute('data-company-id');
        const name = el.getAttribute('data-company-name') || 'Perusahaan';
        const subtitle = document.getElementById('companyUnapproveSubtitle');
        if (subtitle) subtitle.textContent = name;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modal-company-unapprove' }));
    };

    window.openCompanyUserStatus = function (el) {
        currentUserId = el.getAttribute('data-user-id');
        const name = el.getAttribute('data-company-name') || 'Perusahaan';
        const nextStatus = el.getAttribute('data-next-status') || 'inactive';
        const subtitle = document.getElementById('companyUserStatusSubtitle');
        const body = document.getElementById('companyUserStatusBody');
        if (subtitle) subtitle.textContent = name;
        if (body) body.textContent = nextStatus === 'inactive'
            ? 'Nonaktifkan user perusahaan ini? Mereka tidak dapat login sampai diaktifkan kembali.'
            : 'Aktifkan user perusahaan ini?';
        const btn = document.getElementById('confirmCompanyUserStatusBtn');
        if (btn) {
            btn.onclick = function () {
                const lw = getWire();
                if (lw && currentUserId) lw.toggleUserStatus(parseInt(currentUserId));
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-company-user-status' }));
            };
        }
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-user-status' }));
    };

    window.openCompanyDeleteModal = function (el) {
        currentUserId = el.getAttribute('data-user-id');
        const subtitle = document.getElementById('companyDeleteSubtitle');
        if (subtitle) subtitle.textContent = 'Hapus akun perusahaan ini?';
        const btn = document.getElementById('confirmCompanyDeleteBtn');
        if (btn) {
            btn.onclick = function () {
                const lw = getWire();
                if (lw && currentUserId) lw.deleteUser(parseInt(currentUserId));
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-company-delete' }));
            };
        }
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-company-delete' }));
    };

    const approveBtn = document.getElementById('companyApproveBtn');
    if (approveBtn) {
        approveBtn.onclick = function () {
            const lw = getWire();
            if (lw && currentCompanyId) {
                lw.approve(parseInt(currentCompanyId));
            }
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'modal-company-approve' }));
        };
    }
    const rejectBtn = document.getElementById('companyRejectBtn');
    if (rejectBtn) {
        rejectBtn.onclick = function () {
            const lw = getWire();
            if (lw && currentCompanyId) {
                lw.unapprove(parseInt(currentCompanyId));
            }
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'modal-company-reject' }));
        };
    }
    const unapproveBtn = document.getElementById('companyUnapproveBtn');
    if (unapproveBtn) {
        unapproveBtn.onclick = function () {
            const lw = getWire();
            if (lw && currentCompanyId) {
                lw.unapprove(parseInt(currentCompanyId));
            }
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'modal-company-unapprove' }));
        };
    }
})();
</script>
@endpush
</div>
