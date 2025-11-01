@props([
    'title' => 'Modal Title',
    'maxWidth' => 'max-w-2xl',
])

<div x-data="{ open: false }" class="relative">
    {{-- Trigger Tombol --}}
    <div @click="open = true">
        {{ $trigger ?? '' }}
    </div>

    {{-- Modal Overlay --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50"
        style="display: none"
    >
        <div
            @click.away="open = false"
            x-transition.scale
            class="bg-gray-800 text-gray-100 rounded-2xl shadow-2xl w-full {{ $maxWidth }} mx-4 border border-gray-700"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
                <button @click="open = false" class="text-gray-400 hover:text-gray-200 text-xl leading-none">✕</button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
