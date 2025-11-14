@php
    $mapLabel = [
        'approved'   => 'Pengajuan Disetujui',
        'rejected'   => 'Pengajuan Ditolak',
        'resubmit'   => 'Diminta Perbaikan/Revisi',
        'pending'    => 'Pengajuan Diajukan',
        'printed'    => 'Kartu Dicetak',
        'picked_up'  => 'Kartu Diambil',
        'unapproved' => 'Persetujuan Dibatalkan',
    ];

    $mapColor = [
        'approved'   => 'bg-emerald-400',
        'rejected'   => 'bg-rose-400',
        'resubmit'   => 'bg-amber-400',
        'pending'    => 'bg-sky-400',
        'printed'    => 'bg-sky-400',
        'picked_up'  => 'bg-indigo-400',
        'unapproved' => 'bg-orange-400',
    ];
@endphp

<div class="space-y-4">
    @if($histories->isEmpty())
        <div class="text-center py-10 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm">Belum ada riwayat aktivitas.</p>
        </div>
    @else
        <div class="space-y-5 pr-1">
            @foreach($histories as $index => $h)
                @php
                    $statusKey = strtolower($h->status ?? '');
                    $label = $mapLabel[$statusKey] ?? ucfirst($statusKey ?: 'Aktivitas');
                    $color = $mapColor[$statusKey] ?? 'bg-slate-400';
                    $isLast = $index === $histories->count() - 1;

                    $rawText = (string) ($h->keterangan ?? '');
                    // Normalisasi newline: literal "\n" dari DB dan newline asli
                    $normalized = str_replace(["\r\n", "\r", "\\n"], "\n", $rawText);
                    $lines = collect(preg_split("/\n/", trim($normalized)))->filter();
                    $mainLine = $lines->shift();
                @endphp

                <div class="relative pl-8">
                    <span class="absolute left-0 top-2 inline-flex h-3 w-3 rounded-full {{ $color }} ring-4 ring-slate-950"></span>
                    @unless($isLast)
                        <span class="absolute left-1.5 top-5 bottom-0 border-l border-slate-700"></span>
                    @endunless

                    <div class="rounded-xl border border-slate-800 bg-slate-900/70 px-4 py-3 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-100">
                                <span>{{ $label }}</span>
                                @if(($h->type ?? null) === 'activity')
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 uppercase tracking-wide">Aktivitas</span>
                                @elseif(($h->type ?? null) === 'ak1')
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-900/60 text-indigo-200 uppercase tracking-wide">AK1</span>
                                @endif
                            </div>
                            <time class="text-xs text-slate-400">
                                {{ $h->created_at?->format('d M Y, H:i') ?? '-' }}
                            </time>
                        </div>

                        @if($mainLine)
                            <p class="mt-1 text-sm text-slate-200 leading-relaxed">{{ $mainLine }}</p>
                        @endif

                        @if($lines->isNotEmpty())
                            <ul class="mt-2 space-y-1 text-xs text-slate-300">
                                @foreach($lines as $line)
                                    <li class="flex gap-1">
                                        <span class="mt-0.5 h-1 w-1 rounded-full bg-slate-500 flex-shrink-0"></span>
                                        <span>{{ $line }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
