@props([
    'id' => null,
    'name' => null,
    'title' => null,
    'size' => 'md',
    'maxWidth' => null,
    'hideHeader' => false,
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
    $sizeKey = $size ?: ($maxWidth === 'full' ? 'full' : 'md');
    $modalSize = $sizes[$sizeKey] ?? $sizes['md'];
@endphp

<div id="{{ $resolvedId }}" class="hidden fixed inset-0 z-[99999] modal-backdrop flex items-center justify-center p-4">
    <div class="w-full {{ $modalSize }} max-h-full">
        <div class="relative modal-panel">
            @unless($hideHeader)
                <div class="modal-panel-header flex items-center justify-between px-4 py-3 rounded-t-2xl">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    <button type="button" data-close-modal="{{ $resolvedId }}" class="modal-close">
                        ✕
                    </button>
                </div>
            @endunless
            <div class="px-4 py-4">
                {{ $slot }}
            </div>
            @if (isset($footer))
                <div class="modal-panel-footer px-4 py-3 flex items-center justify-end gap-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    (() => {
        const id = @json($resolvedId);
        const modal = document.getElementById(id);
        if (!modal) return;

        const closeButtons = modal.querySelectorAll('[data-close-modal]');
        closeButtons.forEach(btn => btn.addEventListener('click', () => modal.classList.add('hidden')));

        window.addEventListener('open-modal', (e) => {
            if (e.detail === id || (e.detail?.id && e.detail.id === id)) {
                modal.classList.remove('hidden');
            }
        });
        window.addEventListener('close-modal', (e) => {
            if (!e.detail || e.detail === id || (e.detail?.id && e.detail.id === id)) {
                modal.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            }
        });
    })();
</script>
