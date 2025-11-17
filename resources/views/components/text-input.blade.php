@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm']) }}>
