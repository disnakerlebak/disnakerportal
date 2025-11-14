<?php $__env->startSection('title', 'Kelola Admin'); ?>

<?php $__env->startSection('content'); ?>
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold text-gray-100">Kelola Admin Disnaker Portal</h1>
      <button
        @click="$dispatch('open-modal-create-admin')"
        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white"
      >+ Tambah Admin</button>
    </div>

    <?php if(session('success')): ?>
      <div class="mb-4 p-3 rounded bg-green-600/20 text-green-300 border border-green-600/40"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
      <div class="mb-4 p-3 rounded bg-red-600/20 text-red-300 border border-red-600/40">
        <ul class="list-disc list-inside">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-slate-200">
          <thead class="bg-slate-800 text-slate-200 uppercase text-xs sticky top-0 z-20 border-b border-slate-700 shadow-md shadow-slate-900/30">
            <tr>
              <th class="px-4 py-3 text-left">Nama</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Dibuat</th>
              <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr class="hover:bg-slate-800/50 transition">
                <td class="px-4 py-3"><?php echo e($admin->name); ?></td>
                <td class="px-4 py-3"><?php echo e($admin->email); ?></td>
                <td class="px-4 py-3">
                  <?php if($admin->status === 'active'): ?>
                    <span class="px-2 py-1 text-[11px] rounded-full bg-green-700/30 text-green-200 border border-green-600/40">Active</span>
                  <?php else: ?>
                    <span class="px-2 py-1 text-[11px] rounded-full bg-red-700/30 text-red-200 border border-red-600/40">Inactive</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3"><?php echo e($admin->created_at->format('d M Y H:i')); ?></td>
                <td class="px-4 py-3">
                  <?php if (isset($component)) { $__componentOriginal341083a04f2141aed5ec90f8f312c171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal341083a04f2141aed5ec90f8f312c171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action-dropdown','data' => ['admin' => $admin]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['admin' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal341083a04f2141aed5ec90f8f312c171)): ?>
<?php $attributes = $__attributesOriginal341083a04f2141aed5ec90f8f312c171; ?>
<?php unset($__attributesOriginal341083a04f2141aed5ec90f8f312c171); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal341083a04f2141aed5ec90f8f312c171)): ?>
<?php $component = $__componentOriginal341083a04f2141aed5ec90f8f312c171; ?>
<?php unset($__componentOriginal341083a04f2141aed5ec90f8f312c171); ?>
<?php endif; ?>
                </td>
              </tr>

              <!-- Edit Modal for this admin -->
              <?php if (isset($component)) { $__componentOriginal6aa2e8fd7174592948bd2011dea2bee9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-admin-form','data' => ['modalId' => 'edit-admin-' . $admin->id,'title' => 'Edit Admin','action' => route('admin.manage.update', $admin->id),'method' => 'POST','admin' => $admin]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-admin-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('edit-admin-' . $admin->id),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Edit Admin'),'action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.manage.update', $admin->id)),'method' => 'POST','admin' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9)): ?>
<?php $attributes = $__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9; ?>
<?php unset($__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6aa2e8fd7174592948bd2011dea2bee9)): ?>
<?php $component = $__componentOriginal6aa2e8fd7174592948bd2011dea2bee9; ?>
<?php unset($__componentOriginal6aa2e8fd7174592948bd2011dea2bee9); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada admin.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Modal -->
    <?php if (isset($component)) { $__componentOriginal6aa2e8fd7174592948bd2011dea2bee9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-admin-form','data' => ['modalId' => 'create-admin','title' => 'Tambah Admin','action' => route('admin.manage.store'),'method' => 'POST']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-admin-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'create-admin','title' => 'Tambah Admin','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.manage.store')),'method' => 'POST']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9)): ?>
<?php $attributes = $__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9; ?>
<?php unset($__attributesOriginal6aa2e8fd7174592948bd2011dea2bee9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6aa2e8fd7174592948bd2011dea2bee9)): ?>
<?php $component = $__componentOriginal6aa2e8fd7174592948bd2011dea2bee9; ?>
<?php unset($__componentOriginal6aa2e8fd7174592948bd2011dea2bee9); ?>
<?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php if (! $__env->hasRenderedOnce('c5b543a2-e3a9-4b05-b44d-2be4ec97e900')): $__env->markAsRenderedOnce('c5b543a2-e3a9-4b05-b44d-2be4ec97e900'); ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            // Dropdown util (dipakai juga di halaman lain) — posisi fixed, teleport ke body
            window.dropdownMenu = window.dropdownMenu || function () {
                return {
                    open: false,
                    dropUp: false,
                    style: '',
                    width: 224,
                    init() { window.addEventListener('close-dropdowns', () => { this.open = false; }); },
                    toggle(e) {
                        this.open = !this.open;
                        if (this.open) {
                            const rect = e.currentTarget.getBoundingClientRect();
                            const spaceBelow = window.innerHeight - rect.bottom;
                            this.dropUp = spaceBelow < 240;
                            let left = rect.right - this.width;
                            left = Math.max(8, Math.min(left, window.innerWidth - this.width - 8));
                            let top = this.dropUp ? rect.top - 8 : rect.bottom + 8;
                            this.style = `left:${left}px;top:${top}px`;
                        }
                    },
                    close() { this.open = false; }
                }
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/admin/manage_admin/index.blade.php ENDPATH**/ ?>