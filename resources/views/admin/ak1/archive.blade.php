@extends('layouts.admin')
@section('title', 'Arsip AK1')

@section('content')
    @if (class_exists('Livewire\\Livewire'))
        <livewire:admin.ak1-table :archived-only="true" />
    @else
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="rounded-xl border border-amber-500 bg-amber-500/10 text-amber-100 p-8 space-y-4">
                <h2 class="text-2xl font-semibold">Livewire belum tersedia</h2>
                <p class="text-sm text-amber-200">
                    Komponen arsip AK1 membutuhkan paket <span class="font-semibold">livewire/livewire</span>.
                    Jalankan perintah <code class="bg-black/30 px-2 py-1 rounded">composer require livewire/livewire</code>
                    kemudian refresh halaman ini.
                </p>
            </div>
        </div>
    @endif
@endsection
