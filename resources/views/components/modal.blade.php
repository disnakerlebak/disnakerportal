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
        'sm'   => 'max-w-md',
        'md'   => 'max-w-lg',
        'lg'   => 'max-w-3xl',
        'xl'   => 'max-w-5xl',
        'full' => 'max-w-7xl',
    ];

    $resolvedId = $id ?? $name ?? 'modal-default';
    $sizeKey    = $size ?: ($maxWidth === 'full' ? 'full' : 'md');
    $modalSize  = $sizes[$sizeKey] ?? $sizes['md'];
@endphp

<div
    id="{{ $resolvedId }}"
    data-global-modal
    class="modal-backdrop fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-slate-950/60"
>
    <div class="w-full {{ $modalSize }} max-h-full relative z-[100000]">
        <div class="modal-panel opacity-0 scale-95 transform transition-all duration-200 ease-out
                    shadow-2xl rounded-2xl overflow-hidden
                    bg-slate-900/90 border border-slate-800 text-slate-100 backdrop-blur">

            @unless($hideHeader)
                <div class="flex items-center justify-between px-5 py-4 rounded-t-2xl border-b border-slate-800 bg-slate-900/80">
                    <h3 class="text-lg font-semibold text-gray-100">{{ $title }}</h3>
                    <button
                        type="button"
                        class="modal-close text-slate-300 hover:text-white"
                        data-close-modal="{{ $resolvedId }}"
                    >
                        ✕
                    </button>
                </div>
            @endunless

            <div class="px-5 py-5">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="px-5 py-4 flex items-center justify-end gap-2 rounded-b-2xl border-t border-slate-800 bg-slate-900/80">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
