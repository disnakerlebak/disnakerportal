{{-- resources/views/admin/pencaker/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Daftar Pencaker Disetujui')

@section('content')
<div class="py-8 max-w-6xl mx-auto px-6" x-data="{ open:false, html:'', loading:false, load(url){ this.open=true; this.loading=true; this.html=''; fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.text()).then(t=>{ this.html=t; }).catch(()=>{ this.html='<div class=\'p-6 text-red-300\'>Gagal memuat detail.</div>'; }).finally(()=>{ this.loading=false; }); } }">

  <div class="flex justify-between items-center mb-4">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
      Daftar Pencaker yang Telah Disetujui
    </h2>
  </div>

  {{-- TABEL Pencaker --}}
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-gray-200">
        <thead class="bg-gray-700 text-gray-100">
          <tr>
            <th class="p-3 text-left">Nama Lengkap</th>
            <th class="p-3 text-left">Jenis Kelamin</th>
            <th class="p-3 text-left">Usia</th>
            <th class="p-3 text-left">Pendidikan</th>
            <th class="p-3 text-left">Kecamatan</th>
            <th class="p-3 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          @forelse ($users as $u)
            @php
              $p = $u->jobseekerProfile;
              $app = optional($u->cardApplications->first());
              $usia = $p?->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->age : '-';
              $foto = $app?->foto_closeup ? asset('storage/'.$app->foto_closeup) : asset('images/placeholder-avatar.png');
              $ktp  = $app?->ktp_file    ? asset('storage/'.$app->ktp_file)    : null;
              $ijz  = $app?->ijazah_file ? asset('storage/'.$app->ijazah_file) : null;
            @endphp

            <tr>
              <td class="p-3">{{ $p->nama_lengkap ?? '-' }}</td>
              <td class="p-3">{{ $p->jenis_kelamin ?? '-' }}</td>
              <td class="p-3">{{ $usia }}</td>
              <td class="p-3">{{ $p->pendidikan_terakhir ?? '-' }}</td>
              <td class="p-3">{{ $p->domisili_kecamatan ?? '-' }}</td>
              <td class="p-3 text-center">
                <button type="button" class="px-3 py-1.5 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm"
                        @click="load('{{ route('admin.pencaker.detail', $u->id) }}')">Detail</button>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada data pencaker disetujui.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4 border-t border-gray-700">
      {{ $users->withQueryString()->links() }}
    </div>
  </div>

  {{-- MODAL DETAIL (AJAX) --}}
  <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
       @keydown.escape.window="open=false">
    <div @click.outside="open=false" class="bg-gray-900 w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden border border-gray-700">
      <div class="flex items-center justify-between px-6 py-3 border-b border-gray-800 sticky top-0 bg-gray-900 z-10">
        <h3 class="text-lg font-semibold text-gray-100">Detail Pencaker</h3>
        <button class="px-3 py-1 rounded bg-slate-700 hover:bg-slate-600" @click="open=false">Tutup</button>
      </div>
      <div class="max-h-[85vh] overflow-y-auto">
        <template x-if="loading">
          <div class="p-6 text-gray-300">Memuat...</div>
        </template>
        <div class="p-6" x-html="html"></div>
      </div>
    </div>
  </div>

</div>
@endsection
