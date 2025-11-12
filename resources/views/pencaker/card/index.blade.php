@extends('layouts.pencaker')
@section('title', 'Pengajuan Kartu (AK1)')
@section('content')
<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100">
  <!-- @if (session('success'))
    <div class="mb-4 bg-green-800 border border-green-600 text-green-100 px-4 py-3 rounded">
      ✅ {{ session('success') }}
    </div>
  @endif -->

  <!-- {{-- ALERTS (gagal / error validasi) --}}
    @if (session('error'))
      <div class="mb-4 rounded-lg bg-red-600/20 border border-red-600 text-red-200 px-4 py-3">
        {{ session('error') }}
      </div>
    @endif -->

    @if ($errors->any())
      <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3">
        <div class="font-semibold mb-1">Periksa isian berikut:</div>
        <ul class="list-disc ms-5">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $editable = !$application || in_array($application->status, ['Ditolak', 'Revisi Diminta', 'Batal']);
      $fotoDoc = $application?->documents->firstWhere('type', 'foto_closeup');
      $ktpDoc = $application?->documents->firstWhere('type', 'ktp_file');
      $ijazahDoc = $application?->documents->firstWhere('type', 'ijazah_file');
      // Data helper untuk preview awal (mendukung PDF)
      $ktpPath = $ktpDoc?->file_path;
      $ktpUrl  = $ktpPath ? asset('storage/' . $ktpPath) : '';
      $ktpName = $ktpPath ? basename($ktpPath) : '';
      $ktpType = ($ktpPath && str_ends_with(strtolower($ktpPath), '.pdf')) ? 'pdf' : 'image';

      $ijPath = $ijazahDoc?->file_path;
      $ijUrl  = $ijPath ? asset('storage/' . $ijPath) : '';
      $ijName = $ijPath ? basename($ijPath) : '';
      $ijType = ($ijPath && str_ends_with(strtolower($ijPath), '.pdf')) ? 'pdf' : 'image';
    @endphp

    
    {{-- ====== FORM PENGAJUAN AK1 ====== --}}
    <form id="ak1Form" novalidate
          method="POST"
          action="{{ route('pencaker.card.store') }}"
          enctype="multipart/form-data"
          class="space-y-8">
      @if ($application && in_array($application->status, ['Revisi Diminta', 'Ditolak', 'Batal']))
        <input type="hidden" name="is_resubmission" value="1">
      @endif
      @csrf
    {{-- Judul --}}
    <h2 class="text-2xl font-semibold text-white mb-6">
      Pengajuan Kartu Pencari Kerja (AK1)
    </h2>
    {{-- Status Pengajuan --}}
@if ($application)
  <div class="mb-6 rounded-2xl border border-dashed border-indigo-500/40 bg-slate-900/70 px-6 py-5 text-slate-100 shadow-lg">
    <p class="text-slate-300 text-sm uppercase tracking-wide">Status Pengajuan</p>
    <p class="text-lg font-semibold
      @class([
        'text-yellow-400' => in_array($application->status, ['Revisi Diminta','Batal']),
        'text-green-400'  => $application->status === 'Disetujui',
        'text-red-400'    => $application->status === 'Ditolak',
        'text-slate-300'   => $application->status === 'Menunggu Verifikasi',
        'text-blue-400'   => $application->status === 'Menunggu Revisi Verifikasi',
      ])">
      {{ $application->status }}
    </p>

    @if ($application->nomor_ak1)
      <p class="text-sm text-slate-400">Nomor AK1: {{ $application->nomor_ak1 }}</p>
    @endif
    @php
        $latestNote = $application->logs->first()?->notes;
    @endphp

    @if ($latestNote && in_array($application->status, ['Ditolak', 'Revisi Diminta', 'Batal', 'Menunggu Revisi Verifikasi']))
        <div class="mt-3 rounded-lg border border-yellow-500/40 bg-yellow-500/10 px-3 py-2 text-sm text-yellow-100">
            <p class="font-semibold uppercase tracking-wide text-yellow-300">Catatan Admin</p>
            <p class="mt-1 leading-relaxed text-yellow-100">{{ $latestNote }}</p>
        </div>
    @endif
  </div>

  {{-- Notifikasi Status --}}
  @if(in_array($application->status, ['Revisi Diminta','Batal']))
      <div class="bg-yellow-900 text-yellow-200 px-4 py-2 rounded-md mb-4">
          ⚠️ Pengajuan Anda memerlukan pembaruan. Silakan perbaiki data/dokumen lalu ajukan ulang.
      </div>
  @elseif($application->status === 'Menunggu Revisi Verifikasi')
      <div class="bg-blue-900 text-blue-200 px-4 py-2 rounded-md mb-4">
          ⏳ Pengajuan revisi Anda sedang menunggu verifikasi admin. Harap menunggu hasil pemeriksaan.
      </div>
  @elseif($application->status === 'Ditolak')
      <div class="bg-red-900 text-red-200 px-4 py-2 rounded-md mb-4">
          ❌ Pengajuan Anda ditolak. Silakan perbaiki data atau dokumen dan ajukan kembali.
      </div>
  @elseif($application->status === 'Disetujui')
      <div class="bg-green-900 text-green-200 px-4 py-2 rounded-md mb-4">
          ✅ Pengajuan Anda telah disetujui. Silakan unduh kartu AK1 pada menu yang tersedia.
      </div>
  @endif
@endif

    {{-- Catatan --}}
    <div class="mb-6 rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-amber-100">
      <p class="text-sm md:text-base leading-relaxed">
        Pastikan seluruh data berikut sudah benar dan sesuai dengan dokumen resmi Anda. Lengkapi data diri dengan mengunggah foto, mengunggah KTP dan Ijazah Terakhir sebelum mengajukan kartu AK1.
        <br>Jika masih ada kesalahan, ubah terlebih dahulu pada halaman
        <a href="{{ route('pencaker.profile') }}" class="text-amber-300 underline underline-offset-2">Data Diri</a>,
        <a href="{{ route('pencaker.education.index') }}" class="text-amber-300 underline underline-offset-2">Pendidikan</a>,
        atau
        <a href="{{ route('pencaker.training.index') }}" class="text-amber-300 underline underline-offset-2">Pelatihan</a>.
      </p>
    </div>

            {{-- ===================== FOTO + DATA DIRI ===================== --}}
    <div class="rounded-2xl bg-slate-900 shadow-lg">
      <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-10 lg:px-10">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
          {{-- Kolom Foto --}}
          <div class="flex flex-col items-center justify-start gap-3 text-sm text-slate-400 lg:items-start">
            <div class="relative h-52 w-44 overflow-hidden rounded-xl border border-slate-800/60 bg-slate-900 shadow-md sm:h-56 sm:w-48">
              <img id="fotoPreview"
                   src="{{ $fotoDoc ? asset('storage/' . $fotoDoc->file_path) : asset('images/placeholder-avatar.png') }}"
                   alt="Foto Close-Up"
                   class="h-full w-full object-cover" />
              @if($editable)
                <label for="fotoCloseup"
                       class="absolute inset-x-0 bottom-0 bg-black/60 py-2 text-center text-xs text-slate-100 transition hover:bg-black/75">
                  Ganti Foto
                </label>
              @endif
            </div>
            <p class="text-center text-xs text-slate-400 sm:text-left">Format: JPG/PNG &bull; Maks: 2 MB</p>
            <input id="fotoCloseup" name="foto_closeup" type="file" accept="image/*"
                   class="hidden" onchange="previewImage(event)" @disabled(!$editable) @if($editable && !$fotoDoc) required @endif>
          </div>

          {{-- Kolom Data Diri --}}
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-white sm:text-xl">Data Diri</h3>
            <p class="mt-1 text-sm text-slate-400">Pastikan informasi sesuai dengan dokumen kependudukan.</p>

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
                <dd class="mt-1 text-base font-semibold text-white">
                    {{ $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-' }}
                </dd>
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

    {{-- ===================== RIWAYAT PENDIDIKAN ===================== --}}
    <div class=" bg-slate-900 rounded-2xl mt-8">
      <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <h3 class="text-lg font-semibold text-white mb-4">Riwayat Pendidikan</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm md:text-base border-collapse text-slate-300">
            <thead class="bg-slate-800 text-slate-200">
              <tr>
                <th class="p-3 text-left">Tingkat</th>
                <th class="p-3 text-left">Lembaga / Sekolah</th>
                <th class="p-3 text-left">Jurusan</th>
                <th class="p-3 text-left">Tahun</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($educations as $edu)
                <tr class="border-b border-slate-800">
                  <td class="p-3">{{ $edu->tingkat }}</td>
                  <td class="p-3">{{ $edu->nama_institusi }}</td>
                  <td class="p-3">{{ $edu->jurusan ?: '-' }}</td>
                  <td class="p-3">{{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ===================== RIWAYAT PELATIHAN ===================== --}}
    <div class="bg-slate-900 rounded-2xl mt-8">
      <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <h3 class="text-lg font-semibold text-white mb-4">Riwayat Pelatihan</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm md:text-base border-collapse text-slate-300">
            <thead class="bg-slate-800 text-slate-200">
              <tr>
                <th class="p-3 text-left">Jenis Pelatihan</th>
                <th class="p-3 text-left">Lembaga</th>
                <th class="p-3 text-left">Tahun</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($trainings as $training)
                <tr class="border-b border-slate-800">
                  <td class="p-3">{{ $training->jenis_pelatihan }}</td>
                  <td class="p-3">{{ $training->lembaga_pelatihan }}</td>
                  <td class="p-3">{{ $training->tahun }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ===================== UNGGAH DOKUMEN ===================== --}}
      <div class="bg-slate-900 rounded-2xl mt-8">
        <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
          <h3 class="text-lg font-semibold text-white mb-4">Unggah Dokumen Wajib</h3>

          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Dropzone KTP (inline preview) --}}
            <div x-data='dropzoneInline("#ktpFile", @json($ktpUrl), @json($ktpType), @json($ktpName))' class="space-y-3">
              <label class="block font-medium">KTP (Wajib)</label>
              <div
                class="relative rounded-2xl border border-dashed border-slate-600/60 bg-slate-800/40 p-0 text-center text-slate-300 cursor-pointer hover:border-slate-500 transition overflow-hidden"
                :class="{ 'ring-2 ring-indigo-500': dragging }"
                @click.prevent="browse()"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="handleDrop($event)"
              >
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
                      <template x-if="fileUrl">
                        <a :href="fileUrl" target="_blank" class="text-indigo-400 underline text-xs">Lihat</a>
                      </template>
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
              <input type="file" name="ktp_file" id="ktpFile" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                     @disabled(!$editable) @if($editable && !$ktpDoc) required @endif @change="handleChange($event)">
            </div>

            {{-- Dropzone Ijazah (inline preview) --}}
            <div x-data='dropzoneInline("#ijazahFile", @json($ijUrl), @json($ijType), @json($ijName))' class="space-y-3">
              <label class="block font-medium">Ijazah Terakhir (Wajib)</label>
              <div
                class="relative rounded-2xl border border-dashed border-slate-600/60 bg-slate-800/40 p-0 text-center text-slate-300 cursor-pointer hover:border-slate-500 transition overflow-hidden"
                :class="{ 'ring-2 ring-indigo-500': dragging }"
                @click.prevent="browse()"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="handleDrop($event)"
              >
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
                      <template x-if="fileUrl">
                        <a :href="fileUrl" target="_blank" class="text-indigo-400 underline text-xs">Lihat</a>
                      </template>
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
              <input type="file" name="ijazah_file" id="ijazahFile" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                     @disabled(!$editable) @if($editable && !$ijazahDoc) required @endif @change="handleChange($event)">
            </div>
          </div>

       </div>
      </div>
    </div>
    {{-- END Unggah Dokumen --}}

    {{-- Aksi Pengajuan (di luar card unggah) --}}
    @if ($editable)
      <div class="mx-auto max-w-6xl px-4 sm:px-8 lg:px-10 mt-6 flex items-center gap-3">
        @if (!$application)
          <button type="submit" id="submitBtn"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
            Kirim Pengajuan AK1
          </button>
        @elseif(in_array($application->status, ['Revisi Diminta', 'Ditolak', 'Batal']))
          <button type="submit" id="resubmitBtn"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
            Ajukan Ulang
          </button>
        @elseif($application->status === 'Menunggu Revisi Verifikasi')
          <button type="button" disabled
                  class="bg-slate-800 text-slate-400 px-5 py-2 rounded-lg cursor-not-allowed">
            Menunggu Verifikasi Ulang
          </button>
        @elseif($application->status === 'Disetujui')
          <a href="{{ route('pencaker.card.cetak', $application->id) }}" target="_blank"
            class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg">
            Unduh Kartu AK1
          </a>
        @endif
        <span id="submitSpinner" class="hidden text-sm text-slate-300">Mengirim… mohon tunggu.</span>
      </div>
      <p class="mx-auto max-w-6xl px-4 sm:px-8 lg:px-10 text-xs mt-2 text-amber-300 py-2 inline-block">
        ⚠️ Setelah pengajuan dikirim, data tidak dapat diubah sebelum diverifikasi oleh petugas Disnaker.
      </p>
    @else
      <p class="mx-auto max-w-6xl px-4 sm:px-8 lg:px-10 text-slate-400 text-sm italic mt-3">
        📌 Anda tidak dapat mengubah foto atau dokumen selama pengajuan masih diproses atau sudah disetujui.
      </p>
      @if ($application && $application->status === 'Disetujui')
        <div class="mx-auto max-w-6xl px-4 sm:px-8 lg:px-10 mt-3">
          <a href="{{ route('pencaker.card.cetak', $application->id) }}" target="_blank"
             class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg">
            Unduh Kartu AK1
          </a>
        </div>
      @endif
    @endif
  </form>
  
    {{-- Script Preview Foto + cegah double submit --}}
  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById('fotoPreview').src = reader.result;
      };
      if (event.target.files && event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
      }
    }

    const form = document.getElementById('ak1Form');
    const spn  = document.getElementById('submitSpinner');

    if (form) {
      form.addEventListener('submit', (e) => {
        if (form.dataset.confirmed === 'true') {
          return;
        }
        // Validasi wajib unggah foto, KTP, dan Ijazah bila editable
        const editable = {{ $editable ? 'true' : 'false' }};
        if (editable) {
          const MAX = 1 * 1024 * 1024; // 1MB
          const MIN = 20 * 1024; // 20KB

          const fotoInp = document.getElementById('fotoCloseup');
          const ktpInp  = document.getElementById('ktpFile');
          const ijInp   = document.getElementById('ijazahFile');

          const hasFoto = {{ $fotoDoc ? 'true' : 'false' }} || (fotoInp?.files?.length > 0);
          const hasKtp  = {{ $ktpDoc ? 'true' : 'false' }}  || (ktpInp?.files?.length > 0);
          const hasIj   = {{ $ijazahDoc ? 'true' : 'false' }} || (ijInp?.files?.length > 0);
          if (!hasFoto || !hasKtp || !hasIj) {
            e.preventDefault();
            if (window.Toastify) {
              Toastify({
                text: 'Lengkapi unggahan: Foto, KTP, dan Ijazah wajib diunggah.',
                duration: 4000,
                close: true,
                gravity: 'bottom',
                position: 'right',
                backgroundColor: '#dc2626',
              }).showToast();
            } else {
              alert('Lengkapi unggahan: Foto Close-up, KTP, dan Ijazah Terakhir wajib diunggah.');
            }
            return;
          }

          const all = [fotoInp, ktpInp, ijInp].filter(Boolean).flatMap(i => Array.from(i.files || []));
          for (const f of all) {
            if (f.size > MAX) {
              e.preventDefault();
              Toastify?.({text:`Ukuran berkas ${f.name} melebihi 1MB.`,duration:4000,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast();
              return;
            }
            if (f.size < MIN) {
              e.preventDefault();
              Toastify?.({text:`Ukuran berkas ${f.name} terlalu kecil (minimal 20KB).`,duration:4000,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast();
              return;
            }
          }
          // Jika lolos validasi, munculkan modal konfirmasi lebih dulu
          e.preventDefault();
          toggleSubmitConfirm(true);
          return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        if (spn) spn.classList.remove('hidden');
      });
    }
  </script>
  <script>
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
          if (file.size > 2 * 1024 * 1024) {
            (window.Toastify ? Toastify({text:'Ukuran berkas melebihi 2MB.',duration:3500,backgroundColor:'#f59e0b',gravity:'bottom',position:'right',close:true}).showToast() : alert('Ukuran berkas melebihi 2MB.'));
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
            this.isPdf = true;
            this.src = '';
            this.fileName = file.name;
            this.fileUrl = URL.createObjectURL(file);
          } else {
            const reader = new FileReader();
            reader.onload = () => { this.src = reader.result; this.isPdf = false; this.fileName=''; this.fileUrl=''; };
            reader.readAsDataURL(file);
          }
        },
      }));
    });
  </script>
  <div id="confirmOverlay" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center">
    <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 max-w-md w-full text-slate-100">
      <h3 class="text-lg font-semibold mb-2">Konfirmasi Pengajuan AK1</h3>
      <p class="text-sm text-slate-300">Apakah Anda yakin seluruh data dan dokumen sudah benar dan ingin mengirim pengajuan AK1 ke admin?</p>
      <div class="mt-5 flex justify-end gap-3">
        <button type="button" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-700" onclick="toggleSubmitConfirm(false)">Batal</button>
        <button type="button" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700" onclick="submitAk1Form()">Kirim</button>
      </div>
    </div>
  </div>
  <script>
    function toggleSubmitConfirm(show){
      const el = document.getElementById('confirmOverlay');
      if(el) el.classList.toggle('hidden', !show);
    }
    function submitAk1Form(){
      const form = document.getElementById('ak1Form');
      const spn  = document.getElementById('submitSpinner');
      toggleSubmitConfirm(false);
      if (spn) spn.classList.remove('hidden');
      form.dataset.confirmed = 'true';
      form.submit();
    }
  </script>
@endsection
