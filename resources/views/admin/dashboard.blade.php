@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="py-10">
        <div class="max-w-6xl mx-auto px-6 space-y-8 text-slate-100">
        <!-- <h2 class="text-2xl font-semibold text-gray-100">Dashboard Admin Disnaker</h2> -->
            {{-- Statistik singkat --}}
            <div class="grid sm:grid-cols-3 mt-3 md:gap-6">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6 text-center shadow">
                    <p class="text-slate-400 text-sm mb-2">Total Pencaker</p>
                    <h3 class="text-3xl font-bold text-slate-100">{{ $totalPencaker ?? '0' }}</h3>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/70 mt-2 p-6 text-center shadow">
                    <p class="text-slate-400 text-sm mb-2">Lengkap Profil</p>
                    <h3 class="text-3xl font-bold text-green-400">{{ $lengkapProfil ?? '0' }}</h3>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/70 mt-2 p-6 text-center shadow">
                    <p class="text-slate-400 text-sm mb-2">Belum Lengkap</p>
                    <h3 class="text-3xl font-bold text-red-400">{{ $belumLengkap ?? '0' }}</h3>
                </div>
            </div>

            {{-- Pencaker terbaru --}}
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow mt-3 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Pencari Kerja Terbaru
                    </h3>
                    <a href="{{ route('admin.pencaker.index') }}" class="text-blue-400 text-sm hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-lg border border-slate-800 text-slate-200">
                        <thead class="bg-slate-800 text-slate-200">
                            <tr>
                                <th class="py-2 px-3 text-left">Nama</th>
                                <th class="py-2 px-3 text-left">Email</th>
                                <th class="py-2 px-3 text-left">Tanggal Daftar</th>
                                <th class="py-2 px-3 text-left">Status Profil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($users ?? [] as $p)
                                <tr class="hover:bg-slate-800/50 transition">
                                    <td class="py-2 px-3">{{ $p->name }}</td>
                                    <td class="py-2 px-3">{{ $p->email }}</td>
                                    <td class="py-2 px-3">{{ $p->created_at->format('d M Y') }}</td>
                                    <td class="py-2 px-3">
                                        @if ($p->jobseekerProfile?->nik)
                                            <span class="text-green-400 font-medium">Lengkap</span>
                                        @else
                                            <span class="text-yellow-400 font-medium">Belum Lengkap</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-slate-400">Belum ada data pencaker</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
