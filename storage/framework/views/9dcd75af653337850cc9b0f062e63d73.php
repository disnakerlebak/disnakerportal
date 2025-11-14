<?php $__env->startSection('title', 'Verifikasi AK1'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(class_exists('Livewire\\Livewire')): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.ak1-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4077545573-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php else: ?>
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="rounded-xl border border-amber-500 bg-amber-500/10 text-amber-100 p-8 space-y-4">
                <h2 class="text-2xl font-semibold">Livewire belum tersedia</h2>
                <p class="text-sm text-amber-200">
                    Komponen verifikasi AK1 baru membutuhkan paket <span class="font-semibold">livewire/livewire</span>.
                    Jalankan perintah <code class="bg-black/30 px-2 py-1 rounded">composer require livewire/livewire</code>
                    kemudian refresh halaman ini.
                </p>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/admin/ak1/index.blade.php ENDPATH**/ ?>