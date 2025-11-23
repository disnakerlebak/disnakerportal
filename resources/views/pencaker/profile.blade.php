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

<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100" x-data="{ open: {{ $openDefault }} }">

    <h1 class="text-2xl font-semibold text-slate-100 mb-6">Profil Pencaker</h1>

    <!-- @if (session('success'))
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
    @endif -->

    <div class="space-y-4">

        <!-- Data Diri -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 1 ? open = null : open = 1"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Data Diri</span>
                <svg :class="{'rotate-180': open === 1}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 1"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-slate-100">Data Diri Pencari Kerja</h2>
                    @if ($locked)
                        <button id="openEdit" disabled
                                title="Terkunci karena pengajuan AK1 sedang diproses/diterima"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-slate-700 cursor-not-allowed flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Terkunci</span>
                        </button>
                    @else
                        <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalProfileEdit' }))"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                            Edit Profil
                        </button>
                    @endif
                </div>

                @if ($locked)
                    <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                        </svg>
                        <span>Pengeditan data diri terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.</span>
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
                        'Status Disabilitas' => $profile->status_disabilitas ?? '-',
                        'Akun Media Sosial' => $profile->akun_media_sosial ?? '-',
                    ];
                @endphp

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <table class="w-full text-slate-100">
                        <tbody>
                            @foreach ($fields as $label => $value)
                                <tr class="border-b border-slate-800/60">
                                    <td class="py-2 px-4 font-semibold text-slate-400 w-1/3">{{ $label }}</td>
                                    <td class="py-2 px-4 text-slate-100">: {{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pendidikan -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 2 ? open = null : open = 2"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Pendidikan</span>
                <svg :class="{'rotate-180': open === 2}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 2"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                @if ($locked)
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pendidikan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                @endif

                @if ($locked)
                        <button id="openEdit" disabled
                                title="Terkunci karena pengajuan AK1 sedang diproses/diterima"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-slate-700 cursor-not-allowed flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Terkunci</span>
                        </button>
                    @else
                    <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalEducationCreate' }))"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        + Tambah
                    </button>
                @endif

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Tingkat</th>
                                <th class="border border-slate-700 p-2 text-left">Nama Sekolah / Institusi</th>
                                <th class="border border-slate-700 p-2 text-left">Jurusan</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            @forelse ($educations as $edu)
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2">{{ $edu->tingkat }}</td>
                                    <td class="border border-slate-800 p-2">{{ $edu->nama_institusi }}</td>
                                    <td class="border border-slate-800 p-2 text-center">{{ $edu->jurusan ?: '-' }}</td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        {{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}
                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        @if ($locked)
                                            <span class="inline-flex items-center justify-center text-slate-400" title="Terkunci">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <button type="button" title="Edit"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalEducationEdit{{ $edu->id }}' }))"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                    onclick="
                                                        (function(){
                                                            const form = document.getElementById('formEducationDelete');
                                                            if (form) { form.setAttribute('action', '{{ route('pencaker.education.destroy', $edu) }}'); }
                                                            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalEducationDelete' }));
                                                        })();">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada data pendidikan.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pelatihan -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 3 ? open = null : open = 3"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Pelatihan</span>
                <svg :class="{'rotate-180': open === 3}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 3"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                @if ($locked)
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pelatihan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                @endif

                @if ($locked)
                    <button id="openEdit" disabled
                            title="Terkunci karena pengajuan AK1 sedang diproses/diterima"
                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-slate-700 cursor-not-allowed flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Terkunci</span>
                    </button>
                @else
                    <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalTrainingCreate' }))"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        + Tambah
                    </button>
                @endif

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Jenis Pelatihan</th>
                                <th class="border border-slate-700 p-2 text-left">Lembaga</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center">Sertifikat</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            @forelse ($trainings as $train)
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2">{{ $train->jenis_pelatihan }}</td>
                                    <td class="border border-slate-800 p-2">{{ $train->lembaga_pelatihan }}</td>
                                    <td class="border border-slate-800 p-2 text-center">{{ $train->tahun }}</td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        @if ($train->sertifikat_file)
                                            <a href="{{ asset('storage/'.$train->sertifikat_file) }}"
                                               target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        @if ($locked)
                                            <span class="inline-flex items-center justify-center text-slate-400" title="Terkunci">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <button type="button" title="Edit"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalTrainingEdit{{ $train->id }}' }))"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                    onclick="
                                                        (function(){
                                                            const form = document.getElementById('formTrainingDelete');
                                                            if (form) { form.setAttribute('action', '{{ route('pencaker.training.destroy', $train) }}'); }
                                                            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalTrainingDelete' }));
                                                        })();">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada data pelatihan.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Kerja -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 4 ? open = null : open = 4"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Kerja</span>
                <svg :class="{'rotate-180': open === 4}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 4"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalWorkCreate' }))"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah
                </button>

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Perusahaan</th>
                                <th class="border border-slate-700 p-2 text-left">Jabatan</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center">Surat Pengalaman</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            @forelse ($works as $work)
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2">{{ $work->nama_perusahaan }}</td>
                                    <td class="border border-slate-800 p-2">{{ $work->jabatan }}</td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        {{ $work->tahun_mulai }} - {{ $work->tahun_selesai ?? 'Sekarang' }}
                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        @if ($work->surat_pengalaman)
                                            <a href="{{ asset('storage/'.$work->surat_pengalaman) }}" target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Tidak ada</span>
                                        @endif
                                    </td>
                                        <td class="border border-slate-800 p-2 text-center">
                                            <button type="button" title="Edit"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalWorkEdit{{ $work->id }}' }))"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                    onclick="
                                                        (function(){
                                                            const form = document.getElementById('formWorkDelete');
                                                            if (form) { form.setAttribute('action', '{{ route('pencaker.work.destroy', $work) }}'); }
                                                            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalWorkDelete' }));
                                                        })();">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada riwayat kerja.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minat Kerja -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 5 ? open = null : open = 5"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Referensi & Minat Kerja</span>
                <svg :class="{'rotate-180': open === 5}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 5"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4 text-slate-300">
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalPreferenceForm' }))"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah
                </button>

                <div class="bg-slate-950/40 rounded-xl p-6 shadow-inner">
                    <h3 class="text-lg font-semibold mb-4">Data Minat Kerja</h3>
                    @if ($preference)
                        @php
                            $minatLokasi = is_array($preference->minat_lokasi)
                                ? implode(', ', $preference->minat_lokasi)
                                : ($preference->minat_lokasi ?? '-');
                            $minatBidang = is_array($preference->minat_bidang)
                                ? implode(', ', $preference->minat_bidang)
                                : ($preference->minat_bidang ?? '-');
                        @endphp
                        <div class="grid gap-y-2 text-sm">
                            <div class="flex">
                                <span class="w-40 text-slate-400">Minat Lokasi</span>
                                <span class="flex-1 text-slate-100">: {{ $minatLokasi ?: '-' }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-40 text-slate-400">Bidang Usaha</span>
                                <span class="flex-1 text-slate-100">: {{ $minatBidang ?: '-' }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-40 text-slate-400">Gaji Harapan</span>
                                <span class="flex-1 text-slate-100">: {{ $preference->gaji_harapan ?: '-' }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-40 text-slate-400">Deskripsi Diri</span>
                                <span class="flex-1 text-slate-100">: {{ $preference->deskripsi_diri ?: '-' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-400 italic">Belum ada data minat kerja yang diisi.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal: Edit Profil (refactor ke komponen) --}}
<x-modal id="modalProfileEdit" size="xl" title="Edit Data Diri">
    <div class="max-h-[75vh] overflow-y-auto pr-1">
    <form method="POST" action="{{ route('pencaker.profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="__accordion" value="profile">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <x-input-text label="Nama Lengkap"
                          name="nama_lengkap"
                          :value="old('nama_lengkap', $profile->nama_lengkap ?? '')"
                          required />

            <x-input-text label="NIK"
                          name="nik"
                          :value="old('nik', $profile->nik ?? '')"
                          required />

            <x-input-text label="Tempat Lahir"
                          name="tempat_lahir"
                          :value="old('tempat_lahir', $profile->tempat_lahir ?? '')" />

            <div>
                <label class="block text-sm text-slate-400">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" lang="id"
                       value="{{ old('tanggal_lahir', $profile->tanggal_lahir ? \Illuminate\Support\Carbon::parse($profile->tanggal_lahir)->format('Y-m-d') : '') }}"
                       placeholder="dd/mm/yyyy"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500"
                       autocomplete="bday" style="color-scheme: dark;">
            </div>

            <div>
                <label class="block text-sm text-slate-400">Jenis Kelamin</label>
                <select name="jenis_kelamin"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan')>Perempuan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-400">Agama</label>
                <select name="agama"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    @foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                        <option value="{{ $agama }}" @selected(old('agama', $profile->agama ?? '') == $agama)>{{ $agama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-400">Status Perkawinan</label>
                <select name="status_perkawinan"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                        <option value="{{ $status }}" @selected(old('status_perkawinan', $profile->status_perkawinan ?? '') == $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-400">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    @php($listPendidikan = ['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'])
                    <option value="">Pilih</option>
                    @foreach ($listPendidikan as $pd)
                        <option value="{{ $pd }}" @selected(old('pendidikan_terakhir', $profile->pendidikan_terakhir ?? '') === $pd)>{{ $pd }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-400">Kecamatan Domisili</label>
                <select name="domisili_kecamatan"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatan as $kec)
                        <option value="{{ $kec }}" @selected(old('domisili_kecamatan', $profile->domisili_kecamatan ?? '') == $kec)>{{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            <x-input-text label="No. Telepon"
                          name="no_telepon"
                          :value="old('no_telepon', $profile->no_telepon ?? '')" />

            <div>
                <label class="block text-sm text-slate-400">Status Disabilitas</label>
                <select name="status_disabilitas"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    @php($opsiDisabilitas = [
                        'Tidak',
                        'Ya, disabilitas fisik',
                        'Ya, disabilitas netra',
                        'Ya, disabilitas rungu',
                        'Ya, disabilitas intelektual',
                        'Ya, lainnya',
                    ])
                    <option value="">Pilih</option>
                    @foreach ($opsiDisabilitas as $opsi)
                        <option value="{{ $opsi }}" @selected(old('status_disabilitas', $profile->status_disabilitas ?? '') === $opsi)>{{ $opsi }}</option>
                    @endforeach
                </select>
            </div>

            <x-input-text label="Akun Media Sosial"
                          name="akun_media_sosial"
                          :value="old('akun_media_sosial', $profile->akun_media_sosial ?? '')"
                          placeholder="@username / link profil (opsional)" />

            <div class="md:col-span-2">
                <label class="block text-sm text-slate-400">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" rows="2"
                          class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">{{ old('alamat_lengkap', $profile->alamat_lengkap ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="button"
                    data-close-modal="modalProfileEdit"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                Simpan
            </button>
        </div>
    </form>
    </div>
</x-modal>

{{-- Modal: Tambah Pendidikan --}}
<x-modal id="modalEducationCreate" title="Tambah Riwayat Pendidikan">
    <form method="POST" action="{{ route('pencaker.education.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="__accordion" value="education">
        <div>
            <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
            <select name="tingkat"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500" required>
                <option value="">- Pilih -</option>
                @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                    <option value="{{ $tingkat }}">{{ $tingkat }}</option>
                @endforeach
            </select>
        </div>

        <x-input-text label="Nama Institusi / Sekolah" name="nama_institusi" required />
        <x-input-text label="Jurusan" name="jurusan" />

        <div class="grid grid-cols-2 gap-3">
            <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number" placeholder="contoh: 2018" />
            <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number" placeholder="contoh: 2022" />
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalEducationCreate"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                Simpan
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Edit Pendidikan --}}
@foreach ($educations as $edu)
    <x-modal id="modalEducationEdit{{ $edu->id }}" title="Edit Riwayat Pendidikan">
        <form method="POST" action="{{ route('pencaker.education.update', $edu->id) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="__accordion" value="education">

            <div>
                <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
                <select name="tingkat"
                        class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500" required>
                    @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                        <option value="{{ $tingkat }}" @selected($edu->tingkat == $tingkat)>{{ $tingkat }}</option>
                    @endforeach
                </select>
            </div>

            <x-input-text label="Nama Institusi / Sekolah" name="nama_institusi"
                          :value="old('nama_institusi', $edu->nama_institusi)" required />

            <x-input-text label="Jurusan" name="jurusan"
                          :value="old('jurusan', $edu->jurusan)" />

            <div class="grid grid-cols-2 gap-3">
                <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number"
                              :value="old('tahun_mulai', $edu->tahun_mulai)" required />
                <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number"
                              :value="old('tahun_selesai', $edu->tahun_selesai)" required />
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="modalEducationEdit{{ $edu->id }}"
                        class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                    Update
                </button>
            </div>
        </form>
    </x-modal>
@endforeach

{{-- Modal: Hapus Pendidikan --}}
<x-modal id="modalEducationDelete" title="Konfirmasi Hapus">
    <form method="POST" action="" class="space-y-4" id="formEducationDelete">
        @csrf
        @method('DELETE')
        <input type="hidden" name="__accordion" value="education">
        <p class="text-slate-400">Apakah Anda yakin ingin menghapus data pendidikan ini?</p>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalEducationDelete"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold">
                Ya, Hapus
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Tambah Pelatihan --}}
<x-modal id="modalTrainingCreate" title="Tambah Riwayat Pelatihan">
    <form method="POST" action="{{ route('pencaker.training.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="__accordion" value="training">
        <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan" required />
        <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan" required />
        <x-input-text label="Tahun" name="tahun" type="number" placeholder="contoh: 2024" required />
        <x-input-file label="Upload Sertifikat (PDF/JPG/PNG)" name="sertifikat_file" required />
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalTrainingCreate"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                Simpan
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Edit Pelatihan --}}
@foreach ($trainings as $train)
    <x-modal id="modalTrainingEdit{{ $train->id }}" title="Edit Riwayat Pelatihan">
        <form method="POST" action="{{ route('pencaker.training.update', $train->id) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="__accordion" value="training">
            <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan"
                          :value="$train->jenis_pelatihan" required />
            <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan"
                          :value="$train->lembaga_pelatihan" required />
            <x-input-text label="Tahun" name="tahun" type="number"
                          :value="$train->tahun" required />
            <x-input-file label="Upload Sertifikat Baru (opsional)" name="sertifikat_file" />

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="modalTrainingEdit{{ $train->id }}"
                        class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                    Perbarui
                </button>
            </div>
        </form>
    </x-modal>
@endforeach

{{-- Modal: Hapus Pelatihan --}}
<x-modal id="modalTrainingDelete" title="Konfirmasi Hapus">
    <form method="POST" action="" class="space-y-4" id="formTrainingDelete">
        @csrf
        @method('DELETE')
        <input type="hidden" name="__accordion" value="training">
        <p class="text-slate-400">Apakah Anda yakin ingin menghapus data pelatihan ini?</p>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalTrainingDelete"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold">
                Ya, Hapus
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Tambah Riwayat Kerja --}}
<x-modal id="modalWorkCreate" title="Tambah Riwayat Kerja">
    <form method="POST" action="{{ route('pencaker.work.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="__accordion" value="work">
        <x-input-text label="Nama Perusahaan" name="nama_perusahaan" required />
        <x-input-text label="Jabatan" name="jabatan" required />
        <div class="grid grid-cols-2 gap-3">
            <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number" placeholder="2020" required />
            <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number" placeholder="2024" />
        </div>
        <x-input-file label="Upload Surat Pengalaman (Opsional)" name="surat_pengalaman" />
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalWorkCreate"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                Simpan
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Edit Riwayat Kerja --}}
@foreach ($works as $work)
    <x-modal id="modalWorkEdit{{ $work->id }}" title="Edit Riwayat Kerja">
        <form method="POST" action="{{ route('pencaker.work.update', $work->id) }}" class="space-y-4">
            @csrf
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

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="modalWorkEdit{{ $work->id }}"
                        class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                    Perbarui
                </button>
            </div>
        </form>
    </x-modal>
@endforeach

{{-- Modal: Hapus Riwayat Kerja --}}
<x-modal id="modalWorkDelete" title="Konfirmasi Hapus">
    <form method="POST" action="" class="space-y-4" id="formWorkDelete">
        @csrf
        @method('DELETE')
        <input type="hidden" name="__accordion" value="work">
        <p class="text-slate-400">Apakah Anda yakin ingin menghapus data riwayat kerja ini?</p>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalWorkDelete"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold">
                Ya, Hapus
            </button>
        </div>
    </form>
</x-modal>

{{-- Modal: Minat Kerja --}}
<x-modal id="modalPreferenceForm" title="Isi Minat Kerja">
    <div class="max-h-[70vh] overflow-y-auto pr-1">
    <form method="POST" action="{{ route('pencaker.preferences.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="__accordion" value="preference">

        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Minat Lokasi Kerja (boleh lebih dari satu)
            </label>
            <div class="flex flex-wrap gap-3">
                @foreach (['Kabupaten Lebak', 'Luar Kabupaten Lebak', 'Luar Negeri'] as $lokasi)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_lokasi[]" value="{{ $lokasi }}"
                               class="rounded border-slate-600 dark:border-slate-700"
                               {{ in_array($lokasi, old('minat_lokasi', $preference->minat_lokasi ?? [])) ? 'checked' : '' }}>
                        <span>{{ $lokasi }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Minat Bidang Usaha (boleh lebih dari satu)
            </label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach (['IT','Jasa','Pertambangan','Kelautan','Pertanian','Pendidikan','Kesehatan','Konstruksi','Transportasi','Administrasi'] as $bidang)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_bidang[]" value="{{ $bidang }}"
                               class="rounded border-slate-600 dark:border-slate-700"
                               {{ in_array($bidang, old('minat_bidang', $preference->minat_bidang ?? [])) ? 'checked' : '' }}>
                        <span>{{ $bidang }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <x-input-text label="Gaji yang Diharapkan (contoh: 3–5 juta)" name="gaji_harapan"
                      :value="old('gaji_harapan', $preference->gaji_harapan ?? '')" />

        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Deskripsi Singkat Tentang Diri Anda
            </label>
            <textarea name="deskripsi_diri" rows="4"
                      class="w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi_diri', $preference->deskripsi_diri ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalPreferenceForm"
                    class="px-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                Simpan
            </button>
        </div>
    </form>
    </div>
</x-modal>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const registerDeleteModal = (selector) => {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const modalId = button.getAttribute('data-delete-modal');
                const action = button.getAttribute('data-action');
                const form = document.querySelector(`#${modalId} form`);
                if (form && action) {
                    form.setAttribute('action', action);
                }
                window.dispatchEvent(new CustomEvent('open-modal', { detail: modalId }));
            });
        });
    };

});
</script>
@endsection
