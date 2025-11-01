{{-- resources/views/admin/pencaker/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Pencari Kerja Terdaftar</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-6" x-data="{ open:false, sel:{} }">
        {{-- Tabel --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-200">
                    <thead class="bg-gray-700 text-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nama Lengkap</th>
                            <th class="p-3 text-left">Jenis Kelamin</th>
                            <th class="p-3 text-left">Usia</th>
                            <th class="p-3 text-left">Pendidikan Terakhir</th>
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
                                <td class="p-3">{{ $p->kecamatan ?? '-' }}</td>
                                <td class="p-3">
                                    <button
                                        class="px-3 py-1.5 rounded bg-blue-600 hover:bg-blue-700"
                                        @click="
                                            sel = JSON.parse($el.dataset.json);
                                            open = true;
                                        "
                                        data-json='@json([
                                            'nama' => $p->nama_lengkap ?? $u->name,
                                            'nik'  => $p->nik ?? '-',
                                            'ttl'  => trim(($p->tempat_lahir ?? '').', '.($p->tanggal_lahir ?? '')),
                                            'jk'   => $p->jenis_kelamin ?? '-',
                                            'agama'=> $p->agama ?? '-',
                                            'status'=> $p->status_perkawinan ?? '-',
                                            'pendidikan'=> $p->pendidikan_terakhir ?? '-',
                                            'alamat'=> $p->alamat_lengkap ?? '-',
                                            'kecamatan'=> $p->kecamatan ?? '-',
                                            'hp'   => $p->no_hp ?? '-',
                                            'email'=> $u->email,
                                            'foto' => $foto,
                                            'ktp'  => $ktp,
                                            'ijazah' => $ijz,
                                        ])'
                                    >Detail</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>

        {{-- MODAL DETAIL (satu saja, diisi dari tombol via Alpine) --}}
        <div
            x-show="open"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
        >
            <div @click.outside="open=false"
                 class="bg-gray-900 w-full max-w-3xl rounded-2xl shadow-lg overflow-hidden">
                <div class="grid md:grid-cols-[200px,1fr] gap-6 p-6">
                    <div class="flex flex-col items-center">
                        <img :src="sel.foto" alt="Foto" class="w-48 h-56 object-cover rounded-lg border border-gray-700">
                        <div class="mt-3 flex gap-2">
                            <template x-if="sel.ktp">
                                <a :href="sel.ktp" target="_blank" class="text-xs px-3 py-1 bg-slate-700 rounded">Lihat KTP</a>
                            </template>
                            <template x-if="sel.ijazah">
                                <a :href="sel.ijazah" target="_blank" class="text-xs px-3 py-1 bg-slate-700 rounded">Lihat Ijazah</a>
                            </template>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-4" x-text="sel.nama"></h3>
                        <div class="grid sm:grid-cols-2 gap-y-2 text-sm">
                            <div><span class="text-gray-400">NIK</span> : <span x-text="sel.nik"></span></div>
                            <div><span class="text-gray-400">TTL</span> : <span x-text="sel.ttl"></span></div>
                            <div><span class="text-gray-400">JK</span> : <span x-text="sel.jk"></span></div>
                            <div><span class="text-gray-400">Agama</span> : <span x-text="sel.agama"></span></div>
                            <div><span class="text-gray-400">Status</span> : <span x-text="sel.status"></span></div>
                            <div><span class="text-gray-400">Pendidikan</span> : <span x-text="sel.pendidikan"></span></div>
                            <div class="sm:col-span-2"><span class="text-gray-400">Alamat</span> : <span x-text="sel.alamat"></span></div>
                            <div><span class="text-gray-400">Kecamatan</span> : <span x-text="sel.kecamatan"></span></div>
                            <div><span class="text-gray-400">No. HP</span> : <span x-text="sel.hp"></span></div>
                            <div><span class="text-gray-400">Email</span> : <span x-text="sel.email"></span></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 bg-gray-800 px-6 py-3">
                    <button class="px-4 py-2 rounded bg-slate-600 hover:bg-slate-700" @click="open=false">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
