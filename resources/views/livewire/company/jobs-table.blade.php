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
                            <th class="px-4 py-3 text-left">Lokasi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Expired</th>
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
                                <td class="px-4 py-3 text-slate-200">{{ $job->lokasi_kerja ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $color = match($job->status) {
                                            \App\Models\JobPosting::STATUS_DRAFT => 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                                            \App\Models\JobPosting::STATUS_ACTIVE => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                                            default => 'bg-slate-500/20 text-slate-300 border-slate-500/40',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $color }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($job->tanggal_expired)
                                        <div>{{ $job->tanggal_expired->format('d M Y') }}</div>
                                        @php
                                            $days = now()->diffInDays($job->tanggal_expired, false);
                                        @endphp
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
                    <div class="flex items-center justify-end">
                        <x-dropdown :id="'job-actions-'.$job->id">
                            <x-dropdown-item wire:click="preview({{ $job->id }})"
                                             class="text-slate-100 hover:text-white">
                                Preview
                            </x-dropdown-item>
                            <x-dropdown-item wire:click="edit({{ $job->id }})"
                                             class="text-slate-100 hover:text-white">
                                Edit
                            </x-dropdown-item>

                            @if($job->status === \App\Models\JobPosting::STATUS_DRAFT)
                                <x-dropdown-item wire:click="confirmAction('publish', {{ $job->id }})"
                                                 class="text-emerald-200 hover:text-emerald-100">
                                    Publikasikan
                                </x-dropdown-item>
                            @elseif($job->status === \App\Models\JobPosting::STATUS_ACTIVE)
                                <x-dropdown-item wire:click="confirmAction('close', {{ $job->id }})"
                                                 class="text-amber-200 hover:text-amber-100">
                                    Tutup
                                </x-dropdown-item>
                            @else
                                <x-dropdown-item wire:click="confirmAction('reopen', {{ $job->id }})"
                                                 class="text-emerald-200 hover:text-emerald-100">
                                    Buka Lagi
                                </x-dropdown-item>
                            @endif

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
