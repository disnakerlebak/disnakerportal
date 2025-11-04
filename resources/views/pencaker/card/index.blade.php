@extends('layouts.pencaker')
@section('title', 'Pengajuan Kartu (AK1)')
@section('content')
  <!-- <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-gray-900 dark:text-gray-200">
  @if (session('success'))
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
      $editable = !$application || in_array($application->status, ['Ditolak', 'Revisi Diminta']);
      $fotoDoc = $application?->documents->firstWhere('type', 'foto_closeup');
      $ktpDoc = $application?->documents->firstWhere('type', 'ktp_file');
      $ijazahDoc = $application?->documents->firstWhere('type', 'ijazah_file');
    @endphp

    
    {{-- ====== FORM PENGAJUAN AK1 ====== --}}
    <form id="ak1Form"
          method="POST"
          action="{{ route('pencaker.card.store') }}"
          enctype="multipart/form-data"
          class="space-y-8">
      @if ($application && in_array($application->status, ['Revisi Diminta', 'Ditolak']))
        <input type="hidden" name="is_resubmission" value="1">
      @endif
      @csrf
    {{-- Judul --}}
    <h2 class="text-2xl font-semibold text-white mb-6">
      Pengajuan Kartu Pencari Kerja (AK1)
    </h2>
    {{-- Status Pengajuan --}}
@if ($application)
  <div class="rounded-xl border border-dashed border-blue-300/40 bg-blue-50/50 px-6 py-5 text-blue-900 shadow-sm dark:border-blue-400/30 dark:bg-blue-500/10 dark:text-blue-100 mb-6">
    <p class="text-gray-300 text-sm">Status Pengajuan:</p>
    <p class="text-lg font-semibold
      @class([
        'text-yellow-400' => $application->status === 'Revisi Diminta',
        'text-green-400'  => $application->status === 'Disetujui',
        'text-red-400'    => $application->status === 'Ditolak',
        'text-gray-300'   => $application->status === 'Menunggu Verifikasi',
        'text-blue-400'   => $application->status === 'Menunggu Revisi Verifikasi',
      ])">
      {{ $application->status }}
    </p>

    @if ($application->nomor_ak1)
      <p class="text-sm text-gray-400">Nomor AK1: {{ $application->nomor_ak1 }}</p>
    @endif
    @php
        $latestNote = $application->logs->first()?->notes;
    @endphp

    @if ($latestNote && in_array($application->status, ['Ditolak', 'Revisi Diminta', 'Menunggu Revisi Verifikasi']))
        <div class="mt-3 rounded-lg border border-yellow-500/40 bg-yellow-500/10 px-3 py-2 text-sm text-yellow-100">
            <p class="font-semibold uppercase tracking-wide text-yellow-300">Catatan Admin</p>
            <p class="mt-1 leading-relaxed text-yellow-100">{{ $latestNote }}</p>
        </div>
    @endif
  </div>

  {{-- Notifikasi Status --}}
  @if($application->status === 'Revisi Diminta')
      <div class="bg-yellow-900 text-yellow-200 px-4 py-2 rounded-md mb-4">
          ⚠️ Admin meminta revisi pada dokumen Anda. Silakan unggah ulang dokumen yang diminta lalu ajukan ulang.
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
    <p class="mb-6 text-sm md:text-base text-gray-300 leading-relaxed">
      Pastikan seluruh data berikut sudah benar dan sesuai dengan dokumen resmi Anda.
      Jika masih ada kesalahan, ubah terlebih dahulu pada halaman
      <a href="{{ route('pencaker.profile') }}" class="text-blue-500 hover:underline">Data Diri</a>,
      <a href="{{ route('pencaker.education.index') }}" class="text-blue-400 hover:underline">Pendidikan</a>,
      atau
      <a href="{{ route('pencaker.training.index') }}" class="text-blue-400 hover:underline">Pelatihan</a>.
    </p>

            {{-- ===================== FOTO + DATA DIRI ===================== --}}
    <div class="rounded-2xl bg-gray-800 shadow-lg">
      <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-10 lg:px-10">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
          {{-- Kolom Foto --}}
          <div class="flex flex-col items-center justify-start gap-3 text-sm text-gray-400 lg:items-start">
            <div class="relative h-52 w-44 overflow-hidden rounded-xl border border-gray-700/60 bg-gray-800 shadow-md sm:h-56 sm:w-48">
              <img id="fotoPreview"
                   src="{{ $fotoDoc ? asset('storage/' . $fotoDoc->file_path) : asset('images/placeholder-avatar.png') }}"
                   alt="Foto Close-Up"
                   class="h-full w-full object-cover" />
              @if($editable)
                <label for="fotoCloseup"
                       class="absolute inset-x-0 bottom-0 bg-black/60 py-2 text-center text-xs text-gray-100 transition hover:bg-black/75">
                  Ganti Foto
                </label>
              @endif
            </div>
            <p class="text-center text-xs text-gray-400 sm:text-left">Format: JPG/PNG &bull; Maks: 2 MB</p>
            <input id="fotoCloseup" name="foto_closeup" type="file" accept="image/*"
                   class="hidden" onchange="previewImage(event)" @disabled(!$editable) @if($editable && !$fotoDoc) required @endif>
          </div>

          {{-- Kolom Data Diri --}}
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-white sm:text-xl">Data Diri</h3>
            <p class="mt-1 text-sm text-gray-400">Pastikan informasi sesuai dengan dokumen kependudukan.</p>

            <dl class="mt-6 grid grid-cols-1 gap-4 text-sm text-gray-200 sm:grid-cols-2">
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">NIK</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nik ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Status</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->status_perkawinan ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Nama Lengkap</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nama_lengkap ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Agama</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->agama ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Tempat Lahir</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->tempat_lahir ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Tanggal Lahir</dt>
                <dd class="mt-1 text-base font-semibold text-white">
                    {{ $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-' }}
                </dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Jenis Kelamin</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->jenis_kelamin ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Pendidikan Terakhir</dt>
                <dd class="mt-1 text-base font-semibold text-white">{{ $profile->pendidikan_terakhir ?? '-' }}</dd>
              </div>
              <div class="rounded-lg border border-gray-800/60 bg-gray-700/60 px-4 py-3 shadow-sm sm:col-span-2">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Alamat Domisili</dt>
                <dd class="mt-1 text-base font-semibold text-white leading-relaxed">{{ $profile->alamat_lengkap ?? '-' }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{-- ===================== RIWAYAT PENDIDIKAN ===================== --}}
    <div class=" bg-gray-800 rounded-2xl mt-8">
      <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <h3 class="text-lg font-semibold text-white mb-4">Riwayat Pendidikan</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm md:text-base border-collapse text-gray-300">
            <thead class="bg-gray-700 text-gray-200">
              <tr>
                <th class="p-3 text-left">Tingkat</th>
                <th class="p-3 text-left">Lembaga / Sekolah</th>
                <th class="p-3 text-left">Jurusan</th>
                <th class="p-3 text-left">Tahun</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($educations as $edu)
                <tr class="border-b border-gray-700">
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
    <div class="bg-gray-800 rounded-2xl mt-8">
      <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <h3 class="text-lg font-semibold text-white mb-4">Riwayat Pelatihan</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm md:text-base border-collapse text-gray-300">
            <thead class="bg-gray-700 text-gray-200">
              <tr>
                <th class="p-3 text-left">Jenis Pelatihan</th>
                <th class="p-3 text-left">Lembaga</th>
                <th class="p-3 text-left">Tahun</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($trainings as $training)
                <tr class="border-b border-gray-700">
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
      <div class="bg-gray-800 rounded-2xl mt-8">
        <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
          <h3 class="text-lg font-semibold text-white mb-4">Unggah Dokumen Wajib</h3>

          {{-- KTP --}}
          <div class="mb-5">
            <label class="block font-medium mb-2">KTP (Wajib)</label>
            @if ($ktpDoc)
              <a href="{{ asset('storage/' . $ktpDoc->file_path) }}" target="_blank">
                <img src="{{ asset('storage/' . $ktpDoc->file_path) }}" alt="KTP" class="w-40 h-24 object-cover rounded border border-gray-600 mb-2">
              </a>
            @endif
            <input type="file" name="ktp_file" id="ktpFile" accept=".jpg,.jpeg,.png,.pdf"
                   class="block w-full text-sm text-gray-300"
                   @disabled(!$editable) @if($editable && !$ktpDoc) required @endif>
          </div>

          {{-- Ijazah --}}
          <div class="mb-6">
            <label class="block font-medium mb-2">Ijazah Terakhir (Wajib)</label>
            @if ($ijazahDoc)
              <a href="{{ asset('storage/' . $ijazahDoc->file_path) }}" target="_blank">
                <img src="{{ asset('storage/' . $ijazahDoc->file_path) }}" alt="Ijazah" class="w-40 h-24 object-cover rounded border border-gray-600 mb-2">
              </a>
            @endif
            <input type="file" name="ijazah_file" id="ijazahFile" accept=".jpg,.jpeg,.png,.pdf"
                   class="block w-full text-sm text-gray-300"
                   @disabled(!$editable) @if($editable && !$ijazahDoc) required @endif>
          </div>

          {{-- Tombol Submit --}}
            @if ($editable)
              <div class="flex items-center gap-3">
                {{-- Tombol untuk pengajuan baru --}}
                @if (!$application)
                  <button type="submit" id="submitBtn"
                          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                    Kirim Pengajuan AK1
                  </button>

                {{-- Tombol untuk pengajuan ulang setelah revisi atau ditolak --}}
                @elseif(in_array($application->status, ['Revisi Diminta', 'Ditolak']))
                  <button type="submit" id="resubmitBtn"
                          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                    Ajukan Ulang
                  </button>
                
                {{-- Jika sedang menunggu verifikasi revisi --}}
                @elseif($application->status === 'Menunggu Revisi Verifikasi')
                  <button type="button" disabled
                          class="bg-gray-700 text-gray-400 px-5 py-2 rounded-lg cursor-not-allowed">
                    Menunggu Verifikasi Ulang
                  </button>

                {{-- Jika sudah disetujui --}}
                @elseif($application->status === 'Disetujui')
                  <a href="{{ route('pencaker.card.cetak', $application->id) }}" target="_blank"
                    class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg">
                    Unduh Kartu AK1
                  </a>
                @endif

                <span id="submitSpinner" class="hidden text-sm text-gray-300">Mengirim… mohon tunggu.</span>
              </div>
            <p class="text-xs mt-1 text-yellow-400">
              ⚠️ Setelah pengajuan dikirim, data tidak dapat diubah sebelum diverifikasi oleh petugas Disnaker.
            </p>
          @else
            <p class="text-gray-400 text-sm italic">
              📌 Anda tidak dapat mengubah foto atau dokumen selama pengajuan masih diproses atau sudah disetujui.
            </p>
            @if ($application && $application->status === 'Disetujui')
              <div class="mt-3">
                <a href="{{ route('pencaker.card.cetak', $application->id) }}" target="_blank"
                   class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg">
                  Unduh Kartu AK1
                </a>
              </div>
            @endif
          @endif
       </div>
      </div>
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
      form.addEventListener('submit', () => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        if (spn) spn.classList.remove('hidden');
      });
    }
  </script>
@endsection
