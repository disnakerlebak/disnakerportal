<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col px-6 py-8 gap-6 box-border">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Verifikasi Pengajuan AK1
        </h2>
    </div>

    {{-- ===== Filter (Livewire reactive) ===== --}}
    <form wire:submit.prevent="noop" class="flex flex-wrap items-center gap-3">
        <input type="text"
               wire:model.debounce.500ms="search"
               placeholder="Cari nama / email"
               class="w-64 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

        <select wire:model="status" class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            @foreach ($statusOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-white transition hover:bg-indigo-500">Filter</button>

        @if ($this->hasActiveFilters)
            <button type="button" wire:click="clearFilters" class="rounded-lg border border-slate-700 px-3 py-2 text-slate-200 hover:bg-slate-800">Reset</button>
        @endif
    </form>

    {{-- ===== Tab Jenis Pengajuan ===== --}}
    <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
        @foreach ($typeTabs as $value => $label)
            <button type="button"
                    wire:click="setType('{{ $value }}')"
                    class="px-4 py-2 rounded-full border transition {{ $type === $value ? 'bg-indigo-600 border-indigo-500 text-white shadow' : 'border-slate-700 text-slate-300 hover:bg-slate-800' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>


    {{-- ===== Tabel Daftar Pengajuan ===== --}}
    <div class="relative flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow">
        <!-- Overlay loading terpusat + blur konten -->
        <div wire:loading.flex
             wire:target="search,status,type,noop,setType,clearFilters"
             class="absolute inset-0 z-20 items-center justify-center bg-slate-950/30 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-900/70 border border-slate-700 shadow text-indigo-200">
                <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm">Memuat data…</span>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-hidden"
             wire:target="search,status,type,noop,setType,clearFilters"
             wire:loading.class="blur-[1px] opacity-60">
            <div class="overflow-x-auto h-full min-h-0">
                <div class="h-full min-h-0 overflow-y-auto lg:overflow-y-visible">
                    <table class="min-w-full w-full text-sm text-gray-200">
                        <thead class="bg-gray-700 text-gray-100 uppercase text-xs whitespace-nowrap">
                        <tr>
                            <th class="p-3 text-center whitespace-nowrap">Pemohon</th>
                            <th class="p-3 text-center whitespace-nowrap">Tanggal</th>
                            <th class="p-3 text-center whitespace-nowrap">Status</th>
                            <th class="p-3 text-center whitespace-nowrap">Tipe Pengajuan</th>
                            <th class="p-3 text-center whitespace-nowrap">Keterangan</th>
                            <th class="p-3 text-center whitespace-nowrap">Nomor AK/1</th>
                            <th class="p-3 text-center whitespace-nowrap">Ditangani Oleh</th>
                            <th class="p-3 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-800 text-gray-200">
                        @forelse ($apps as $app)
                            @php
                                $badgeClass = match($app->status) {
                                    'Menunggu Verifikasi' => 'bg-gray-700 text-gray-300',
                                    'Menunggu Revisi Verifikasi' => 'bg-blue-700 text-blue-100',
                                    'Revisi Diminta' => 'bg-yellow-700 text-yellow-100',
                                    'Disetujui' => 'bg-green-700 text-green-100',
                                    'Ditolak' => 'bg-red-700 text-red-100',
                                    default => 'bg-gray-700 text-gray-300',
                                };

                                $typeLabel = match($app->type) {
                                    'perbaikan' => 'Perbaikan',
                                    'perpanjangan' => 'Perpanjangan',
                                    default => 'Pengajuan Baru',
                                };

                                $latestLog = $app->logs->first();
                                $catatan = '';
                                if (in_array($app->status, ['Ditolak', 'Revisi Diminta']) && $latestLog?->notes) {
                                    $catatan = $latestLog->notes;
                                }

                                $logPayload = $app->logs->sortBy('created_at')->values()->map(function ($log) {
                                    return [
                                        'id'          => $log->id,
                                        'action'      => $log->action,
                                        'from_status' => $log->from_status,
                                        'to_status'   => $log->to_status,
                                        'notes'       => $log->notes,
                                        'actor'       => $log->actor?->name,
                                        'created_at'  => optional($log->created_at)->format('d M Y H:i'),
                                        'timestamp'   => optional($log->created_at)?->timestamp,
                                    ];
                                });
                            @endphp

                            <tr class="transition hover:bg-gray-800/60" wire:key="ak1-row-{{ $app->id }}">
                                {{-- Pemohon --}}
                                <td class="p-3 align-top">
                                    <div class="font-semibold text-white">{{ $app->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $app->user->email }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        ID: <span class="font-mono">{{ $app->id }}</span>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="p-3 align-top text-center whitespace-nowrap">
                                    <div>{{ $app->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $app->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Status --}}
                                <td class="p-3 align-top text-center">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $app->status }}
                                    </span>
                                </td>

                                {{-- Tipe Pengajuan --}}
                                <td class="p-3 align-top text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full
                                            {{ $app->type === 'perpanjangan' ? 'bg-amber-400' : ($app->type === 'perbaikan' ? 'bg-yellow-400' : 'bg-indigo-400') }}">
                                        </span>
                                        {{ $typeLabel }}
                                    </span>
                                </td>

                                {{-- Keterangan --}}
                                <td class="p-3 align-top text-sm">
                                    @if($app->status === 'Ditolak')
                                        <span class="text-red-400">{{ $catatan ?: '—' }}</span>
                                    @elseif($app->status === 'Revisi Diminta')
                                        <span class="text-yellow-400">{{ $catatan ?: '—' }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Nomor AK/1 --}}
                                <td class="p-3 align-top text-center whitespace-nowrap">
                                    {{ $app->nomor_ak1 ?? '-' }}
                                </td>

                                {{-- Ditangani Oleh --}}
                                <td class="p-3 align-top whitespace-nowrap">
                                    @if($app->lastHandler?->actor?->name)
                                        <div class="font-medium">{{ $app->lastHandler->actor->name }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ ucfirst(str_replace('_',' ',$app->lastHandler->action)) }}
                                            · {{ $app->lastHandler->created_at?->format('d M Y H:i') }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="relative p-3 align-top text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="relative" x-data="{ open: false }" x-id="['action-menu']">
                                            <button @click="open = !open"
                                                    type="button"
                                                    class="rounded-md border border-slate-700 bg-slate-800 p-2 text-white text-sm transition duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="5" r="1"/>
                                                    <circle cx="12" cy="12" r="1"/>
                                                    <circle cx="12" cy="19" r="1"/>
                                                </svg>
                                            </button>

                                            <div x-show="open" @click.away="open = false"
                                                 x-transition:enter="transition ease-out duration-150"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 x-transition:leave="transition ease-in duration-100"
                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                 x-transition:leave-end="opacity-0 transform scale-95"
                                                 class="absolute left-0 z-50 mt-2 w-48 origin-top-left rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800 md:left-auto md:right-0 md:origin-top-right">

                                                {{-- 👁️ Detail --}}
                                                <button onclick="showDetail({{ $app->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </button>

                                                @if(in_array($app->status, ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi']))
                                                    <form method="POST" action="{{ route('admin.ak1.approve', $app) }}">
                                                        @csrf
                                                        <button class="w-full text-left px-4 py-2 text-sm text-green-400 hover:bg-green-700/20 flex items-center gap-2 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Setujui
                                                        </button>
                                                    </form>

                                                    <button onclick="openRejectModal({{ $app->id }})"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Tolak
                                                    </button>

                                                    <button onclick="openRevisionModal({{ $app->id }})"
                                                            class="w-full text-left px-4 py-2 text-sm text-yellow-400 hover:bg-yellow-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17h2m-1-12a9 9 0 11-9 9 9 9 0 019-9z" />
                                                        </svg>
                                                        Revisi
                                                    </button>
                                                @endif

                                                @if ($app->status === 'Disetujui')
                                                    <button
                                                        type="button"
                                                        onclick="openUnapproveModal(this)"
                                                        data-unapprove-url="{{ route('admin.ak1.unapprove', $app) }}"
                                                        data-app-name="{{ $app->user->name }}"
                                                        data-app-email="{{ $app->user->email }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-orange-300 hover:bg-orange-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16M9 3l-6 9 6 9" />
                                                        </svg>
                                                        Batalkan Persetujuan
                                                    </button>
                                                @endif

                                                @if ($app->status === 'Disetujui' && $app->nomor_ak1)
                                                    <a href="{{ route('admin.ak1.cetak', $app->id) }}" target="_blank"
                                                       class="block px-4 py-2 text-sm text-indigo-400 hover:bg-indigo-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Unduh Kartu
                                                    </a>
                                                @endif

                                                <button
                                                    type="button"
                                                    onclick="openLogModal(this)"
                                                    class="w-full text-left px-4 py-2 text-sm text-cyan-400 hover:bg-cyan-700/20 flex items-center gap-2 transition"
                                                    data-app-name="{{ $app->user->name }}"
                                                    data-app-email="{{ $app->user->email }}"
                                                    data-current-status="{{ $app->status }}"
                                                    data-logs='@json($logPayload)'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Riwayat
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">
                                    Belum ada pengajuan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-700">
            {{ $apps->links() }}
        </div>
    </div>

    {{-- ===== Modal Detail ===== --}}
    <div id="detailModal"
         wire:ignore
         class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-gray-900 w-11/12 max-w-4xl p-6 rounded-xl shadow-lg relative text-gray-100">
            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-200">
                ✕
            </button>
            <div id="modalContent" class="overflow-y-auto max-h-[75vh]"></div>
        </div>
    </div>

    {{-- ===== Modal Tolak ===== --}}
    <div id="rejectModal"
         wire:ignore
         class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center">
        <div class="bg-gray-900 w-full max-w-md p-6 rounded-xl shadow-lg text-gray-100 relative">
            <h3 class="text-lg font-semibold mb-3 border-b border-gray-700 pb-2">Tolak Pengajuan</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm mb-1">Pilih Alasan Penolakan:</label>
                    <select name="reason_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-gray-200">
                        @foreach($rejectionReasons as $reason)
                            <option value="{{ $reason['id'] }}">{{ $reason['title'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm mb-1">Catatan Tambahan (opsional):</label>
                    <textarea name="notes" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-gray-200"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="px-3 py-1.5 rounded bg-gray-700 hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-3 py-1.5 rounded bg-red-700 hover:bg-red-800">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Modal Riwayat ===== --}}
    <div id="logModal"
         wire:ignore
         class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4">
        <div class="bg-gray-900 w-full max-w-3xl rounded-xl shadow-xl text-gray-100 relative overflow-hidden">
            <div class="flex items-start justify-between border-b border-gray-800 px-6 py-5">
                <div>
                    <h3 class="text-lg font-semibold" id="logModalTitle">Riwayat Pengajuan</h3>
                    <p class="text-sm text-gray-400 mt-1" id="logModalSubtitle"></p>
                </div>
                <button type="button" onclick="closeLogModal()" class="text-gray-400 hover:text-gray-200 transition">
                    ✕
                </button>
            </div>
            <div id="logModalBody" class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-4"></div>
        </div>
    </div>

    {{-- ===== Modal Batalkan Persetujuan ===== --}}
    <div id="unapproveModal"
         wire:ignore
         class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4">
        <div class="bg-gray-900 w-full max-w-lg rounded-xl shadow-xl text-gray-100 relative overflow-hidden border border-gray-800">
            <div class="flex items-center justify-between border-b border-gray-800 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold">Batalkan Persetujuan</h3>
                    <p class="text-sm text-gray-400 mt-1" id="unapproveModalSubtitle"></p>
                </div>
                <button type="button" onclick="closeUnapproveModal()" class="text-gray-400 hover:text-gray-200 transition">✕</button>
            </div>
            <form id="unapproveForm" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <p class="text-sm text-gray-300 leading-relaxed">
                    Apakah Anda yakin ingin membatalkan persetujuan pengajuan ini? Status akan kembali menjadi
                    <span class="font-semibold text-yellow-300">Revisi Diminta</span> sehingga pemohon dapat memperbarui data.
                </p>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1" for="unapproveNotes">Catatan (opsional)</label>
                    <textarea name="notes" id="unapproveNotes" rows="4"
                              class="w-full resize-none bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
                              placeholder="Catat alasan pembatalan jika diperlukan..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeUnapproveModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 transition text-sm font-semibold text-white">
                        Ya, Batalkan Persetujuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Modal Revisi ===== --}}
    <div id="revisionModal"
         wire:ignore
         class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center">
        <div class="bg-gray-900 w-full max-w-md p-6 rounded-xl shadow-lg text-gray-100 relative">
            <h3 class="text-lg font-semibold mb-3 border-b border-gray-700 pb-2">Minta Revisi Pengajuan</h3>
            <form id="revisionForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm mb-1">Catatan Revisi:</label>
                    <textarea name="notes" rows="4" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-gray-200" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRevisionModal()" class="px-3 py-1.5 rounded bg-gray-700 hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-3 py-1.5 rounded bg-yellow-600 hover:bg-yellow-700">Kirim</button>
                </div>
            </form>
        </div>
    </div>

@once
    @push('scripts')
        <script>
            (() => {
                const statusLabels = {
                    'Menunggu Verifikasi': 'Menunggu Verifikasi',
                    'Menunggu Revisi Verifikasi': 'Menunggu Revisi Verifikasi',
                    'Revisi Diminta': 'Revisi Diminta',
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
                };

                const actionColors = {
                    approve: 'bg-green-400',
                    reject: 'bg-red-400',
                    revision: 'bg-yellow-400',
                    unapprove: 'bg-orange-400',
                    printed: 'bg-sky-400',
                    picked_up: 'bg-indigo-400',
                };

                const getEl = (id) => document.getElementById(id);

                window.closeModal = function () {
                    getEl('detailModal')?.classList.add('hidden');
                };

                function renderSnapshotBlock(title, snapshot) {
                    if (!snapshot) return '';

                    const profile = snapshot.profile || {};
                    const educations = Array.isArray(snapshot.educations) ? snapshot.educations : [];
                    const trainings = Array.isArray(snapshot.trainings) ? snapshot.trainings : [];
                    const documents = Array.isArray(snapshot.documents) ? snapshot.documents : [];

                    const profileMap = [
                        ['Nama Lengkap', profile.nama_lengkap],
                        ['NIK', profile.nik],
                        ['Tempat Lahir', profile.tempat_lahir],
                        ['Tanggal Lahir', profile.tanggal_lahir],
                        ['Jenis Kelamin', profile.jenis_kelamin],
                        ['Agama', profile.agama],
                        ['Status', profile.status_perkawinan],
                        ['Pendidikan Terakhir', profile.pendidikan_terakhir],
                        ['Alamat', profile.alamat_lengkap],
                        ['Kecamatan', profile.domisili_kecamatan],
                        ['No. Telepon', profile.no_telepon],
                    ].map(([label, value]) => `
                        <div class="flex text-xs sm:text-sm text-gray-300">
                            <span class="w-32 text-gray-400">${label}</span>
                            <span class="flex-1">${value ?? '-'}</span>
                        </div>
                    `).join('');

                    const listOrEmpty = (items, formatter) => {
                        return items.length
                            ? `<ul class="list-disc pl-5 space-y-1 text-xs sm:text-sm text-gray-300">${items.map(formatter).join('')}</ul>`
                            : '<p class="text-xs text-gray-500">Tidak ada data</p>';
                    };

                    const eduList = listOrEmpty(educations, (e) => `<li>${e.tingkat} - ${e.nama_institusi} (${e.tahun_mulai ?? '-'} - ${e.tahun_selesai ?? '-'})</li>`);
                    const trainingList = listOrEmpty(trainings, (t) => `<li>${t.jenis_pelatihan} - ${t.lembaga_pelatihan} (${t.tahun ?? '-'})</li>`);
                    const documentList = listOrEmpty(documents, (d) => `<li>${d.type ?? '-'} : ${d.file_path ?? '-'}</li>`);

                    return `
                        <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4 space-y-4">
                            <h4 class="text-sm font-semibold text-gray-100">${title}</h4>
                            <div class="space-y-2">${profileMap}</div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Riwayat Pendidikan</p>
                                ${eduList}
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Riwayat Pelatihan</p>
                                ${trainingList}
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Dokumen</p>
                                ${documentList}
                            </div>
                        </div>
                    `;
                }

                function renderDocumentCard(label, contentHtml) {
                    return `
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">${label}</p>
                            ${contentHtml || '<p class="text-xs text-gray-500">Tidak ada</p>'}
                        </div>
                    `;
                }

                function renderDocumentsSection(fotoHtml, ktpHtml, ijazahHtml) {
                    return `
                        <div class="border-t border-gray-700 pt-4 mt-6">
                            <h3 class="font-semibold text-lg mb-3">Dokumen Terunggah</h3>
                            <div class="grid gap-6 md:grid-cols-3">
                                ${renderDocumentCard('Foto', fotoHtml)}
                                ${renderDocumentCard('KTP', ktpHtml)}
                                ${renderDocumentCard('Ijazah', ijazahHtml)}
                            </div>
                        </div>
                    `;
                }

                window.showDetail = async function (id) {
                    const modal = getEl('detailModal');
                    const body = getEl('modalContent');

                    modal?.classList.remove('hidden');
                    if (body) body.innerHTML = "<p class='text-gray-400'>Memuat data...</p>";

                    try {
                        const res = await fetch(`/admin/ak1/${id}/detail`, { headers: { 'Accept': 'application/json' }});
                        if (!res.ok) throw new Error('HTTP ' + res.status);

                        const data = await res.json();
                        const profile = data.profile || {};
                        const app = data.application || {};

                        const foto = app.foto_closeup
                            ? `<img src="/storage/${app.foto_closeup}" class="w-40 h-48 object-cover rounded-lg border border-gray-600 shadow-md">`
                            : `<div class="w-40 h-48 flex items-center justify-center border border-gray-700 rounded-lg text-gray-500 text-xs">Tidak ada foto</div>`;

                        const ktp = app.ktp_file
                            ? `<a class="text-indigo-400 text-sm underline" href="/storage/${app.ktp_file}" target="_blank">Lihat KTP</a>`
                            : '<p class="text-xs text-gray-500">Tidak ada</p>';

                        const ijazah = app.ijazah_file
                            ? `<a class="text-indigo-400 text-sm underline" href="/storage/${app.ijazah_file}" target="_blank">Lihat Ijazah</a>`
                            : '<p class="text-xs text-gray-500">Tidak ada</p>';

                        const snapshotBlocks = [];
                        if (app.snapshot_before) {
                            snapshotBlocks.push(renderSnapshotBlock(app.type === 'perbaikan' ? 'Data Sebelum Perbaikan' : 'Data Sebelumnya', app.snapshot_before));
                        }
                        if (app.snapshot_after) {
                            snapshotBlocks.push(renderSnapshotBlock(app.type === 'perbaikan' ? 'Data Setelah Perbaikan' : 'Data Saat Pengajuan', app.snapshot_after));
                        }

                        const profileBlock = renderSnapshotBlock('Profil Saat Ini', {
                            profile,
                            educations: data.educations || [],
                            trainings: data.trainings || [],
                            documents: [],
                        });

                        if (body) {
                            body.innerHTML = `
                                <div class="space-y-6">
                                    <div class="flex items-start gap-6 flex-col md:flex-row">
                                        ${foto}
                                        <div class="flex-1 space-y-2">
                                            <h3 class="text-2xl font-semibold text-gray-100">${profile.nama_lengkap ?? '-'}</h3>
                                            <div class="text-sm text-gray-400">${profile.nik ?? '-'}</div>
                                            <div class="text-sm text-gray-400">${profile.alamat_lengkap ?? '-'}</div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">
                                                Status: ${app.status ?? '-'} · Tipe: ${app.type ?? '-'}
                                            </div>
                                        </div>
                                    </div>
                                    ${[profileBlock, ...snapshotBlocks].join('')}
                                    ${renderDocumentsSection(foto, ktp, ijazah)}
                                </div>
                            `;
                        }
                    } catch (error) {
                        if (body) body.innerHTML = `<p class="text-red-400 text-sm">Gagal memuat data. ${error.message}</p>`;
                    }
                };

                window.openLogModal = function (button) {
                    const logModalEl = getEl('logModal');
                    const logModalTitle = getEl('logModalTitle');
                    const logModalSubtitle = getEl('logModalSubtitle');
                    const logModalBody = getEl('logModalBody');

                    const logs = (JSON.parse(button.getAttribute('data-logs') || '[]') || [])
                        .slice()
                        .sort((a, b) => {
                            const aDate = a.timestamp ? new Date(a.timestamp * 1000) : new Date(a.created_at || 0);
                            const bDate = b.timestamp ? new Date(b.timestamp * 1000) : new Date(b.created_at || 0);
                            return aDate - bDate;
                        });

                    const name = button.getAttribute('data-app-name') || 'Pemohon';
                    const email = button.getAttribute('data-app-email') || '';
                    const currentStatus = button.getAttribute('data-current-status') || '-';

                    if (logModalTitle) logModalTitle.textContent = `Riwayat Pengajuan — ${name}`;
                    if (logModalSubtitle) logModalSubtitle.textContent = email ? `${email} · Status saat ini: ${currentStatus}` : `Status saat ini: ${currentStatus}`;

                    if (!logs.length) {
                        if (logModalBody) logModalBody.innerHTML = `<p class="text-sm text-gray-400">Belum ada aktivitas pada pengajuan ini.</p>`;
                    } else if (logModalBody) {
                        const content = logs.map((log, idx) => {
                            const label = actionLabels[log.action] || log.action;
                            const noteSection = log.notes
                                ? `<div class="mt-3 rounded-lg bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                                        <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">Catatan</span>
                                        <p class="mt-1 leading-relaxed">${escapeHtml(log.notes).replace(/\n/g, '<br>')}</p>
                                   </div>`
                                : '';
                            const circleColor = actionColors[log.action] || 'bg-gray-400';
                            const isLast = idx === logs.length - 1;

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
                                        ${noteSection}
                                    </div>
                                </div>
                            `;
                        }).join('');

                        logModalBody.innerHTML = `
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-sm font-semibold text-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Linimasa Pengajuan
                                </div>
                                <div class="space-y-6">${content}</div>
                            </div>
                        `;
                    }

                    logModalEl?.classList.remove('hidden');
                };

                window.closeLogModal = function () {
                    getEl('logModal')?.classList.add('hidden');
                };

                window.openUnapproveModal = function (button) {
                    const modal = getEl('unapproveModal');
                    const form = getEl('unapproveForm');
                    const subtitle = getEl('unapproveModalSubtitle');
                    const notes = getEl('unapproveNotes');

                    if (!modal || !form) return;

                    form.action = button.getAttribute('data-unapprove-url');
                    if (notes) notes.value = '';
                    if (subtitle) {
                        const name = button.getAttribute('data-app-name') || '';
                        const email = button.getAttribute('data-app-email') || '';
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    modal.classList.remove('hidden');
                };

                window.closeUnapproveModal = function () {
                    getEl('unapproveModal')?.classList.add('hidden');
                };

                window.openRejectModal = function (id) {
                    const modal = getEl('rejectModal');
                    const form = getEl('rejectForm');
                    if (!modal || !form) return;
                    form.action = `/admin/ak1/${id}/reject`;
                    modal.classList.remove('hidden');
                };

                window.closeRejectModal = function () {
                    getEl('rejectModal')?.classList.add('hidden');
                };

                window.openRevisionModal = function (id) {
                    const modal = getEl('revisionModal');
                    const form = getEl('revisionForm');
                    if (!modal || !form) return;
                    form.action = `/admin/ak1/${id}/revision`;
                    modal.classList.remove('hidden');
                };

                window.closeRevisionModal = function () {
                    getEl('revisionModal')?.classList.add('hidden');
                };

                const escapeHtml = (unsafe) => (unsafe ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');

                const formatStatus = (status) => statusLabels[status] || status || '-';

                ['detailModal', 'logModal', 'unapproveModal', 'rejectModal', 'revisionModal'].forEach((id) => {
                    const modal = getEl(id);
                    if (!modal) return;
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });
            })();
        </script>
    @endpush
@endonce

</div>
