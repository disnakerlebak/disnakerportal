@props(['items' => []])

@php
    $trail = collect($items)->filter(fn($item) => !empty($item['label']))->values();
@endphp

<nav class="flex items-center text-sm text-slate-300" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        <li class="inline-flex items-center">
            <a href="{{ route('company.dashboard') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-blue-400">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </li>

        @foreach($trail as $index => $item)
            @php $isLast = $index === $trail->count() - 1; @endphp
            <li>
                <div class="flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7"/>
                    </svg>
                    @if(!$isLast && !empty($item['url']))
                        <a href="{{ $item['url'] }}"
                           class="text-sm font-medium text-slate-300 hover:text-blue-400">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-sm font-semibold text-slate-100">{{ $item['label'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
