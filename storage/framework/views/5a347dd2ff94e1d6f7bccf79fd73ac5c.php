<div class="space-y-6 text-gray-200">
  <div class="grid md:grid-cols-[240px,1fr] gap-6">
    <div>
      <div class="text-sm text-gray-400 mb-2">Foto Close-Up</div>
      <?php
        $fotoUrl = isset($fotoPath) && $fotoPath ? asset('storage/'.$fotoPath) : asset('images/placeholder-avatar.png');
      ?>
      <img src="<?php echo e($fotoUrl); ?>" alt="Foto Close-Up" class="w-56 h-64 object-cover rounded border border-gray-700" />
    </div>
    <div>
      <h3 class="text-lg font-semibold mb-3">Data Diri</h3>
      <div class="grid md:grid-cols-2 gap-y-2 text-sm">
        <div><span class="text-gray-400 w-40 inline-block">Nama</span>: <?php echo e($profile->nama_lengkap ?? $user->name); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">NIK</span>: <?php echo e($profile->nik ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Tempat Lahir</span>: <?php echo e($profile->tempat_lahir ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Tanggal Lahir</span>: <?php echo e(isset($profile->tanggal_lahir) ? indoDateOnly($profile->tanggal_lahir) : '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Jenis Kelamin</span>: <?php echo e($profile->jenis_kelamin ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Agama</span>: <?php echo e($profile->agama ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Kecamatan</span>: <?php echo e($profile->kecamatan ?? $profile->domisili_kecamatan ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">No. HP</span>: <?php echo e($profile->no_hp ?? $profile->no_telepon ?? '-'); ?></div>
        <div><span class="text-gray-400 w-40 inline-block">Email</span>: <?php echo e($user->email); ?></div>
        <div class="md:col-span-2"><span class="text-gray-400 w-40 inline-block">Alamat</span>: <?php echo e($profile->alamat_lengkap ?? '-'); ?></div>
      </div>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pendidikan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Tingkat</th>
            <th class="px-3 py-2 text-left">Institusi</th>
            <th class="px-3 py-2 text-left">Jurusan</th>
            <th class="px-3 py-2 text-left">Tahun</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        <?php $__empty_1 = true; $__currentLoopData = $educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="px-3 py-2"><?php echo e($e->tingkat); ?></td>
            <td class="px-3 py-2"><?php echo e($e->nama_institusi); ?></td>
            <td class="px-3 py-2"><?php echo e($e->jurusan); ?></td>
            <td class="px-3 py-2"><?php echo e($e->tahun_mulai); ?> - <?php echo e($e->tahun_selesai ?? '-'); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pelatihan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Jenis</th>
            <th class="px-3 py-2 text-left">Lembaga</th>
            <th class="px-3 py-2 text-left">Tahun</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="px-3 py-2"><?php echo e($t->jenis_pelatihan); ?></td>
            <td class="px-3 py-2"><?php echo e($t->lembaga_pelatihan); ?></td>
            <td class="px-3 py-2"><?php echo e($t->tahun); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Riwayat Pekerjaan</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-800 text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Perusahaan</th>
            <th class="px-3 py-2 text-left">Jabatan</th>
            <th class="px-3 py-2 text-left">Tahun</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        <?php $__empty_1 = true; $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="px-3 py-2"><?php echo e($w->nama_perusahaan); ?></td>
            <td class="px-3 py-2"><?php echo e($w->jabatan); ?></td>
            <td class="px-3 py-2"><?php echo e($w->tahun_mulai); ?> - <?php echo e($w->tahun_selesai ?? 'Sekarang'); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400">Belum ada data</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h3 class="text-lg font-semibold mb-2">Minat Bekerja</h3>
    <?php if($preference): ?>
      <div class="text-sm">
        <div><span class="text-gray-400">Lokasi</span>: <?php echo e(is_array($preference->minat_lokasi) ? implode(', ', $preference->minat_lokasi) : ($preference->minat_lokasi ?? '-')); ?></div>
        <div><span class="text-gray-400">Bidang</span>: <?php echo e(is_array($preference->minat_bidang) ? implode(', ', $preference->minat_bidang) : ($preference->minat_bidang ?? '-')); ?></div>
        <div><span class="text-gray-400">Gaji Harapan</span>: <?php echo e($preference->gaji_harapan ?? '-'); ?></div>
        <div><span class="text-gray-400">Deskripsi Diri</span>: <?php echo e($preference->deskripsi_diri ?? '-'); ?></div>
      </div>
    <?php else: ?>
      <div class="text-gray-400">Belum ada data</div>
    <?php endif; ?>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/admin/pencaker/partials/detail.blade.php ENDPATH**/ ?>