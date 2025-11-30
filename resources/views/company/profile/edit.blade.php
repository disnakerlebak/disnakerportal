@extends('layouts.company-sidebar')

@section('title', 'Lengkapi Profil Perusahaan')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">
        <div class="space-y-3">
            <x-company-breadcrumb :items="[
                ['label' => 'Profil Perusahaan', 'url' => route('company.profile.show')],
                ['label' => 'Lengkapi Profil']
            ]" />
            <div>
                <h2 class="text-2xl font-semibold text-slate-100">Lengkapi Profil Perusahaan</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Data ini akan digunakan oleh Disnaker dan pencari kerja untuk mengenal perusahaan Anda.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('company.profile.update') }}" class="space-y-6">
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

            <div class="space-y-4">
                <x-input-text
                    label="Alamat Lengkap"
                    name="alamat_lengkap"
                    :value="$company->alamat_lengkap ?? ''"
                    required
                />

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

            <div class="space-y-4">
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
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('company.profile.show') }}"
                   class="inline-flex items-center rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800">
                    Batal
                </a>
                <x-primary-button>
                    Simpan Profil
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
