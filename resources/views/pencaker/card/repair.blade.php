@extends('layouts.pencaker')
@section('title', 'Perbaikan AK1')

@section('content')
<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100">
    <h2 class="text-2xl font-semibold text-white mb-4">Perbaikan Data Kartu AK1</h2>

    @if (session('success'))
        <div class="mb-4 bg-green-800 border border-green-600 text-green-100 px-4 py-3 rounded">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-600/20 border border-red-600 text-red-200 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8 rounded-2xl border border-indigo-800/60 bg-indigo-900/40 px-6 py-5 text-slate-100 shadow-lg">
        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-200">Informasi Perbaikan</p>
        <p class="mt-2 text-sm leading-relaxed text-slate-200">Silakan perbarui data diri, riwayat pendidikan/pelatihan, atau dokumen yang perlu direvisi. Setelah yakin perubahan sesuai, ajukan perbaikan untuk diverifikasi admin.</p>
    </div>

    @php
        $snapshotChanged = $snapshotChanged ?? false;
        $hasPendingRepair = $hasPendingRepair ?? false;
        $pendingRepair = $pendingRepair ?? null;
    @endphp

    @if ($hasPendingRepair && $pendingRepair)
        <div class="mb-6 rounded-lg border border-yellow-700 bg-yellow-900/40 px-4 py-3 text-sm text-yellow-100">
            ⏳ Pengajuan perbaikan terakhir ({{ optional($pendingRepair->created_at)->format('d M Y H:i') }}) berstatus
            <span class="font-semibold text-white">{{ $pendingRepair->status }}</span>. Harap tunggu proses verifikasi admin.
            @php
                $pendingNote = optional($pendingRepair->logs->first())->notes;
            @endphp
            @if ($pendingNote)
                <div class="mt-2 text-xs text-slate-200">
                    <span class="font-semibold text-yellow-200">Catatan Admin:</span>
                    {{ $pendingNote }}
                </div>
            @endif
        </div>
    @endif

    <form id="repairForm" action="{{ route('pencaker.card.repair.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-snapshot-changed="{{ $snapshotChanged ? 'true' : 'false' }}">
        @csrf

        {{-- Status ringkas --}}
        <div class="rounded-2xl bg-slate-900 shadow-lg">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-8 lg:px-10">
                <h3 class="text-lg font-semibold text-white mb-2">Status Kartu Aktif</h3>
                <div class="text-sm text-slate-300 space-y-1">
                    <p>Nomor AK1: <span class="font-semibold text-white">{{ $application->nomor_ak1 ?? '-' }}</span></p>
                    <p>Status Saat Ini: <span class="font-semibold text-white">{{ $application->status }}</span></p>
                    <p>Tanggal Persetujuan: {{ indoDate($approvedAt) }}</p>
                </div>
            </div>
        </div>

        {{-- Foto & data diri --}}
        <div class="rounded-2xl bg-slate-900 shadow-lg">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-10 lg:px-10">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
                    <div class="flex flex-col items-center justify-start gap-3 text-sm text-slate-400 lg:items-start">
                        <div class="relative h-52 w-44 overflow-hidden rounded-xl border border-slate-800/60 bg-slate-900 shadow-md sm:h-56 sm:w-48">
                            <img id="fotoPreview" src="{{ optional($application->documents->firstWhere('type', 'foto_closeup'))->file_path ? asset('storage/' . $application->documents->firstWhere('type', 'foto_closeup')->file_path) : asset('images/placeholder-avatar.png') }}" alt="Foto Close-Up" class="h-full w-full object-cover" />
                            <label for="fotoCloseup" class="absolute inset-x-0 bottom-0 bg-black/60 py-2 text-center text-xs text-slate-100 transition hover:bg-black/75">
                                Ganti Foto
                            </label>
                        </div>
                        <p class="text-center text-xs text-slate-400 sm:text-left">Format JPG/PNG &bull; Maks 1 MB</p>
                        <input id="fotoCloseup" name="foto_closeup" type="file" accept="image/*" class="hidden" onchange="previewImage(event); enableRepairButton();">
                        <button type="button" class="text-xs text-blue-400 hover:text-blue-300" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairProfile' }))">Ubah Data Diri</button>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-white sm:text-xl">Data Diri</h3>
                                <p class="mt-1 text-sm text-slate-400">Pastikan informasi sesuai dengan dokumen kependudukan.</p>
                            </div>
                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairProfile' }))" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Ubah Data</button>
                        </div>

                        <dl class="mt-6 grid grid-cols-1 gap-4 text-sm text-slate-200 sm:grid-cols-2">
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">NIK</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nik ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Status</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->status_perkawinan ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Lengkap</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nama_lengkap ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Agama</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->agama ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tempat Lahir</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->tempat_lahir ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tanggal Lahir</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Jenis Kelamin</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->jenis_kelamin ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Pendidikan Terakhir</dt>
                                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->pendidikan_terakhir ?? '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm sm:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat Domisili</dt>
                                <dd class="mt-1 text-base font-semibold text-white leading-relaxed">{{ $profile->alamat_lengkap ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendidikan --}}
        <div class="rounded-2xl bg-slate-900 shadow-lg">
            <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Riwayat Pendidikan</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairEducationCreate' }))" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Tambah</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm md:text-base border-collapse text-slate-300">
                        <thead class="bg-slate-800 text-slate-200">
                            <tr>
                                <th class="p-3 text-center">Tingkat</th>
                                <th class="p-3 text-center">Lembaga / Sekolah</th>
                                <th class="p-3 text-center">Jurusan</th>
                                <th class="p-3 text-center">Tahun</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($educations as $edu)
                                <tr class="border-b border-slate-800">
                                    <td class="p-3">{{ $edu->tingkat }}</td>
                                    <td class="p-3">{{ $edu->nama_institusi }}</td>
                                    <td class="p-3">{{ $edu->jurusan ?: '-' }}</td>
                                    <td class="p-3">{{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairEducationEdit{{ $edu->id }}' }))"
                                                    class="p-2 rounded-full bg-slate-800 text-blue-300 hover:bg-blue-700/30"
                                                    title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13.5V19h5.5L19 9.5l-5.5-5.5L4 13.5z" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    class="p-2 rounded-full bg-slate-800 text-red-300 hover:bg-red-700/30"
                                                    title="Hapus"
                                                    onclick="openDeleteConfirm('{{ route('pencaker.education.destroy', $edu->id) }}', 'Hapus riwayat pendidikan ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pelatihan --}}
        <div class="rounded-2xl bg-slate-900 shadow-lg">
            <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Riwayat Pelatihan</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairTrainingCreate' }))" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Tambah</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm md:text-base border-collapse text-slate-300">
                        <thead class="bg-slate-800 text-slate-200">
                            <tr>
                                <th class="p-3 text-left">Jenis Pelatihan</th>
                                <th class="p-3 text-left">Lembaga</th>
                                <th class="p-3 text-left">Tahun</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainings as $training)
                                <tr class="border-b border-slate-800">
                                    <td class="p-3">{{ $training->jenis_pelatihan }}</td>
                                    <td class="p-3">{{ $training->lembaga_pelatihan }}</td>
                                    <td class="p-3">{{ $training->tahun }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'modalRepairTrainingEdit{{ $training->id }}' }))"
                                                    class="p-2 rounded-full bg-slate-800 text-blue-300 hover:bg-blue-700/30"
                                                    title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13.5V19h5.5L19 9.5l-5.5-5.5L4 13.5z" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    class="p-2 rounded-full bg-slate-800 text-red-300 hover:bg-red-700/30"
                                                    title="Hapus"
                                                    onclick="openDeleteConfirm('{{ route('pencaker.training.destroy', $training->id) }}', 'Hapus riwayat pelatihan ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Dokumen --}}
        @php
            $ktpPath = optional($application->documents->firstWhere('type', 'ktp_file'))->file_path;
            $ktpUrl  = $ktpPath ? asset('storage/' . $ktpPath) : '';
            $ktpName = $ktpPath ? basename($ktpPath) : '';
            $ktpType = ($ktpPath && str_ends_with(strtolower($ktpPath), '.pdf')) ? 'pdf' : 'image';

            $ijPath = optional($application->documents->firstWhere('type', 'ijazah_file'))->file_path;
            $ijUrl  = $ijPath ? asset('storage/' . $ijPath) : '';
            $ijName = $ijPath ? basename($ijPath) : '';
            $ijType = ($ijPath && str_ends_with(strtolower($ijPath), '.pdf')) ? 'pdf' : 'image';
        @endphp
        <div class="rounded-2xl bg-slate-900 shadow-lg">
            <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
                <h3 class="text-lg font-semibold text-white mb-4">Unggah Dokumen</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Dropzone KTP --}}
                    <div x-data='dropzoneInline("#repairKtpFile", @json($ktpUrl), @json($ktpType), @json($ktpName))' class="space-y-3">
                        <label class="block font-medium">KTP</label>
                        <div class="relative rounded-2xl border border-dashed border-slate-600/60 bg-slate-800/40 p-0 text-center text-slate-300 cursor-pointer hover:border-slate-500 transition overflow-hidden"
                             :class="{ 'ring-2 ring-indigo-500': dragging }"
                             @click.prevent="browse()"
                             @dragover.prevent="dragging = true"
                             @dragleave.prevent="dragging = false"
                             @drop.prevent="handleDrop($event)">
                            <template x-if="hasPreview && !isPdf">
                                <img :src="src" alt="Pratinjau KTP" class="w-full aspect-video object-cover">
                            </template>
                            <template x-if="hasPreview && isPdf">
                                <div class="w-full aspect-video flex items-center justify-center">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-8 h-8 mx-auto text-rose-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-6a2.25 2.25 0 00-2.25-2.25H8.25A2.25 2.25 0 006 8.25v9a2.25 2.25 0 002.25 2.25h4.5M9 8.25h6M9 11.25h6M9 14.25h3M15.75 18.75l1.5 1.5 3-3" />
                                        </svg>
                                        <div class="mt-2 text-sm text-slate-200 truncate" x-text="fileName || 'Berkas PDF'"></div>
                                        <template x-if="fileUrl"><a :href="fileUrl" target="_blank" class="text-indigo-400 underline text-xs">Lihat</a></template>
                                    </div>
                                </div>
                            </template>
                            <div x-show="!hasPreview" class="p-8">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15.75V18a3 3 0 003 3h12a3 3 0 003-3v-2.25M16.5 9.75 12 5.25m0 0L7.5 9.75M12 5.25v12" />
                                    </svg>
                                    <div class="text-base font-semibold">Klik untuk memilih berkas atau seret ke sini</div>
                                    <div class="text-xs text-slate-400">Menerima berkas .jpg, .jpeg, .png, .pdf</div>
                                    <div class="text-xs text-slate-400">Ukuran: minimal 20KB, maksimal 1MB</div>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="ktp_file" id="repairKtpFile" accept=".jpg,.jpeg,.png,.pdf" class="hidden" @change="handleChange($event); window.enableRepairButton && window.enableRepairButton();">
                    </div>

                    {{-- Dropzone Ijazah --}}
                    <div x-data='dropzoneInline("#repairIjazahFile", @json($ijUrl), @json($ijType), @json($ijName))' class="space-y-3">
                        <label class="block font-medium">Ijazah Terakhir</label>
                        <div class="relative rounded-2xl border border-dashed border-slate-600/60 bg-slate-800/40 p-0 text-center text-slate-300 cursor-pointer hover:border-slate-500 transition overflow-hidden"
                             :class="{ 'ring-2 ring-indigo-500': dragging }"
                             @click.prevent="browse()"
                             @dragover.prevent="dragging = true"
                             @dragleave.prevent="dragging = false"
                             @drop.prevent="handleDrop($event)">
                            <template x-if="hasPreview && !isPdf">
                                <img :src="src" alt="Pratinjau Ijazah" class="w-full aspect-video object-cover">
                            </template>
                            <template x-if="hasPreview && isPdf">
                                <div class="w-full aspect-video flex items-center justify-center">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-8 h-8 mx-auto text-rose-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-6a2.25 2.25 0 00-2.25-2.25H8.25A2.25 2.25 0 006 8.25v9a2.25 2.25 0 002.25 2.25h4.5M9 8.25h6M9 11.25h6M9 14.25h3M15.75 18.75l1.5 1.5 3-3" />
                                        </svg>
                                        <div class="mt-2 text-sm text-slate-200 truncate" x-text="fileName || 'Berkas PDF'"></div>
                                        <template x-if="fileUrl"><a :href="fileUrl" target="_blank" class="text-indigo-400 underline text-xs">Lihat</a></template>
                                    </div>
                                </div>
                            </template>
                            <div x-show="!hasPreview" class="p-8">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15.75V18a3 3 0 003 3h12a3 3 0 003-3v-2.25M16.5 9.75 12 5.25m0 0L7.5 9.75M12 5.25v12" />
                                    </svg>
                                    <div class="text-base font-semibold">Klik untuk memilih berkas atau seret ke sini</div>
                                    <div class="text-xs text-slate-400">Menerima berkas .jpg, .jpeg, .png, .pdf</div>
                                    <div class="text-xs text-slate-400">Ukuran: minimal 20KB, maksimal 1MB</div>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="ijazah_file" id="repairIjazahFile" accept=".jpg,.jpeg,.png,.pdf" class="hidden" @change="handleChange($event); window.enableRepairButton && window.enableRepairButton();">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            @if ($hasPendingRepair)
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="bg-slate-800 text-slate-300 px-5 py-2 rounded-lg cursor-not-allowed">
                        Menunggu Verifikasi Perbaikan
                    </button>
                    <span class="text-sm text-slate-400">Anda akan mendapat notifikasi setelah proses selesai.</span>
                </div>
            @else
                <div class="flex items-center gap-3">
                    <button type="submit" id="repairSubmitBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                            @if(!$snapshotChanged) disabled @endif>
                        Ajukan Perbaikan
                    </button>
                    <span id="submitSpinner" class="hidden text-sm text-slate-300">Mengirim… mohon tunggu.</span>
                </div>
                <p class="text-xs mt-1 text-yellow-400">⚠️ Setelah pengajuan perbaikan dikirim, data tidak dapat diubah sebelum diverifikasi.</p>
            @endif
        </div>
    </form>
</div>

{{-- ===================== MODALS ===================== --}}

<x-modal id="modalRepairProfile" size="xl" title="Perbarui Data Diri">
    <div class="max-h-[75vh] overflow-y-auto pr-1">
    <form method="POST" action="{{ route('pencaker.profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="repair_mode" value="1">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-slate-400">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">NIK</label>
                <input type="text" name="nik" value="{{ $profile->nik }}"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-400" readonly>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile->tempat_lahir) }}"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $profile->tanggal_lahir) }}"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
            </div>
            <div>
                <label class="block text-sm text-slate-400">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
                    <option value="">- Pilih -</option>
                    @foreach (['Laki-laki','Perempuan'] as $jk)
                        <option value="{{ $jk }}" @selected($profile->jenis_kelamin === $jk)>{{ $jk }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Agama</label>
                <select name="agama" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
                    <option value="">Pilih</option>
                    @foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                        <option value="{{ $agama }}" @selected(old('agama', $profile->agama) == $agama)>{{ $agama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Status Perkawinan</label>
                <select name="status_perkawinan" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
                    <option value="">- Pilih -</option>
                    @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                        <option value="{{ $status }}" @selected($profile->status_perkawinan === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
                <select name="pendidikan_terakhir" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
                    <option value="">Pilih</option>
                    @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                        <option value="{{ $tingkat }}" @selected(old('pendidikan_terakhir', $profile->pendidikan_terakhir) == $tingkat)>{{ $tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm text-slate-400">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" rows="3" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">{{ old('alamat_lengkap', $profile->alamat_lengkap) }}</textarea>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Domisili Kecamatan</label>
                <select name="domisili_kecamatan" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
                    <option value="">- Pilih -</option>
                    @foreach ($kecamatanList as $kecamatan)
                        <option value="{{ $kecamatan }}" @selected($profile->domisili_kecamatan === $kecamatan)>{{ $kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Nomor Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $profile->no_telepon) }}"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalRepairProfile"
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

<x-modal id="modalRepairEducationCreate" title="Tambah Riwayat Pendidikan">
    <form method="POST" action="{{ route('pencaker.education.store') }}" class="space-y-3">
        @csrf
        <input type="hidden" name="repair_mode" value="1">
        <div>
            <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
            <select name="tingkat" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
                <option value="">- Pilih -</option>
                @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                    <option value="{{ $tingkat }}">{{ $tingkat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-slate-400">Nama Institusi / Sekolah</label>
            <input type="text" name="nama_institusi" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
        </div>
        <div>
            <label class="block text-sm text-slate-400">Jurusan</label>
            <input type="text" name="jurusan" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-slate-400">Tahun Mulai</label>
                <input type="number" name="tahun_mulai" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" placeholder="2018">
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tahun Selesai</label>
                <input type="number" name="tahun_selesai" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" placeholder="2022">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalRepairEducationCreate"
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

@foreach ($educations as $edu)
    <x-modal id="modalRepairEducationEdit{{ $edu->id }}" title="Perbarui Riwayat Pendidikan">
        <form method="POST" action="{{ route('pencaker.education.update', $edu->id) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <input type="hidden" name="repair_mode" value="1">
            <div>
                <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
                <select name="tingkat" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
                    @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                        <option value="{{ $tingkat }}" @selected($edu->tingkat === $tingkat)>{{ $tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Nama Institusi / Sekolah</label>
                <input type="text" name="nama_institusi" value="{{ old('nama_institusi', $edu->nama_institusi) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Jurusan</label>
                <input type="text" name="jurusan" value="{{ old('jurusan', $edu->jurusan) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-slate-400">Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai', $edu->tahun_mulai) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
                </div>
                <div>
                    <label class="block text-sm text-slate-400">Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" value="{{ old('tahun_selesai', $edu->tahun_selesai) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="modalRepairEducationEdit{{ $edu->id }}"
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

<x-modal id="modalRepairTrainingCreate" title="Tambah Riwayat Pelatihan">
    <form method="POST" action="{{ route('pencaker.training.store') }}" class="space-y-3" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="repair_mode" value="1">
        <div>
            <label class="block text-sm text-slate-400">Jenis Pelatihan</label>
            <input type="text" name="jenis_pelatihan" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
        </div>
        <div>
            <label class="block text-sm text-slate-400">Lembaga</label>
            <input type="text" name="lembaga_pelatihan" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
        </div>
        <div>
            <label class="block text-sm text-slate-400">Tahun</label>
            <input type="number" name="tahun" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
        </div>
        <div>
            <label class="block text-sm text-slate-400">Sertifikat (PDF/JPG/PNG, maks 2MB)</label>
            <input type="file" name="sertifikat_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full text-sm text-slate-300" required>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-modal="modalRepairTrainingCreate"
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

@foreach ($trainings as $training)
    <x-modal id="modalRepairTrainingEdit{{ $training->id }}" title="Perbarui Riwayat Pelatihan">
        <form method="POST" action="{{ route('pencaker.training.update', $training->id) }}" class="space-y-3" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="repair_mode" value="1">
            <div>
                <label class="block text-sm text-slate-400">Jenis Pelatihan</label>
                <input type="text" name="jenis_pelatihan" value="{{ old('jenis_pelatihan', $training->jenis_pelatihan) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Lembaga</label>
                <input type="text" name="lembaga_pelatihan" value="{{ old('lembaga_pelatihan', $training->lembaga_pelatihan) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tahun</label>
                <input type="number" name="tahun" value="{{ old('tahun', $training->tahun) }}" class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Sertifikat Baru (opsional)</label>
                <input type="file" name="sertifikat_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full text-sm text-slate-300">
                @if ($training->sertifikat_file)
                    <p class="text-xs text-slate-400 mt-1">Dokumen saat ini: <a href="{{ asset('storage/'.$training->sertifikat_file) }}" target="_blank" class="text-blue-300 underline">Lihat</a></p>
                @endif
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close-modal="modalRepairTrainingEdit{{ $training->id }}"
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

<div id="confirmOverlay" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center">
    <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 max-w-md w-full text-slate-100">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Ajukan Perbaikan</h3>
        <p class="text-sm text-slate-300">Apakah Anda yakin ingin mengirim pengajuan perbaikan AK1 kepada admin?</p>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-700" onclick="toggleConfirm(false)">Batal</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700" onclick="submitRepairForm()">Kirim</button>
        </div>
    </div>
</div>

<div id="deleteConfirmOverlay" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center">
    <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 max-w-md w-full text-slate-100">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Hapus Data</h3>
        <p class="text-sm text-slate-300" id="deleteConfirmMessage">Apakah Anda yakin?</p>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-700" onclick="toggleDeleteConfirm(false)">Batal</button>
            <button type="button" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700" onclick="submitDeleteForm()">Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let deleteTargetUrl = null;

    function toggleConfirm(show) {
        document.getElementById('confirmOverlay').classList.toggle('hidden', !show);
    }

    function submitRepairForm() {
        const form = document.getElementById('repairForm');
        const spinner = document.getElementById('submitSpinner');
        toggleConfirm(false);
        if (spinner) spinner.classList.remove('hidden');
        form.dataset.confirmed = 'true';
        form.submit();
    }

    function enableRepairButton() {
        const btn = document.getElementById('repairSubmitBtn');
        if (btn) btn.disabled = false;
    }

    const repairForm = document.getElementById('repairForm');
    if (repairForm) {
        repairForm.addEventListener('submit', function (e) {
            if (repairForm.dataset.confirmed === 'true') {
                return;
            }
            e.preventDefault();
            toggleConfirm(true);
        });
    }

    function openDeleteConfirm(targetUrl, message) {
        deleteTargetUrl = targetUrl;
        document.getElementById('deleteConfirmMessage').textContent = message || 'Apakah Anda yakin?';
        toggleDeleteConfirm(true);
    }

    function toggleDeleteConfirm(show) {
        document.getElementById('deleteConfirmOverlay').classList.toggle('hidden', !show);
    }

    function submitDeleteForm() {
        if (!deleteTargetUrl) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteTargetUrl;
        form.classList.add('hidden');

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
        form.remove();

        toggleDeleteConfirm(false);
        deleteTargetUrl = null;
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('fotoPreview');
            if (preview) preview.src = reader.result;
        };
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
<script>
// Dropzone inline (gambar/PDF) – sama seperti halaman pengajuan baru
if (!window.__dzInlineDefined) {
  document.addEventListener('alpine:init', () => {
    Alpine.data('dropzoneInline', (inputSel, initialUrl = '', initialType = 'image', initialName = '') => ({
      dragging: false,
      src: initialType === 'image' ? initialUrl : '',
      isPdf: initialType === 'pdf',
      fileName: initialName,
      fileUrl: initialType === 'pdf' ? initialUrl : '',
      get hasPreview() { return this.isPdf || !!this.src; },
      browse() {
        const input = document.querySelector(inputSel);
        if (input && !input.disabled) input.click();
      },
      handleChange(e) {
        const file = e.target.files?.[0];
        if (file) this.processFile(file);
      },
      handleDrop(e) {
        this.dragging = false;
        const file = e.dataTransfer?.files?.[0];
        if (!file) return;
        if (!['image/jpeg','image/png','application/pdf'].includes(file.type)) {
          (window.Toastify ? Toastify({text:'Format tidak didukung. Gunakan JPG/PNG/PDF.',duration:3500,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast() : alert('Format tidak didukung. Gunakan JPG/PNG/PDF.'));
          return;
        }
        const MAX = 1 * 1024 * 1024; // 1MB
        const MIN = 20 * 1024; // 20KB
        if (file.size > MAX) {
          (window.Toastify ? Toastify({text:'Ukuran berkas melebihi 1MB.',duration:3500,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast() : alert('Ukuran berkas melebihi 1MB.'));
          return;
        }
        if (file.size < MIN) {
          (window.Toastify ? Toastify({text:'Ukuran berkas terlalu kecil (minimal 20KB).',duration:3500,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast() : alert('Ukuran berkas terlalu kecil (minimal 20KB).'));
          return;
        }
        const input = document.querySelector(inputSel);
        if (input) {
          const dt = new DataTransfer();
          dt.items.add(file);
          input.files = dt.files;
        }
        this.processFile(file);
      },
      processFile(file) {
        if (file.type === 'application/pdf') {
          this.isPdf = true; this.src = ''; this.fileName = file.name; this.fileUrl = URL.createObjectURL(file);
        } else {
          const reader = new FileReader();
          reader.onload = () => { this.src = reader.result; this.isPdf = false; this.fileName=''; this.fileUrl=''; };
          reader.readAsDataURL(file);
        }
        if (window.enableRepairButton) window.enableRepairButton();
      },
    }));
  });
  window.__dzInlineDefined = true;
}
</script>
@endpush
