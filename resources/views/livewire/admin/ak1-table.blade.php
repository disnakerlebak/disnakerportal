<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col px-6 py-8 gap-6 box-border">
    <!-- <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Verifikasi Pengajuan AK1
        </h2>
    </div> -->

    {{-- ===== Filter (submit or Enter applies) ===== --}}
    <form wire:submit.prevent="applyFilters" @keydown.enter.prevent="$wire.applyFilters()" class="flex flex-wrap items-center gap-3">
        <input type="text"
               wire:model.defer="search"
               placeholder="Cari nama / email"
               class="w-64 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

        <select wire:model.defer="status" class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            @foreach ($statusOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

        <label class="inline-flex items-center gap-2 text-sm text-slate-200 bg-slate-800/60 px-3 py-2 rounded border border-slate-700">
            <input type="checkbox" wire:model.defer="activeOnly" class="rounded border-slate-600 bg-slate-800">
            <span>Hanya kartu aktif</span>
        </label>

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
    <div class="relative flex-1 min-h-0 flex flex-col rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
        <!-- Overlay loading terpusat + blur konten -->
        <div wire:loading.flex
             wire:target="search,status,type,applyFilters,setType,clearFilters"
             class="absolute inset-0 z-20 items-center justify-center bg-slate-950/30 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-900/70 border border-slate-700 shadow text-indigo-200">
                <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm">Memuat data…</span>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-visible"
             wire:target="search,status,type,applyFilters,setType,clearFilters"
             wire:loading.class="blur-[1px] opacity-60">
            <div class="overflow-x-auto h-full min-h-0 pb-10">
                <div class="h-full min-h-0 overflow-visible">
                    <table class="min-w-full w-full text-sm text-slate-200">
                        <thead class="bg-slate-800 text-slate-200 uppercase text-xs whitespace-nowrap sticky top-0 z-20 border-b border-slate-700 shadow-md shadow-slate-900/30">
                        <tr>
                            <th class="p-3 text-center whitespace-nowrap">Pemohon</th>
                            <th class="p-3 text-center whitespace-nowrap">Status Disabilitas</th>
                            <th class="p-3 text-center whitespace-nowrap">Tipe Pengajuan</th>
                            <th class="p-3 text-center whitespace-nowrap">Status</th>
                            <th class="p-3 text-center whitespace-nowrap">Keterangan</th>
                            <th class="p-3 text-center whitespace-nowrap">Nomor AK/1</th>
                            <th class="p-3 text-center whitespace-nowrap">Ditangani Oleh</th>
                            <th class="p-3 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800 text-slate-200">
                        @forelse ($apps as $app)
                            @php
                                $badgeClass = match($app->status) {
                                    'Menunggu Verifikasi' => 'bg-gray-700 text-gray-300',
                                    'Menunggu Revisi Verifikasi' => 'bg-blue-700 text-blue-100',
                                    'Revisi Diminta' => 'bg-yellow-700 text-yellow-100',
                                    'Batal' => 'bg-orange-700 text-orange-100',
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
                                if (in_array($app->status, ['Ditolak', 'Revisi Diminta', 'Batal']) && $latestLog?->notes) {
                                    $catatan = $latestLog->notes;
                                }

                                $statusDisabilitas = optional($app->user->jobseekerProfile)->status_disabilitas ?? '-';

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
                                    <div class="font-semibold text-white text-sm">{{ $app->user->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $app->created_at->format('d M Y') }}
                                        <span class="text-gray-500">· {{ $app->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>

                                {{-- Status Disabilitas --}}
                                <td class="p-3 align-top text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-800 text-xs text-slate-100 max-w-[12rem] truncate">
                                        {{ $statusDisabilitas }}
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
                                {{-- Status --}}
                                <td class="p-3 align-top text-center">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $app->status }}
                                    </span>
                                </td>


                                {{-- Keterangan (expandable on click) --}}
                                <td class="p-3 align-middle text-left text-sm">
                                    @php
                                        $hasNote = filled($catatan);
                                        $noteColor = $app->status === 'Ditolak' ? 'text-red-400' : (in_array($app->status, ['Revisi Diminta','Batal']) ? 'text-yellow-400' : 'text-gray-400');
                                    @endphp
                                    @if(in_array($app->status, ['Ditolak','Revisi Diminta','Batal']))
                                        @if($hasNote)
                                            <div x-data="{ open:false }" class="{{ $noteColor }} max-w-[38ch]">
                                                <button type="button" @click="open = !open" class="text-left w-full">
                                                    <span class="block whitespace-pre-line"
                                                          :style="open ? '' : '-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;'">
                                                        {{ $catatan }}
                                                    </span>
                                                    <span class="mt-1 inline-block text-xs text-slate-400 hover:text-slate-200 underline">
                                                        <span x-show="!open">Selengkapnya</span>
                                                        <span x-show="open">Tutup</span>
                                                    </span>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Nomor AK/1 + badge aktif/nonaktif --}}
                                <td class="p-3 align-top text-center whitespace-nowrap">
                                    @if($app->nomor_ak1)
                                        <div class="flex flex-col items-center gap-1">
                                            <span>{{ $app->nomor_ak1 }}</span>
                                            @if($app->is_active)
                                                <span class="inline-flex items-center rounded-full bg-green-700/80 text-green-100 px-2 py-0.5 text-[10px] uppercase tracking-wide">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-700 text-slate-300 px-2 py-0.5 text-[10px] uppercase tracking-wide">Nonaktif</span>
                                            @endif
                                        </div>
                                    @else
                                        <span>-</span>
                                    @endif
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
                                        <div class="relative" x-data="dropdownMenu()" x-id="['action-menu']">
                                            <button @click="toggle($event)"
                                                    type="button"
                                                    class="rounded-md border border-slate-700 bg-slate-800 p-2 text-white text-sm transition duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="2">
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
                                                    <button
                                                        type="button"
                                                        onclick="openApproveModal(this)"
                                                        data-approve-url="{{ route('admin.ak1.approve', $app) }}"
                                                        data-app-name="{{ $app->user->name }}"
                                                        data-app-email="{{ $app->user->email }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-green-400 hover:bg-green-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Setujui
                                                    </button>

                                                    <button onclick="openRejectModal({{ $app->id }})"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-700/20 flex items-center gap-2 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Tolak/Minta Revisi
                                                    </button>
                                                @endif

                                                @if ($app->status === 'Disetujui' && $app->is_active)
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

                                                @if ($app->status === 'Disetujui' && $app->nomor_ak1 && $app->is_active)
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
                                            </template>
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

    {{-- ===== Modal Detail (custom overlay) ===== --}}
    <div id="detail-ak1-overlay" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4" onclick="if(event.target === this) closeDetailModal()">
        <div class="modal-panel w-full max-w-5xl shadow-xl overflow-hidden">
            <div class="modal-panel-header flex items-center justify-between px-6 py-3 sticky top-0 z-10">
                <h3 class="text-lg font-semibold text-gray-100">Detail Pemohon AK1</h3>
                <button type="button" onclick="closeDetailModal()" class="modal-close">✕</button>
            </div>
            <div id="modalContent" class="px-6 pb-6 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>

    {{-- ===== Modal Tolak/Minta Revisi (x-modal) ===== --}}
    <x-modal name="reject-ak1" :show="false" maxWidth="md" animation="slide-up" title="Tolak / Minta Revisi">
        <form id="rejectForm" method="POST" class="px-6 py-5">
            @csrf
            <fieldset class="mb-4">
                <legend class="block text-sm mb-2 text-gray-300">Pilih jenis tindakan:</legend>
                <div class="flex gap-4 text-sm text-gray-200">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="reject_mode" value="reject" class="text-red-500" checked>
                        Tolak
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="reject_mode" value="revision" class="text-yellow-500">
                        Minta Revisi
                    </label>
                </div>
            </fieldset>
            <div class="mb-3">
                <label class="block text-sm mb-1">Pilih Alasan Penolakan:</label>
                <select name="reason_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-gray-200">
                    @foreach($rejectionReasons as $reason)
                        <option value="{{ $reason['id'] }}">{{ $reason['title'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-gray-200"></textarea>
                </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-close-modal="reject-ak1" class="px-3 py-1.5 rounded bg-gray-700 hover:bg-gray-600">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded bg-red-700 hover:bg-red-800 text-white">Kirim</button>
            </div>
        </form>
    </x-modal>

    {{-- ===== Modal Riwayat (custom layout, seragam dengan manage pencaker) ===== --}}
    <div id="log-ak1-overlay" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4" onclick="if(event.target === this) closeLogModal()">
        <div class="modal-panel w-full max-w-4xl shadow-xl overflow-hidden">
            <div class="modal-panel-header flex items-start justify-between px-6 py-4 sticky top-0 z-10">
                <div>
                    <h3 class="text-lg font-semibold text-gray-100" id="logModalTitle">Riwayat Pengajuan</h3>
                    <p class="text-sm text-gray-400 mt-1" id="logModalSubtitle"></p>
                </div>
                <button type="button" onclick="closeLogModal()" class="modal-close">✕</button>
            </div>
            <div id="logModalBody" class="px-6 py-5 max-h-[75vh] overflow-y-auto space-y-4"></div>
        </div>
    </div>

    {{-- ===== Modal Batalkan Persetujuan (x-modal) ===== --}}
    <x-modal name="unapprove-ak1" :show="false" maxWidth="lg" animation="slide-up" title="Batalkan Persetujuan">
        <div class="px-6 pt-2 text-sm text-gray-400" id="unapproveModalSubtitle"></div>
        <form id="unapproveForm" method="POST" class="px-6 pb-5 space-y-4">
            @csrf
            <p class="text-sm text-gray-300 leading-relaxed">
                Apakah Anda yakin ingin membatalkan persetujuan pengajuan ini? Status akan diubah menjadi
                <span class="font-semibold text-orange-300">Batal</span> dan nomor AK1 akan dinonaktifkan. Pemohon dapat memperbarui data dan mengajukan ulang.
            </p>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1" for="unapproveNotes">Catatan (opsional)</label>
                <textarea name="notes" id="unapproveNotes" rows="4"
                          class="w-full resize-none bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
                          placeholder="Catat alasan pembatalan jika diperlukan..."></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="unapprove-ak1" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 transition text-sm font-semibold text-white">Ya, Batalkan Persetujuan</button>
            </div>
        </form>
    </x-modal>

    {{-- ===== Modal Setujui (x-modal) ===== --}}
    <x-modal name="approve-ak1" :show="false" maxWidth="md" animation="slide-up" title="Konfirmasi Persetujuan">
        <div class="px-6 pt-2 text-sm text-gray-400" id="approveModalSubtitle"></div>
        <form id="approveForm" method="POST" class="px-6 pb-5 space-y-4">
            @csrf
            <p class="text-sm text-gray-300 leading-relaxed">
                Sebelum menyetujui pastikan data diri, riwayat pendidikan sudah sesuai dengan dokumen yang telah diunggah oleh pencaker.
            </p>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1" for="approveNotes">Catatan (opsional)</label>
                <textarea name="notes" id="approveNotes" rows="4"
                          class="w-full resize-none bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400"
                          placeholder="Catatan untuk pemohon..."></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="approve-ak1" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 transition text-sm font-semibold text-white">Ya, Setujui</button>
            </div>
        </form>
    </x-modal>

    {{-- (Modal Revisi dihapus karena fungsi digabung dalam modal Tolak) --}}

@once
    @push('scripts')
        <script>
            (() => {
                // Helper dropdown agar bisa flip ke atas saat dekat tepi bawah
                window.dropdownMenu = function () {
                    return {
                        open: false,
                        dropUp: false,
                        style: '',
                        width: 224, // w-56
                        init() {
                            window.addEventListener('close-dropdowns', () => { this.open = false; });
                        },
                        toggle(e) {
                            this.open = !this.open;
                            if (this.open) {
                                const rect = e.currentTarget.getBoundingClientRect();
                                const spaceBelow = window.innerHeight - rect.bottom;
                                this.dropUp = spaceBelow < 240; // flip jika ruang bawah sedikit

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
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'detail-ak1' }));
                };

                function renderSnapshotBlock(title, snapshot, includeDocuments = true) {
                    if (!snapshot) return '';

                    const profile = snapshot.profile || {};
                    const educations = Array.isArray(snapshot.educations) ? snapshot.educations : [];
                    const trainings = Array.isArray(snapshot.trainings) ? snapshot.trainings : [];
                    const documents = Array.isArray(snapshot.documents) ? snapshot.documents : [];

                    // Hanya tampilkan field yang tercantum di kartu AK1
                    const profileMap = [
                        ['NIK', profile.nik],
                        ['Nama Lengkap', profile.nama_lengkap],
                        ['Tempat Lahir', profile.tempat_lahir],
                        ['Tanggal Lahir', profile.tanggal_lahir],
                        ['Jenis Kelamin', profile.jenis_kelamin],
                        ['Status', profile.status_perkawinan],
                        ['Agama', profile.agama],
                        ['Status Disabilitas', profile.status_disabilitas],
                        ['Alamat Domisili', profile.alamat_lengkap],
                    ].map(([label, value]) => `
                        <div class="flex text-xs sm:text-sm text-gray-300">
                            <span class="w-40 text-gray-400">${label}</span>
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
                            ${includeDocuments ? `
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Dokumen</p>
                                ${documentList}
                            </div>` : ''}
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

                function renderDocumentsSection(fotoHtml, ktpHtml, ijazahHtml, sertifikatHtml = '') {
                    const hasSertifikat = !!sertifikatHtml;
                    return `
                        <div class="border-t border-gray-700 pt-4 mt-6">
                            <h3 class="font-semibold text-lg mb-3">Dokumen Terunggah</h3>
                            <div class="grid gap-6 ${hasSertifikat ? 'md:grid-cols-4' : 'md:grid-cols-3'}">
                                ${renderDocumentCard('Foto', fotoHtml)}
                                ${renderDocumentCard('KTP', ktpHtml)}
                                ${renderDocumentCard('Ijazah', ijazahHtml)}
                                ${hasSertifikat ? renderDocumentCard('Sertifikat Keahlian', sertifikatHtml) : ''}
                            </div>
                        </div>
                    `;
                }

                // Hanya untuk KTP & Ijazah (tanpa foto) + opsional Sertifikat Keahlian
                function renderKtpIjazahSection(ktpHtml, ijazahHtml, sertifikatHtml = '') {
                    const hasSertifikat = !!sertifikatHtml;
                    return `
                        <div class="border-t border-gray-700 pt-4 mt-6">
                            <h3 class="font-semibold text-lg mb-3">Dokumen</h3>
                            <div class="grid gap-6 ${hasSertifikat ? 'md:grid-cols-3' : 'md:grid-cols-2'}">
                                ${renderDocumentCard('KTP', ktpHtml)}
                                ${renderDocumentCard('Ijazah', ijazahHtml)}
                                ${hasSertifikat ? renderDocumentCard('Sertifikat Keahlian', sertifikatHtml) : ''}
                            </div>
                        </div>
                    `;
                }

                // Buat thumbnail untuk gambar; jika pdf maka tampilkan tautan saja
                function renderThumbnail(path, alt, sizeClass = 'w-full h-40 md:h-48') {
                    if (!path) return '';
                    const lower = (path || '').toLowerCase();
                    const isImage = ['.jpg', '.jpeg', '.png', '.gif', '.webp'].some(ext => lower.endsWith(ext));
                    const url = `/storage/${path}`;
                    if (isImage) {
                        return `<a href="${url}" target="_blank">
                                    <img src="${url}" alt="${alt}" class="${sizeClass} object-contain rounded-lg border border-gray-600 shadow-md bg-gray-800/40" />
                                </a>`;
                    }
                    if (lower.endsWith('.pdf')) {
                        return `<a class="inline-flex items-center gap-2 px-2 py-1.5 rounded bg-slate-800 border border-slate-700 text-indigo-300 text-xs" href="${url}" target="_blank">
                                    <span class="inline-block h-2 w-2 rounded-full bg-red-400"></span> Lihat PDF
                                </a>`;
                    }
                    return `<a class="text-indigo-400 text-sm underline" href="${url}" target="_blank">Lihat Dokumen</a>`;
                }

                window.closeDetailModal = function () {
                    const overlay = getEl('detail-ak1-overlay');
                    if (overlay) overlay.classList.add('hidden');
                };

                window.showDetail = async function (id) {
                    // tutup semua dropdown aksi lebih dulu
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    const body = getEl('modalContent');
                    const overlay = getEl('detail-ak1-overlay');
                    if (overlay) overlay.classList.remove('hidden');
                    if (body) body.innerHTML = "<p class='text-gray-400'>Memuat data...</p>";

                    try {
                        const res = await fetch(`/admin/ak1/${id}/detail`, { headers: { 'Accept': 'application/json' }});
                        if (!res.ok) throw new Error('HTTP ' + res.status);

                        const data = await res.json();
                        const profile = data.profile || {};
                        const app = data.application || {};

                        const foto = app.foto_closeup
                            ? renderThumbnail(app.foto_closeup, 'Foto', 'w-56 h-64')
                            : `<div class="w-56 h-64 flex items-center justify-center border border-gray-700 rounded-lg text-gray-500 text-xs bg-slate-900">Tidak ada foto</div>`;

                        const ktp = app.ktp_file ? renderThumbnail(app.ktp_file, 'KTP') : '<p class="text-xs text-gray-500">Tidak ada</p>';
                        const ijazah = app.ijazah_file ? renderThumbnail(app.ijazah_file, 'Ijazah') : '<p class="text-xs text-gray-500">Tidak ada</p>';
                        const sertifikat = app.sertifikat_keahlian ? renderThumbnail(app.sertifikat_keahlian, 'Sertifikat Keahlian') : '';

                        // Siapkan blok snapshot sesuai jenis pengajuan (untuk perbaikan/perpanjangan)
                        const snapshotBlocks = [];
                        const hasBefore = !!app.snapshot_before;
                        const hasAfter  = !!app.snapshot_after;
                        const isPerbaikan = app.type === 'perbaikan';
                        const isPerpanjangan = app.type === 'perpanjangan';
                        const isBaru = app.type === 'baru';

                        if (!isBaru) {
                            if (isPerbaikan && hasBefore && hasAfter) {
                                snapshotBlocks.push(renderSnapshotBlock('Data Sebelum Perbaikan', app.snapshot_before));
                                snapshotBlocks.push(renderSnapshotBlock('Data Setelah Perbaikan', app.snapshot_after));
                            }
                            if (isPerpanjangan && hasBefore && hasAfter) {
                                const same = JSON.stringify(app.snapshot_before) === JSON.stringify(app.snapshot_after);
                                if (!same) {
                                    snapshotBlocks.push(renderSnapshotBlock('Data Sebelumnya', app.snapshot_before));
                                    snapshotBlocks.push(renderSnapshotBlock('Data Saat Pengajuan', app.snapshot_after));
                                }
                            }
                        }

                        const profileBlock = renderSnapshotBlock('Profil Saat Ini', {
                            profile,
                            educations: data.educations || [],
                            trainings: data.trainings || [],
                            documents: [],
                        });

                        const infoHeader = `
                            <div class="mb-4 rounded-xl border border-slate-700 bg-slate-900/80 px-4 py-3 text-xs sm:text-sm text-gray-200">
                                <div class="flex flex-wrap gap-4">
                                    <div>
                                        <div class="text-[10px] uppercase tracking-wide text-gray-400">Status Pengajuan</div>
                                        <div class="font-semibold mt-0.5">${app.status ?? '-'}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase tracking-wide text-gray-400">Tipe</div>
                                        <div class="mt-0.5">${app.type ?? '-'}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase tracking-wide text-gray-400">Nomor AK/1</div>
                                        <div class="mt-0.5">${app.nomor_ak1 ?? '-'}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase tracking-wide text-gray-400">Tanggal Pengajuan</div>
                                        <div class="mt-0.5">${app.tanggal ?? '-'}</div>
                                    </div>
                                </div>
                            </div>
                        `;

                        if (body) {
                            if (isBaru) {
                                // Layout pengajuan baru: foto di kiri, data diri di kanan, lalu riwayat & dokumen (KTP + Ijazah) di bawah
                                const row = (label, value) => `
                                    <div class="flex text-xs sm:text-sm text-gray-300">
                                        <span class="w-40 text-gray-400">${label}</span>
                                        <span class="flex-1">${value ?? '-'}</span>
                                    </div>`;

                                const dataDiriGrid = `
                                    <div class="grid gap-x-8 gap-y-2 sm:grid-cols-2">
                                        ${row('Nama Lengkap', profile.nama_lengkap)}
                                        ${row('NIK', profile.nik)}
                                        ${row('Tempat Lahir', profile.tempat_lahir)}
                                        ${row('Tanggal Lahir', profile.tanggal_lahir)}
                                        ${row('Jenis Kelamin', profile.jenis_kelamin)}
                                        ${row('Agama', profile.agama)}
                                        ${row('Kecamatan', profile.kecamatan ?? profile.domisili_kecamatan)}
                                        ${row('No. HP', profile.no_telepon ?? '-')}
                                        ${row('Status Disabilitas', profile.status_disabilitas)}
                                        ${row('Akun Media Sosial', profile.akun_media_sosial)}
                                        <div class="sm:col-span-2">${row('Email', profile.email_cache ?? '-')}</div>
                                        <div class="sm:col-span-2">${row('Alamat', profile.alamat_lengkap)}</div>
                                    </div>`;

                                const eduList = (data.educations && data.educations.length)
                                    ? `<ul class="list-disc pl-5 space-y-1 text-xs sm:text-sm text-gray-300">${(data.educations || []).map((e) => `<li>${e.tingkat} - ${e.nama_institusi} (${e.tahun_mulai ?? '-'} - ${e.tahun_selesai ?? '-'})</li>`).join('')}</ul>`
                                    : '<p class="text-xs text-gray-500">Tidak ada data</p>';

                                const trainingList = (data.trainings && data.trainings.length)
                                    ? `<ul class="list-disc pl-5 space-y-1 text-xs sm:text-sm text-gray-300">${(data.trainings || []).map((t) => `<li>${t.jenis_pelatihan} - ${t.lembaga_pelatihan} (${t.tahun ?? '-'})</li>`).join('')}</ul>`
                                    : '<p class="text-xs text-gray-500">Tidak ada data</p>';

                                body.innerHTML = `
                                    <div class="space-y-6">
                                        ${infoHeader}
                                        <div class="grid md:grid-cols-[240px,1fr] gap-6">
                                            <div>
                                                <div class="text-sm text-gray-400 mb-2">Foto Close-Up</div>
                                                ${foto}
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold mb-3 text-gray-100">Data Diri</h3>
                                                ${dataDiriGrid}
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-lg font-semibold mb-2 text-gray-100">Riwayat Pendidikan</h3>
                                            ${eduList}
                                        </div>

                                        <div>
                                            <h3 class="text-lg font-semibold mb-2 text-gray-100">Riwayat Pelatihan</h3>
                                            ${trainingList}
                                        </div>

                                        ${renderKtpIjazahSection(ktp, ijazah, sertifikat)}
                                    </div>
                                `;
                            } else {
                                // Perbaikan / Perpanjangan: hanya perbandingan data + dokumen
                                const snapshotHtml = snapshotBlocks.length
                                    ? `<div class="space-y-4">
                                            <h3 class="font-semibold text-lg text-gray-100">Perbandingan Data</h3>
                                            <div class="grid gap-4 md:grid-cols-2">
                                                ${snapshotBlocks.join('')}
                                            </div>
                                       </div>`
                                    : '<p class="text-sm text-gray-400">Tidak ada data perbandingan.</p>';

                                const documentsHtml = renderDocumentsSection(foto, ktp, ijazah, sertifikat);

                                body.innerHTML = `
                                    <div class="space-y-6">
                                        ${infoHeader}
                                        ${snapshotHtml}
                                        ${documentsHtml}
                                    </div>
                                `;
                            }
                        }
                    } catch (error) {
                        if (body) body.innerHTML = `<p class="text-red-400 text-sm">Gagal memuat data. ${error.message}</p>`;
                    }
                };

                window.closeLogModal = function () {
                    const overlay = getEl('log-ak1-overlay');
                    if (overlay) overlay.classList.add('hidden');
                };

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        closeDetailModal();
                        closeLogModal();
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'reject-ak1' }));
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'unapprove-ak1' }));
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'approve-ak1' }));
                    }
                });

                window.openLogModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    const overlay = getEl('log-ak1-overlay');
                    if (overlay) overlay.classList.remove('hidden');
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

                    // shown via x-modal open event
                };

                window.openUnapproveModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'unapprove-ak1' }));
                    const form = getEl('unapproveForm');
                    const subtitle = getEl('unapproveModalSubtitle');
                    const notes = getEl('unapproveNotes');

                    if (!form) return;
                    form.action = button.getAttribute('data-unapprove-url');
                    if (notes) notes.value = '';
                    if (subtitle) {
                        const name = button.getAttribute('data-app-name') || '';
                        const email = button.getAttribute('data-app-email') || '';
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                };

                window.closeUnapproveModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'unapprove-ak1' }));
                };

                window.openApproveModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'approve-ak1' }));
                    const form = getEl('approveForm');
                    const subtitle = getEl('approveModalSubtitle');
                    const notes = getEl('approveNotes');
                    if (!form) return;
                    form.action = button.getAttribute('data-approve-url');
                    if (notes) notes.value = '';
                    if (subtitle) {
                        const name = button.getAttribute('data-app-name') || '';
                        const email = button.getAttribute('data-app-email') || '';
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                };

                window.closeApproveModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'approve-ak1' }));
                };

                window.openRejectModal = function (id) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'reject-ak1' }));
                    const form = getEl('rejectForm');
                    if (!form) return;
                    // default ke penolakan
                    form.action = `/admin/ak1/${id}/reject`;
                    const radios = form.querySelectorAll('input[name="reject_mode"]');
                    radios.forEach(r => r.checked = (r.value === 'reject'));
                    radios.forEach(r => {
                        r.onchange = () => {
                            form.action = (r.value === 'revision') ? `/admin/ak1/${id}/revision` : `/admin/ak1/${id}/reject`;
                        };
                    });
                };

                window.closeRejectModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'reject-ak1' }));
                };

                // fungsi revisi dihapus, digantikan oleh openRejectModal di atas

                const escapeHtml = (unsafe) => (unsafe ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');

                const formatStatus = (status) => statusLabels[status] || status || '-';

                // x-modal already handles overlay click to close
            })();
        </script>
    @endpush
@endonce

</div>
