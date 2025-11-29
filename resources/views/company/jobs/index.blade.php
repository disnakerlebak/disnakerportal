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

    @include('company.jobs.partials.job-form-modal')
    @include('company.jobs.partials.job-action-modal')
    @include('company.jobs.partials.job-preview-modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('toast', (detail = {}) => {
                if (typeof Toastify === 'undefined') return;
                Toastify({
                    text: detail?.message || 'Berhasil',
                    duration: 3500,
                    close: true,
                    gravity: 'bottom',
                    position: 'right',
                    backgroundColor: detail?.type === 'error' ? '#dc2626' : '#16a34a',
                    stopOnFocus: true,
                }).showToast();
            });
        });

        document.addEventListener('livewire:load', () => {
            const reinitTooltips = () => {
                if (typeof initFlowbite === 'function') {
                    initFlowbite();
                }
            };

            Livewire.hook('message.processed', () => {
                reinitTooltips();
            });
        });
    </script>
@endpush
