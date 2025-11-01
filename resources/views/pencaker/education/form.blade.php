@extends('layouts.pencaker')
@section('title', 'Dashboard')
@section('content')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Edit Riwayat Pendidikan
        </h2>

    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <form method="POST" action="{{ route('pencaker.education.update', $education) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Tingkat Pendidikan</label>
                    <select name="tingkat" class="w-full border rounded p-2" required>
                        @foreach(['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $t)
                            <option value="{{ $t }}" @selected($education->tingkat == $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Nama Institusi</label>
                    <input type="text" name="nama_institusi" value="{{ old('nama_institusi', $education->nama_institusi) }}" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm mb-1">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $education->jurusan) }}" class="w-full border rounded p-2">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm mb-1">Tahun Mulai</label>
                        <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai', $education->tahun_mulai) }}" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tahun Selesai</label>
                        <input type="number" name="tahun_selesai" value="{{ old('tahun_selesai', $education->tahun_selesai) }}" class="w-full border rounded p-2" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm mb-1">Upload Ijazah (opsional)</label>
                    <input type="file" name="ijazah_file" class="w-full border rounded p-2">
                    @if($education->ijazah_file)
                        <p class="text-sm mt-1">File saat ini:
                            <a href="{{ asset('storage/'.$education->ijazah_file) }}" target="_blank" class="text-blue-600">Lihat</a>
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('pencaker.education.index') }}" class="px-4 py-2 rounded border border-gray-400 dark:border-gray-600">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
