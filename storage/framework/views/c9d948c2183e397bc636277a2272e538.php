<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    // animation: 'zoom' | 'slide-up' | 'slide-down'
    'animation' => 'zoom',
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
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    // animation: 'zoom' | 'slide-up' | 'slide-down'
    'animation' => 'zoom',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
    'full' => 'sm:max-w-[95vw]',
][$maxWidth] ?? 'sm:max-w-2xl';

// Panel transition presets
[$enter,$enterStart,$enterEnd,$leave,$leaveStart,$leaveEnd] = match($animation) {
    'slide-up' => [
        'ease-out duration-250',
        'opacity-0 translate-y-3',
        'opacity-100 translate-y-0',
        'ease-in duration-150',
        'opacity-100 translate-y-0',
        'opacity-0 translate-y-3',
    ],
    'slide-down' => [
        'ease-out duration-250',
        'opacity-0 -translate-y-3',
        'opacity-100 translate-y-0',
        'ease-in duration-150',
        'opacity-100 translate-y-0',
        'opacity-0 -translate-y-3',
    ],
    default => [ // zoom
        'ease-out duration-250',
        'opacity-0 translate-y-1 sm:translate-y-0 sm:scale-95',
        'opacity-100 translate-y-0 sm:scale-100',
        'ease-in duration-150',
        'opacity-100 translate-y-0 sm:scale-100',
        'opacity-0 translate-y-1 sm:translate-y-0 sm:scale-95',
    ],
};
?>

<div
    x-data="{
        show: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?>,
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            <?php echo e($attributes->has('focusable') ? 'setTimeout(() => firstFocusable()?.focus(), 100)' : ''); ?>

        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '<?php echo e($name); ?>' ? show = true : null"
    x-on:close-modal.window="$event.detail == '<?php echo e($name); ?>' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 z-50"
    style="display: <?php echo e($show ? 'block' : 'none'); ?>;"
>
    <!-- Overlay -->
    <div
        x-show="show"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Panel wrapper (centered) -->
    <div
        x-show="show"
        class="relative z-10 flex h-full w-full items-center justify-center p-4"
        x-transition:enter="<?php echo e($enter); ?>"
        x-transition:enter-start="<?php echo e($enterStart); ?>"
        x-transition:enter-end="<?php echo e($enterEnd); ?>"
        x-transition:leave="<?php echo e($leave); ?>"
        x-transition:leave-start="<?php echo e($leaveStart); ?>"
        x-transition:leave-end="<?php echo e($leaveEnd); ?>"
    >
        <div class="w-full <?php echo e($maxWidthClass); ?> max-h-[85vh] overflow-y-auto rounded-xl border border-slate-800 bg-slate-900 text-slate-100 shadow-xl">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/components/modal.blade.php ENDPATH**/ ?>