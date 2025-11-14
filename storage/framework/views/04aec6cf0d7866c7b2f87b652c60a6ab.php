<div class="max-w-6xl mx-auto h-full min-h-0 flex flex-col gap-4">
    <!-- <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-100">Daftar Pencaker Disetujui</h2>
    </div> -->

    <form wire:submit.prevent="apply" class="flex flex-wrap items-center gap-3" @keydown.enter.prevent="$wire.apply()">
        <input type="text"
               wire:model.defer="q"
               placeholder="Cari nama..."
               class="w-64 max-w-full rounded-lg border-slate-700 bg-slate-900/70 px-3 py-2 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />

        <label class="inline-flex items-center gap-2 text-sm text-slate-200 bg-slate-800/60 px-3 py-1.5 rounded border border-slate-700">
            <input type="checkbox" wire:model.defer="hasTraining" class="rounded border-slate-600 bg-slate-800">
            <span>Memiliki Pelatihan</span>
        </label>

        <label class="inline-flex items-center gap-2 text-sm text-slate-200 bg-slate-800/60 px-3 py-1.5 rounded border border-slate-700">
            <input type="checkbox" wire:model.defer="hasWork" class="rounded border-slate-600 bg-slate-800">
            <span>Memiliki Pengalaman</span>
        </label>

        <button type="submit" class="px-4 py-1.5 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Terapkan</button>
        <button type="button" wire:click="clearFilters" class="px-3 py-1.5 rounded bg-slate-700 hover:bg-slate-600 text-sm">Reset</button>
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
                    <th class="p-3 text-left">Jenis Kelamin</th>
                    <th class="p-3 text-left">Usia</th>
                    <th class="p-3 text-left">Pendidikan</th>
                    <th class="p-3 text-left">Keahlian</th>
                    <th class="p-3 text-left">Pengalaman Kerja</th>
                    <th class="p-3 text-left">Kecamatan</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $p = $u->jobseekerProfile;
                        $app = optional($u->cardApplications->first());
                        $usia = $p?->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->age : '-';
                        $trainingCount = $p?->trainings_count ?? 0;
                        $workCount = $p?->work_experiences_count ?? 0;
                    ?>
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="p-3"><?php echo e($p->nama_lengkap ?? '-'); ?></td>
                        <td class="p-3"><?php echo e($p->jenis_kelamin ?? '-'); ?></td>
                        <td class="p-3"><?php echo e($usia); ?></td>
                        <td class="p-3"><?php echo e($p->pendidikan_terakhir ?? '-'); ?></td>
                        <td class="p-3"><?php echo e($trainingCount); ?> Pelatihan</td>
                        <td class="p-3"><?php echo e($workCount); ?> Pengalaman</td>
                        <td class="p-3"><?php echo e($p->domisili_kecamatan ?? '-'); ?></td>
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
                                             class="fixed z-[70] w-56 rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800"
                                             :style="style + (dropUp ? ';transform: translateY(-100%)' : '')">
                                            <button type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition"
                                                    @click="
                                                      window.dispatchEvent(new CustomEvent('close-dropdowns'));
                                                      window.dispatchEvent(new CustomEvent('pencaker-detail', { detail: { url: '<?php echo e(route('admin.pencaker.detail', $u->id)); ?>', ak1: '<?php echo e($app?->nomor_ak1 ?? ''); ?>' } }));
                                                    ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="p-6 text-center text-slate-400">Belum ada data pencaker disetujui.</td>
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
         @pencaker-detail.window="open=true; loading=true; html=''; ak1=($event.detail.ak1||''); fetch($event.detail.url, {headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.text()).then(t=>{ html=t; }).catch(()=>{ html='<div class=\'p-6 text-red-300\'>Gagal memuat detail.</div>'; }).finally(()=>{ loading=false; })">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
             @keydown.escape.window="open=false">
            <div @click.outside="open=false" class="bg-slate-900 w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden border border-slate-800">
                <div class="flex items-center justify-between px-6 py-3 border-b border-slate-800 sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-lg font-semibold text-slate-100">Detail Pencaker <span x-show="ak1" class="ml-2 text-sm font-normal text-slate-300">— AK/1: <span x-text="ak1"></span></span></h3>
                    <button class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700" @click="open=false">Tutup</button>
                </div>
                <div class="max-h-[85vh] overflow-y-auto">
                    <template x-if="loading"><div class="p-6 text-slate-300">Memuat...</div></template>
                    <div class="p-6" x-html="html"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (! $__env->hasRenderedOnce('c7482258-01cc-47b9-8aa8-279292c7a8b0')): $__env->markAsRenderedOnce('c7482258-01cc-47b9-8aa8-279292c7a8b0'); ?>
        <?php $__env->startPush('scripts'); ?>
            <script>
                window.dropdownMenu = function () {
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
</div>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/livewire/admin/jobseeker-table.blade.php ENDPATH**/ ?>