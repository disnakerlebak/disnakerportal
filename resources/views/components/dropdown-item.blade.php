@props(['icon' => null, 'modal' => null])

<li>
    <button type="button"
            data-trigger="dropdown-modal"
            @if($modal) data-modal-target="{{ $modal }}" data-modal-toggle="{{ $modal }}" @endif
            {{ $attributes->merge(['class' => 'flex w-full items-center gap-2 px-4 py-2 text-left hover:bg-slate-700 hover:text-white transition rounded-md']) }}>
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4"/>
        @endif
        {{ $slot }}
    </button>
</li>
