@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    
        <h2 class="font-semibold text-xl text-gray-200">
            Detail Pengajuan AK1 — {{ $application->user->name }}
        </h2>
    

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="bg-green-800/40 p-3 rounded text-green-200">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-800/40 p-3 rounded text-red-200">{{ session('error') }}</div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Berkas & status --}}
            <div class="space-y-4">
                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-100 mb-3">Berkas Pemohon</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-gray-400 mb-1">Foto Close-Up</div>
                            @if($application->foto_closeup)
                                <img src="{{ asset('storage/'.$application->foto_closeup) }}" class="rounded w-full">
                            @else
                                <span class="text-gray-500">Tidak ada</span>
                            @endif
                        </div>

                        <div>
                            <div class="text-gray-400 mb-1">KTP</div>
                            @if($application->ktp_file)
                                <a target="_blank" class="text-blue-400 hover:underline"
                                   href="{{ asset('storage/'.$application->ktp_file) }}">Lihat KTP</a>
                            @else <span class="text-gray-500">Tidak ada</span> @endif
                        </div>

                        <div>
                            <div class="text-gray-400 mb-1">Ijazah Terakhir</div>
                            @if($application->ijazah_file)
                                <a target="_blank" class="text-blue-400 hover:underline"
                                   href="{{ asset('storage/'.$application->ijazah_file) }}">Lihat Ijazah</a>
                            @else <span class="text-gray-500">Tidak ada</span> @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-100 mb-3">Status</h3>
                    <div class="text-sm text-gray-300">
                        <div>Status: <b>{{ $application->status }}</b></div>
                        <div>Pengajuan: {{ optional($application->tanggal_pengajuan)->format('d M Y H:i') ?? '-' }}</div>
                        <div>Verifikasi: {{ optional($application->tanggal_verifikasi)->format('d M Y H:i') ?? '-' }}</div>
                        <div>Nomor AK1: <b>{{ $application->nomor_ak1 ?? '-' }}</b></div>
                        <div>Petugas: {{ $application->assignedTo->name ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Data ringkasan --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-100 mb-3">Data Diri</h3>
                    <div class="grid grid-cols-2 gap-x-10 gap-y-1 text-sm text-gray-300">
                        <p><span class="w-40 inline-block text-gray-400">NIK</span>: {{ $profile->nik ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Status</span>: {{ $profile->status_perkawinan ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Nama</span>: {{ $profile->nama_lengkap ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Agama</span>: {{ $profile->agama ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Tempat Lahir</span>: {{ $profile->tempat_lahir ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Tanggal Lahir</span>: {{ $profile->tanggal_lahir ?? '-' }}</p>
                        <p><span class="w-40 inline-block text-gray-400">Jenis Kelamin</span>: {{ $profile->jenis_kelamin ?? '-' }}</p>
                        <p class="col-span-2"><span class="w-40 inline-block text-gray-400">Alamat</span>: {{ $profile->alamat_lengkap ?? '-' }}</p>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-100 mb-3">Riwayat Pendidikan</h3>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="p-2 text-left">Tingkat</th>
                                <th class="p-2 text-left">Lembaga</th>
                                <th class="p-2 text-left">Jurusan</th>
                                <th class="p-2 text-left">Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($educations as $e)
                                <tr class="border-b border-gray-700">
                                    <td class="p-2">{{ $e->tingkat }}</td>
                                    <td class="p-2">{{ $e->nama_institusi }}</td>
                                    <td class="p-2">{{ $e->jurusan ?: '-' }}</td>
                                    <td class="p-2">{{ $e->tahun_mulai }} - {{ $e->tahun_selesai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-100 mb-3">Riwayat Pelatihan</h3>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="p-2 text-left">Jenis</th>
                                <th class="p-2 text-left">Lembaga</th>
                                <th class="p-2 text-left">Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainings as $t)
                                <tr class="border-b border-gray-700">
                                    <td class="p-2">{{ $t->jenis_pelatihan }}</td>
                                    <td class="p-2">{{ $t->lembaga_pelatihan }}</td>
                                    <td class="p-2">{{ $t->tahun }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Aksi --}}
                <div class="bg-gray-800 rounded-lg p-4 space-y-4">
                    <h3 class="font-semibold text-gray-100 mb-2">Aksi Verifikasi</h3>

                    {{-- Assign petugas --}}
                    <form method="POST" action="{{ route('admin.ak1.assign', $application) }}" class="flex gap-2 items-center">
                        @csrf
                        <select name="assigned_to" class="rounded border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih Petugas</option>
                            @foreach(\App\Models\User::where('role','admin')->get() as $petugas)
                                <option value="{{ $petugas->id }}" @selected($application->assigned_to==$petugas->id)>
                                    {{ $petugas->name }}
                                </option>
                            @endforeach
                        </select>
                        <button class="px-3 py-2 bg-gray-700 text-white rounded">Simpan Assign</button>
                    </form>

                    <div class="flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('admin.ak1.approve',$application) }}">
                            @csrf
                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Setujui</button>
                        </form>

                        <form method="POST" action="{{ route('admin.ak1.revision',$application) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="revision_notes" class="w-80 rounded border-gray-700 dark:bg-gray-900" placeholder="Catatan revisi (wajib)">
                            <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded">Minta Revisi</button>
                        </form>

                        <form method="POST" action="{{ route('admin.ak1.reject',$application) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="alasan_penolakan" class="w-80 rounded border-gray-700 dark:bg-gray-900" placeholder="Alasan penolakan (wajib)">
                            <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Tolak</button>
                        </form>
                    </div>

                    {{-- Aksi cetak/diambil dinonaktifkan --}}
                </div>

            </div>
        </div>

    </div>
@endsection
