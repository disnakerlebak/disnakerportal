@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
])

<div>
    <label for="{{ $name }}" class="block text-sm text-gray-500 dark:text-gray-300">
        {{ $label }}
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'mt-1 w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
        ]) }}
    >
</div>
