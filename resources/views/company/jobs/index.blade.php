@extends('layouts.company-sidebar')

@section('title', 'Kelola Lowongan')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-100">Kelola Lowongan</h2>
            <p class="text-slate-400 text-sm">Pantau draft, publish, dan tutup lowongan dari satu tempat.</p>
        </div>

        <livewire:company.jobs-table />
      
    </div>
@endsection
@include('company.jobs.partials.job-form-modal')
        @include('company.jobs.partials.job-action-modal')
        @include('company.jobs.partials.job-preview-modal')
