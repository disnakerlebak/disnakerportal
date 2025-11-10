@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    // animation: 'zoom' | 'slide-up' | 'slide-down'
    'animation' => 'zoom',
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
    'full' => 'sm:max-w-[95vw]',
][$maxWidth] ?? 'sm:max-w-2xl';

// Panel transition presets
[$enter,$enterStart,$enterEnd,$leave,$leaveStart,$leaveEnd] = match($animation) {
    'slide-up' => [
        'ease-out duration-250',
        'opacity-0 translate-y-3',
        'opacity-100 translate-y-0',
        'ease-in duration-150',
        'opacity-100 translate-y-0',
        'opacity-0 translate-y-3',
    ],
    'slide-down' => [
        'ease-out duration-250',
        'opacity-0 -translate-y-3',
        'opacity-100 translate-y-0',
        'ease-in duration-150',
        'opacity-100 translate-y-0',
        'opacity-0 -translate-y-3',
    ],
    default => [ // zoom
        'ease-out duration-250',
        'opacity-0 translate-y-1 sm:translate-y-0 sm:scale-95',
        'opacity-100 translate-y-0 sm:scale-100',
        'ease-in duration-150',
        'opacity-100 translate-y-0 sm:scale-100',
        'opacity-0 translate-y-1 sm:translate-y-0 sm:scale-95',
    ],
};
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable()?.focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 z-50"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <!-- Overlay -->
    <div
        x-show="show"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Panel wrapper (centered) -->
    <div
        x-show="show"
        class="relative z-10 flex h-full w-full items-center justify-center p-4"
        x-transition:enter="{{ $enter }}"
        x-transition:enter-start="{{ $enterStart }}"
        x-transition:enter-end="{{ $enterEnd }}"
        x-transition:leave="{{ $leave }}"
        x-transition:leave-start="{{ $leaveStart }}"
        x-transition:leave-end="{{ $leaveEnd }}"
    >
        <div class="w-full {{ $maxWidthClass }} max-h-[85vh] overflow-y-auto rounded-xl border border-slate-800 bg-slate-900 text-slate-100 shadow-xl">
            {{ $slot }}
        </div>
    </div>
</div>
