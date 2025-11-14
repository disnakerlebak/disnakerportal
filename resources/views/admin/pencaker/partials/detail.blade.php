<div class="space-y-6 text-gray-200">
  <div class="grid md:grid-cols-[240px,1fr] gap-6">
    <div>
      <div class="text-sm text-gray-400 mb-2">Foto Close-Up</div>
      @php
        $fotoUrl = isset($fotoPath) && $fotoPath ? asset('storage/'.$fotoPath) : asset('images/placeholder-avatar.png');
      @endphp
      <img src="{{ $fotoUrl }}" alt="Foto Close-Up" class="w-56 h-64 object-cover rounded border border-gray-700" />
    </div>
    <div>
      <h3 class="text-lg font-semibold mb-3">Data Diri</h3>
      <div class="grid md:grid-cols-2 gap-y-2 text-sm">
        <div><span class="text-gray-400 w-40 inline-block">Nama</span>: {{ $profile->nama_lengkap ?? $user->name }}</div>
        <div><span class="text-gray-400 w-40 inline-block">NIK</span>: {{ $profile->nik ?? '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">Tempat Lahir</span>: {{ $profile->tempat_lahir ?? '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">Tanggal Lahir</span>: {{ isset($profile->tanggal_lahir) ? indoDateOnly($profile->tanggal_lahir) : '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">Jenis Kelamin</span>: {{ $profile->jenis_kelamin ?? '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">Agama</span>: {{ $profile->agama ?? '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">Kecamatan</span>: {{ $profile->kecamatan ?? $profile->domisili_kecamatan ?? '-' }}</div>
        <div><span class="text-gray-400 w-40 inline-block">No. HP</span>: {{ $profile->no_hp ?? $profile->no_telepon ?? '-' }}</div>
        <div class="md:col-span-2">
          <span class="text-gray-400 w-40 inline-block align-top">Email</span>:
          <span class="inline-block break-all">
            {{ $profile->email_cache ?? $user->email }}
          </span>
        </div>
        <div class="md:col-span-2">
          <span class="text-gray-400 w-40 inline-block">Status Disabilitas</span>:
          {{ $profile->status_disabilitas ?? '-' }}
        </div>
        <div class="md:col-span-2">
          <span class="text-gray-400 w-40 inline-block">Alamat</span>:
          {{ $profile->alamat_lengkap ?? '-' }}
        </div>
      </div>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pendidikan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Tingkat</th>
            <th class="px-3 py-2 text-left">Institusi</th>
            <th class="px-3 py-2 text-left">Jurusan</th>
            <th class="px-3 py-2 text-left">Tahun</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        @forelse($educations as $e)
          <tr>
            <td class="px-3 py-2">{{ $e->tingkat }}</td>
            <td class="px-3 py-2">{{ $e->nama_institusi }}</td>
            <td class="px-3 py-2">{{ $e->jurusan }}</td>
            <td class="px-3 py-2">{{ $e->tahun_mulai }} - {{ $e->tahun_selesai ?? '-' }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pelatihan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Jenis</th>
            <th class="px-3 py-2 text-left">Lembaga</th>
            <th class="px-3 py-2 text-left">Tahun</th>
            <th class="px-3 py-2 text-left">Sertifikat</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        @forelse($trainings as $t)
          <tr>
            <td class="px-3 py-2">{{ $t->jenis_pelatihan }}</td>
            <td class="px-3 py-2">{{ $t->lembaga_pelatihan }}</td>
            <td class="px-3 py-2">{{ $t->tahun }}</td>
            <td class="px-3 py-2">
              @if($t->sertifikat_file)
                <a href="{{ asset('storage/'.$t->sertifikat_file) }}" target="_blank" class="text-indigo-300 hover:underline">
                  Lihat
                </a>
              @else
                <span class="text-gray-500 italic">Tidak ada</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pekerjaan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Perusahaan</th>
            <th class="px-3 py-2 text-left">Jabatan</th>
            <th class="px-3 py-2 text-left">Tahun</th>
            <th class="px-3 py-2 text-left">Surat Pengalaman</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        @forelse($works as $w)
          <tr>
            <td class="px-3 py-2">{{ $w->nama_perusahaan }}</td>
            <td class="px-3 py-2">{{ $w->jabatan }}</td>
            <td class="px-3 py-2">{{ $w->tahun_mulai }} - {{ $w->tahun_selesai ?? 'Sekarang' }}</td>
            <td class="px-3 py-2">
              @if($w->surat_pengalaman)
                <a href="{{ asset('storage/'.$w->surat_pengalaman) }}" target="_blank" class="text-indigo-300 hover:underline">
                  Lihat
                </a>
              @else
                <span class="text-gray-500 italic">Tidak ada</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Minat Bekerja</h3>
    @if($preference)
      <div class="text-sm">
        <div><span class="text-gray-400">Lokasi</span>: {{ is_array($preference->minat_lokasi) ? implode(', ', $preference->minat_lokasi) : ($preference->minat_lokasi ?? '-') }}</div>
        <div><span class="text-gray-400">Bidang</span>: {{ is_array($preference->minat_bidang) ? implode(', ', $preference->minat_bidang) : ($preference->minat_bidang ?? '-') }}</div>
        <div><span class="text-gray-400">Gaji Harapan</span>: {{ $preference->gaji_harapan ?? '-' }}</div>
        <div><span class="text-gray-400">Deskripsi Diri</span>: {{ $preference->deskripsi_diri ?? '-' }}</div>
      </div>
    @else
      <div class="text-gray-400">Belum ada data</div>
    @endif
  </div>
</div>
