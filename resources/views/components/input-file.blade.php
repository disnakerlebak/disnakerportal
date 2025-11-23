@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'size' => 'md', // sm | md | lg
])

@php
    // Size variants (responsive)
    $sizes = [
        'sm' => 'file:px-2 file:py-1 text-xs sm:text-sm',
        'md' => 'file:px-3 file:py-2 text-sm sm:text-base',
        'lg' => 'file:px-4 file:py-2 text-base sm:text-lg',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ $label }}
            @if($required)
                <span class="text-red-400">*</span>
            @endif
        </label>
    @endif

    <input 
        type="file" 
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif

        {{ $attributes->merge([
            'class' => "
                block w-full cursor-pointer shadow-sm rounded-lg
                border border-gray-300 bg-white text-gray-900
                dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200

                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500

                file:cursor-pointer file:border-0 
                file:text-gray-700 file:bg-gray-100
                dark:file:text-gray-200 dark:file:bg-gray-700

                file:rounded-l-lg file:mr-4
                $sizeClass
            "
        ]) }}
    >
</div>
