<div>
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input
                        type="text"
                        wire:model.debounce.300ms="search"
                        placeholder="Cari judul / posisi / lokasi..."
                        class="w-64 rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    />
                    <span class="absolute right-3 top-2.5 text-slate-500 text-sm">⌕</span>
                </div>
                <select
                    wire:model="status"
                    class="rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="all">Semua status</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>

            <button
               type="button"
               wire:click="create"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                + Tambah Lowongan
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900/60 shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-slate-100">
                    <thead class="bg-slate-900/80 text-slate-400 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Judul / Posisi</th>
                    <th class="px-4 py-3 text-left">Jenis / Model Kerja</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Batas Waktu</th>
                    <th class="px-4 py-3 text-left">Pelamar</th>
                    <th class="px-4 py-3 text-left"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                @forelse($jobs as $job)
                            <tr class="hover:bg-slate-800/40">
                                <td class="px-4 py-3">
                                    <div class="font-semibold">{{ $job->judul ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">{{ $job->posisi ?? 'Posisi tidak diisi' }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-200">
                                    @php
                                        $tipe = $job->tipe_pekerjaan ?? null;
                                        $model = $job->model_kerja ?? null;
                                        $modelColor = match($model) {
                                            'WFO' => 'bg-sky-600/20 text-sky-200 border-sky-500/40',
                                            'WFH/Remote' => 'bg-purple-600/20 text-purple-200 border-purple-500/40',
                                            'Hybrid' => 'bg-amber-600/20 text-amber-200 border-amber-500/40',
                                            default => 'bg-slate-700/30 text-slate-200 border-slate-600/40',
                                        };
                                    @endphp
                                    <div class="flex flex-col gap-1 leading-tight">
                    <span class="{{ $tipe ? 'text-[13px] font-semibold text-slate-100' : 'text-slate-500 text-[12px]' }}">
                        {{ $tipe ?? '-' }}
                    </span>
                    <div class="flex flex-wrap gap-1 text-[10px]">
                        @if($model)
                            <span class="inline-flex items-center px-2 py-1 rounded border {{ $modelColor }}">{{ $model }}</span>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-200">{{ $job->lokasi_kerja ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $color = match($job->status) {
                                            \App\Models\JobPosting::STATUS_DRAFT => 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                                            \App\Models\JobPosting::STATUS_ACTIVE => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                                            default => 'bg-slate-500/20 text-slate-300 border-slate-500/40',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded border px-2.5 py-1 text-xs font-semibold {{ $color }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($job->tanggal_expired)
                                        @php
                                            $daysRaw = now()->startOfDay()->diffInDays(optional($job->tanggal_expired)->startOfDay(), false);
                                            $days = (int) round($daysRaw);
                                        @endphp
                                        <div>{{ $job->tanggal_expired->format('d M Y') }}</div>
                                        <div class="text-xs {{ $days <= 7 ? 'text-amber-300' : 'text-slate-400' }}">
                                            {{ $days >= 0 ? "Sisa {$days} hari" : 'Sudah kedaluwarsa' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold">{{ $job->applications_count }}</div>
                                    <div class="text-xs text-slate-400">pelamar</div>
                                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2 relative">
                        @if($job->status === \App\Models\JobPosting::STATUS_DRAFT)
                            <button
                                type="button"
                                wire:click="confirmAction('publish', {{ $job->id }})"
                                data-tooltip-target="tooltip-publish-{{ $job->id }}"
                                data-tooltip-placement="top"
                                aria-describedby="tooltip-publish-{{ $job->id }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 -rotate-[15deg]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                            <div id="tooltip-publish-{{ $job->id }}" role="tooltip"
                                 class="absolute z-50 inline-block px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow opacity-0 invisible tooltip">
                                Publikasikan lowongan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @elseif($job->status === \App\Models\JobPosting::STATUS_ACTIVE)
                            <button
                                type="button"
                                wire:click="confirmAction('close', {{ $job->id }})"
                                data-tooltip-target="tooltip-close-{{ $job->id }}"
                                data-tooltip-placement="top"
                                aria-describedby="tooltip-close-{{ $job->id }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-amber-600 text-white hover:bg-amber-700 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div id="tooltip-close-{{ $job->id }}" role="tooltip"
                                 class="absolute z-50 inline-block px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow opacity-0 invisible tooltip">
                                Tutup lowongan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @else
                            <button
                                type="button"
                                wire:click="confirmAction('reopen', {{ $job->id }})"
                                data-tooltip-target="tooltip-reopen-{{ $job->id }}"
                                data-tooltip-placement="top"
                                aria-describedby="tooltip-reopen-{{ $job->id }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m-7-7l7 7-7 7" />
                                </svg>
                            </button>
                            <div id="tooltip-reopen-{{ $job->id }}" role="tooltip"
                                 class="absolute z-50 inline-block px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow opacity-0 invisible tooltip">
                                Buka lagi lowongan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @endif

                        <x-dropdown :id="'job-actions-'.$job->id">
                            <x-dropdown-item wire:click="preview({{ $job->id }})"
                                             class="text-slate-100 hover:text-white">
                                Preview
                            </x-dropdown-item>
                            <x-dropdown-item wire:click="edit({{ $job->id }})"
                                             class="text-slate-100 hover:text-white">
                                Edit
                            </x-dropdown-item>

                            <x-dropdown-item wire:click="confirmAction('delete', {{ $job->id }})"
                                             class="text-rose-200 hover:text-rose-100">
                                Hapus
                            </x-dropdown-item>
                        </x-dropdown>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                                    Belum ada lowongan. Mulai dengan tombol "Tambah Lowongan".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jobs instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="border-t border-slate-800 bg-slate-900/80 px-4 py-3">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
