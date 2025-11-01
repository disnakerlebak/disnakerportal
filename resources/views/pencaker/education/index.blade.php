@extends('layouts.pencaker')
@section('title', 'Dashboard')
@section('content')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Riwayat Pendidikan
        </h2>

    <div class="max-w-5xl mx-auto p-6 space-y-6">
        @php $locked = $isLocked ?? false; @endphp
        @if($locked)
            <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                Perubahan data pendidikan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
            </div>
        @endif
        <!-- @if(session('success'))
            <div class="bg-green-100 dark:bg-green-800/40 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif -->

        {{-- Tombol tambah --}}
        @if($locked)
            <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg cursor-not-allowed">
                + Tambah Pendidikan (Terkunci)
            </button>
        @else
            <button data-modal-open="modalPendidikan"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                + Tambah Pendidikan
            </button>
        @endif
<x-modal-form id="modalPendidikan" title="Tambah Riwayat Pendidikan"
              action="{{ route('pencaker.education.store') }}" method="POST"
              submitLabel="Simpan" cancelLabel="Batal">

    <div>
        <label class="block text-sm text-gray-500">Tingkat Pendidikan</label>
        <select name="tingkat"
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                   dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
            <option value="">- Pilih -</option>
            @foreach(['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
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

    <!-- <div>
        <label class="block text-sm text-gray-500">Upload Ijazah (opsional)</label>
        <input type="file" name="ijazah_file"
               class="mt-1 w-full text-sm text-gray-300 border border-gray-700 rounded-lg p-2
                      bg-gray-900 focus:ring-2 focus:ring-indigo-500">
    </div> -->

</x-modal-form>

{{-- Modal Edit Pendidikan --}}
@foreach($educations as $edu)
<x-modal-form id="modalEdit{{ $edu->id }}" title="Edit Riwayat Pendidikan"
              action="{{ route('pencaker.education.update', $edu->id) }}" method="POST" enctype="multipart/form-data"
              submitLabel="Update" cancelLabel="Batal">

    <div>
        <label class="block text-sm text-gray-500">Tingkat Pendidikan</label>
        <select name="tingkat" required
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                   dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
            @foreach(['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $tingkat)
                <option value="{{ $tingkat }}" @selected($edu->tingkat == $tingkat)>{{ $tingkat }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm text-gray-500">Nama Institusi / Sekolah</label>
        <input type="text" name="nama_institusi" value="{{ $edu->nama_institusi }}"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                      dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm text-gray-500">Jurusan</label>
        <input type="text" name="jurusan" value="{{ $edu->jurusan }}"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                      dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm text-gray-500">Tahun Mulai</label>
            <input type="number" name="tahun_mulai" value="{{ $edu->tahun_mulai }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm text-gray-500">Tahun Selesai</label>
            <input type="number" name="tahun_selesai" value="{{ $edu->tahun_selesai }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <!-- <div>
        <label class="block text-sm text-gray-500">Upload Ijazah (opsional)</label>
        <input type="file" name="ijazah_file"
               class="mt-1 w-full text-sm text-gray-300 border border-gray-700 rounded-lg p-2
                      bg-gray-900 focus:ring-2 focus:ring-indigo-500">
        @if($edu->ijazah_file)
            <p class="text-xs text-gray-400 mt-1">
                File saat ini: <a href="{{ asset('storage/'.$edu->ijazah_file) }}" target="_blank" class="text-blue-400 underline">Lihat Ijazah</a>
            </p>
        @endif
    </div> -->

</x-modal-form>
@endforeach

{{-- Modal Konfirmasi Hapus --}}
<x-modal-form id="modalDelete" title="Konfirmasi Hapus"
              action="" method="POST"
              submitLabel="Ya, Hapus" cancelLabel="Batal">
    @method('DELETE')
    <p class="text-gray-300">Apakah Anda yakin ingin menghapus data pendidikan ini?</p>
</x-modal-form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const actionUrl = btn.getAttribute('data-action');
            const modal = document.getElementById('modalDelete');
            const form = modal.querySelector('form');
            form.setAttribute('action', actionUrl);
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });
});
</script>

        {{-- Tabel Pendidikan --}}
        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse border border-gray-400 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="border p-2">Tingkat</th>
                        <th class="border p-2">Nama Sekolah/Lembaga/Institusi</th>
                        <th class="border p-2">Jurusan</th>
                        <th class="border p-2">Tahun</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200">
                    @forelse($educations as $edu)
                        <tr>
                            <td class="border p-2">{{ $edu->tingkat }}</td>
                            <td class="border p-2">{{ $edu->nama_institusi }}</td>
                            <td class="border p-2 text-center">{{ $edu->jurusan ?: '-' }}</td>
                            <td class="border p-2 text-center">{{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}</td>
                            <td class="border p-2 text-center">
                                @if($locked)
                                    <span class="text-gray-400">Terkunci</span>
                                @else
                                    <a href="javascript:void(0)" data-modal-open="modalEdit{{ $edu->id }}"
                                       class="text-yellow-400 hover:text-yellow-300">Edit</a> |
                                    <form action="{{ route('pencaker.education.destroy', $edu) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-400 btnDelete"
                                                data-action="{{ route('pencaker.education.destroy', $edu) }}">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-3 text-gray-500">
                                Belum ada data pendidikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-modal-open');
            document.getElementById(target)?.classList.remove('hidden');
        });
    });
});
// document.addEventListener('DOMContentLoaded', () => {
//     const modal = document.getElementById('modalPendidikan');
//     const openBtn = document.getElementById('btnTambah');
//     const closeBtns = [document.getElementById('btnClosePendidikan'), document.getElementById('btnBatalPendidikan')];

//     if (openBtn && modal) {
//         openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
//     }
//     closeBtns.forEach(btn => {
//         if (btn) btn.addEventListener('click', () => modal.classList.add('hidden'));
//     });
// });
</script>

@endsection
