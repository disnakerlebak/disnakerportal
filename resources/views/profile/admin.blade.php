@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <header class="mb-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-blue-400">Pengaturan</p>
        <h1 class="text-2xl font-semibold text-gray-100 mt-1">Pengaturan Akun Admin</h1>
        <p class="text-sm text-gray-400 mt-1">Kelola detail akun, keamanan, dan preferensi login Anda.</p>
    </header>

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-800 bg-gray-900/70 p-6 shadow-lg ring-1 ring-white/5">
            @include('profile.partials.update-profile-information-form', ['user' => $user])
        </div>

        <div class="rounded-2xl border border-gray-800 bg-gray-900/70 p-6 shadow-lg ring-1 ring-white/5">
            @include('profile.partials.update-password-form')
        </div>

        <div class="rounded-2xl border border-gray-800 bg-gray-900/70 p-6 shadow-lg ring-1 ring-white/5">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
