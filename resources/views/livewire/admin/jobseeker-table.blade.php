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
                                <div class="relative" x-data="dropdownMenu()" x-init="init()">
                                    <button @click="toggle($event)"
                                            type="button"
                                            class="rounded-md border border-slate-700 bg-slate-800 p-2 text-white text-sm transition duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="5" r="1"/>
                                            <circle cx="12" cy="12" r="1"/>
                                            <circle cx="12" cy="19" r="1"/>
                                        </svg>
                                    </button>
                                    <template x-teleport="body">
                                        <div x-show="open" @click.away="close()" @keydown.escape.window="close()"
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                            :class="dropUp ? 'origin-bottom-right' : 'origin-top-right'"
                                             class="fixed z-[70] w-56 rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800"
                                             :style="style + (dropUp ? ';transform: translateY(-100%)' : '')">
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition"
                                                    @click="
                                                      window.dispatchEvent(new CustomEvent('close-dropdowns'));
                                                      window.dispatchEvent(new CustomEvent('pencaker-detail', { detail: { url: '{{ route('admin.pencaker.detail', $u->id) }}', ak1: '{{ $app?->nomor_ak1 ?? '' }}' } }));
                                                    ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </button>

                                            @if($app)
                                                <a href="{{ route('admin.ak1.cetak', $app->id) }}"
                                                   target="_blank"
                                                   class="w-full text-left px-4 py-2 text-sm text-indigo-300 hover:bg-indigo-700/20 flex items-center gap-2 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-3-3m3 3l3-3M6 20h12" />
                                                    </svg>
                                                    Unduh AK1
                                                </a>
                                            @endif

                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-purple-300 hover:bg-purple-700/20 flex items-center gap-2 transition"
                                                    @click="
                                                      window.dispatchEvent(new CustomEvent('close-dropdowns'));
                                                      window.openUserAk1History(
                                                        '{{ route('admin.ak1.userLogs', $u->id) }}',
                                                        '{{ $p->nama_lengkap ?? $u->name ?? 'Pencaker' }}',
                                                        '{{ $u->email }}'
                                                      );
                                                    ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Riwayat AK1
                                            </button>
                                        </div>
                                    </template>
                                </div>
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
                window.dropdownMenu = function () {
                    return {
                        open: false,
                        dropUp: false,
                        style: '',
                        width: 224,
                        init() { window.addEventListener('close-dropdowns', () => { this.open = false; }); },
                        toggle(e) {
                            this.open = !this.open;
                            if (this.open) {
                                const rect = e.currentTarget.getBoundingClientRect();
                                const spaceBelow = window.innerHeight - rect.bottom;
                                this.dropUp = spaceBelow < 240;
                                let left = rect.right - this.width;
                                left = Math.max(8, Math.min(left, window.innerWidth - this.width - 8));
                                let top = this.dropUp ? rect.top - 8 : rect.bottom + 8;
                                this.style = `left:${left}px;top:${top}px`;
                            }
                        },
                        close() { this.open = false; }
                    }
                }

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
