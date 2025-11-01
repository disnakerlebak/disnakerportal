@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="py-10">
        <div class="max-w-6xl mx-auto px-6 space-y-8">
        <h2 class="text-2xl font-semibold text-gray-100">Dashboard Admin Disnaker</h2>
            {{-- Statistik singkat --}}
            <div class="grid sm:grid-cols-3 mt-3 md:gap-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center transition hover:scale-[1.02]">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">Total Pencaker</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalPencaker ?? '0' }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md mt-2 p-6 text-center transition hover:scale-[1.02]">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">Lengkap Profil</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $lengkapProfil ?? '0' }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md mt-2 p-6 text-center transition hover:scale-[1.02]">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">Belum Lengkap</p>
                    <h3 class="text-3xl font-bold text-red-500">{{ $belumLengkap ?? '0' }}</h3>
                </div>
            </div>

            {{-- Pencaker terbaru --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md mt-3 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Pencari Kerja Terbaru
                    </h3>
                    <a href="{{ route('admin.pencaker.index') }}" class="text-blue-600 text-sm hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600 rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            <tr>
                                <th class="py-2 px-3 text-left">Nama</th>
                                <th class="py-2 px-3 text-left">Email</th>
                                <th class="py-2 px-3 text-left">Tanggal Daftar</th>
                                <th class="py-2 px-3 text-left">Status Profil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users ?? [] as $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="py-2 px-3">{{ $p->name }}</td>
                                    <td class="py-2 px-3">{{ $p->email }}</td>
                                    <td class="py-2 px-3">{{ $p->created_at->format('d M Y') }}</td>
                                    <td class="py-2 px-3">
                                        @if ($p->jobseekerProfile?->nik)
                                            <span class="text-green-500 font-medium">Lengkap</span>
                                        @else
                                            <span class="text-yellow-500 font-medium">Belum Lengkap</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-400">Belum ada data pencaker</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
