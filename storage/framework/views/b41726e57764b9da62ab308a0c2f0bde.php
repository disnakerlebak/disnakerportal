<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col gap-4">
    
    <form wire:submit.prevent="applyFilters"
          class="flex flex-wrap items-center gap-3"
          @keydown.enter.prevent="$wire.applyFilters()">

        <input type="text"
               wire:model.defer="q"
               placeholder="Cari nama atau NIK..."
               class="w-72 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />

        <select wire:model.defer="profileStatus"
                class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status Profil</option>
            <option value="complete">Lengkap</option>
            <option value="incomplete">Belum Lengkap</option>
        </select>

        <select wire:model.defer="ak1Status"
                class="rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Status AK1</option>
            <option value="never">Belum Pernah Mengajukan</option>
            <option value="pending">Menunggu Verifikasi</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
            <option value="expired">Kadaluarsa</option>
        </select>

        <button type="submit"
                class="px-4 py-1.5 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
            Terapkan
        </button>

        <button type="button"
                wire:click="clearFilters"
                class="px-3 py-1.5 rounded bg-slate-700 hover:bg-slate-600 text-sm">
            Reset
        </button>
    </form>

    
    <div class="relative flex-1 min-h-0 flex flex-col rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
        
        <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-slate-950/30 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-900/70 border border-slate-700 shadow text-indigo-200">
                <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-sm">Memuat data…</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-200">
                <thead class="bg-slate-800 text-slate-200">
                <tr>
                    <th class="p-3 text-left">Nama Lengkap</th>
                    <th class="p-3 text-left">NIK</th>
                    <th class="p-3 text-left">Status Pengguna</th>
                    <th class="p-3 text-left">Status Profil</th>
                    <th class="p-3 text-left">Status AK1</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $p   = $u->jobseekerProfile;
                        $app = $u->latestCardApplication;

                        // Status pengguna (aktif/nonaktif)
                        $isActive = ($u->status ?? 'active') === 'active';
                        $userStatusLabel = $isActive ? 'Aktif' : 'Tidak Aktif';
                        $userStatusClass = $isActive
                            ? 'bg-emerald-600/90 text-emerald-50'
                            : 'bg-slate-600/90 text-slate-100';

                        // Status profil
                        $profilLengkap = $p && $p->nik;
                        $profilLabel   = $profilLengkap ? 'Lengkap' : 'Belum Lengkap';
                        $profilClass   = $profilLengkap
                            ? 'bg-emerald-600/90 text-emerald-50'
                            : 'bg-amber-500/90 text-slate-950';

                        // Status AK1
                        if (!$app) {
                            $ak1Label = 'Belum Pernah Mengajukan';
                            $ak1Class = 'bg-slate-700/80 text-slate-100';
                        } else {
                            switch ($app->status) {
                                case 'pending':
                                    $ak1Label = 'Menunggu Verifikasi';
                                    $ak1Class = 'bg-indigo-600/90 text-indigo-50';
                                    break;
                                case 'approved':
                                    $ak1Label = 'Disetujui';
                                    $ak1Class = 'bg-emerald-600/90 text-emerald-50';
                                    break;
                                case 'rejected':
                                    $ak1Label = 'Ditolak';
                                    $ak1Class = 'bg-rose-600/90 text-rose-50';
                                    break;
                                case 'expired':
                                    $ak1Label = 'Kadaluarsa';
                                    $ak1Class = 'bg-amber-600/90 text-amber-50';
                                    break;
                                default:
                                    $ak1Label = strtoupper($app->status);
                                    $ak1Class = 'bg-slate-700/80 text-slate-100';
                            }
                        }
                    ?>
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($p->nama_lengkap ?? $u->name ?? '-'); ?></div>
                            <div class="text-[11px] text-slate-400"><?php echo e($u->email); ?></div>
                        </td>
                        <td class="p-3"><?php echo e($p->nik ?? '-'); ?></td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold <?php echo e($userStatusClass); ?>">
                                <?php echo e($userStatusLabel); ?>

                            </span>
                        </td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold <?php echo e($profilClass); ?>">
                                <?php echo e($profilLabel); ?>

                            </span>
                        </td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold <?php echo e($ak1Class); ?>">
                                <?php echo e($ak1Label); ?>

                            </span>
                            <!--[if BLOCK]><![endif]--><?php if($app?->nomor_ak1): ?>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    No: <?php echo e($app->nomor_ak1); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center">
                                <div class="relative" x-data="dropdownMenu()" x-init="init()">
                                    <button @click="toggle($event)"
                                            type="button"
                                            class="rounded-md border border-slate-700 bg-slate-800 p-2 text-white text-sm transition duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="5" r="1"/>
                                            <circle cx="12" cy="12" r="1"/>
                                            <circle cx="12" cy="19" r="1"/>
                                        </svg>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" @click.away="close()" @keydown.escape.window="close()"
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                             :class="dropUp ? 'origin-bottom-right' : 'origin-top-right'"
                                             class="fixed z-[70] w-64 rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800"
                                             :style="style + (dropUp ? ';transform: translateY(-100%)' : '')">
                                            
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition"
                                                    @click="
                                                      window.dispatchEvent(new CustomEvent('close-dropdowns'));
                                                      window.dispatchEvent(new CustomEvent('pencaker-detail', {
                                                        detail: {
                                                          url: '<?php echo e(route('admin.pencaker.detail', $u->id)); ?>',
                                                          ak1: '<?php echo e($app?->nomor_ak1 ?? ''); ?>'
                                                        }
                                                      }));
                                                    ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </button>

                                            
                                            <a href="<?php echo e(route('admin.pencaker.history', $u->id)); ?>"
                                               class="w-full text-left px-4 py-2 text-sm text-purple-400 hover:bg-purple-700/20 flex items-center gap-2 transition"
                                               @click="window.dispatchEvent(new CustomEvent('close-dropdowns'))">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Riwayat
                                            </a>

                                            
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-amber-300 hover:bg-amber-600/20 flex items-center gap-2 transition"
                                                    onclick="openDeactivateModal(this)"
                                                    data-user-id="<?php echo e($u->id); ?>"
                                                    data-user-name="<?php echo e($p->nama_lengkap ?? $u->name ?? '-'); ?>"
                                                    data-user-email="<?php echo e($u->email); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M18.364 18.364A9 9 0 1 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Nonaktifkan Akun
                                            </button>

                                            
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-sky-300 hover:bg-sky-600/20 flex items-center gap-2 transition"
                                                    onclick="openResetModal(this)"
                                                    data-user-id="<?php echo e($u->id); ?>"
                                                    data-user-name="<?php echo e($p->nama_lengkap ?? $u->name ?? '-'); ?>"
                                                    data-user-email="<?php echo e($u->email); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 4v6h6M20 20v-6h-6M5 19A9 9 0 0 1 19 5"/>
                                                </svg>
                                                Reset Profil
                                            </button>

                                            
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-rose-300 hover:bg-rose-700/20 flex items-center gap-2 transition"
                                                    onclick="openDeleteModal(this)"
                                                    data-user-id="<?php echo e($u->id); ?>"
                                                    data-user-name="<?php echo e($p->nama_lengkap ?? $u->name ?? '-'); ?>"
                                                    data-user-email="<?php echo e($u->email); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 7h12M9 7V4h6v3m-7 4v7m4-7v7m4-7v7M5 7l1 13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-13"/>
                                                </svg>
                                                Hapus Pencaker
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">
                            Belum ada data pencaker.
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            <?php echo e($users->links()); ?>

        </div>
    </div>

    
    <div x-data="{ open:false, html:'', loading:false, ak1:'' }"
         @pencaker-detail.window="
            open=true; loading=true; html=''; ak1=($event.detail.ak1||'');
            fetch($event.detail.url, {headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(r=>r.text())
                .then(t=>{ html=t; })
                .catch(()=>{ html='<div class=\'p-6 text-red-300\'>Gagal memuat detail.</div>'; })
                .finally(()=>{ loading=false; });
         ">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
             @keydown.escape.window="open=false">
            <div @click.outside="open=false" class="bg-slate-900 w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden border border-slate-800">
                <div class="flex items-center justify-between px-6 py-3 border-b border-slate-800 sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Detail Pencaker
                        <span x-show="ak1" class="ml-2 text-sm font-normal text-slate-300">
                            — AK/1: <span x-text="ak1"></span>
                        </span>
                    </h3>
                    <button class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700" @click="open=false">Tutup</button>
                </div>
                <div class="max-h-[85vh] overflow-y-auto">
                    <template x-if="loading"><div class="p-6 text-slate-300">Memuat...</div></template>
                    <div class="p-6" x-html="html"></div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'confirm-deactivate','show' => false,'maxWidth' => 'md','animation' => 'slide-up']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'confirm-deactivate','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'maxWidth' => 'md','animation' => 'slide-up']); ?>
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold">Nonaktifkan Akun Pencaker</h3>
                <p class="text-sm text-gray-400 mt-1" id="deactivateModalSubtitle"></p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-deactivate'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed">
                Nonaktifkan akun pencaker ini? Mereka tidak dapat login sampai diaktifkan kembali.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeDeactivateModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmDeactivateBtn" class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 transition text-sm font-semibold text-white">Nonaktifkan</button>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'confirm-reset','show' => false,'maxWidth' => 'md','animation' => 'slide-up']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'confirm-reset','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'maxWidth' => 'md','animation' => 'slide-up']); ?>
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold">Reset Profil Pencaker</h3>
                <p class="text-sm text-gray-400 mt-1" id="resetModalSubtitle"></p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-reset'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed">
                Reset seluruh profil & riwayat (pendidikan, pelatihan, pengalaman) pencaker ini? AK1 tetap dipertahankan.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmResetBtn" class="px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 transition text-sm font-semibold text-white">Reset Profil</button>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'confirm-delete','show' => false,'maxWidth' => 'md','animation' => 'slide-up']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'confirm-delete','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'maxWidth' => 'md','animation' => 'slide-up']); ?>
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold">Hapus Pencaker</h3>
                <p class="text-sm text-gray-400 mt-1" id="deleteModalSubtitle"></p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'confirm-delete'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-300 leading-relaxed">
                Hapus pencaker ini <span class="font-semibold text-rose-300">BESERTA seluruh data dan riwayat AK1</span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 transition text-sm font-semibold text-white">Hapus Pencaker</button>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

    
    <?php if (! $__env->hasRenderedOnce('278fd0db-02c3-4b3f-aade-236c8ae9cfb1')): $__env->markAsRenderedOnce('278fd0db-02c3-4b3f-aade-236c8ae9cfb1'); ?>
        <?php $__env->startPush('scripts'); ?>
            <script>
                window.dropdownMenu = function () {
                    return {
                        open: false,
                        dropUp: false,
                        style: '',
                        width: 256,
                        init() {
                            window.addEventListener('close-dropdowns', () => { this.open = false; });
                        },
                        toggle(e) {
                            this.open = !this.open;
                            if (this.open) {
                                const rect = e.currentTarget.getBoundingClientRect();
                                const spaceBelow = window.innerHeight - rect.bottom;
                                this.dropUp = spaceBelow < 260;
                                let left = rect.right - this.width;
                                left = Math.max(8, Math.min(left, window.innerWidth - this.width - 8));
                                let top = this.dropUp ? rect.top - 8 : rect.bottom + 8;
                                this.style = `left:${left}px;top:${top}px`;
                            }
                        },
                        close() { this.open = false; }
                    }
                }

                // Variabel global untuk menyimpan data user yang akan dioperasikan
                let currentUserId = null;

                // Modal Nonaktifkan
                window.openDeactivateModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-deactivate' }));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deactivateModalSubtitle');
                    if (subtitle) {
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    // Set event listener untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmDeactivateBtn');
                    if (confirmBtn) {
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deactivateUser(currentUserId);
                            }
                            closeDeactivateModal();
                        };
                    }
                };

                window.closeDeactivateModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-deactivate' }));
                };

                // Modal Reset
                window.openResetModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-reset' }));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('resetModalSubtitle');
                    if (subtitle) {
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    // Set event listener untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmResetBtn');
                    if (confirmBtn) {
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).resetProfile(currentUserId);
                            }
                            closeResetModal();
                        };
                    }
                };

                window.closeResetModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-reset' }));
                };

                // Modal Delete
                window.openDeleteModal = function (button) {
                    window.dispatchEvent(new CustomEvent('close-dropdowns'));
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete' }));
                    currentUserId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-user-name') || '';
                    const email = button.getAttribute('data-user-email') || '';
                    const subtitle = document.getElementById('deleteModalSubtitle');
                    if (subtitle) {
                        subtitle.textContent = email ? `${name} · ${email}` : name;
                    }
                    // Set event listener untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmDeleteBtn');
                    if (confirmBtn) {
                        confirmBtn.onclick = function() {
                            const wireComponent = document.querySelector('[wire\\:id]');
                            if (wireComponent && window.Livewire) {
                                const wireId = wireComponent.getAttribute('wire:id');
                                window.Livewire.find(wireId).deleteUser(currentUserId);
                            }
                            closeDeleteModal();
                        };
                    }
                };

                window.closeDeleteModal = function () {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-delete' }));
                };
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/livewire/admin/manage-jobseekers-table.blade.php ENDPATH**/ ?>