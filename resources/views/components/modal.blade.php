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

<div id="{{ $resolvedId }}" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4">

    <!-- BACKDROP ANIMATED -->
    <div class="fixed inset-0 bg-black/40 opacity-0 transition-opacity duration-200 ease-out"
         data-backdrop="{{ $resolvedId }}"></div>

    <div class="w-full {{ $modalSize }} max-h-full relative z-[100000]">
        <!-- PANEL ANIMATED -->
        <div class="relative bg-white rounded-2xl modal-panel opacity-0 scale-95
                    transition-all duration-200 ease-out shadow-xl">

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
                <div class="modal-panel-footer px-4 py-3 flex items-center justify-end gap-2 rounded-b-2xl">
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

    const panel = modal.querySelector('.modal-panel');
    const backdrop = modal.querySelector(`[data-backdrop="${id}"]`);

    const showModal = () => {
        modal.classList.remove('hidden');

        // start hidden state
        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('opacity-100', 'scale-100');
        }, 10);
    };

    const hideModal = () => {
        // animate exit
        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'scale-95');
        panel.classList.remove('opacity-100', 'scale-100');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    const closeButtons = modal.querySelectorAll('[data-close-modal]');
    closeButtons.forEach(btn => btn.addEventListener('click', hideModal));

    window.addEventListener('open-modal', (e) => {
        if (e.detail === id || (e.detail?.id && e.detail.id === id)) {
            showModal();
        }
    });

    window.addEventListener('close-modal', (e) => {
        // Only close when ID matches — prevent closing all modals
        if (!e.detail) return;

        if (e.detail === id || (e.detail?.id && e.detail.id === id)) {
            hideModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });
})();
</script>
