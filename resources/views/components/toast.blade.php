@props([
    'type' => 'success',
    'message' => '',
    'position' => 'bottom-right',
])

@php
$colors = [
    'success' => 'bg-green-600 text-white',
    'error' => 'bg-red-600 text-white',
    'warning' => 'bg-yellow-500 text-black',
    'info' => 'bg-blue-600 text-white',
];

$icons = [
    'success' => '✅',
    'error' => '❌',
    'warning' => '⚠️',
    'info' => 'ℹ️',
];

$positions = [
    'top-right' => 'top-5 right-5',
    'bottom-right' => 'bottom-5 right-5',
    'bottom-center' => 'bottom-5 left-1/2 -translate-x-1/2',
    'center' => 'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2',
];
@endphp

<div id="toast"
     class="fixed {{ $positions[$position] ?? $positions['bottom-right'] }}
            z-[9999] flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg
            {{ $colors[$type] }}
            opacity-0 translate-y-3
            transition-all duration-500 ease-in-out select-none pointer-events-auto">
    <span class="text-lg">{{ $icons[$type] ?? '' }}</span>
    <span class="text-sm font-medium">{{ $message }}</span>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById('toast');
    if (!toast) return;

    // Munculkan dengan animasi
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-y-3');
        toast.classList.add('opacity-100', 'translate-y-0');
    }, 150);

    // Hilangkan otomatis
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-3');
        setTimeout(() => toast.remove(), 500); // Hapus elemen dari DOM
    }, 4000);
});
</script>
