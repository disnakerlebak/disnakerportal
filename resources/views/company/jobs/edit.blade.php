@extends('layouts.company-sidebar')

@section('title', 'Edit Lowongan')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-semibold text-slate-100">Edit Lowongan</h2>
                <p class="text-slate-400 text-sm">Perbarui informasi lowongan dan simpan sebagai draft.</p>
            </div>
            <a href="{{ route('company.jobs.index') }}"
               class="inline-flex items-center gap-2 rounded-md border border-slate-700 px-3 py-2 text-sm text-slate-100 hover:bg-slate-800">
                ← Kembali ke Kelola
            </a>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow">
            <div class="p-4 md:p-6">
                <livewire:company.job-form
                    :use-modal="false"
                    :redirect-to="route('company.jobs.index')"
                    :job-id="$job->id"
                />
            </div>
        </div>
    </div>
@endsection

