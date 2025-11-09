<x-modal-form id="modalRepairProfile"
              title="Perbarui Data Diri"
              action="{{ route('pencaker.profile.update') }}"
              method="POST"
              submitLabel="Simpan"
              cancelLabel="Batal">
    @method('PUT')
    <input type="hidden" name="repair_mode" value="1">
    <div class="grid gap-3 sm:grid-cols-2">
        <div>
            <label class="block text-sm text-slate-400">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100">
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
                    <option value="{{ $agama }}" @selected(old('agama', $profile->agama ?? '') == $agama)>{{ $agama }}</option>
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
                    <option value="{{ $tingkat }}" @selected(old('pendidikan_terakhir', $profile->pendidikan_terakhir ?? '') === $tingkat)>{{ $tingkat }}</option>
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
</x-modal-form>

<x-modal-form id="modalRepairEducationCreate"
              title="Tambah Riwayat Pendidikan"
              action="{{ route('pencaker.education.store') }}"
              method="POST"
              submitLabel="Simpan"
              cancelLabel="Batal">
    <input type="hidden" name="repair_mode" value="1">
    <div class="space-y-3">
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
    </div>
</x-modal-form>

@foreach ($educations as $edu)
    <x-modal-form id="modalRepairEducationEdit{{ $edu->id }}"
                  title="Perbarui Riwayat Pendidikan"
                  action="{{ route('pencaker.education.update', $edu->id) }}"
                  method="POST"
                  submitLabel="Update"
                  cancelLabel="Batal">
        @method('PUT')
        <input type="hidden" name="repair_mode" value="1">
        <div class="space-y-3">
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
        </div>
    </x-modal-form>
@endforeach

<x-modal-form id="modalRepairTrainingCreate"
              title="Tambah Riwayat Pelatihan"
              action="{{ route('pencaker.training.store') }}"
              method="POST"
              submitLabel="Simpan"
              cancelLabel="Batal">
    <input type="hidden" name="repair_mode" value="1">
    <div class="space-y-3">
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
    </div>
</x-modal-form>

@foreach ($trainings as $training)
    <x-modal-form id="modalRepairTrainingEdit{{ $training->id }}"
                  title="Perbarui Riwayat Pelatihan"
                  action="{{ route('pencaker.training.update', $training->id) }}"
                  method="POST"
                  submitLabel="Update"
                  cancelLabel="Batal">
        @method('PUT')
        <input type="hidden" name="repair_mode" value="1">
        <div class="space-y-3">
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
        </div>
    </x-modal-form>
@endforeach
