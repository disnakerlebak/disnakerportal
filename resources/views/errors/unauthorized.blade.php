@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-gray-200">
    <h1 class="text-3xl font-bold text-red-500 mb-4">Akses Ditolak</h1>
    <p class="text-gray-400 mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ route('admin.dashboard') }}"
       class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md">
        Kembali ke Dashboard
    </a>
</div>
@endsection
