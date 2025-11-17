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
        class="fixed inset-0 flex items-center justify-center modal-backdrop z-50"
        style="display: none"
    >
        <div
            @click.away="open = false"
            x-transition.scale
            class="modal-panel w-full {{ $maxWidth }} mx-4"
        >
            {{-- Header --}}
            <div class="modal-panel-header flex items-center justify-between px-6 py-4">
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
                <button @click="open = false" class="modal-close text-xl leading-none">✕</button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
