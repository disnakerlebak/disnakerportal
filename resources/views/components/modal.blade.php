@props([
    'id' => null,
    'name' => null,
    'title' => null,
    'size' => 'md',
    'maxWidth' => null,
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

<div id="{{ $resolvedId }}" class="hidden fixed inset-0 z-[99999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full {{ $modalSize }} max-h-full">
        <div class="relative rounded-xl border border-slate-800 bg-slate-950/95 text-slate-100 shadow-2xl">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-800 bg-slate-900/70">
                <h3 class="text-lg font-semibold">{{ $title }}</h3>
                <button type="button" data-close-modal="{{ $resolvedId }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    ✕
                </button>
            </div>
            <div class="px-4 py-4">
                {{ $slot }}
            </div>
            @if (isset($footer))
                <div class="px-4 py-3 border-t border-slate-800 bg-slate-900/70 flex items-center justify-end gap-2">
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
    })();
</script>
