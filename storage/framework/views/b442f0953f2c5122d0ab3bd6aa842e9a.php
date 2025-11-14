<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard-stats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-121666824-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>


<?php if(false): ?>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-8 text-slate-100">
            
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
                    <p class="text-slate-400 text-xs">Antrian Verifikasi</p>
                    <div class="text-3xl font-bold text-indigo-400 mt-1"><?php echo e($pending ?? 0); ?></div>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
                    <p class="text-slate-400 text-xs">AK1 Aktif</p>
                    <div class="text-3xl font-bold text-emerald-400 mt-1"><?php echo e($approvedActive ?? 0); ?></div>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
                    <p class="text-slate-400 text-xs">Ditolak Bulan Ini</p>
                    <div class="text-3xl font-bold text-rose-400 mt-1"><?php echo e($rejectedThisMonth ?? 0); ?></div>
                </div>
                
            </div>

            
            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <p class="text-slate-400 text-sm">Total Pencaker</p>
                    <div class="text-3xl font-bold"><?php echo e($totalPencaker ?? 0); ?></div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                        <div class="rounded border border-slate-800 p-3">
                            <div class="text-slate-400">Lengkap Profil</div>
                            <div class="font-semibold text-emerald-400"><?php echo e($lengkapProfil ?? 0); ?></div>
                        </div>
                        <div class="rounded border border-slate-800 p-3">
                            <div class="text-slate-400">Belum Lengkap</div>
                            <div class="font-semibold text-yellow-400"><?php echo e($belumLengkap ?? 0); ?></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <p class="text-slate-400 text-sm">Pencaker Aktif (punya AK1)</p>
                    <div class="text-3xl font-bold text-indigo-300"><?php echo e($activeSeekers ?? 0); ?></div>
                    <div class="mt-3 space-y-2 text-sm">
                        <?php
                            $activeSeekers = max(1, (int)($activeSeekers ?? 0));
                            $pctTrain = round(100 * (int)($withTraining ?? 0) / $activeSeekers);
                            $pctWork  = round(100 * (int)($withWork ?? 0) / $activeSeekers);
                        ?>
                        <div class="flex items-center justify-between"><span class="text-slate-400">Dengan Pelatihan</span><span class="font-medium"><?php echo e($withTraining ?? 0); ?> (<?php echo e($pctTrain); ?>%)</span></div>
                        <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-emerald-500" style="width: <?php echo e($pctTrain); ?>%"></div></div>
                        <div class="flex items-center justify-between mt-2"><span class="text-slate-400">Dengan Pengalaman</span><span class="font-medium"><?php echo e($withWork ?? 0); ?> (<?php echo e($pctWork); ?>%)</span></div>
                        <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-sky-500" style="width: <?php echo e($pctWork); ?>%"></div></div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <p class="text-slate-400 text-sm mb-3">Komposisi Jenis Kelamin Pencaker Terdaftar</p>
                    <div class="h-60">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>

            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <h3 class="text-lg font-semibold mb-3">Tingkat Pendidikan (Disetujui)</h3>
                    <div class="h-80">
                        <canvas id="chartEdu"></canvas>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <h3 class="text-lg font-semibold mb-3">Sebaran Kecamatan (Disetujui)</h3>
                    <div class="h-96">
                        <canvas id="chartDistrict"></canvas>
                    </div>
                </div>
            </div>

            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <h3 class="text-lg font-semibold mb-3">Tipe Pengajuan (bulan ini)</h3>
                    <?php
                        $totalType = collect($typeDist ?? [])->sum();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = ($typeDist ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $pct = $totalType ? round(100 * $val / $totalType) : 0; ?>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm"><span class="text-slate-300"><?php echo e(ucfirst($label ?: 'tidak diketahui')); ?></span><span class="font-medium"><?php echo e($val); ?> (<?php echo e($pct); ?>%)</span></div>
                            <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-indigo-500" style="width: <?php echo e($pct); ?>%"></div></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-slate-400">Tidak ada data bulan ini</div>
                    <?php endif; ?>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <h3 class="text-lg font-semibold mb-3">Outcome Status (bulan ini)</h3>
                    <?php $totalStatus = collect($statusDist ?? [])->sum(); ?>
                    <?php $__empty_1 = true; $__currentLoopData = ($statusDist ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $pct = $totalStatus ? round(100 * $val / $totalStatus) : 0; ?>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm"><span class="text-slate-300"><?php echo e($label); ?></span><span class="font-medium"><?php echo e($val); ?> (<?php echo e($pct); ?>%)</span></div>
                            <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-emerald-500" style="width: <?php echo e($pct); ?>%"></div></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-slate-400">Tidak ada data bulan ini</div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <h3 class="text-lg font-semibold mb-3">Rekap Disetujui per Bulan (12 Bulan)</h3>
                    <div class="h-80">
                        <canvas id="chartMonthly"></canvas>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
                        <a href="<?php echo e(route('admin.ak1.index')); ?>" class="text-blue-400 text-sm hover:underline">Buka Verifikasi</a>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-800 text-slate-300">
                                <tr>
                                    <th class="p-2 text-left">Waktu</th>
                                    <th class="p-2 text-left">Aktor</th>
                                    <th class="p-2 text-left">Aksi</th>
                                    <th class="p-2 text-left">Ke Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                <?php $__empty_1 = true; $__currentLoopData = ($recentLogs ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-800/40">
                                        <td class="p-2"><?php echo e($log->created_at?->format('d M H:i')); ?></td>
                                        <td class="p-2"><?php echo e($log->actor?->name ?? '-'); ?></td>
                                        <td class="p-2"><?php echo e(ucfirst($log->action ?? '-')); ?></td>
                                        <td class="p-2"><?php echo e($log->to_status ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="p-3 text-slate-400">Belum ada aktivitas</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-400">Data diperbarui: <?php echo e(($lastUpdated ?? now())->format('d M Y H:i')); ?> WIB</p>

            
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-100">Pencari Kerja Terbaru</h3>
                    <a href="<?php echo e(route('admin.pencaker.index')); ?>" class="text-blue-400 text-sm hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-lg border border-slate-800 text-slate-200">
                        <thead class="bg-slate-800 text-slate-200">
                            <tr>
                                <th class="py-2 px-3 text-left">Nama</th>
                                <th class="py-2 px-3 text-left">Email</th>
                                <th class="py-2 px-3 text-left">Tanggal Daftar</th>
                                <th class="py-2 px-3 text-left">Status Profil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-800/50 transition">
                                    <td class="py-2 px-3"><?php echo e($p->name); ?></td>
                                    <td class="py-2 px-3"><?php echo e($p->email); ?></td>
                                    <td class="py-2 px-3"><?php echo e($p->created_at->format('d M Y')); ?></td>
                                    <td class="py-2 px-3">
                                        <?php if($p->jobseekerProfile?->nik): ?>
                                            <span class="text-green-400 font-medium">Lengkap</span>
                                        <?php else: ?>
                                            <span class="text-yellow-400 font-medium">Belum Lengkap</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-slate-400">Belum ada data pencaker</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
      // Paksa warna yang kontras agar terbaca di tema gelap
      const textColor = '#e2e8f0';
      const gridColor = 'rgba(148,163,184,0.25)';
      const tooltipBg = '#0b1220';
      const borderCol = 'rgba(148,163,184,0.3)';

      // Set default colors so semua elemen (legend, ticks, tooltip) konsisten
      Chart.defaults.color = textColor;
      Chart.defaults.borderColor = gridColor;

      // Data dari server
      const genderLabels = Object.keys(<?php echo json_encode($genderApproved ?? (object)[], 15, 512) ?>);
      const genderData = Object.values(<?php echo json_encode($genderApproved ?? (object)[], 15, 512) ?>);
      const genderColors = ['#3b82f6','#ef4444','#64748b','#10b981','#f59e0b'];

      const eduLabels = (<?php echo json_encode(($educationApproved ?? collect())->pluck('label'), 15, 512) ?> || []);
      const eduData   = (<?php echo json_encode(($educationApproved ?? collect())->pluck('total'), 15, 512) ?> || []);

      const distLabels = (<?php echo json_encode(($districtApproved ?? collect())->pluck('label'), 15, 512) ?> || []);
      const distData   = (<?php echo json_encode(($districtApproved ?? collect())->pluck('total'), 15, 512) ?> || []);

      const monthlyLabels = (<?php echo json_encode($monthlyLabels ?? [], 15, 512) ?> || []);
      const monthlyCounts = (<?php echo json_encode($monthlyCounts ?? [], 15, 512) ?> || []);

      const commonOpts = {
        responsive: true,
        animation: { duration: 800, easing: 'easeOutQuart' },
        plugins: {
          legend: {
            position: 'top',
            labels: { color: textColor, boxWidth: 12, usePointStyle: true, pointStyle: 'rectRounded' }
          },
          tooltip: {
            backgroundColor: tooltipBg,
            titleColor: textColor,
            bodyColor: textColor,
            borderColor: borderCol,
            borderWidth: 1
          }
        },
      };

      // Gender doughnut (legend berisi jumlah + persen dengan font kecil)
      const genderTotal = (genderData || []).reduce((a,b)=>a + (Number(b)||0), 0);
      const genderLegendLabels = (genderLabels || []).map((lbl,i)=>{
        const v = Number(genderData[i]||0);
        const pct = genderTotal ? Math.round((v / genderTotal) * 100) : 0;
        return `${lbl} — ${v} (${pct}%)`;
      });

      new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: { labels: genderLegendLabels, datasets: [{ data: genderData, backgroundColor: genderColors }] },
        options: { 
          ...commonOpts,
          maintainAspectRatio: false,
          cutout: '60%',
          plugins: {
            ...commonOpts.plugins,
            legend: {
              position: 'bottom',
              labels: { color: textColor, boxWidth: 10, usePointStyle: true, pointStyle: 'circle', padding: 10, font: { size: 11 } }
            },
            tooltip: {
              backgroundColor: tooltipBg,
              titleColor: textColor,
              bodyColor: textColor,
              displayColors: false,
              callbacks: {
                title: (items) => {
                  const i = items?.[0]?.dataIndex ?? 0;
                  const val = Number(genderData[i] || 0);
                  const pct = genderTotal ? Math.round((val / genderTotal) * 100) : 0;
                  return `${genderLabels[i]} — ${val} (${pct}%)`;
                },
                // Hapus seluruh body agar tidak muncul angka default
                label: () => '',
                beforeLabel: () => '',
                afterLabel: () => '',
                footer: () => '',
                beforeFooter: () => '',
                afterFooter: () => '',
              }
            }
          }
        }
      });

      // Education horizontal bar
      new Chart(document.getElementById('chartEdu'), {
        type: 'bar',
        data: { labels: eduLabels, datasets: [{ label: 'Jumlah', data: eduData, backgroundColor: '#6366f1' }] },
        options: { ...commonOpts, indexAxis: 'y',
          scales: {
            x: { grid: { color: gridColor }, ticks:{ color: textColor, font: { size: 12 } } },
            y: { grid:{ color: gridColor }, ticks:{ color: textColor, font: { size: 12 } } }
          }
        }
      });

      // District horizontal bar
      new Chart(document.getElementById('chartDistrict'), {
        type: 'bar',
        data: { labels: distLabels, datasets: [{ label: 'Jumlah', data: distData, backgroundColor: '#10b981' }] },
        options: { ...commonOpts, indexAxis: 'y',
          scales: {
            x: { grid: { color: gridColor }, ticks:{ color: textColor, font: { size: 12 } } },
            y: { grid:{ color: gridColor }, ticks:{ color: textColor, font: { size: 12 } } }
          }
        }
      });

      // Monthly vertical bar
      new Chart(document.getElementById('chartMonthly'), {
        type: 'bar',
        data: { labels: monthlyLabels, datasets: [{ label: 'Disetujui', data: monthlyCounts, backgroundColor: '#3b82f6' }] },
        options: { ...commonOpts,
          scales: {
            x: { grid: { color: gridColor }, ticks: { color: textColor, maxRotation: 45, minRotation: 35, font: { size: 12 } } },
            y: { grid: { color: gridColor }, ticks: { color: textColor, precision: 0, font: { size: 12 } } }
          }
        }
      });
    </script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>