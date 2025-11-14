<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['disabled' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['disabled' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button
    <?php echo e($attributes->merge([
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
    ])); ?>

    <?php if($disabled): ?> disabled <?php endif; ?>
>
    <?php echo e($slot); ?>

</button>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/components/primary-button.blade.php ENDPATH**/ ?>