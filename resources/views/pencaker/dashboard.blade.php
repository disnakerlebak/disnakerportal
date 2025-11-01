@extends('layouts.pencaker')
@section('title', 'Dashboard')
@section('content')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard Pencaker
        </h2>

    <div class="max-w-7xl mx-auto p-6">
        <a href="{{ route('pencaker.profile.edit') }}"
           class="inline-flex items-center px-4 py-2 rounded bg-blue-600 text-white">
            Lengkapi Data Diri
        </a>
    </div>
@endsection
