<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'modalDefault',
    'title' => 'Form',
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => 'Simpan',
    'cancelLabel' => 'Batal'
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
    'id' => 'modalDefault',
    'title' => 'Form',
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => 'Simpan',
    'cancelLabel' => 'Batal'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div id="<?php echo e($id); ?>"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">

    
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-700/30
                flex flex-col overflow-hidden min-h-0"
         style="max-height:90vh">

        
        <div class="p-4 border-b border-gray-600/20 flex justify-between items-center
                    bg-white dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?php echo e($title); ?></h3>
            <button type="button"
                    class="text-gray-400 hover:text-gray-200 text-2xl font-bold"
                    data-modal-close="<?php echo e($id); ?>">×</button>
        </div>

        
        <?php
            $httpMethod = strtoupper($method);
            $formMethod = in_array($httpMethod, ['PUT','PATCH','DELETE']) ? 'POST' : $httpMethod;
        ?>
        <form method="<?php echo e($formMethod); ?>" action="<?php echo e($action); ?>" enctype="multipart/form-data"
              class="p-6 overflow-y-auto grow space-y-4">
            <?php echo csrf_field(); ?>
            <?php if(in_array($httpMethod, ['PUT', 'PATCH', 'DELETE'])): ?>
                <?php echo method_field($httpMethod); ?>
            <?php endif; ?>

            
            <?php echo e($slot); ?>


            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-600/30">
                <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-400 text-gray-700 dark:text-gray-200
                               hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        data-modal-close="<?php echo e($id); ?>">
                    <?php echo e($cancelLabel); ?>

                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                    <?php echo e($submitLabel); ?>

                </button>
            </div>
        </form>
    </div>
</div>


<?php if (! $__env->hasRenderedOnce('3f9d9d5b-188b-406b-b049-66e8c6df09dd')): $__env->markAsRenderedOnce('3f9d9d5b-188b-406b-b049-66e8c6df09dd'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === Buka Modal ===
            document.querySelectorAll('[data-modal-open]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-modal-open');
                    const modal = document.getElementById(target);
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden'); // 🔒 kunci scroll
                    }
                });
            });

            // === Tutup Modal ===
            document.querySelectorAll('[data-modal-close]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-modal-close');
                    const modal = document.getElementById(target);
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden'); // 🔓 aktifkan scroll
                    }
                });
            });

            // === Tutup Modal jika klik area luar ===
            document.querySelectorAll('[id^="modal"]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            });

            // === Tutup Modal saat tekan ESC ===
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[id^="modal"]').forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                }
            });
        });
    </script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/components/modal-form.blade.php ENDPATH**/ ?>