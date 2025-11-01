@props([
    'id' => null,
    'name',
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'autocomplete' => 'off',
])

<div x-data="{ show: false }" class="w-full">
    @if($label)
        <x-input-label :for="$id ?? $name" :value="$label" />
    @endif

    <div class="relative mt-1">
        <input
            :id="$id ?? $name"
            name="{{ $name }}"
            type="password"
            x-bind:type="show ? 'text' : 'password'"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            autocomplete="{{ $autocomplete }}"
            {{ $attributes->merge([
                'class' =>
                    'block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 ' .
                    'dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500'
            ]) }}
        />

        <button type="button"
                x-on:click="show = !show"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-300">
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                <circle cx="12" cy="12" r="3" />
            </svg>
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.315-3.694m3.21-2.152A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.054 10.054 0 01-4.43 5.818M3 3l18 18"/>
            </svg>
        </button>
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
