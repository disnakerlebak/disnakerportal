@extends('layouts.pencaker')
@section('title', 'Dashboard')
@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 mb-6">
            Dashboard Pencaker
        </h2>

        <div class="rounded-xl border border-dashed border-blue-300/40 bg-blue-50/50 px-6 py-5 text-blue-900 shadow-sm dark:border-blue-400/30 dark:bg-blue-500/10 dark:text-blue-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wide text-blue-500 dark:text-blue-300">Selamat datang</p>
                    <p class="text-base font-semibold text-blue-900 dark:text-blue-100">Lengkapi atau perbarui profil Anda sebelum mengajukan kartu AK1.</p>
                </div>
                <a href="{{ route('pencaker.profile') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-gray-950">
                    <span>Kelola Profil</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection
