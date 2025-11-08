@props([
    'icon' => null,
    'label',
    'href' => '#',
    'method' => 'GET',
])

@php
    $method = strtoupper($method);
    $url = is_string($href) ? $href : '#';
    $current = url()->current();
    $isExternal = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    $resolvedUrl = $isExternal ? $url : ($url === '#' ? $url : url($url));
    $isActive = $url !== '#' && $current === $resolvedUrl;

    $baseClasses = 'flex w-full items-center gap-3 px-3 py-2 rounded-md transition-colors duration-150';
    $stateClasses = $isActive
        ? 'bg-indigo-600 text-white shadow-sm'
        : 'text-slate-300 hover:bg-slate-800';

    $iconMarkup = $icon instanceof \Illuminate\Contracts\Support\Htmlable
        ? $icon->toHtml()
        : (string) $icon;
@endphp

@if ($method === 'POST')
    <form method="POST" action="{{ $url }}" class="w-full">
        @csrf
        <button type="submit" class="{{ $baseClasses }} {{ $stateClasses }} w-full text-left">
            @if ($icon)
                <span class="flex h-5 w-5 items-center justify-center text-current">{!! $iconMarkup !!}</span>
            @endif
            <span class="truncate">{{ $label }}</span>
        </button>
    </form>
@else
    <a href="{{ $url }}" class="{{ $baseClasses }} {{ $stateClasses }}">
        @if ($icon)
            <span class="flex h-5 w-5 items-center justify-center text-current">{!! $iconMarkup !!}</span>
        @endif
        <span class="truncate">{{ $label }}</span>
    </a>
@endif
