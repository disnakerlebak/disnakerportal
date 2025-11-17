<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col gap-4">
    <!-- <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-100">Daftar Pencaker Disetujui</h2>
    </div> -->

    <form wire:submit.prevent="apply" class="flex flex-wrap items-center gap-3" @keydown.enter.prevent="$wire.apply()">
        <input type="text"
               wire:model.defer="q"
               placeholder="Cari nama..."
               class="w-64 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />

        <label class="inline-flex items-center gap-2 text-sm text-slate-200 bg-slate-800/60 px-3 py-1.5 rounded border border-slate-700">
            <input type="checkbox" wire:model.defer="hasTraining" class="rounded border-slate-600 bg-slate-800">
            <span>Memiliki Pelatihan</span>
        </label>

        <label class="inline-flex items-center gap-2 text-sm text-slate-200 bg-slate-800/60 px-3 py-1.5 rounded border border-slate-700">
            <input type="checkbox" wire:model.defer="hasWork" class="rounded border-slate-600 bg-slate-800">
            <span>Memiliki Pengalaman</span>
        </label>

        <button type="submit" class="px-4 py-1.5 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Terapkan</button>
        <button type="button" wire:click="clearFilters" class="px-3 py-1.5 rounded bg-slate-700 hover:bg-slate-600 text-sm">Reset</button>
    </form>

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

        <div class="flex-1 min-h-0 overflow-visible">
            <div class="overflow-x-auto h-full min-h-0 pb-4">
                <div class="h-full min-h-0 overflow-visible">
                    <table class="min-w-[960px] text-sm text-slate-200">
                <thead class="bg-slate-800 text-slate-200">
                <tr>
                    <th class="p-3 text-left">Nama Lengkap</th>
                    <th class="p-3 text-left">Jenis Kelamin</th>
                    <th class="p-3 text-left">Usia</th>
                    <th class="p-3 text-left">Pendidikan</th>
                    <th class="p-3 text-left">Keahlian</th>
                    <th class="p-3 text-left whitespace-nowrap">Pengalaman Kerja</th>
                    <th class="p-3 text-left whitespace-nowrap">Status Disabilitas</th>
                    <th class="p-3 text-left">Kecamatan</th>
                    <th class="p-3 text-left whitespace-nowrap">No. AK/1</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                @forelse($users as $u)
                    @php
                        $p = $u->jobseekerProfile;
                        $app = optional($u->cardApplications->first());
                        $usia = $p?->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->age : '-';
                        $trainingCount = $p?->trainings_count ?? 0;
                        $workCount = $p?->work_experiences_count ?? 0;
                    @endphp
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="p-3">{{ $p->nama_lengkap ?? '-' }}</td>
                        <td class="p-3">{{ $p->jenis_kelamin ?? '-' }}</td>
                        <td class="p-3">{{ $usia }}</td>
                        <td class="p-3">{{ $p->pendidikan_terakhir ?? '-' }}</td>
                        <td class="p-3 whitespace-nowrap">{{ $trainingCount }} Pelatihan</td>
                        <td class="p-3 whitespace-nowrap">{{ $workCount }} Pengalaman</td>
                        <td class="p-3 whitespace-nowrap">{{ $p->status_disabilitas ?? '-' }}</td>
                        <td class="p-3">{{ $p->domisili_kecamatan ?? '-' }}</td>
                        <td class="p-3 whitespace-nowrap text-xs">{{ $app?->nomor_ak1 ?? '-' }}</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center">
                                <x-dropdown :id="'approved-jobseeker-actions-'.$u->id">
                                    <x-dropdown-item
                                        class="text-blue-300 hover:text-blue-100"
                                        onclick="window.dispatchEvent(new CustomEvent('pencaker-detail', { detail: { url: '{{ route('admin.pencaker.detail', $u->id) }}', ak1: '{{ $app?->nomor_ak1 ?? '' }}' } }));">
                                        Lihat Detail
                                    </x-dropdown-item>

                                    @if($app)
                                        <li>
                                            <a href="{{ route('admin.ak1.cetak', $app->id) }}"
                                               target="_blank"
                                               class="flex w-full items-center gap-2 px-4 py-2 rounded-md text-indigo-300 hover:text-indigo-100 hover:bg-slate-700 transition">
                                                Unduh AK1
                                            </a>
                                        </li>
                                    @endif

                                    <x-dropdown-item
                                        class="text-purple-300 hover:text-purple-100"
                                        onclick="window.openUserAk1History('{{ route('admin.ak1.userLogs', $u->id) }}','{{ $p->nama_lengkap ?? $u->name ?? 'Pencaker' }}','{{ $u->email }}');">
                                        Riwayat AK1
                                    </x-dropdown-item>

                                    @if(($u->status ?? 'active') === 'active')
                                        <x-dropdown-item
                                            class="text-amber-300 hover:text-amber-100"
                                            onclick="openDeactivateModalApproved(this)"
                                            modal="confirm-deactivate-approved"
                                            data-user-id="{{ $u->id }}"
                                            data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                            data-user-email="{{ $u->email }}">
                                            Nonaktifkan Akun
                                        </x-dropdown-item>
                                    @else
                                        <x-dropdown-item
                                            class="text-emerald-300 hover:text-emerald-100"
                                            onclick="openActivateModalApproved(this)"
                                            modal="confirm-deactivate-approved"
                                            data-user-id="{{ $u->id }}"
                                            data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                            data-user-email="{{ $u->email }}">
                                            Aktifkan Akun
                                        </x-dropdown-item>
                                    @endif

                                    <x-dropdown-item
                                        class="text-sky-300 hover:text-sky-100"
                                        onclick="openResetModalApproved(this)"
                                        modal="confirm-reset-approved"
                                        data-user-id="{{ $u->id }}"
                                        data-user-name="{{ $p->nama_lengkap ?? $u->name ?? '-' }}"
                                        data-user-email="{{ $u->email }}">
                                        Reset Profil
                                    </x-dropdown-item>

                                    <x-dropdown-item
                                        class="text-rose-300 hover:text-rose-100"
                                        onclick="openDeleteModalApproved(this)"
                                        modal="confirm-delete-approved"
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
                        <td colspan="10" class="p-6 text-center text-slate-400">Belum ada data pencaker disetujui.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $users->links() }}
        </div>
    </div>

    <div x-data="{ open:false, html:'', loading:false, ak1:'' }"
         @pencaker-detail.window="open=true; loading=true; html=''; ak1=($event.detail.ak1||''); fetch($event.detail.url, {headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.text()).then(t=>{ html=t; }).catch(()=>{ html='<div class=\'p-6 text-red-300\'>Gagal memuat detail.</div>'; }).finally(()=>{ loading=false; })">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
             @keydown.escape.window="open=false">
            <div @click.outside="open=false" class="bg-slate-900 w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden border border-slate-800">
                <div class="flex items-center justify-between px-6 py-3 border-b border-slate-800 sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-lg font-semibold text-slate-100">Detail Pencaker <span x-show="ak1" class="ml-2 text-sm font-normal text-slate-300">— AK/1: <span x-text="ak1"></span></span></h3>
                    <button class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700" @click="open=false">Tutup</button>
                </div>
                <div class="max-h-[85vh] overflow-y-auto">
                    <template x-if="loading"><div class="p-6 text-slate-300">Memuat...</div></template>
                    <div class="p-6" x-html="html"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi (custom, non-Flowbite) --}}
    <div id="confirm-modal-approved" class="hidden fixed inset-0 z-[99999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
            <div class="rounded-xl border border-slate-800 bg-slate-950/95 text-slate-100 shadow-2xl">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-800 bg-slate-900/70">
                    <div>
                        <h3 id="confirmTitle" class="text-lg font-semibold">Konfirmasi</h3>
                        <p id="confirmSubtitle" class="text-sm text-slate-300 mt-1"></p>
                    </div>
                    <button type="button" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition" onclick="closeConfirmModalApproved()">
                        ✕
                    </button>
                </div>
                <div class="px-4 py-4 space-y-3">
                    <p id="confirmBody" class="text-sm text-slate-200 leading-relaxed">Apakah Anda yakin?</p>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm" onclick="closeConfirmModalApproved()">Batal</button>
                        <button type="button" id="confirmActionBtn" class="px-4 py-2 rounded-lg text-white text-sm font-semibold"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="log-ak1-user" :show="false" maxWidth="3xl" animation="zoom">
        <div class="flex items-start justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold" id="userAk1LogTitle">Riwayat AK1 Pencaker</h3>
                <p class="text-sm text-gray-400 mt-1" id="userAk1LogSubtitle"></p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'log-ak1-user'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div id="userAk1LogBody" class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-4"></div>
    </x-modal>

    @once
        @push('scripts')
            <script>
                // -------- Modal handlers (pencaker disetujui) --------
                const confirmModal = document.getElementById('confirm-modal-approved');
                const confirmTitle = document.getElementById('confirmTitle');
                const confirmSubtitle = document.getElementById('confirmSubtitle');
                const confirmBody = document.getElementById('confirmBody');
                const confirmBtn = document.getElementById('confirmActionBtn');
                let approvedCurrentUserId = null;

                window.openDeactivateModalApproved = function (button) {
                    approvedCurrentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deactivateModalSubtitle');
                    const title = document.getElementById('deactivateModalTitle');
                    const body = document.getElementById('deactivateModalBody');
                    const confirmBtn = document.getElementById('confirmDeactivateBtn');

                    if (confirmTitle) confirmTitle.textContent = 'Nonaktifkan Akun Pencaker';
                    if (confirmBody) confirmBody.textContent = 'Nonaktifkan akun pencaker ini? Mereka tidak dapat login sampai diaktifkan kembali.';
                    if (confirmSubtitle) confirmSubtitle.textContent = email ? `${name} · ${email}` : name;

                    if (confirmBtn) {
                        confirmBtn.textContent = 'Nonaktifkan';
                        confirmBtn.className = 'px-4 py-2 rounded-lg text-white text-sm font-semibold bg-amber-600 hover:bg-amber-700 transition';
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deactivateUser(approvedCurrentUserId);
                            }
                            closeConfirmModalApproved();
                        };
                    }

                    openConfirmModalApproved();
                };

                window.openActivateModalApproved = function (button) {
                    approvedCurrentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deactivateModalSubtitle');
                    const title = document.getElementById('deactivateModalTitle');
                    const body = document.getElementById('deactivateModalBody');
                    const confirmBtn = document.getElementById('confirmDeactivateBtn');

                    if (confirmTitle) confirmTitle.textContent = 'Aktifkan Akun Pencaker';
                    if (confirmBody) confirmBody.textContent = 'Aktifkan kembali akun pencaker ini? Mereka akan dapat login dan mengakses layanan kembali.';
                    if (confirmSubtitle) confirmSubtitle.textContent = email ? `${name} · ${email}` : name;
                    if (confirmBtn) {
                        confirmBtn.textContent = 'Aktifkan';
                        confirmBtn.className = 'px-4 py-2 rounded-lg text-white text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 transition';
                        confirmBtn.onclick = function () {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).activateUser(approvedCurrentUserId);
                            }
                            closeConfirmModalApproved();
                        };
                    }

                    openConfirmModalApproved();
                };

                window.openResetModalApproved = function (button) {
                    approvedCurrentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';

                    if (confirmTitle) confirmTitle.textContent = 'Reset Profil Pencaker';
                    if (confirmBody) confirmBody.textContent = 'Reset seluruh profil & riwayat (pendidikan, pelatihan, pengalaman) pencaker ini? AK1 tetap dipertahankan.';
                    if (confirmSubtitle) confirmSubtitle.textContent = email ? `${name} · ${email}` : name;

                    if (confirmBtn) {
                        confirmBtn.textContent = 'Reset Profil';
                        confirmBtn.className = 'px-4 py-2 rounded-lg text-white text-sm font-semibold bg-sky-600 hover:bg-sky-700 transition';
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).resetProfile(approvedCurrentUserId);
                            }
                            closeConfirmModalApproved();
                        };
                    }

                    openConfirmModalApproved();
                };

                window.openDeleteModalApproved = function (button) {
                    approvedCurrentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';

                    if (confirmTitle) confirmTitle.textContent = 'Hapus Pencaker';
                    if (confirmBody) confirmBody.innerHTML = "Hapus pencaker ini <span class='font-semibold text-rose-300'>BESERTA seluruh data dan riwayat AK1</span>? Tindakan ini tidak dapat dibatalkan.";
                    if (confirmSubtitle) confirmSubtitle.textContent = email ? `${name} · ${email}` : name;

                    if (confirmBtn) {
                        confirmBtn.textContent = 'Hapus Pencaker';
                        confirmBtn.className = 'px-4 py-2 rounded-lg text-white text-sm font-semibold bg-rose-600 hover:bg-rose-700 transition';
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deleteUser(approvedCurrentUserId);
                            }
                            closeConfirmModalApproved();
                        };
                    }

                    openConfirmModalApproved();
                };

                window.openConfirmModalApproved = function () {
                    if (confirmModal) confirmModal.classList.remove('hidden');
                };
                window.closeConfirmModalApproved = function () {
                    if (confirmModal) confirmModal.classList.add('hidden');
                };

                // -------- Riwayat AK1 helpers --------
                const statusLabels = {
                    'Menunggu Verifikasi': 'Menunggu Verifikasi',
                    'Menunggu Revisi Verifikasi': 'Menunggu Revisi Verifikasi',
                    'Revisi Diminta': 'Revisi Diminta',
                    'Batal': 'Batal',
                    'Disetujui': 'Disetujui',
                    'Ditolak': 'Ditolak',
                    'Diambil': 'Diambil',
                    'Dicetak': 'Dicetak'
                };

                const actionLabels = {
                    approve: 'Disetujui',
                    reject: 'Ditolak',
                    revision: 'Revisi Diminta',
                    unapprove: 'Persetujuan dibatalkan',
                    printed: 'Dicetak',
                    picked_up: 'Diambil',
                    submitted: 'Pengajuan',
                };

                const actionColors = {
                    approve: 'bg-green-400',
                    reject: 'bg-red-400',
                    revision: 'bg-yellow-400',
                    unapprove: 'bg-orange-400',
                    printed: 'bg-sky-400',
                    picked_up: 'bg-indigo-400',
                    submitted: 'bg-blue-400',
                };

                const escapeHtml = (unsafe) => (unsafe ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');

                const formatStatus = (status) => statusLabels[status] || status || '-';

                window.openUserAk1History = async function (url, name, email) {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'log-ak1-user' }));
                    const title = document.getElementById('userAk1LogTitle');
                    const subtitle = document.getElementById('userAk1LogSubtitle');
                    const body = document.getElementById('userAk1LogBody');

                    if (title) title.textContent = `Riwayat AK1 — ${name || 'Pencaker'}`;
                    if (subtitle) subtitle.textContent = email || '';
                    if (body) body.innerHTML = '<p class="text-sm text-slate-300">Memuat riwayat...</p>';

                    try {
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const data = await res.json();
                        const logs = (data.logs || [])
                            .slice()
                            .sort((a, b) => {
                                const aDate = a.timestamp ? new Date(a.timestamp * 1000) : new Date(a.created_at || 0);
                                const bDate = b.timestamp ? new Date(b.timestamp * 1000) : new Date(b.created_at || 0);
                                return aDate - bDate;
                            });

                        if (!logs.length) {
                            if (body) body.innerHTML = '<p class="text-sm text-gray-400">Belum ada riwayat AK1.</p>';
                            return;
                        }

                        const content = logs.map((log, idx) => {
                            const label = actionLabels[log.action] || log.action;
                            const noteSection = log.notes
                                ? `<div class="mt-3 rounded-lg bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                                        <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">Catatan</span>
                                        <p class="mt-1 leading-relaxed">${escapeHtml(log.notes).replace(/\\n/g, '<br>')}</p>
                                   </div>`
                                : '';
                            const circleColor = actionColors[log.action] || 'bg-gray-400';
                            const isLast = idx === logs.length - 1;

                            const extra = [];
                            if (log.nomor_ak1) extra.push(`No. AK1: ${escapeHtml(log.nomor_ak1)}`);
                            if (log.type) extra.push(`Tipe: ${escapeHtml(log.type)}`);

                            return `
                                <div class="relative pl-10">
                                    <span class="absolute left-1 top-1.5 inline-flex h-3 w-3 rounded-full ${circleColor} ring-4 ring-gray-900"></span>
                                    ${isLast ? '' : '<span class="absolute left-2.5 top-4 bottom-0 border-l border-gray-700"></span>'}
                                    <div class="rounded-xl border border-gray-800 bg-gray-900/60 px-4 py-3 shadow-sm">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <h4 class="text-sm font-semibold text-gray-100">${escapeHtml(label)}</h4>
                                            <span class="text-xs text-gray-400">${log.created_at ?? '-'}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Perubahan: <span class="text-gray-300">${formatStatus(log.from_status)} → ${formatStatus(log.to_status)}</span></p>
                                        <p class="mt-2 text-sm text-gray-300 leading-relaxed">
                                            Oleh: <span class="font-medium text-gray-100">${escapeHtml(log.actor || 'Sistem')}</span>
                                        </p>
                                        ${extra.length ? `<p class="mt-1 text-xs text-gray-400">${extra.join(' · ')}</p>` : ''}
                                        ${noteSection}
                                    </div>
                                </div>
                            `;
                        }).join('');

                        if (body) {
                            body.innerHTML = `
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Linimasa Pengajuan AK1
                                    </div>
                                    <div class="space-y-6">${content}</div>
                                </div>
                            `;
                        }
                    } catch (error) {
                        if (body) body.innerHTML = `<p class="text-sm text-red-400">Gagal memuat riwayat. ${error.message}</p>`;
                    }
                };
            </script>
        @endpush
    @endonce
</div>
