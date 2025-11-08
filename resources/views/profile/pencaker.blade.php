@extends('layouts.pencaker')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100">
    <div class="mb-6">
        <p class="text-sm uppercase tracking-wide text-blue-400">Pengaturan</p>
        <h1 class="text-2xl font-semibold text-slate-100 mt-1">Kelola Akun Anda</h1>
        <p class="text-sm text-slate-400 mt-1">Perbarui informasi profil, kata sandi, dan keamanan akun pencaker.</p>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl ring-1 ring-white/5">
            @include('profile.partials.update-profile-information-form', ['user' => $user])
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl ring-1 ring-white/5">
            @include('profile.partials.update-password-form')
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl ring-1 ring-white/5">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
