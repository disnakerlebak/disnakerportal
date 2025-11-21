@props(['status'])

@php
$color = match($status) {
    'submit' => 'bg-blue-400',
    'repair_submit' => 'bg-yellow-400',
    'extend_submit' => 'bg-purple-400',
    'approve' => 'bg-green-400',
    'reject' => 'bg-red-400',
    'revision' => 'bg-amber-400',
    'printed' => 'bg-sky-400',
    'picked_up' => 'bg-indigo-400',
    'archived' => 'bg-gray-400',
    default => 'bg-gray-500'
};
@endphp

<span {{ $attributes->merge(['class' => "h-3 w-3 rounded-full ring-4 ring-gray-900 $color"]) }}></span>
