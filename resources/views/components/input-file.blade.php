@props([
    'label' => '',
    'name' => '',
    'required' => false,
])

<div>
    <label for="{{ $name }}" class="block text-sm text-gray-500 dark:text-gray-300">
        {{ $label }}
    </label>
    <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'mt-1 w-full text-sm text-gray-300 border border-gray-700 rounded-lg p-2
            bg-gray-900 focus:ring-2 focus:ring-indigo-500'
        ]) }}
    >
</div>
