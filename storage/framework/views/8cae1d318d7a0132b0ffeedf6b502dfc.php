<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
]));

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

foreach (array_filter(([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div>
    <label for="<?php echo e($name); ?>" class="block text-sm text-gray-500 dark:text-gray-300">
        <?php echo e($label); ?>

    </label>
    <input 
        type="<?php echo e($type); ?>" 
        name="<?php echo e($name); ?>" 
        id="<?php echo e($name); ?>"
        value="<?php echo e(old($name, $value)); ?>"
        <?php if($required): ?> required <?php endif; ?>
        <?php echo e($attributes->merge([
            'class' => 'mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500'
        ])); ?>

    >
</div>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/components/input-text.blade.php ENDPATH**/ ?>