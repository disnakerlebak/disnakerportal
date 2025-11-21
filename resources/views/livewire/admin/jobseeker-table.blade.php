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
                    <th class="p-3 text-left">
                        <button type="button"
                                wire:click="sortBy('usia')"
                                class="flex items-center gap-1 text-xs font-semibold capitalize tracking-wide text-slate-200 hover:text-white">
                            <span>Usia</span>
                            @php $isSorted = $sortField === 'usia'; @endphp
                            <svg class="w-3.5 h-3.5 {{ $isSorted ? 'text-indigo-400' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($isSorted)
                                    @if($sortDirection === 'asc')
                                        <path d="M10 6l4 4H6l4-4z" />
                                    @else
                                        <path d="M10 14l-4-4h8l-4 4z" />
                                    @endif
                                @else
                                    <path d="M10 5l4 4H6l4-4zm0 10l-4-4h8l-4 4z" />
                                @endif
                            </svg>
                        </button>
                    </th>
                    <th class="p-3 text-left">
                        <button type="button"
                                wire:click="sortBy('pendidikan')"
                                class="flex items-center gap-1 text-xs font-semibold capitalize tracking-wide text-slate-200 hover:text-white">
                            <span>Pendidikan</span>
                            @php $isSorted = $sortField === 'pendidikan'; @endphp
                            <svg class="w-3.5 h-3.5 {{ $isSorted ? 'text-indigo-400' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($isSorted)
                                    @if($sortDirection === 'asc')
                                        <path d="M10 6l4 4H6l4-4z" />
                                    @else
                                        <path d="M10 14l-4-4h8l-4 4z" />
                                    @endif
                                @else
                                    <path d="M10 5l4 4H6l4-4zm0 10l-4-4h8l-4 4z" />
                                @endif
                            </svg>
                        </button>
                    </th>
                    <th class="p-3 text-left">
                        <button type="button"
                                wire:click="sortBy('keahlian')"
                                class="flex items-center gap-1 text-xs font-semibold capitalize tracking-wide text-slate-200 hover:text-white">
                            <span>Keahlian</span>
                            @php $isSorted = $sortField === 'keahlian'; @endphp
                            <svg class="w-3.5 h-3.5 {{ $isSorted ? 'text-indigo-400' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($isSorted)
                                    @if($sortDirection === 'asc')
                                        <path d="M10 6l4 4H6l4-4z" />
                                    @else
                                        <path d="M10 14l-4-4h8l-4 4z" />
                                    @endif
                                @else
                                    <path d="M10 5l4 4H6l4-4zm0 10l-4-4h8l-4 4z" />
                                @endif
                            </svg>
                        </button>
                    </th>
                    <th class="p-3 text-left whitespace-nowrap">
                        <button type="button"
                                wire:click="sortBy('pengalaman')"
                                class="flex items-center gap-1 text-xs font-semibold capitalize tracking-wide text-slate-200 hover:text-white">
                            <span>Pengalaman Kerja</span>
                            @php $isSorted = $sortField === 'pengalaman'; @endphp
                            <svg class="w-3.5 h-3.5 {{ $isSorted ? 'text-indigo-400' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($isSorted)
                                    @if($sortDirection === 'asc')
                                        <path d="M10 6l4 4H6l4-4z" />
                                    @else
                                        <path d="M10 14l-4-4h8l-4 4z" />
                                    @endif
                                @else
                                    <path d="M10 5l4 4H6l4-4zm0 10l-4-4h8l-4 4z" />
                                @endif
                            </svg>
                        </button>
                    </th>
                    <th class="p-3 text-left">Status Disabilitas</th>
                    <th class="p-3 text-left">Kecamatan</th>
                    <th class="p-3 text-left whitespace-nowrap">
                        <button type="button"
                                wire:click="sortBy('nomor_ak1')"
                                class="flex items-center gap-1 text-xs font-semibold capitalize tracking-wide text-slate-200 hover:text-white">
                            <span>No. AK/1</span>
                            @php $isSorted = $sortField === 'nomor_ak1'; @endphp
                            <svg class="w-3.5 h-3.5 {{ $isSorted ? 'text-indigo-400' : 'text-slate-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($isSorted)
                                    @if($sortDirection === 'asc')
                                        <path d="M10 6l4 4H6l4-4z" />
                                    @else
                                        <path d="M10 14l-4-4h8l-4 4z" />
                                    @endif
                                @else
                                    <path d="M10 5l4 4H6l4-4zm0 10l-4-4h8l-4 4z" />
                                @endif
                            </svg>
                        </button>
                    </th>
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
                        <td class="p-3">{{ $p?->nama_lengkap ? ucwords(strtolower($p->nama_lengkap)) : ($u->name ? ucwords(strtolower($u->name)) : '-') }}</td>
                        <td class="p-3">{{ $p?->jenis_kelamin ? ucwords(strtolower($p->jenis_kelamin)) : '-' }}</td>
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
                                {{-- DETAIL --}}
                                <x-dropdown-item
                                    class="text-blue-300 hover:text-blue-100"
                                    onclick="
                                        window.dispatchEvent(
                                            new CustomEvent('approved-admin:open', {
                                                detail: {
                                                    id: 'approved-admin:detail',
                                                    url: '{{ route('admin.pencaker.detail', $u->id) }}',
                                                    ak1: '{{ $app?->nomor_ak1 ?? '' }}'
                                                }
                                            })
                                        );
                                    ">
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

                            {{-- RIWAYAT AK1 --}}
                            <x-dropdown-item
                                class="text-purple-300 hover:text-purple-100"
                                onclick="
                                    window.dispatchEvent(
                                        new CustomEvent('approved-admin:open', {
                                            detail: {
                                                id: 'approved-admin:history',
                                                url: '{{ route('admin.ak1.userLogs', $u->id) }}',
                                                name: '{{ $p->nama_lengkap ?? $u->name ?? 'Pencaker' }}',
                                                email: '{{ $u->email }}'
                                            }
                                        })
                                    );
                                ">
                        Riwayat AK1
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

    {{-- MODAL DETAIL PENCaker --}}
<div id="approved-admin:detail"
     class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4"
     x-data="approvedDetailModal()"
     @click.self="close()">
    <div class="modal-panel w-full max-w-5xl shadow-lg overflow-hidden">
        <div class="modal-panel-header flex items-center justify-between px-6 py-3 sticky top-0 z-10">
            <h3 class="text-lg font-semibold text-gray-100">
                Detail Pencaker
                <span x-show="ak1" class="ml-2 text-sm font-normal text-gray-300">
                    — AK/1: <span x-text="ak1"></span>
                </span>
            </h3>
            <button
                class="px-3 py-1 rounded border border-gray-700 bg-gray-800 hover:bg-gray-700"
                @click="close()">
                Tutup
            </button>
        </div>
        <div class="max-h-[85vh] overflow-y-auto">
            <template x-if="loading">
                <div class="p-6 text-gray-300">Memuat...</div>
            </template>
            <div class="p-6" x-html="html"></div>
        </div>
    </div>
</div>


    {{-- Modal Konfirmasi (custom, non-Flowbite) --}}
    <div id="confirm-modal-approved" class="hidden fixed inset-0 z-[99999] modal-backdrop flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
            <div class="modal-panel">
                <div class="modal-panel-header flex items-center justify-between px-4 py-3">
                    <div>
                        <h3 id="confirmTitle" class="text-lg font-semibold">Konfirmasi</h3>
                        <p id="confirmSubtitle" class="text-sm text-gray-300 mt-1"></p>
                    </div>
                    <button type="button" class="modal-close" onclick="closeConfirmModalApproved()">
                        ✕
                    </button>
                </div>
                <div class="px-4 py-4 space-y-3">
                    <p id="confirmBody" class="text-sm text-gray-200 leading-relaxed">Apakah Anda yakin?</p>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="px-4 py-2 rounded-lg border border-gray-700 bg-gray-800 hover:bg-gray-700 transition text-sm" onclick="closeConfirmModalApproved()">Batal</button>
                        <button type="button" id="confirmActionBtn" class="px-4 py-2 rounded-lg text-white text-sm font-semibold"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL RIWAYAT AK1 (TIMELINE) --}}
    <div id="approved-admin:history"
        class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4"
        x-data="approvedHistoryModal()"
        @click.self="close()">
        <div class="modal-panel w-full max-w-4xl shadow-xl overflow-hidden">
            <div class="modal-panel-header flex items-start justify-between px-6 py-4 sticky top-0 z-10">
                <div>
                    <h3 class="text-lg font-semibold text-gray-100" x-text="title"></h3>
                    <p class="text-sm text-gray-400 mt-1" x-text="subtitle"></p>
                </div>
                <button type="button" @click="close()" class="modal-close">✕</button>
            </div>
            <div class="px-6 py-5 max-h-[75vh] overflow-y-auto">
                <template x-if="loading">
                    <p class="text-sm text-slate-300">Memuat riwayat...</p>
                </template>
                <div x-html="html" class="space-y-4"></div>
            </div>
        </div>
    </div>


    @once
    @push('scripts')
        <script>
            // ========= MODAL ENGINE: APPROVED JOBSEEKERS =========

            function approvedDetailModal() {
                return {
                    html: '',
                    loading: false,
                    ak1: '',
                    load(detail) {
                        this.ak1 = detail?.ak1 || '';
                        const url = detail?.url;

                        if (!url) {
                            this.html = "<div class='p-6 text-red-300'>URL detail tidak tersedia.</div>";
                            this.loading = false;
                            return;
                        }

                        this.loading = true;
                        this.html = '';

                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(t => { this.html = t; })
                            .catch(() => {
                                this.html = "<div class='p-6 text-red-300'>Gagal memuat detail.</div>";
                            })
                            .finally(() => { this.loading = false; });
                    },
                    close() {
                        const el = document.getElementById('approved-admin:detail');
                        if (el) el.classList.add('hidden');
                    }
                }
            }

            function approvedHistoryModal() {

// ====== LABELS UNTUK AK1 (VERSI FINAL) ======
            const ak1ActionLabels = {
                submit: "Pengajuan Baru",
                resubmit: "Pengajuan Ulang",
                repair_submit: "Pengajuan Perbaikan",
                extend_submit: "Pengajuan Perpanjangan",
                approve: "Disetujui",
                unapprove: "Batal Setuju",
                reject: "Ditolak",
                revision: "Revisi Diminta",
                printed: "Dicetak",
                picked_up: "Diambil",
                archived: "AK1 Diarsipkan",
            };

            // Badges berdasarkan jenis pengajuan
            const ak1Badges = {
                submit: "BARU",
                repair_submit: "PERBAIKAN",
                extend_submit: "PERPANJANGAN",
            };

            // Warna titik timeline
            const actionColors = {
                submit: "bg-blue-400",
                repair_submit: "bg-yellow-400",
                extend_submit: "bg-purple-400",

                approve: "bg-green-400",
                reject: "bg-red-400",
                revision: "bg-amber-400",
                printed: "bg-sky-400",
                picked_up: "bg-indigo-400",
                archived: "bg-gray-400",
            };

            const escapeHtml = (unsafe) => (unsafe ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');

            const formatStatus = (status) =>
                status && status !== "null" ? status : "—";

            const formatRole = (role) => {
                if (!role) return "";
                const adminRoles = ['superadmin','admin_ak1','admin_laporan','admin_verifikator','admin_loker','admin_statistik'];
                if (adminRoles.includes(role)) return "Admin";
                if (role === 'pencaker') return "Pemohon";
                if (role === 'perusahaan') return "Perusahaan";
                return role;
            };

            const buildTimeline = (logs) => {
                const items = logs.map((log, idx) => {

                    const label =
                        ak1ActionLabels[log.action] ||
                        log.action ||
                        "Aktivitas";

                    const badge = ak1Badges[log.action]
                        ? `<span class="ml-2 rounded bg-emerald-700/40 px-2 py-0.5 text-xs text-emerald-300">
                            ${ak1Badges[log.action]}
                        </span>`
                        : "";

                    const circleColor = actionColors[log.action] || "bg-gray-500";
                    const isLast = idx === logs.length - 1;

                    const date =
                        log.created_at ||
                        (log.timestamp ? new Date(log.timestamp * 1000).toLocaleString() : "-");

                    const noteSection = log.notes
                        ? `<div class="mt-3 rounded-lg bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                                <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">Catatan</span>
                                <p class="mt-1 leading-relaxed">${escapeHtml(log.notes).replace(/\\n/g, '<br>')}</p>
                        </div>`
                        : "";

                    const extraInfo =
                        (log.nomor_ak1 || log.type)
                            ? `<p class="mt-1 text-xs text-gray-400">
                                ${log.nomor_ak1 ? "No. AK1: " + escapeHtml(log.nomor_ak1) : ""}
                                ${log.type ? " · Tipe: " + escapeHtml(log.type) : ""}
                            </p>`
                            : "";

                    return `
                        <div class="relative pl-10">
                            <span class="absolute left-1 top-1.5 inline-flex h-3 w-3 rounded-full ${circleColor} ring-4 ring-gray-900"></span>
                            ${!isLast ? '<span class="absolute left-2.5 top-4 bottom-0 border-l border-gray-700"></span>' : ""}
                            
                            <div class="rounded-xl border border-gray-800 bg-gray-900/60 px-4 py-3 shadow-sm">
                                
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-sm font-semibold text-gray-100">
                                        ${escapeHtml(label)} ${badge}
                                    </h4>
                                    <span class="text-xs text-gray-400">${date}</span>
                                </div>

                                <p class="mt-1 text-xs text-gray-400">
                                    Perubahan:
                                    <span class="text-gray-300">
                                        ${formatStatus(log.from_status)} → ${formatStatus(log.to_status)}
                                    </span>
                                </p>

                                <p class="mt-2 text-sm text-gray-300 leading-relaxed">
                                    Oleh:
                                    <span class="font-medium text-gray-100">
                                        ${escapeHtml(log.actor || "Sistem")}
                                        ${formatRole(log.actor_role) ? " (" + formatRole(log.actor_role) + ")" : ""}
                                    </span>
                                </p>

                                ${extraInfo}
                                ${noteSection}
                            </div>
                        </div>
                    `;
                }).join("");

                return `
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-200 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Linimasa Pengajuan AK1</span>
                        </div>
                        <div class="space-y-6">${items}</div>
                    </div>
                `;
            };

            // ====== RETURN ALPINE OBJECT ======
            return {
                html: '',
                loading: false,
                title: 'Riwayat AK1',
                subtitle: '',
                load(detail) {

                    this.title = `Riwayat AK1 — ${detail?.name || 'Pencaker'}`;
                    this.subtitle = detail?.email || '';

                    const url = detail?.url;
                    if (!url) {
                        this.html = "<p class='text-sm text-red-400'>URL riwayat tidak tersedia.</p>";
                        return;
                    }

                    this.loading = true;
                    this.html = "";

                    fetch(url, { headers: { Accept: "application/json" } })
                        .then((r) => r.json())
                        .then((data) => {
                            const logs = (data.logs || []).slice().sort((a, b) => {
                                const da = a.timestamp ? a.timestamp : Date.parse(a.created_at);
                                const db = b.timestamp ? b.timestamp : Date.parse(b.created_at);
                                return da - db;
                            });

                            if (!logs.length) {
                                this.html = "<p class='text-sm text-gray-400'>Belum ada riwayat pengajuan.</p>";
                                return;
                            }

                            this.html = buildTimeline(logs);
                        })
                        .catch((e) => {
                            this.html = `<p class='text-sm text-red-400'>Gagal memuat riwayat: ${e.message}</p>`;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                close() {
                    const el = document.getElementById("approved-admin:history");
                    if (el) el.classList.add("hidden");
                },
            };
            }

            // Batas Suci
            (function () {
                const withAlpine = (id, cb) => {
                    const el = document.getElementById(id);
                    if (!el || !window.Alpine) return;
                    const data = Alpine.$data(el);
                    if (!data) return;
                    cb(data, el);
                };

                window.addEventListener('approved-admin:open', (event) => {
                    const detail = event.detail || {};
                    if (!detail.id) return;

                    if (detail.id === 'approved-admin:detail') {
                        withAlpine('approved-admin:detail', (comp, el) => {
                            comp.load(detail);
                            el.classList.remove('hidden');
                        });
                    }

                    if (detail.id === 'approved-admin:history') {
                        withAlpine('approved-admin:history', (comp, el) => {
                            comp.load(detail);
                            el.classList.remove('hidden');
                        });
                    }
                });

                window.addEventListener('approved-admin:close', (event) => {
                    const id = typeof event.detail === 'string'
                        ? event.detail
                        : event.detail?.id;

                    if (!id) return;

                    if (id === 'approved-admin:detail') {
                        withAlpine('approved-admin:detail', comp => comp.close && comp.close());
                    }

                    if (id === 'approved-admin:history') {
                        withAlpine('approved-admin:history', comp => comp.close && comp.close());
                    }
                });

                // ESC → tutup semua modal approved-admin
                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    ['approved-admin:detail', 'approved-admin:history'].forEach(id => {
                        window.dispatchEvent(new CustomEvent('approved-admin:close', { detail: { id } }));
                    });
                });
            })();


            // ========= MODAL KONFIRMASI (LAMA) – TETAP DIPAKAI =========

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
        </script>
    @endpush
@endonce

</div>
