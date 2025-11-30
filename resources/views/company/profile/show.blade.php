@extends('layouts.company-sidebar')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        <div class="space-y-3">
            <x-company-breadcrumb :items="[['label' => 'Profil Perusahaan']]" />
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-100">Profil Perusahaan</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Ringkasan data perusahaan yang terdaftar di DisnakerPortal.
                    </p>
                </div>
                <button type="button"
                        onclick="openModal('modalCompanyProfile')"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    Kelola Profil
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 grid gap-6 lg:grid-cols-3">
            <!-- Logo / Foto perusahaan -->
            <div class="w-full flex flex-col items-center gap-4 lg:col-span-1">
                @php
                    $logoPath = $company?->logo ? asset('storage/'.$company->logo) : null;
                    $initial  = $company && $company->nama_perusahaan ? mb_substr($company->nama_perusahaan, 0, 1) : 'P';
                @endphp

                <form id="logo-upload-form"
                      method="POST"
                      action="{{ route('company.profile.logo') }}"
                      enctype="multipart/form-data"
                      class="w-full max-w-sm">
                    @csrf
                    <div class="relative h-40 w-40 lg:h-44 lg:w-44 mx-auto rounded-2xl overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center group">
                        @if($logoPath)
                            <img src="{{ $logoPath }}" alt="Logo Perusahaan" class="h-full w-full object-cover pointer-events-none">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500/80 to-slate-900 pointer-events-none">
                                <span class="text-5xl font-bold text-white">{{ $initial }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 rounded-2xl bg-slate-900/70 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center text-center px-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-indigo-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3l4.5 4.5m-9 0H9.75c.414 0 .75.336.75.75V18m0 0h4.5M12 18v-9" />
                            </svg>
                            <p class="text-xs text-slate-200 font-medium">Klik atau tarik file ke sini</p>
                            <p class="text-[11px] text-slate-400">JPG/PNG, maks. 1 MB</p>
                        </div>
                        <input type="file"
                               name="logo"
                               accept="image/*"
                               class="absolute inset-0 h-full w-full opacity-0 cursor-pointer"
                               onchange="document.getElementById('logo-upload-form').submit()">
                    </div>
                </form>

                <div class="text-center space-y-1">
                    <p class="text-lg font-semibold text-slate-100">
                        {{ $company->nama_perusahaan ?? 'Nama perusahaan belum diisi' }}
                    </p>
                    <p class="text-sm text-slate-400">
                        {{ $company->jenis_usaha ?? 'Jenis usaha/bidang belum diisi' }}
                    </p>
                </div>

            </div>

            <!-- Detail perusahaan -->
            <div class="w-full lg:col-span-2 space-y-4">
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
        <form id="formCompanyProfile" method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
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
            <div class="flex justify-end gap-3 pt-1 pb-1">
                <button type="button" data-close-modal="modalCompanyProfile"
                        class="px-4 py-2 border rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                    Simpan Profil
                </button>
            </div>
        </form>
    </x-modal>

    @if ($errors->any())
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => openModal('modalCompanyProfile'));
            </script>
        @endpush
    @endif
@endsection
