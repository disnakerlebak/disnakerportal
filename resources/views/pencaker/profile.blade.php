@extends('layouts.pencaker')
@section('title', 'Profil Pencaker')

@section('content')
@php
    $accordionMap = [
        'profile' => 1,
        'education' => 2,
        'training' => 3,
        'work' => 4,
        'preference' => 5,
    ];
    $currentAccordion = old('__accordion') ?? session('accordion') ?? 'profile';
    $openDefault = $accordionMap[$currentAccordion] ?? 1;
    $locked = $isLocked ?? false;
@endphp

<div class="max-w-6xl mx-auto py-8 px-4" x-data="{ open: {{ $openDefault }} }">

    <h1 class="text-2xl font-semibold text-gray-100 mb-6">Profil Pencaker</h1>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-600/20 border border-green-600 text-green-100 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-600/20 border border-red-600 text-red-100 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3">
            <div class="font-semibold mb-1">Periksa data berikut:</div>
            <ul class="list-disc ms-5 space-y-1">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-4">

        <!-- Data Diri -->
        <div class="bg-gray-800 rounded-xl shadow">
            <button @click="open === 1 ? open = null : open = 1"
                    class="w-full text-left px-5 py-4 font-semibold text-gray-200 flex justify-between items-center">
                <span>Data Diri</span>
                <svg :class="{'rotate-180': open === 1}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 1" x-collapse class="px-6 pb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-100">Data Diri Pencari Kerja</h2>
                    @if ($locked)
                        <button id="openEdit" disabled
                                title="Terkunci karena pengajuan AK1 sedang diproses/diterima"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gray-600 cursor-not-allowed">
                            Edit Profil (Terkunci)
                        </button>
                    @else
                        <button id="openEdit"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                            Edit Profil
                        </button>
                    @endif
                </div>

                @if ($locked)
                    <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3">
                        Pengeditan data diri terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                @endif

                @php
                    $fields = [
                        'Nama Lengkap' => $profile->nama_lengkap ?? '-',
                        'NIK' => $profile->nik ?? '-',
                        'Tempat Lahir' => $profile->tempat_lahir ?? '-',
                        'Tanggal Lahir' => $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-',
                        'Jenis Kelamin' => $profile->jenis_kelamin ?? '-',
                        'Agama' => $profile->agama ?? '-',
                        'Status Perkawinan' => $profile->status_perkawinan ?? '-',
                        'Pendidikan Terakhir' => $profile->pendidikan_terakhir ?? '-',
                        'Alamat Lengkap' => $profile->alamat_lengkap ?? '-',
                        'Domisili Kecamatan' => $profile->domisili_kecamatan ?? '-',
                        'No. Telepon' => $profile->no_telepon ?? '-',
                    ];
                @endphp

                <div class="bg-gray-900/40 rounded-lg border border-gray-700/60">
                    <table class="w-full text-gray-100">
                        <tbody>
                            @foreach ($fields as $label => $value)
                                <tr class="border-b border-gray-700/40">
                                    <td class="py-2 px-4 font-semibold text-gray-300 w-1/3">{{ $label }}</td>
                                    <td class="py-2 px-4 text-gray-100">: {{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pendidikan -->
        <div class="bg-gray-800 rounded-xl shadow">
            <button @click="open === 2 ? open = null : open = 2"
                    class="w-full text-left px-5 py-4 font-semibold text-gray-200 flex justify-between items-center">
                <span>Riwayat Pendidikan</span>
                <svg :class="{'rotate-180': open === 2}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 2" x-collapse class="px-6 pb-6 space-y-4">
                @if ($locked)
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pendidikan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                @endif

                @if ($locked)
                    <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg cursor-not-allowed">
                        + Tambah Pendidikan (Terkunci)
                    </button>
                @else
                    <button data-modal-open="modalEducationCreate"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        + Tambah Pendidikan
                    </button>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-600 rounded-lg">
                        <thead class="bg-gray-700 text-gray-100">
                            <tr>
                                <th class="border border-gray-600 p-2 text-left">Tingkat</th>
                                <th class="border border-gray-600 p-2 text-left">Nama Sekolah / Institusi</th>
                                <th class="border border-gray-600 p-2 text-left">Jurusan</th>
                                <th class="border border-gray-600 p-2 text-center">Tahun</th>
                                <th class="border border-gray-600 p-2 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200">
                            @forelse ($educations as $edu)
                                <tr class="border-t border-gray-700">
                                    <td class="border border-gray-700 p-2">{{ $edu->tingkat }}</td>
                                    <td class="border border-gray-700 p-2">{{ $edu->nama_institusi }}</td>
                                    <td class="border border-gray-700 p-2 text-center">{{ $edu->jurusan ?: '-' }}</td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        {{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}
                                    </td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        @if ($locked)
                                            <span class="text-gray-400">Terkunci</span>
                                        @else
                                            <a href="javascript:void(0)"
                                               data-modal-open="modalEducationEdit{{ $edu->id }}"
                                               class="text-yellow-400 hover:text-yellow-300 mr-2">
                                                Edit
                                            </a>
                                            <button type="button"
                                                    class="text-red-500 hover:text-red-400"
                                                    data-delete-modal="modalEducationDelete"
                                                    data-action="{{ route('pencaker.education.destroy', $edu) }}">
                                                Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400">
                                        Belum ada data pendidikan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pelatihan -->
        <div class="bg-gray-800 rounded-xl shadow">
            <button @click="open === 3 ? open = null : open = 3"
                    class="w-full text-left px-5 py-4 font-semibold text-gray-200 flex justify-between items-center">
                <span>Riwayat Pelatihan</span>
                <svg :class="{'rotate-180': open === 3}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 3" x-collapse class="px-6 pb-6 space-y-4">
                @if ($locked)
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pelatihan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                @endif

                @if ($locked)
                    <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg cursor-not-allowed">
                        + Tambah Pelatihan (Terkunci)
                    </button>
                @else
                    <button data-modal-open="modalTrainingCreate"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        + Tambah Pelatihan
                    </button>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-600 rounded-lg">
                        <thead class="bg-gray-700 text-gray-100">
                            <tr>
                                <th class="border border-gray-600 p-2 text-left">Jenis Pelatihan</th>
                                <th class="border border-gray-600 p-2 text-left">Lembaga</th>
                                <th class="border border-gray-600 p-2 text-center">Tahun</th>
                                <th class="border border-gray-600 p-2 text-center">Sertifikat</th>
                                <th class="border border-gray-600 p-2 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200">
                            @forelse ($trainings as $train)
                                <tr class="border-t border-gray-700">
                                    <td class="border border-gray-700 p-2">{{ $train->jenis_pelatihan }}</td>
                                    <td class="border border-gray-700 p-2">{{ $train->lembaga_pelatihan }}</td>
                                    <td class="border border-gray-700 p-2 text-center">{{ $train->tahun }}</td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        @if ($train->sertifikat_file)
                                            <a href="{{ asset('storage/'.$train->sertifikat_file) }}"
                                               target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        @if ($locked)
                                            <span class="text-gray-400">Terkunci</span>
                                        @else
                                            <a href="javascript:void(0)"
                                               data-modal-open="modalTrainingEdit{{ $train->id }}"
                                               class="text-yellow-400 hover:text-yellow-300 mr-2">
                                                Edit
                                            </a>
                                            <button type="button"
                                                    class="text-red-500 hover:text-red-400"
                                                    data-delete-modal="modalTrainingDelete"
                                                    data-action="{{ route('pencaker.training.destroy', $train) }}">
                                                Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400">
                                        Belum ada data pelatihan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat Kerja -->
        <div class="bg-gray-800 rounded-xl shadow">
            <button @click="open === 4 ? open = null : open = 4"
                    class="w-full text-left px-5 py-4 font-semibold text-gray-200 flex justify-between items-center">
                <span>Riwayat Kerja</span>
                <svg :class="{'rotate-180': open === 4}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 4" x-collapse class="px-6 pb-6 space-y-4">
                <button data-modal-open="modalWorkCreate"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah Riwayat Kerja
                </button>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-600 rounded-lg">
                        <thead class="bg-gray-700 text-gray-100">
                            <tr>
                                <th class="border border-gray-600 p-2 text-left">Perusahaan</th>
                                <th class="border border-gray-600 p-2 text-left">Jabatan</th>
                                <th class="border border-gray-600 p-2 text-center">Tahun</th>
                                <th class="border border-gray-600 p-2 text-center">Surat Pengalaman</th>
                                <th class="border border-gray-600 p-2 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200">
                            @forelse ($works as $work)
                                <tr class="border-t border-gray-700">
                                    <td class="border border-gray-700 p-2">{{ $work->nama_perusahaan }}</td>
                                    <td class="border border-gray-700 p-2">{{ $work->jabatan }}</td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        {{ $work->tahun_mulai }} - {{ $work->tahun_selesai ?? 'Sekarang' }}
                                    </td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        @if ($work->surat_pengalaman)
                                            <a href="{{ asset('storage/'.$work->surat_pengalaman) }}" target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-700 p-2 text-center">
                                        <a href="javascript:void(0)"
                                           data-modal-open="modalWorkEdit{{ $work->id }}"
                                           class="text-yellow-400 hover:text-yellow-300 mr-2">
                                            Edit
                                        </a>
                                        <button type="button"
                                                class="text-red-500 hover:text-red-400"
                                                data-delete-modal="modalWorkDelete"
                                                data-action="{{ route('pencaker.work.destroy', $work) }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400">
                                        Belum ada riwayat kerja.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Minat Kerja -->
        <div class="bg-gray-800 rounded-xl shadow">
            <button @click="open === 5 ? open = null : open = 5"
                    class="w-full text-left px-5 py-4 font-semibold text-gray-200 flex justify-between items-center">
                <span>Referensi & Minat Kerja</span>
                <svg :class="{'rotate-180': open === 5}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 5" x-collapse class="px-6 pb-6 space-y-4 text-gray-200">
                <button data-modal-open="modalPreferenceForm"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah / Ubah Minat Kerja
                </button>

                <div class="bg-gray-900/40 rounded-lg p-6 border border-gray-700/60">
                    <h3 class="text-lg font-semibold mb-4">Data Minat Kerja</h3>
                    @if ($preference)
                        <div class="space-y-3">
                            <p><strong>Minat Lokasi:</strong>
                                {{ implode(', ', $preference->minat_lokasi ?? []) ?: '-' }}</p>
                            <p><strong>Bidang Usaha:</strong>
                                {{ implode(', ', $preference->minat_bidang ?? []) ?: '-' }}</p>
                            <p><strong>Gaji Harapan:</strong>
                                {{ $preference->gaji_harapan ?: '-' }}</p>
                            <p><strong>Deskripsi Diri:</strong>
                                {{ $preference->deskripsi_diri ?: '-' }}</p>
                        </div>
                    @else
                        <p class="text-gray-400 italic">Belum ada data minat kerja yang diisi.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal: Edit Profil --}}
<div id="profileModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-700/30
                flex flex-col overflow-hidden min-h-0"
         style="max-height:90vh">

        <div class="p-4 border-b border-gray-600/20 flex justify-between items-center sticky top-0
                    bg-white dark:bg-gray-800 z-10">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Data Diri</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-200 text-2xl font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('pencaker.profile.update') }}"
              class="p-6 overflow-y-auto grow space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="__accordion" value="profile">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-500">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                           value="{{ old('nama_lengkap', $profile->nama_lengkap ?? '') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">NIK</label>
                    <input type="text" name="nik"
                           value="{{ old('nik', $profile->nik ?? '') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki')>
                            Laki-laki
                        </option>
                        <option value="Perempuan" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan')>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Agama</label>
                    <select name="agama"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Pilih</option>
                        @foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" @selected(old('agama', $profile->agama ?? '') == $agama)>
                                {{ $agama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Status Perkawinan</label>
                    <select name="status_perkawinan"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Pilih</option>
                        @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                            <option value="{{ $status }}" @selected(old('status_perkawinan', $profile->status_perkawinan ?? '') == $status)>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Pilih</option>
                        @foreach (['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $p)
                            <option value="{{ $p }}" @selected(old('pendidikan_terakhir', $profile->pendidikan_terakhir ?? '') == $p)>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-500">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" rows="2"
                              class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                     dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">{{ old('alamat_lengkap', $profile->alamat_lengkap ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">Kecamatan Domisili</label>
                    <select name="domisili_kecamatan"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($kecamatan as $kec)
                            <option value="{{ $kec }}" @selected(old('domisili_kecamatan', $profile->domisili_kecamatan ?? '') == $kec)>
                                {{ $kec }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-500">No. Telepon</label>
                    <input type="text" name="no_telepon"
                           value="{{ old('no_telepon', $profile->no_telepon ?? '') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-600/30">
                <button type="button" id="cancelEdit"
                        class="px-4 py-2 rounded-lg border border-gray-400 text-gray-700 dark:text-gray-200
                               hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Tambah Pendidikan --}}
<x-modal-form id="modalEducationCreate"
              title="Tambah Riwayat Pendidikan"
              action="{{ route('pencaker.education.store') }}"
              method="POST"
              submitLabel="Simpan" cancelLabel="Batal">
    <input type="hidden" name="__accordion" value="education">
    <div>
        <label class="block text-sm text-gray-500">Tingkat Pendidikan</label>
        <select name="tingkat"
                class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                       dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
            <option value="">- Pilih -</option>
            @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                <option value="{{ $tingkat }}">{{ $tingkat }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm text-gray-500">Nama Institusi / Sekolah</label>
        <input type="text" name="nama_institusi"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                      dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm text-gray-500">Jurusan</label>
        <input type="text" name="jurusan"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                      dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm text-gray-500">Tahun Mulai</label>
            <input type="number" name="tahun_mulai" placeholder="contoh: 2018"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm text-gray-500">Tahun Selesai</label>
            <input type="number" name="tahun_selesai" placeholder="contoh: 2022"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div>
        <label class="block text-sm text-gray-500">Upload Ijazah (opsional)</label>
        <input type="file" name="ijazah_file"
               class="mt-1 w-full text-sm text-gray-300 border border-gray-700 rounded-lg p-2
                      bg-gray-900 focus:ring-2 focus:ring-indigo-500">
    </div>
</x-modal-form>

{{-- Modal: Edit Pendidikan --}}
@foreach ($educations as $edu)
    <x-modal-form id="modalEducationEdit{{ $edu->id }}"
                  title="Edit Riwayat Pendidikan"
                  action="{{ route('pencaker.education.update', $edu->id) }}"
                  method="POST"
                  submitLabel="Update" cancelLabel="Batal">
        @method('PUT')
        <input type="hidden" name="__accordion" value="education">

        <div>
            <label class="block text-sm text-gray-500">Tingkat Pendidikan</label>
            <select name="tingkat"
                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                           dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
                @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                    <option value="{{ $tingkat }}" @selected($edu->tingkat == $tingkat)>{{ $tingkat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-500">Nama Institusi / Sekolah</label>
            <input type="text" name="nama_institusi" value="{{ old('nama_institusi', $edu->nama_institusi) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
        </div>

        <div>
            <label class="block text-sm text-gray-500">Jurusan</label>
            <input type="text" name="jurusan" value="{{ old('jurusan', $edu->jurusan) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-500">Tahun Mulai</label>
                <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai', $edu->tahun_mulai) }}"
                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                              dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm text-gray-500">Tahun Selesai</label>
                <input type="number" name="tahun_selesai" value="{{ old('tahun_selesai', $edu->tahun_selesai) }}"
                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                              dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-500">Upload Ijazah (opsional)</label>
            <input type="file" name="ijazah_file"
                   class="mt-1 w-full text-sm text-gray-300 border border-gray-700 rounded-lg p-2
                          bg-gray-900 focus:ring-2 focus:ring-indigo-500">
            @if ($edu->ijazah_file)
                <p class="text-xs text-gray-400 mt-1">
                    File saat ini:
                    <a href="{{ asset('storage/'.$edu->ijazah_file) }}" target="_blank"
                       class="text-blue-400 underline">Lihat Ijazah</a>
                </p>
            @endif
        </div>
    </x-modal-form>
@endforeach

{{-- Modal: Hapus Pendidikan --}}
<x-modal-form id="modalEducationDelete"
              title="Konfirmasi Hapus"
              action=""
              method="POST"
              submitLabel="Ya, Hapus" cancelLabel="Batal">
    @method('DELETE')
    <input type="hidden" name="__accordion" value="education">
    <p class="text-gray-300">Apakah Anda yakin ingin menghapus data pendidikan ini?</p>
</x-modal-form>

{{-- Modal: Tambah Pelatihan --}}
<x-modal-form id="modalTrainingCreate"
              title="Tambah Riwayat Pelatihan"
              action="{{ route('pencaker.training.store') }}"
              method="POST"
              submitLabel="Simpan" cancelLabel="Batal">
    <input type="hidden" name="__accordion" value="training">
    <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan" required />
    <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan" required />
    <x-input-text label="Tahun" name="tahun" type="number" placeholder="contoh: 2024" required />
    <x-input-file label="Upload Sertifikat (PDF/JPG/PNG)" name="sertifikat_file" required />
</x-modal-form>

{{-- Modal: Edit Pelatihan --}}
@foreach ($trainings as $train)
    <x-modal-form id="modalTrainingEdit{{ $train->id }}"
                  title="Edit Riwayat Pelatihan"
                  action="{{ route('pencaker.training.update', $train->id) }}"
                  method="POST"
                  submitLabel="Perbarui" cancelLabel="Batal">
        @method('PUT')
        <input type="hidden" name="__accordion" value="training">
        <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan"
                      :value="$train->jenis_pelatihan" required />
        <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan"
                      :value="$train->lembaga_pelatihan" required />
        <x-input-text label="Tahun" name="tahun" type="number"
                      :value="$train->tahun" required />
        <x-input-file label="Upload Sertifikat Baru (opsional)" name="sertifikat_file" />
    </x-modal-form>
@endforeach

{{-- Modal: Hapus Pelatihan --}}
<x-modal-form id="modalTrainingDelete"
              title="Konfirmasi Hapus"
              action=""
              method="POST"
              submitLabel="Ya, Hapus" cancelLabel="Batal">
    @method('DELETE')
    <input type="hidden" name="__accordion" value="training">
    <p class="text-gray-300">Apakah Anda yakin ingin menghapus data pelatihan ini?</p>
</x-modal-form>

{{-- Modal: Tambah Riwayat Kerja --}}
<x-modal-form id="modalWorkCreate"
              title="Tambah Riwayat Kerja"
              action="{{ route('pencaker.work.store') }}"
              method="POST"
              submitLabel="Simpan" cancelLabel="Batal">
    <input type="hidden" name="__accordion" value="work">
    <x-input-text label="Nama Perusahaan" name="nama_perusahaan" required />
    <x-input-text label="Jabatan" name="jabatan" required />
    <div class="grid grid-cols-2 gap-3">
        <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number" placeholder="2020" required />
        <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number" placeholder="2024" />
    </div>
    <x-input-file label="Upload Surat Pengalaman (Opsional)" name="surat_pengalaman" />
</x-modal-form>

{{-- Modal: Edit Riwayat Kerja --}}
@foreach ($works as $work)
    <x-modal-form id="modalWorkEdit{{ $work->id }}"
                  title="Edit Riwayat Kerja"
                  action="{{ route('pencaker.work.update', $work->id) }}"
                  method="POST"
                  submitLabel="Perbarui" cancelLabel="Batal">
        @method('PUT')
        <input type="hidden" name="__accordion" value="work">
        <x-input-text label="Nama Perusahaan" name="nama_perusahaan"
                      :value="$work->nama_perusahaan" required />
        <x-input-text label="Jabatan" name="jabatan"
                      :value="$work->jabatan" required />
        <div class="grid grid-cols-2 gap-3">
            <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number"
                          :value="$work->tahun_mulai" required />
            <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number"
                          :value="$work->tahun_selesai" />
        </div>
        <x-input-file label="Upload Surat Pengalaman Baru (Opsional)"
                      name="surat_pengalaman" />
    </x-modal-form>
@endforeach

{{-- Modal: Hapus Riwayat Kerja --}}
<x-modal-form id="modalWorkDelete"
              title="Konfirmasi Hapus"
              action=""
              method="POST"
              submitLabel="Ya, Hapus" cancelLabel="Batal">
    @method('DELETE')
    <input type="hidden" name="__accordion" value="work">
    <p class="text-gray-300">Apakah Anda yakin ingin menghapus data riwayat kerja ini?</p>
</x-modal-form>

{{-- Modal: Minat Kerja --}}
<x-modal-form id="modalPreferenceForm"
              title="Isi Minat Kerja"
              action="{{ route('pencaker.preferences.store') }}"
              method="POST"
              submitLabel="Simpan" cancelLabel="Batal">
    <input type="hidden" name="__accordion" value="preference">

    <div class="space-y-4">
        <div>
            <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                Minat Lokasi Kerja (boleh lebih dari satu)
            </label>
            <div class="flex flex-wrap gap-3">
                @php
                    $lokasiList = ['Kabupaten Lebak', 'Luar Kabupaten Lebak', 'Luar Negeri'];
                    $selectedLokasi = old('minat_lokasi', $preference->minat_lokasi ?? []);
                @endphp
                @foreach ($lokasiList as $lokasi)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_lokasi[]" value="{{ $lokasi }}"
                               class="rounded border-gray-400 dark:border-gray-600"
                               {{ in_array($lokasi, $selectedLokasi ?? []) ? 'checked' : '' }}>
                        <span>{{ $lokasi }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                Minat Bidang Usaha (boleh lebih dari satu)
            </label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @php
                    $bidangList = ['IT', 'Jasa', 'Pertambangan', 'Kelautan', 'Pertanian', 'Pendidikan', 'Kesehatan', 'Konstruksi', 'Transportasi', 'Administrasi'];
                    $selectedBidang = old('minat_bidang', $preference->minat_bidang ?? []);
                @endphp
                @foreach ($bidangList as $bidang)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_bidang[]" value="{{ $bidang }}"
                               class="rounded border-gray-400 dark:border-gray-600"
                               {{ in_array($bidang, $selectedBidang ?? []) ? 'checked' : '' }}>
                        <span>{{ $bidang }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <x-input-text label="Gaji yang Diharapkan (contoh: 3–5 juta)" name="gaji_harapan"
                      :value="old('gaji_harapan', $preference->gaji_harapan ?? '')" />

        <div>
            <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                Deskripsi Singkat Tentang Diri Anda
            </label>
            <textarea name="deskripsi_diri" rows="4"
                      class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi_diri', $preference->deskripsi_diri ?? '') }}</textarea>
        </div>
    </div>
</x-modal-form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileModal = document.getElementById('profileModal');
    const openBtn = document.getElementById('openEdit');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelEdit');

    const openProfileModal = () => {
        if (!profileModal) return;
        profileModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const closeProfileModal = () => {
        if (!profileModal) return;
        profileModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    if (openBtn && !openBtn.disabled) {
        openBtn.addEventListener('click', openProfileModal);
    }
    closeBtn?.addEventListener('click', closeProfileModal);
    cancelBtn?.addEventListener('click', closeProfileModal);
    profileModal?.addEventListener('click', (event) => {
        if (event.target === profileModal) {
            closeProfileModal();
        }
    });

    const registerDeleteModal = (selector) => {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const modalId = button.getAttribute('data-delete-modal');
                const action = button.getAttribute('data-action');
                const modal = document.getElementById(modalId);
                if (!modal) return;

                const form = modal.querySelector('form');
                if (form && action) {
                    form.setAttribute('action', action);
                }

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        });
    };

    registerDeleteModal('[data-delete-modal="modalEducationDelete"]');
    registerDeleteModal('[data-delete-modal="modalTrainingDelete"]');
    registerDeleteModal('[data-delete-modal="modalWorkDelete"]');
});
</script>
@endsection
