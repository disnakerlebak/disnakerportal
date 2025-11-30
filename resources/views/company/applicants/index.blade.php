@extends('layouts.company-sidebar')

@section('title', 'Semua Pelamar')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="space-y-3 mb-4">
        <x-company-breadcrumb :items="[['label' => 'Semua Pelamar']]" />
        <div>
            <h2 class="text-2xl font-semibold text-slate-100">Semua Pelamar</h2>
            <p class="text-slate-400 text-sm">Daftar semua pelamar ke lowongan Anda akan ditampilkan di sini.</p>
        </div>
    </div>

    <div class="relative mt-6">
        <button id="dropdownButton" data-dropdown-toggle="dropdownMenu"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
            Menu
        </button>

        <div id="dropdownMenu"
            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Detail</a></li>
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Riwayat</a></li>
            </ul>
        </div>
    </div>
</div>

@endsection
