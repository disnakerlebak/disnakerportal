<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col gap-4">
    @php
        // Nilai default untuk placeholder; akan di-overwrite oleh Alpine (entangle)
        $selectedCount = count($selected ?? []);
        $toggleToActivate = ($allSelectedInactive ?? false) && $selectedCount > 0;
        $toggleLabel = $toggleToActivate ? 'Aktif' : 'Non Aktif';
    @endphp

    {{-- Filter bar --}}
    <form wire:submit.prevent="applyFilters"
          class="relative z-30 flex flex-wrap items-center gap-3"
          @keydown.enter.prevent="$wire.applyFilters()">

        <input type="text"
               wire:model.defer="q"
               placeholder="Cari nama atau NIK..."
               class="w-72 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />

        <select wire:model.defer="profileStatus"
                class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status Profil</option>
            <option value="complete">Lengkap</option>
            <option value="incomplete">Belum Lengkap</option>
        </select>

        <select wire:model.defer="ak1Status"
                class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status AK1</option>
            <option value="never">Belum Pernah Mengajukan</option>
            <option value="pending">Menunggu Verifikasi</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
            <option value="expired">Kadaluarsa</option>
        </select>

        <button type="submit"
                class="px-4 py-1.5 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
            Terapkan
        </button>

        <button type="button"
                wire:click="clearFilters"
                class="px-3 py-1.5 rounded bg-slate-700 hover:bg-slate-600 text-sm">
            Reset
        </button>

        <div class="flex-1"></div>

        {{-- Tombol aksi massal (nonaktif/aktif & hapus + modal konfirmasi) --}}
        <div class="relative flex-shrink-0 self-start sm:self-auto"
             x-data="{
                open:false,
                selected: @entangle('selected'),
                get count(){ return (this.selected || []).length; },
                openConfirm(action){
                    if(this.count === 0){
                        return window.dispatchEvent(new CustomEvent('jobseeker-admin:open', {
                            detail:{
                                id:'jobseeker-admin:bulk-confirm',
                                title:'Tidak ada user dipilih',
                                message:'Pilih minimal satu user.',
                                action:null,
                            }
                        }));
                    }
                    let title = '';
                    let message = '';
                    if(action === 'toggle'){
                        return;
                    } else if(action === 'activate'){
                        title = 'Aktifkan Akun';
                        message = `Apakah Anda yakin mengaktifkan ${this.count} user ini?`;
                        window.dispatchEvent(new CustomEvent('jobseeker-admin:open', {
                            detail: { id: 'jobseeker-admin:bulk-confirm', title, message, action: 'activate' }
                        }));
                    } else if(action === 'deactivate'){
                        title = 'Nonaktifkan Akun';
                        message = `Apakah Anda yakin menonaktifkan ${this.count} user ini?`;
                        window.dispatchEvent(new CustomEvent('jobseeker-admin:open', {
                            detail: { id: 'jobseeker-admin:bulk-confirm', title, message, action: 'deactivate' }
                        }));
                    } else if(action === 'delete'){
                        title = 'Hapus Akun';
                        message = `Apakah Anda yakin menghapus ${this.count} user ini?`;
                        window.dispatchEvent(new CustomEvent('jobseeker-admin:open', {
                            detail: { id: 'jobseeker-admin:bulk-confirm', title, message, action: 'delete' }
                        }));
                    }
                    this.open = false;
                }
            }"
             @click.stop>
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
                 class="absolute left-0 sm:left-auto sm:right-0 top-full mt-2 w-44 rounded-xl border border-slate-700 bg-slate-800 shadow-lg z-[200] origin-top-left sm:origin-top-right">
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-emerald-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('activate')"
                        :disabled="count === 0">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Aktifkan Akun</span>
                </button>
                <button type="button"
                        class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-amber-200 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="openConfirm('deactivate')"
                        :disabled="count === 0">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>Nonaktif Akun</span>
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
    </form>


    {{-- Tabel utama --}}
    <div class="relative z-10 flex-1 min-h-0 flex flex-col rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
        {{-- overlay loading --}}
        <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-slate-950/30 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-900/70 border border-slate-700 shadow text-indigo-200">
                <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm">Memuat data…</span>
            </div>
        </div>

        <div class="overflow-x-auto overflow-y-auto relative z-0">
            <table class="min-w-full text-sm text-slate-200">
                <thead class="bg-slate-800 text-slate-200 sticky top-0 z-30 shadow-lg">
                <tr>
                    <th class="p-3 w-10">
                        <input type="checkbox"
                               wire:model="selectAll"
                               class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500">
                    </th>
                    <th class="p-3 text-left">Nama Lengkap</th>
                    <th class="p-3 text-left">NIK</th>
                    <th class="p-3 text-left">Status Pengguna</th>
                    <th class="p-3 text-left">Status Profil</th>
                    <th class="p-3 text-left">Status AK1</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                @forelse($users as $u)
                    @php
                        $p   = $u->jobseekerProfile;
                        $app = $u->latestCardApplication;

                        // Status pengguna (badge seperti halaman admin)
                        $isActive = ($u->status ?? 'active') === 'active';
                        $userStatusLabel = $isActive ? 'Active' : 'Inactive';
                        $userStatusClass = $isActive
                            ? 'bg-green-700/30 text-green-200 border border-green-600/40'
                            : 'bg-red-700/30 text-red-200 border border-red-600/40';

                        // Status profil
                        $hasBiodata   = $p && $p->nama_lengkap && $p->nik && $p->tanggal_lahir;
                        $hasEducation = ($p->educations_count ?? 0) > 0;
                        $profilLengkap = $hasBiodata && $hasEducation;
                        $profilLabel   = $profilLengkap ? 'Lengkap' : 'Belum Lengkap';
                        $profilClass   = $profilLengkap
                            ? 'bg-emerald-600/90 text-emerald-50'
                            : 'bg-amber-500/90 text-slate-950';

                        // Status AK1 (dot + label sederhana)
                        $ak1Label = 'Belum Pernah Mengajukan';
                        $ak1Dot = 'bg-slate-500';
                        if ($app) {
                            switch ($app->status) {
                                case 'Menunggu Verifikasi':
                                    $ak1Label = 'Menunggu Verifikasi';
                                    $ak1Dot = 'bg-amber-400';
                                    break;
                                case 'Disetujui':
                                    $ak1Label = 'Disetujui';
                                    $ak1Dot = 'bg-emerald-500';
                                    break;
                                case 'Ditolak':
                                    $ak1Label = 'Ditolak';
                                    $ak1Dot = 'bg-rose-500';
                                    break;
                                case 'Kadaluarsa':
                                    $ak1Label = 'Kadaluarsa';
                                    $ak1Dot = 'bg-slate-500';
                                    break;
                                default:
                                    $ak1Label = strtoupper($app->status);
                                    $ak1Dot = 'bg-slate-500';
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="p-3 w-10">
                            <input type="checkbox"
                                   value="{{ $u->id }}"
                                   wire:model="selected"
                                   class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500">
                        </td>
                        <td class="p-3">
                            <div class="font-medium">{{ $p->nama_lengkap ?? $u->name ?? '-' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $u->email }}</div>
                        </td>
                        <td class="p-3">{{ $p->nik ?? '-' }}</td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $userStatusClass }}">
                                {{ $userStatusLabel }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $profilClass }}">
                                {{ $profilLabel }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="inline-flex items-center gap-2 text-sm font-semibold">
                                <span class="inline-block w-3 h-3 rounded-full {{ $ak1Dot }}"></span>
                                <span>{{ $ak1Label }}</span>
                            </div>
                            @if($app?->nomor_ak1)
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    No: {{ $app->nomor_ak1 }}
                                </div>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center">
                                <x-dropdown :id="'jobseeker-actions-'.$u->id">
                                    <x-dropdown-item data-trigger="dropdown-modal"
                                                     class="text-blue-300 hover:text-blue-100"
                                                     onclick="
                                                        window.dispatchEvent(new CustomEvent('jobseeker-admin:open', {
                                                          detail: {
                                                            id: 'jobseeker-admin:detail',
                                                            url: '{{ route('admin.pencaker.detail', $u->id) }}',
                                                            ak1: '{{ $app?->nomor_ak1 ?? '' }}'
                                                          }
                                                        }));
                                                     ">
                                        Detail
                                    </x-dropdown-item>

                                    <x-dropdown-item data-trigger="dropdown-modal"
                                                    class="text-purple-300 hover:text-purple-100"
                                                    onclick="
                                                       window.dispatchEvent(
                                                            new CustomEvent('timeline:open', {
                                                                detail: {
                                                                    id: 'pencaker-timeline',
                                                                    url: '{{ route('admin.manage.history', $u->id) }}',
                                                                    title: 'Riwayat Pencaker',
                                                                    name: '{{ $p->nama_lengkap ?? $u->name ?? '-' }}',
                                                                    email: '{{ $u->email }}',
                                                                    nomor_ak1: '{{ $app?->nomor_ak1 ?? '' }}'
                                                                }
                                                            })
                                                        );
                                                     ">
                                        Riwayat
                                    </x-dropdown-item>

                                    @if($isActive)
                                        <x-dropdown-item data-trigger="dropdown-modal"
                                                         class="text-amber-300 hover:text-amber-100"
                                                         onclick="openDeactivateModal(this)"
                                                         data-user-id="{{ $u->id }}"
                                                         data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                                         data-user-email="{{ $u->email }}">
                                            Nonaktifkan Akun
                                        </x-dropdown-item>
                                    @else
                                        <x-dropdown-item data-trigger="dropdown-modal"
                                                         class="text-emerald-300 hover:text-emerald-100"
                                                         onclick="openActivateModal(this)"
                                                         data-user-id="{{ $u->id }}"
                                                         data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                                         data-user-email="{{ $u->email }}">
                                            Aktifkan Akun
                                        </x-dropdown-item>
                                    @endif

                                    <x-dropdown-item data-trigger="dropdown-modal"
                                                     class="text-sky-300 hover:text-sky-100"
                                                     onclick="openResetModal(this)"
                                                     data-user-id="{{ $u->id }}"
                                                     data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                                     data-user-email="{{ $u->email }}">
                                        Reset Profil
                                    </x-dropdown-item>

                                    <x-dropdown-item data-trigger="dropdown-modal"
                                                     class="text-rose-300 hover:text-rose-100"
                                                     onclick="openDeleteModal(this)"
                                                     data-user-id="{{ $u->id }}"
                                                     data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                                     data-user-email="{{ $u->email }}">
                                        Hapus Pencaker
                                    </x-dropdown-item>
                                </x-dropdown>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">
                            Belum ada data pencaker.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal konfirmasi aksi massal --}}
    <div id="jobseeker-admin:bulk-confirm"
         class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop"
         x-data="{
            confirmTitle:'',
            confirmMessage:'',
            confirmAction:null,
            handleOpen(detail){
                if((detail?.id || detail) !== 'jobseeker-admin:bulk-confirm') return;
                this.confirmTitle = detail?.title || '';
                this.confirmMessage = detail?.message || '';
                this.confirmAction = detail?.action || null;
            },
            close(){
                window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail: { id: 'jobseeker-admin:bulk-confirm' } }));
            },
            proceed(){
                if(this.confirmAction === 'delete'){
                    $wire.bulkDelete();
                } else if(this.confirmAction === 'activate'){
                    $wire.bulkActivate();
                } else if(this.confirmAction === 'deactivate'){
                    $wire.bulkDeactivate();
                }
                this.close();
            }
         }"
         @jobseeker-admin:open.window="handleOpen($event.detail)">
        <div class="modal-panel w-full max-w-md p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-100" x-text="confirmTitle"></h3>
                    <p class="text-sm text-gray-300 mt-1" x-text="confirmMessage"></p>
                </div>
                <button class="modal-close" @click="close()">✕</button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-100 hover:bg-gray-700"
                        @click="close()">
                    Batal
                </button>
                <button type="button"
                        class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700"
                        @click="proceed()">
                    Ya, lanjutkan
                </button>
            </div>
        </div>
    </div>
    
    {{-- MODAL DETAIL Pencaker --}}
<div id="jobseeker-admin:detail"
     class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop">

    <div x-data="{
            html:'', loading:false, ak1:'',
            load(detail){
                if(!detail?.url) { this.html = '<div class=\'p-6 text-red-300\'>URL detail tidak valid</div>'; return; }
                this.loading = true;
                this.html = '';
                this.ak1 = detail.ak1 || '';

                fetch(detail.url, { headers:{ 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(r => r.text())
                    .then(t => { this.html = t; })
                    .catch(()=>{ this.html = '<div class=\'p-6 text-red-300\'>Gagal memuat detail.</div>'; })
                    .finally(()=>{ this.loading=false; });
            },
            close(){
                window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail:{ id:'jobseeker-admin:detail' }}));
            }
        }"
        @jobseeker-admin:open.window="if($event.detail.id === 'jobseeker-admin:detail'){ load($event.detail); }"
        class="modal-panel w-full max-w-5xl shadow-xl overflow-hidden max-h-[85vh] flex flex-col">

        <div class="modal-panel-header flex items-center justify-between px-6 py-3 sticky top-0 z-10">
            <h3 class="text-lg font-semibold text-gray-100">Detail Pencaker</h3>
            <button @click="close()" class="px-3 py-1 rounded border border-gray-700 bg-gray-800 hover:bg-gray-700">Tutup</button>
        </div>

        <div class="max-h-[80vh] overflow-y-auto p-6">
            <template x-if="loading">
                <div class="p-6 text-gray-300">Memuat detail...</div>
            </template>
            <div x-html="html"></div>
        </div>
    </div>
</div>

    {{-- Modal Konfirmasi Nonaktifkan / Aktifkan Akun --}}
    <x-modal id="jobseeker-admin:confirm-deactivate" size="md" title="Nonaktifkan Akun Pencaker">
        <div class="px-6 py-5 space-y-4 rounded-2xl">
            <div>
                <p class="text-sm text-gray-400" id="deactivateModalSubtitle"></p>
            </div>
            <p id="deactivateModalBody" class="text-sm text-gray-300 leading-relaxed">
                Nonaktifkan akun pencaker ini? Mereka tidak dapat login sampai diaktifkan kembali.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeDeactivateModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmDeactivateBtn" class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 transition text-sm font-semibold text-white">Nonaktifkan</button>
            </div>
        </div>
    </x-modal>

    {{-- Modal Konfirmasi Reset Profil --}}
    <x-modal id="jobseeker-admin:confirm-reset" size="md" title="Reset Profil Pencaker">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="resetModalSubtitle"></p>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">
                Reset seluruh profil & riwayat (pendidikan, pelatihan, pengalaman) pencaker ini? AK1 tetap dipertahankan.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmResetBtn" class="px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 transition text-sm font-semibold text-white">Reset Profil</button>
            </div>
        </div>
    </x-modal>

    {{-- Modal Konfirmasi Hapus Pencaker --}}
    <x-modal id="jobseeker-admin:confirm-delete" size="md" title="Hapus Pencaker">
        <div class="px-6 py-5 space-y-4">
            <div>
                <p class="text-sm text-gray-400" id="deleteModalSubtitle"></p>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">
                Hapus pencaker ini <span class="font-semibold text-rose-300">BESERTA seluruh data dan riwayat AK1</span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 transition text-sm font-semibold text-white">Hapus Pencaker</button>
            </div>
        </div>
    </x-modal>

    {{-- Script dropdown (re-use yang sudah ada di tabel pencaker disetujui) --}}
    @once
        @push('scripts')
            <script>
                (() => {
                    const toggleModal = (id, show) => {
                        if (!id) return;
                        const modal = document.getElementById(id);
                        if (!modal) return;
                        modal.classList.toggle('hidden', !show);
                    };

                    const resolveId = (detail) => {
                        if (typeof detail === 'string') return detail;
                        return detail?.id;
                    };

                    window.addEventListener('jobseeker-admin:open', (event) => {
                        const id = resolveId(event.detail);
                        if (!id) return;
                        const modal = document.getElementById(id);
                        toggleModal(id, true);
                        if (modal?.__x?.$data?.load) {
                            modal.__x.$data.load(event.detail || {});
                        }
                    });

                    window.addEventListener('jobseeker-admin:close', (event) => {
                        const id = resolveId(event.detail);
                        if (!id) return;
                        toggleModal(id, false);
                    });

                    // ESC close (hanya untuk modal jobseeker-admin)
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            // daftar modal yang boleh di-close
                            const modals = [
                                'jobseeker-admin:detail',
                                'jobseeker-admin:confirm-deactivate',
                                'jobseeker-admin:confirm-reset',
                                'jobseeker-admin:confirm-delete'
                            ];

                    modals.forEach(id => {
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail: { id } }));
                        });
                        }
                    });

                })();

                window.dropdownMenu = function () {
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
                }

                // Variabel global untuk menyimpan data user yang akan dioperasikan
                let currentUserId = null;

                // Modal Nonaktifkan
                window.openDeactivateModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deactivateModalSubtitle');
                    const title = document.getElementById('deactivateModalTitle');
                    const body = document.getElementById('deactivateModalBody');
                    const confirmBtn = document.getElementById('confirmDeactivateBtn');

                    if (title) title.textContent = 'Nonaktifkan Akun Pencaker';
                    if (body) body.textContent = 'Nonaktifkan akun pencaker ini? Mereka tidak dapat login sampai diaktifkan kembali.';
                    if (subtitle) subtitle.textContent = email ? `${name} · ${email}` : name;

                    if (confirmBtn) {
                        confirmBtn.textContent = 'Nonaktifkan';
                        confirmBtn.classList.remove('bg-emerald-600','hover:bg-emerald-700');
                        confirmBtn.classList.add('bg-amber-600','hover:bg-amber-700');
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deactivateUser(currentUserId);
                            }
                            closeDeactivateModal();
                        };
                    }
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:open', { detail: { id: 'jobseeker-admin:confirm-deactivate' } }));
                };

                window.closeDeactivateModal = function () {
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail: { id: 'jobseeker-admin:confirm-deactivate' } }));
                };

                // Modal Aktifkan
                window.openActivateModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deactivateModalSubtitle');
                    const title = document.getElementById('deactivateModalTitle');
                    const body = document.getElementById('deactivateModalBody');
                    const confirmBtn = document.getElementById('confirmDeactivateBtn');

                    if (title) title.textContent = 'Aktifkan Akun Pencaker';
                    if (body) body.textContent = 'Aktifkan kembali akun pencaker ini? Mereka akan dapat login dan mengakses layanan kembali.';
                    if (subtitle) subtitle.textContent = email ? `${name} · ${email}` : name;
                    if (confirmBtn) {
                        confirmBtn.textContent = 'Aktifkan';
                        confirmBtn.classList.remove('bg-amber-600','hover:bg-amber-700');
                        confirmBtn.classList.add('bg-emerald-600','hover:bg-emerald-700');
                        confirmBtn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).activateUser(currentUserId);
                            }
                            closeDeactivateModal();
                        };
                    }
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:open', { detail: { id: 'jobseeker-admin:confirm-deactivate' } }));
                };

                // Modal Reset
                window.openResetModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('resetModalSubtitle');
                    if (subtitle) {
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    // Set event listener untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmResetBtn');
                    if (confirmBtn) {
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).resetProfile(currentUserId);
                            }
                            closeResetModal();
                        };
                    }
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:open', { detail: { id: 'jobseeker-admin:confirm-reset' } }));
                };

                window.closeResetModal = function () {
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail: { id: 'jobseeker-admin:confirm-reset' } }));
                };

                // Modal Delete
                window.openDeleteModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deleteModalSubtitle');
                    if (subtitle) {
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    // Set event listener untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmDeleteBtn');
                    if (confirmBtn) {
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deleteUser(currentUserId);
                            }
                            closeDeleteModal();
                        };
                    }
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:open', { detail: { id: 'jobseeker-admin:confirm-delete' } }));
                };

                window.closeDeleteModal = function () {
                    window.dispatchEvent(new CustomEvent('jobseeker-admin:close', { detail: { id: 'jobseeker-admin:confirm-delete' } }));
                };
            </script>
        @endpush
    @endonce

    {{-- Modal Timeline Global --}}
    <x-timeline.modal id="pencaker-timeline" />
</div>
