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

        {{-- Actions bulk (mirip pencaker) --}}
        <div class="ml-auto relative" x-data="{
            open:false,
            selected: @entangle('selected'),
            get count(){ return (this.selected || []).length; },
            openConfirm(action){
                if(this.count === 0){
                    return window.dispatchEvent(new CustomEvent('open-bulk-confirm', {
                        detail:{ title:'Tidak ada perusahaan dipilih', message:'Pilih minimal satu perusahaan.', action:null }
                    }));
                }
                let title='', message='';
                if(action === 'activate-user'){
                    title = 'Aktifkan User';
                    message = `Aktifkan ${this.count} user perusahaan?`;
                } else if(action === 'deactivate-user'){
                    title = 'Nonaktifkan User';
                    message = `Nonaktifkan ${this.count} user perusahaan?`;
                } else if(action === 'delete'){
                    title = 'Hapus Perusahaan';
                    message = `Hapus ${this.count} perusahaan terpilih?`;
                }
                window.dispatchEvent(new CustomEvent('open-bulk-confirm', { detail:{ title, message, action } }));
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
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                    {{ $verified ? 'bpill-emerald' : 'bpill-amber' }}">
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
                                                     onclick="openCompanyDetail('{{ route('admin.company.show', $c) }}')">
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
                                                         onclick="openCompanyRejectModal(this)">
                                            Tolak
                                        </x-dropdown-item>
                                    @else
                                        <x-dropdown-item class="text-orange-300 hover:text-orange-100"
                                                         data-company-id="{{ $c->id }}"
                                                         data-company-name="{{ $c->nama_perusahaan }}"
                                                         onclick="openCompanyUserStatus('{{ $c->id }}', '{{ $c->nama_perusahaan }}', '{{ $isActive ? 'inactive' : 'active' }}')">
                                            {{ $isActive ? 'Nonaktifkan User' : 'Aktifkan User' }}
                                        </x-dropdown-item>
                                    @endif
                                    <x-dropdown-item class="text-cyan-300 hover:text-cyan-100"
                                                     onclick="openCompanyLog({{ $c->id }})">
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
    <x-modal name="confirm-company-verify" :show="false" maxWidth="md" animation="slide-up" :hideHeader="true">
        <div class="modal-panel-header flex items-center justify-between px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-100" id="companyVerifyTitle">Konfirmasi</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyVerifySubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-verify'}))"
                    class="modal-close">✕</button>
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
    <x-modal name="confirm-company-user-status" :show="false" maxWidth="md" animation="slide-up" :hideHeader="true">
        <div class="modal-panel-header flex items-center justify-between px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-100" id="companyUserStatusTitle">Konfirmasi</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyUserStatusSubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-user-status'}))"
                    class="modal-close">✕</button>
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
    <x-modal name="confirm-company-delete" :show="false" maxWidth="md" animation="slide-up" :hideHeader="true">
        <div class="modal-panel-header flex items-center justify-between px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-100">Hapus Akun Perusahaan</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyDeleteSubtitle"></p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-company-delete'}))"
                    class="modal-close">✕</button>
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

    {{-- Modal log --}}
    <x-modal name="log-company" :show="false" maxWidth="3xl" animation="zoom">
        <div class="flex items-start justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold" id="companyLogTitle">Riwayat Perusahaan</h3>
                <p class="text-sm text-gray-400 mt-1" id="companyLogSubtitle"></p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'log-company'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div id="companyLogBody" class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-4"></div>
    </x-modal>

    {{-- Modal approve --}}
    <x-modal name="modal-company-approve" :show="false" maxWidth="md" animation="slide-up">
        <div class="px-6 py-5 space-y-4">
            <h3 class="text-lg font-semibold text-gray-100" id="companyApproveTitle">Setujui Perusahaan</h3>
            <p class="text-sm text-gray-300" id="companyApproveSubtitle"></p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-approve'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <form method="POST" id="companyApproveForm">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 transition text-sm font-semibold text-white">Setujui</button>
                </form>
            </div>
        </div>
    </x-modal>

    {{-- Modal reject --}}
    <x-modal name="modal-company-reject" :show="false" maxWidth="md" animation="slide-up">
        <div class="px-6 py-5 space-y-4">
            <h3 class="text-lg font-semibold text-gray-100" id="companyRejectTitle">Tolak Perusahaan</h3>
            <p class="text-sm text-gray-300" id="companyRejectSubtitle"></p>
            <form method="POST" id="companyRejectForm" class="space-y-3">
                @csrf
                <label class="block text-sm text-gray-300">Alasan Penolakan</label>
                <textarea name="reason" rows="3" class="w-full rounded-lg bg-slate-800 border border-slate-700 text-gray-100"></textarea>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-reject'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 transition text-sm font-semibold text-white">Tolak</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Modal unapprove --}}
    <x-modal name="modal-company-unapprove" :show="false" maxWidth="md" animation="slide-up">
        <div class="px-6 py-5 space-y-4">
            <h3 class="text-lg font-semibold text-gray-100" id="companyUnapproveTitle">Batalkan Persetujuan</h3>
            <p class="text-sm text-gray-300" id="companyUnapproveSubtitle"></p>
            <form method="POST" id="companyUnapproveForm" class="space-y-3">
                @csrf
                <label class="block text-sm text-gray-300">Catatan (opsional)</label>
                <textarea name="notes" rows="3" class="w-full rounded-lg bg-slate-800 border border-slate-700 text-gray-100"></textarea>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'modal-company-unapprove'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 transition text-sm font-semibold text-white">Batalkan</button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
