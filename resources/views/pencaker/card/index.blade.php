<x-app-layout>
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-gray-200">
  <!-- {{-- ALERTS (sukses / gagal / error validasi) --}}
    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-600/20 border border-green-600 text-green-200 px-4 py-3">
        {{ session('success') }}
      </div>
    @endif

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
      @csrf
    {{-- Judul --}}
    <h2 class="text-2xl font-semibold text-white mb-6">
      Pengajuan Kartu Pencari Kerja (AK1)
    </h2>
    {{-- Status Pengajuan --}}
@if ($application)
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-6">
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
      <a href="{{ route('pencaker.profile.edit') }}" class="text-blue-400 hover:underline">Data Diri</a>,
      <a href="{{ route('pencaker.education.index') }}" class="text-blue-400 hover:underline">Pendidikan</a>,
      atau
      <a href="{{ route('pencaker.training.index') }}" class="text-blue-400 hover:underline">Pelatihan</a>.
    </p>

            {{-- ===================== FOTO + DATA DIRI ===================== --}}
            <div class="bg-gray-800 rounded-2xl">
        <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
          <div class="grid md:grid-cols-[260px_minmax(0,1fr)] gap-6 lg:gap-12 items-start">

            {{-- Kolom Foto --}}
            <div class="flex flex-col items-center md:items-start justify-self-center md:justify-self-start">
              <div class="relative w-48 h-56 bg-gray-700 rounded-lg overflow-hidden shadow-md border border-gray-600">
                <img id="fotoPreview"
                     src="{{ $fotoDoc ? asset('storage/' . $fotoDoc->file_path) : asset('images/placeholder-avatar.png') }}"
                     alt="Foto Close-Up"
                     class="object-cover w-full h-full" />
                @if($editable)
                  <label for="fotoCloseup"
                        class="absolute bottom-0 w-full bg-black/60 text-center py-2 text-xs text-gray-200 cursor-pointer hover:bg-black/75 transition">
                    Ganti Foto
                  </label>
                @endif
              </div>
              <input id="fotoCloseup" name="foto_closeup" type="file" accept="image/*"
                     class="hidden" onchange="previewImage(event)" @disabled(!$editable)>
              <p class="text-xs text-gray-400 mt-2">Format: JPG/PNG | Maks: 2 MB</p>
            </div>

          {{-- Kolom Data Diri --}}
          <div class="md:col-span-1 lg:pl-2">
            <h3 class="text-lg font-semibold text-white mb-4">Data Diri</h3>
            <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm md:text-base text-gray-200">
              <p><span class="font-medium w-40 inline-block">NIK</span>: {{ $profile->nik ?? '-' }}</p>
              <p><span class="font-medium w-40 inline-block">Status</span>: {{ $profile->status_perkawinan ?? '-' }}</p>

              <p><span class="font-medium w-40 inline-block">Nama Lengkap</span>: {{ $profile->nama_lengkap ?? '-' }}</p>
              <p><span class="font-medium w-40 inline-block">Agama</span>: {{ $profile->agama ?? '-' }}</p>

              <p><span class="font-medium w-40 inline-block">Tempat Lahir</span>: {{ $profile->tempat_lahir ?? '-' }}</p>
              <p>  <span class="font-medium w-40 inline-block">Tanggal Lahir</span>:
              {{ indoDateOnly($profile->tanggal_lahir) }}</p>

              <p><span class="font-medium w-40 inline-block">Jenis Kelamin</span>: {{ $profile->jenis_kelamin ?? '-' }}</p>
              <p class="col-span-2"><span class="font-medium w-40 inline-block">Alamat Domisili</span>: {{ $profile->alamat_lengkap ?? '-' }}</p>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- ===================== RIWAYAT PENDIDIKAN ===================== --}}
    <div class="bg-gray-800 rounded-2xl mt-8">
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
                   class="block w-full text-sm text-gray-300" @disabled(!$editable)>
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
                   class="block w-full text-sm text-gray-300" @disabled(!$editable)>
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
</x-app-layout>
