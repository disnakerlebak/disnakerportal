@props([
    'id' => null,
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
])

<div class="w-full">
    @if($label)
        <x-input-label :for="$id ?? $name" :value="$label" />
    @endif

    <select
        :id="$id ?? $name"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' =>
                'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 ' .
                'dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500'
        ]) }}
    >
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
