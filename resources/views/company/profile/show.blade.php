@extends('layouts.company-sidebar')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-100">Profil Perusahaan</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Ringkasan data perusahaan yang terdaftar di DisnakerPortal.
                </p>
            </div>
            <button type="button"
                    data-modal-target="modalCompanyProfile"
                    data-modal-toggle="modalCompanyProfile"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                Kelola Profil
            </button>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 flex flex-col md:flex-row gap-6">
            <!-- Logo / Foto perusahaan -->
            <div class="w-full md:w-1/3 flex flex-col items-center md:items-start gap-3">
                @php
                    $logoPath = $company?->logo ? asset('storage/'.$company->logo) : null;
                    $initial  = $company && $company->nama_perusahaan ? mb_substr($company->nama_perusahaan, 0, 1) : 'P';
                @endphp

                <div class="relative h-32 w-32 rounded-2xl overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center">
                    @if($logoPath)
                        <img src="{{ $logoPath }}" alt="Logo Perusahaan" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500/80 to-slate-900">
                            <span class="text-4xl font-bold text-white">{{ $initial }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-center md:text-left">
                    <p class="text-base font-semibold text-slate-100">
                        {{ $company->nama_perusahaan ?? 'Nama perusahaan belum diisi' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ $company->jenis_usaha ?? 'Jenis usaha/bidang belum diisi' }}
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('company.profile.logo') }}"
                      enctype="multipart/form-data"
                      class="mt-4 w-full space-y-2">
                    @csrf
                    <x-input-file
                        label="Ganti Logo Perusahaan"
                        name="logo"
                    />
                    <p class="text-[11px] text-slate-500">
                        Format: JPG/PNG, maks. 1 MB
                    </p>
                    <x-primary-button class="w-full md:w-auto">
                        Simpan Logo
                    </x-primary-button>
                </form>
            </div>

            <!-- Detail perusahaan -->
            <div class="w-full md:w-2/3 space-y-4">
                <!-- Card: Alamat & Kontak -->
                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat</p>
                        <p class="mt-2 text-sm text-slate-200">
                            {{ $company->alamat_lengkap ?? '-' }}<br>
                            @if($company && (!empty($company->kecamatan) || !empty($company->kabupaten) || !empty($company->provinsi)))
                                {{ $company->kecamatan ?? '' }}{{ $company->kecamatan ? ', ' : '' }}
                                {{ $company->kabupaten ?? '' }}{{ $company->kabupaten ? ', ' : '' }}
                                {{ $company->provinsi ?? '' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kontak</p>
                        <p class="mt-2 text-sm text-slate-200">
                            Telepon: {{ $company->telepon ?? '-' }}<br>
                            Email: {{ $company->email ?? auth()->user()->email }}<br>
                            Website: {{ $company->website ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Card: Legalitas & Skala -->
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

                <!-- Card: Media Sosial -->
                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Media Sosial</p>
                    <div class="mt-2 grid gap-3 md:grid-cols-2 text-sm text-slate-200">
                        <p>Facebook: {{ $company->social_facebook ?? '-' }}</p>
                        <p>Instagram: {{ $company->social_instagram ?? '-' }}</p>
                        <p>LinkedIn: {{ $company->social_linkedin ?? '-' }}</p>
                        <p>Twitter/X: {{ $company->social_twitter ?? '-' }}</p>
                    </div>
                </div>

                <!-- Card: Deskripsi -->
                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Deskripsi Perusahaan</p>
                    <p class="mt-2 text-sm text-slate-200 whitespace-pre-line">
                        {{ $company->deskripsi ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Kelola Profil Perusahaan --}}
    <x-modal id="modalCompanyProfile" size="lg" title="Lengkapi Profil Perusahaan">
        <form id="formCompanyProfile" method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <x-input-text
                    label="Nama Perusahaan"
                    name="nama_perusahaan"
                    :value="$company->nama_perusahaan ?? ''"
                    required
                />

                <x-input-text
                    label="Jenis Usaha / Bidang"
                    name="jenis_usaha"
                    :value="$company->jenis_usaha ?? ''"
                />
            </div>

            <div class="space-y-2">
                <label class="block text-sm text-gray-500 dark:text-gray-300">
                    Alamat Lengkap
                </label>
                <textarea
                    name="alamat_lengkap"
                    rows="3"
                    class="w-full rounded-lg border-gray-700 bg-gray-900 text-gray-100 focus:ring-2 focus:ring-indigo-500"
                    required
                >{{ old('alamat_lengkap', $company->alamat_lengkap ?? '') }}</textarea>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <x-input-text
                    label="Kecamatan"
                    name="kecamatan"
                    :value="$company->kecamatan ?? ''"
                />
                <x-input-text
                    label="Kabupaten/Kota"
                    name="kabupaten"
                    :value="$company->kabupaten ?? ''"
                />
                <x-input-text
                    label="Provinsi"
                    name="provinsi"
                    :value="$company->provinsi ?? ''"
                />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <x-input-text
                    label="Telepon Kantor"
                    name="telepon"
                    :value="$company->telepon ?? ''"
                />
                <x-input-text
                    label="Email Perusahaan"
                    name="email"
                    type="email"
                    :value="$company->email ?? auth()->user()->email"
                />
                <x-input-text
                    label="Website"
                    name="website"
                    :value="$company->website ?? ''"
                    placeholder="https://"
                />
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <x-input-text
                    label="Facebook"
                    name="social_facebook"
                    :value="$company->social_facebook ?? ''"
                />
                <x-input-text
                    label="Instagram"
                    name="social_instagram"
                    :value="$company->social_instagram ?? ''"
                />
                <x-input-text
                    label="LinkedIn"
                    name="social_linkedin"
                    :value="$company->social_linkedin ?? ''"
                />
                <x-input-text
                    label="Twitter / X"
                    name="social_twitter"
                    :value="$company->social_twitter ?? ''"
                />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <x-input-text
                    label="Jumlah Karyawan (perkiraan)"
                    name="jumlah_karyawan"
                    type="number"
                    :value="$company->jumlah_karyawan ?? ''"
                />
                <x-input-text
                    label="NIB"
                    name="nib"
                    :value="$company->nib ?? ''"
                />
                <x-input-text
                    label="NPWP Perusahaan"
                    name="npwp"
                    :value="$company->npwp ?? ''"
                />
            </div>

            <div>
                <label class="block text-sm text-gray-500 dark:text-gray-300">
                    Deskripsi Singkat Perusahaan
                </label>
                <textarea
                    name="deskripsi"
                    rows="4"
                    class="mt-1 w-full rounded-lg border-gray-700 bg-gray-900 text-gray-100 focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ceritakan secara singkat tentang perusahaan, budaya kerja, dan produk/layanan utama."
                >{{ old('deskripsi', $company->deskripsi ?? '') }}</textarea>
            </div>
        </form>

        <x-slot name="footer">
            <button type="button" data-modal-hide="modalCompanyProfile"
                    class="px-4 py-2 border rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600">
                Batal
            </button>
            <button type="submit" form="formCompanyProfile"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                Simpan Profil
            </button>
        </x-slot>
    </x-modal>
@endsection
