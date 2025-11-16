<div class="space-y-4">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-100">{{ $company->nama_perusahaan ?? 'Perusahaan' }}</h2>
            <p class="mt-1 text-sm text-slate-400">
                Profil perusahaan terdaftar di DisnakerPortal.
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 flex flex-col md:flex-row gap-6">
        <div class="w-full md:w-1/3 flex flex-col items-center md:items-start gap-3">
            @php
                $logoPath = $company->logo ? asset('storage/'.$company->logo) : null;
                $initial  = $company->nama_perusahaan ? mb_substr($company->nama_perusahaan, 0, 1) : 'P';
            @endphp

            <div class="relative h-28 w-28 rounded-2xl overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Logo Perusahaan" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500/80 to-slate-900">
                        <span class="text-3xl font-bold text-white">{{ $initial }}</span>
                    </div>
                @endif
            </div>

            <div class="text-center md:text-left">
                <p class="text-base font-semibold text-slate-100">
                    {{ $company->nama_perusahaan ?? '-' }}
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $company->jenis_usaha ?? '-' }}
                </p>
            </div>
        </div>

        <div class="w-full md:w-2/3 space-y-4">
            <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4 grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat</p>
                    <p class="mt-2 text-sm text-slate-200">
                        {{ $company->alamat_lengkap ?? '-' }}<br>
                        @if($company->kecamatan || $company->kabupaten || $company->provinsi)
                            <span class="text-xs text-slate-400">
                                {{ $company->kecamatan ?? '' }}{{ $company->kecamatan ? ', ' : '' }}
                                {{ $company->kabupaten ?? '' }}{{ $company->kabupaten ? ', ' : '' }}
                                {{ $company->provinsi ?? '' }}
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kontak</p>
                    <p class="mt-2 text-sm text-slate-200">
                        Telepon: {{ $company->telepon ?? '-' }}<br>
                        Email: {{ $company->email ?? $company->user->email ?? '-' }}<br>
                        Website: {{ $company->website ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4 grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Legalitas</p>
                    <p class="mt-2 text-sm text-slate-200">
                        NIB: {{ $company->nib ?? '-' }}<br>
                        NPWP: {{ $company->npwp ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Skala Perusahaan</p>
                    <p class="mt-2 text-sm text-slate-200">
                        Jumlah Karyawan: {{ $company->jumlah_karyawan ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Media Sosial</p>
                <div class="mt-2 grid gap-3 md:grid-cols-2 text-sm text-slate-200">
                    <p>Facebook: {{ $company->social_facebook ?? '-' }}</p>
                    <p>Instagram: {{ $company->social_instagram ?? '-' }}</p>
                    <p>LinkedIn: {{ $company->social_linkedin ?? '-' }}</p>
                    <p>Twitter/X: {{ $company->social_twitter ?? '-' }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Deskripsi Perusahaan</p>
                <p class="mt-2 text-sm text-slate-200 whitespace-pre-line">
                    {{ $company->deskripsi ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</div>
