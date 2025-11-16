@props([
    'id' => 'confirm-modal',
    'size' => 'sm',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Ya',
    'cancelText' => 'Batal',
    'confirmColor' => 'red',
])

@php
    $confirmClass = match($confirmColor) {
        'green', 'emerald' => 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
        'yellow', 'amber' => 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400 text-gray-900',
        'gray', 'neutral' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
        default => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    };
@endphp

<x-modal :id="$id" :size="$size" :title="$title">
    <p class="text-sm text-gray-200 leading-relaxed">{!! nl2br(e($message)) !!}</p>

    <x-slot name="footer">
        <button type="button"
            data-modal-hide="{{ $id }}"
            class="px-4 py-2 text-sm rounded-lg border border-gray-600 bg-gray-800 text-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-500">
            {{ $cancelText }}
        </button>

        <button type="button"
            {{ $attributes->merge([
                'class' => "px-4 py-2 text-sm rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-gray-800 {$confirmClass}",
            ]) }}>
            {{ $confirmText }}
        </button>
    </x-slot>
</x-modal>
