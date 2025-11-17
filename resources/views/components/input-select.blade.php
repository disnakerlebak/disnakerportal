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
                'mt-1 block w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500'
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
