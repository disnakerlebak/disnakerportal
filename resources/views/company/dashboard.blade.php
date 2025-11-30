@extends('layouts.company-sidebar')

@section('title', 'Dashboard Perusahaan')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        <x-company-breadcrumb :items="[['label' => 'Dashboard']]" />
        @if(session('error'))
            <div class="rounded-xl border border-rose-600/50 bg-rose-600/10 px-4 py-3 text-sm text-rose-200">
                {{ session('error') }}
            </div>
        @elseif(session('success'))
            <div class="rounded-xl border border-emerald-600/40 bg-emerald-600/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        <!-- Heading -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-100">Dashboard Perusahaan</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Ringkasan aktivitas lowongan dan pelamar di DisnakerPortal.
                </p>
            </div>
            @if($canPostJobs)
                <a href="{{ route('company.jobs.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Lowongan</span>
                </a>
            @else
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-800/70 px-4 py-2 text-sm font-semibold text-slate-300 shadow cursor-not-allowed"
                        title="Perusahaan belum diverifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Menunggu Verifikasi</span>
                </button>
            @endif
        </div>

        <!-- Statistik cards -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Status verifikasi perusahaan -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Status Verifikasi
                        </p>
                        <p class="mt-2 text-lg font-semibold {{ $canPostJobs ? 'text-emerald-300' : 'text-amber-200' }}">
                            {{ $verificationStatus ?? 'Belum Diverifikasi' }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    @if($canPostJobs)
                        Terima kasih telah melengkapi proses verifikasi. Anda sudah dapat mengelola lowongan kerja.
                    @else
                        Lengkapi profil perusahaan dan tunggu persetujuan admin untuk mulai mempublikasikan lowongan.
                    @endif
                </p>
            </div>

            <!-- Jumlah lowongan aktif -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Lowongan Aktif
                        </p>
                        <p class="mt-2 text-2xl font-bold text-slate-100">
                            {{ $activeJobsCount ?? 0 }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 10h16M4 14h10M4 18h6"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Jumlah lowongan yang saat ini sedang tayang dan menerima pelamar.
                </p>
            </div>

            <!-- Total pelamar -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Total Pelamar
                        </p>
                        <p class="mt-2 text-2xl font-bold text-slate-100">
                            {{ $totalApplicants ?? 0 }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-fuchsia-500/10 text-fuchsia-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-1.5C22 16.57 19.314 15 16 15c-.597 0-1.176.053-1.732.152M9 20H2v-1.5C2 16.57 4.686 15 8 15c.597 0 1.176.053 1.732.152M16 11a4 4 0 10-8 0 4 4 0 008 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Akumulasi semua pelamar ke seluruh lowongan perusahaan Anda.
                </p>
            </div>

            <!-- Total pelamar bulan ini -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Pelamar Bulan Ini
                        </p>
                        <p class="mt-2 text-2xl font-bold text-slate-100">
                            {{ $monthlyApplicants ?? 0 }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/10 text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Jumlah pelamar baru yang masuk dalam periode bulan berjalan.
                </p>
            </div>
        </div>

        <!-- Placeholder aktivitas / info -->
        <div class="mt-4 rounded-xl border border-dashed border-slate-700/60 bg-slate-900/40 p-4 text-sm text-slate-300">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-semibold text-slate-100">
                        @if($canPostJobs)
                            Belum ada aktivitas terbaru
                        @else
                            Menunggu verifikasi
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        @if($canPostJobs)
                            Setelah Anda membuat lowongan dan mulai menerima pelamar, ringkasan aktivitas akan muncul di sini.
                        @else
                            Tim kami akan meninjau data perusahaan Anda. Hubungi Disnaker jika membutuhkan update status verifikasi.
                        @endif
                    </p>
                </div>
                @if($canPostJobs)
                    <a href="{{ route('company.jobs.index') }}"
                       class="inline-flex items-center gap-1 text-xs font-medium text-indigo-400 hover:text-indigo-300">
                        Lihat semua lowongan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
