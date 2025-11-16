@props([
    'id' => null,
    'name' => null, // fallback untuk pemanggilan lama
    'size' => 'md',
    'maxWidth' => null, // fallback pemanggilan lama
    'title' => null,
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
        'full' => 'max-w-7xl',
    ];

    $resolvedId = $id ?? $name ?? 'modal-default';
    $sizeKey = $size;

    if (! $sizeKey && $maxWidth) {
        $sizeKey = match($maxWidth) {
            'sm' => 'sm',
            'md' => 'md',
            'lg', 'xl', '2xl' => 'lg',
            '3xl', '4xl' => 'xl',
            '5xl', '6xl', '7xl', 'full' => 'full',
            default => null,
        };
    }

    $modalSize = $sizes[$sizeKey] ?? $sizes['md'];
@endphp

<div id="{{ $resolvedId }}" tabindex="-1"
     class="hidden fixed inset-0 z-[99999] overflow-y-auto bg-black/60 flex items-center justify-center p-4">

    <div class="relative w-full {{ $modalSize }} max-h-full">

        <div class="relative bg-slate-950/95 border border-slate-800 rounded-xl shadow-2xl">

            <div class="flex items-center justify-between p-4 border-b border-slate-800 bg-slate-900/70 backdrop-blur">
                <h3 class="text-lg font-semibold text-slate-100">
                    {{ $title }}
                </h3>
                <button type="button"
                        data-modal-hide="{{ $resolvedId }}"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-4 bg-slate-900/80 text-slate-100">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="p-4 border-t border-slate-800 bg-slate-900/70 flex items-center justify-end gap-2">
                    {{ $footer }}
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    (() => {
        const targetId = @json($resolvedId);
        const el = document.getElementById(targetId);
        const ModalClass = window.FlowbiteModal || window.Modal || window.Flowbite?.Modal;
        if (!el || !ModalClass) return;

        const instance = new ModalClass(el, { closable: true, backdrop: "dynamic" });
        el.__flowbiteModalInstance = instance;

        window.addEventListener("open-modal", (event) => {
            if (!event?.detail) return;
            if (event.detail === targetId || (event.detail.id && event.detail.id === targetId)) {
                instance.show();
            }
        });

        window.addEventListener("close-modal", (event) => {
            if (!event?.detail || event.detail === targetId || (event.detail.id && event.detail.id === targetId)) {
                instance.hide();
            }
        });
    })();
</script>
