<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => null,
    'label',
    'href' => '#',
    'method' => 'GET',
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
    'icon' => null,
    'label',
    'href' => '#',
    'method' => 'GET',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $method = strtoupper($method);
    $url = is_string($href) ? $href : '#';
    $current = url()->current();
    $isExternal = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    $resolvedUrl = $isExternal ? $url : ($url === '#' ? $url : url($url));
    $isActive = $url !== '#' && $current === $resolvedUrl;

    $baseClasses = 'flex w-full items-center gap-3 px-3 py-2 rounded-md transition-colors duration-150';
    $stateClasses = $isActive
        ? 'bg-indigo-600 text-white shadow-sm'
        : 'text-slate-300 hover:bg-slate-800';

    $iconMarkup = $icon instanceof \Illuminate\Contracts\Support\Htmlable
        ? $icon->toHtml()
        : (string) $icon;
?>

<?php if($method === 'POST'): ?>
    <form method="POST" action="<?php echo e($url); ?>" class="w-full">
        <?php echo csrf_field(); ?>
        <button type="submit" class="<?php echo e($baseClasses); ?> <?php echo e($stateClasses); ?> w-full text-left">
            <?php if($icon): ?>
                <span class="flex h-5 w-5 items-center justify-center text-current"><?php echo $iconMarkup; ?></span>
            <?php endif; ?>
            <span class="truncate"><?php echo e($label); ?></span>
        </button>
    </form>
<?php else: ?>
    <a href="<?php echo e($url); ?>" class="<?php echo e($baseClasses); ?> <?php echo e($stateClasses); ?>">
        <?php if($icon): ?>
            <span class="flex h-5 w-5 items-center justify-center text-current"><?php echo $iconMarkup; ?></span>
        <?php endif; ?>
        <span class="truncate"><?php echo e($label); ?></span>
    </a>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/components/sidebar-link.blade.php ENDPATH**/ ?>