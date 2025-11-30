@extends('layouts.company-sidebar')

@section('title', 'Riwayat Proses Pelamar')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="space-y-3 mb-4">
            <x-company-breadcrumb :items="[
                ['label' => 'Semua Pelamar', 'url' => route('company.applicants.index')],
                ['label' => 'Riwayat Proses']
            ]" />
            <div>
                <h2 class="text-2xl font-semibold text-slate-100">Riwayat Proses Pelamar</h2>
                <p class="text-slate-400 text-sm">Timeline dan riwayat proses pelamar akan ditampilkan di sini.</p>
            </div>
        </div>
    </div>
@endsection
