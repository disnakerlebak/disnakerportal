@props(['disabled' => false])

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' =>
            'inline-flex items-center justify-center px-4 py-2 
             bg-blue-600 dark:bg-blue-500 
             border border-transparent rounded-md font-semibold 
             text-white text-sm tracking-wide 
             hover:bg-blue-700 dark:hover:bg-blue-600 
             focus:outline-none focus:ring-2 focus:ring-offset-2 
             focus:ring-blue-500 dark:focus:ring-offset-gray-800 
             transition-all duration-200 ease-in-out shadow-sm hover:shadow-md 
             disabled:opacity-50 disabled:cursor-not-allowed'
    ]) }}
    @if($disabled) disabled @endif
>
    {{ $slot }}
</button>
