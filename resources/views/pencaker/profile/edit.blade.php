<x-app-layout>
<head>
<style>
  html {
    scroll-behavior: smooth;
  }
    table tr:hover td {
        background-color: rgba(255, 255, 255, 0.05);
    }

</style>
</head>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Data Diri Pencari Kerja
            </h2>
            @php $locked = $isLocked ?? false; @endphp
            @if($locked)
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
            <!-- <x-toast type="success" message="Toast test manual tampil" position="bottom-right" /> -->
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto mt-3 sm:px-6 lg:px-8">
        @if($locked)
            <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3">
                Pengeditan data diri terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
            </div>
        @endif
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="divide-y divide-gray-700/30">
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

    <table class="w-full text-gray-100">
        <tbody>
            @foreach ($fields as $label => $value)
                <tr class="border-b border-gray-700/40">
                    <td class="py-2 pr-6 font-semibold text-gray-300 w-1/3">{{ $label }}</td>
                    <td class="py-2 text-gray-100">: {{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

            </div>
        </div>
</div>

{{-- Modal Form Edit --}}
<div id="profileModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">

    {{-- Wrapper modal (kolom, tinggi dibatasi 90vh supaya scroll bisa) --}}
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-700/30
            flex flex-col overflow-hidden min-h-0"
     style="max-height:90vh">

        {{-- Header (selalu terlihat di atas) --}}
        <div class="p-4 border-b border-gray-600/20 flex justify-between items-center sticky top-0
                    bg-white dark:bg-gray-800 z-10">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Data Diri</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-200 text-2xl font-bold">×</button>
        </div>

        {{-- Body scrollable --}}
        <form method="POST" action="{{ route('pencaker.profile.update') }}" class="flex-1 flex flex-col min-h-0">
            @csrf
            @method('PUT')

            <div class="p-6 overflow-y-auto grow"
            style="max-height:calc(90vh - 120px)">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm text-gray-500">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                            value="{{ old('nama_lengkap', $profile->nama_lengkap ?? '') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm text-gray-500">NIK</label>
                        <input type="text" name="nik"
                            value="{{ old('nik', $profile->nik ?? '') }}"
                            {{ isset($profile->nik) && $profile->nik != '' ? 'readonly' : '' }}
                            maxlength="16"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500"
                            required>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-sm text-gray-500">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm text-gray-500">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-sm text-gray-500">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih</option>
                            <option value="Laki-laki" @selected(old('jenis_kelamin',$profile->jenis_kelamin??'')=='Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin',$profile->jenis_kelamin??'')=='Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    {{-- Agama --}}
                    <div>
                        <label class="block text-sm text-gray-500">Agama</label>
                        <select name="agama"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih</option>
                            @foreach (['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                                <option value="{{ $agama }}" @selected(old('agama',$profile->agama??'')==$agama)>
                                    {{ $agama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Perkawinan --}}
                    <div>
                        <label class="block text-sm text-gray-500">Status Perkawinan</label>
                        <select name="status_perkawinan"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih</option>
                            @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                                <option value="{{ $status }}" @selected(old('status_perkawinan',$profile->status_perkawinan??'')==$status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pendidikan --}}
                    <div>
                        <label class="block text-sm text-gray-500">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih</option>
                            @foreach (['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $p)
                                <option value="{{ $p }}" @selected(old('pendidikan_terakhir',$profile->pendidikan_terakhir??'')==$p)>
                                    {{ $p }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-500">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="2"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('alamat_lengkap',$profile->alamat_lengkap??'') }}</textarea>
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label class="block text-sm text-gray-500">Kecamatan Domisili</label>
                        <select name="domisili_kecamatan"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec }}" @selected(old('domisili_kecamatan',$profile->domisili_kecamatan??'')==$kec)>
                                    {{ $kec }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-sm text-gray-500">No. Telepon</label>
                        <input type="text" name="no_telepon"
                            value="{{ old('no_telepon', $profile->no_telepon ?? '') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    </div>
                    <div class="p-4 border-t border-gray-600/30 bg-white dark:bg-gray-800 flex justify-end gap-3 bottom-0">
                <button type="button" id="cancelEdit"
                    class="px-4 py-2 rounded-lg border border-gray-400 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                    Simpan
                </button>
            </div>
                </div>
            </div>            
        </form>
    </div>
</div>
<!-- <x-toast type="success" message="Berhasil disimpan!" position="center" /> -->

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('profileModal');
  const openBtn = document.getElementById('openEdit');
  const closeBtn = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelEdit');

  const openModal = () => { modal.classList.remove('hidden'); document.body.style.overflow='hidden'; };
  const closeModal = () => { modal.classList.add('hidden'); document.body.style.overflow=''; };

  // Jangan buka modal jika tombol disabled (terkunci)
  if (openBtn && !openBtn.disabled) {
    openBtn.addEventListener('click', openModal);
  }
  closeBtn?.addEventListener('click', closeModal);
  cancelBtn?.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
});

</script>

</x-app-layout>
