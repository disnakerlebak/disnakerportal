@extends('layouts.admin')

@section('title', 'Kelola Perusahaan')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-100">Kelola Perusahaan</h1>
            <p class="text-sm text-slate-400">Daftar perusahaan yang terdaftar di Disnaker Portal.</p>
        </div>

        <livewire:admin.manage-companies-table />
    </div>
@endsection

