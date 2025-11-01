<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Detail Pencaker</h2></x-slot>
    <div class="max-w-5xl mx-auto p-6 grid md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <div><b>Nama:</b> {{ $profile->nama_lengkap ?? $user->name }}</div>
            <div><b>NIK:</b> {{ $profile->nik }}</div>
            <div><b>TTL:</b> {{ $profile->tempat_lahir }}, {{ $profile->tanggal_lahir }}</div>
            <div><b>JK:</b> {{ $profile->jenis_kelamin }}</div>
            <div><b>Agama:</b> {{ $profile->agama }}</div>
            <div><b>Status:</b> {{ $profile->status_perkawinan }}</div>
            <div><b>Pendidikan:</b> {{ $profile->pendidikan_terakhir }}</div>
            <div><b>Alamat:</b> {{ $profile->alamat_lengkap }}</div>
            <div><b>Kecamatan:</b> {{ $profile->domisili_kecamatan }}</div>
            <div><b>No. HP:</b> {{ $profile->no_telepon }}</div>
            <div><b>Email:</b> {{ $profile->email_cache }}</div>
        </div>

        <div>
            <h3 class="font-semibold mb-2">Aktivitas Terakhir</h3>
            <ul class="space-y-1">
                @foreach($logs as $log)
                    <li class="text-sm">{{ $log->created_at }} — {{ $log->action }} ({{ class_basename($log->model_type) }} #{{ $log->model_id }}) {{ $log->description }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
