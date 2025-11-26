<div class="space-y-4">
    <div class="space-y-4">
        @if(!$job)
            <p class="text-slate-300">Data lowongan tidak tersedia.</p>
        @else
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-xl font-semibold text-slate-100">{{ $job->judul }}</h3>
                @php
                    $color = match($job->status) {
                        \App\Models\JobPosting::STATUS_DRAFT => 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                        \App\Models\JobPosting::STATUS_ACTIVE => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                        default => 'bg-slate-500/20 text-slate-300 border-slate-500/40',
                    };
                @endphp
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $color }}">
                    {{ ucfirst($job->status) }}
                </span>
            </div>
            <div class="text-slate-300">
                <span class="font-semibold">Posisi:</span> {{ $job->posisi ?? '-' }} ·
                <span class="font-semibold">Lokasi:</span> {{ $job->lokasi_kerja ?? '-' }}
            </div>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-slate-200">
                <div class="space-y-2">
                    <div><span class="text-slate-400">Pendidikan minimal:</span> {{ $job->pendidikan_minimal ?? '-' }}</div>
                    <div><span class="text-slate-400">Jenis kelamin:</span> {{ $job->jenis_kelamin ?? 'L/P' }}</div>
                    <div><span class="text-slate-400">Rentang usia:</span> {{ $job->usia_min ?? '-' }} - {{ $job->usia_max ?? '-' }}</div>
                </div>
                <div class="space-y-2">
                    <div><span class="text-slate-400">Gaji:</span>
                        @if($job->gaji_min || $job->gaji_max)
                            {{ number_format($job->gaji_min ?? 0) }} - {{ number_format($job->gaji_max ?? 0) }}
                        @else
                            -
                        @endif
                    </div>
                    <div><span class="text-slate-400">Disabilitas:</span> {{ $job->menerima_disabilitas ? 'Menerima' : 'Tidak' }}</div>
                    <div><span class="text-slate-400">Expired:</span> {{ $job->tanggal_expired?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>
            <div class="space-y-3 text-sm text-slate-200">
                <div>
                    <p class="text-slate-400 mb-1">Deskripsi</p>
                    <div class="rounded-lg border border-slate-800 bg-slate-900/60 p-3 whitespace-pre-line">{{ $job->deskripsi ?? '-' }}</div>
                </div>
                <div>
                    <p class="text-slate-400 mb-1">Kualifikasi</p>
                    <div class="rounded-lg border border-slate-800 bg-slate-900/60 p-3 whitespace-pre-line">{{ $job->kualifikasi ?? '-' }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-between w-full">
        <div class="text-xs text-slate-400">
            @if($job)
                Dibuat: {{ $job->created_at?->format('d M Y') ?? '-' }} · Pelamar: {{ $job->applications_count }}
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button type="button"
                    wire:click="closeModal"
                    class="rounded-md border border-slate-700 px-4 py-2 text-sm text-slate-100 hover:bg-slate-800">
                Tutup
            </button>
            @if($job && $job->status === \App\Models\JobPosting::STATUS_DRAFT)
                <button wire:click="publish"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Publikasikan
                </button>
            @elseif($job && $job->status === \App\Models\JobPosting::STATUS_ACTIVE)
                <button wire:click="closeJob"
                        class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">
                    Tutup Lowongan
                </button>
            @endif
        </div>
    </div>
</div>
