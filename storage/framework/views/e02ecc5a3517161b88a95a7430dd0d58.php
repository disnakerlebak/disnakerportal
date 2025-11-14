<?php $__env->startSection('title', 'Profil Pencaker'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $accordionMap = [
        'profile' => 1,
        'education' => 2,
        'training' => 3,
        'work' => 4,
        'preference' => 5,
    ];
    $currentAccordion = old('__accordion') ?? session('accordion') ?? 'profile';
    $openDefault = $accordionMap[$currentAccordion] ?? 1;
    $locked = $isLocked ?? false;
?>

<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100" x-data="{ open: <?php echo e($openDefault); ?> }">

    <h1 class="text-2xl font-semibold text-slate-100 mb-6">Profil Pencaker</h1>

    <!-- <?php if(session('success')): ?>
        <div class="mb-4 rounded-lg bg-green-600/20 border border-green-600 text-green-100 px-4 py-3">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 rounded-lg bg-red-600/20 border border-red-600 text-red-100 px-4 py-3">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3">
            <div class="font-semibold mb-1">Periksa data berikut:</div>
            <ul class="list-disc ms-5 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($message); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?> -->

    <div class="space-y-4">

        <!-- Data Diri -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 1 ? open = null : open = 1"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Data Diri</span>
                <svg :class="{'rotate-180': open === 1}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 1"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-slate-100">Data Diri Pencari Kerja</h2>
                    <?php if($locked): ?>
                        <button id="openEdit" disabled
                                title="Terkunci karena pengajuan AK1 sedang diproses/diterima"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-slate-700 cursor-not-allowed flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Terkunci</span>
                        </button>
                    <?php else: ?>
                        <button data-modal-open="modalProfileEdit"
                                class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                            Edit Profil
                        </button>
                    <?php endif; ?>
                </div>

                <?php if($locked): ?>
                    <div class="mb-4 rounded-lg bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                        </svg>
                        <span>Pengeditan data diri terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.</span>
                    </div>
                <?php endif; ?>

                <?php
                    $fields = [
                        'Nama Lengkap' => $profile->nama_lengkap ?? '-',
                        'NIK' => $profile->nik ?? '-',
                        'Tempat Lahir' => $profile->tempat_lahir ?? '-',
                        'Tanggal Lahir' => $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-',
                        'Jenis Kelamin' => $profile->jenis_kelamin ?? '-',
                        'Agama' => $profile->agama ?? '-',
                        'Status Perkawinan' => $profile->status_perkawinan ?? '-',
                        'Pendidikan Terakhir' => $profile->pendidikan_terakhir ?? '-',
                        'Alamat Lengkap' => $profile->alamat_lengkap ?? '-',
                        'Domisili Kecamatan' => $profile->domisili_kecamatan ?? '-',
                        'No. Telepon' => $profile->no_telepon ?? '-',
                    ];
                ?>

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <table class="w-full text-slate-100">
                        <tbody>
                            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-slate-800/60">
                                    <td class="py-2 px-4 font-semibold text-slate-400 w-1/3"><?php echo e($label); ?></td>
                                    <td class="py-2 px-4 text-slate-100">: <?php echo e($value); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pendidikan -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 2 ? open = null : open = 2"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Pendidikan</span>
                <svg :class="{'rotate-180': open === 2}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 2"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <?php if($locked): ?>
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pendidikan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                <?php endif; ?>

                <?php if($locked): ?>
                    <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                            class="px-4 py-2 bg-slate-700 text-white rounded-lg cursor-not-allowed">
                        + Tambah Pendidikan (Terkunci)
                    </button>
                <?php else: ?>
                    <button data-modal-open="modalEducationCreate"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        + Tambah Pendidikan
                    </button>
                <?php endif; ?>

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Tingkat</th>
                                <th class="border border-slate-700 p-2 text-left">Nama Sekolah / Institusi</th>
                                <th class="border border-slate-700 p-2 text-left">Jurusan</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            <?php $__empty_1 = true; $__currentLoopData = $educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2"><?php echo e($edu->tingkat); ?></td>
                                    <td class="border border-slate-800 p-2"><?php echo e($edu->nama_institusi); ?></td>
                                    <td class="border border-slate-800 p-2 text-center"><?php echo e($edu->jurusan ?: '-'); ?></td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php echo e($edu->tahun_mulai); ?> - <?php echo e($edu->tahun_selesai); ?>

                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php if($locked): ?>
                                            <span class="inline-flex items-center justify-center text-slate-400" title="Terkunci">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <button type="button" title="Edit"
                                                    data-modal-open="modalEducationEdit<?php echo e($edu->id); ?>"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                    data-delete-modal="modalEducationDelete"
                                                    data-action="<?php echo e(route('pencaker.education.destroy', $edu)); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada data pendidikan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pelatihan -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 3 ? open = null : open = 3"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Pelatihan</span>
                <svg :class="{'rotate-180': open === 3}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 3"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <?php if($locked): ?>
                    <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                        Perubahan data pelatihan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
                    </div>
                <?php endif; ?>

                <?php if($locked): ?>
                    <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                            class="px-4 py-2 bg-slate-700 text-white rounded-lg cursor-not-allowed">
                        + Tambah Pelatihan (Terkunci)
                    </button>
                <?php else: ?>
                    <button data-modal-open="modalTrainingCreate"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        + Tambah Pelatihan
                    </button>
                <?php endif; ?>

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Jenis Pelatihan</th>
                                <th class="border border-slate-700 p-2 text-left">Lembaga</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center">Sertifikat</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $train): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2"><?php echo e($train->jenis_pelatihan); ?></td>
                                    <td class="border border-slate-800 p-2"><?php echo e($train->lembaga_pelatihan); ?></td>
                                    <td class="border border-slate-800 p-2 text-center"><?php echo e($train->tahun); ?></td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php if($train->sertifikat_file): ?>
                                            <a href="<?php echo e(asset('storage/'.$train->sertifikat_file)); ?>"
                                               target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php if($locked): ?>
                                            <span class="inline-flex items-center justify-center text-slate-400" title="Terkunci">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V9a3 3 0 00-3 3v5.25A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75V12a3 3 0 00-3-3V6.75A5.25 5.25 0 0012 1.5zm3.75 7.5V6.75a3.75 3.75 0 10-7.5 0V9h7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <button type="button" title="Edit"
                                                    data-modal-open="modalTrainingEdit<?php echo e($train->id); ?>"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                    data-delete-modal="modalTrainingDelete"
                                                    data-action="<?php echo e(route('pencaker.training.destroy', $train)); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada data pelatihan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Kerja -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 4 ? open = null : open = 4"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Riwayat Kerja</span>
                <svg :class="{'rotate-180': open === 4}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 4"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4">
                <button data-modal-open="modalWorkCreate"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah Riwayat Kerja
                </button>

                <div class="bg-slate-950/40 rounded-xl p-4 shadow-inner">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-slate-800 rounded-lg overflow-hidden">
                            <thead class="bg-slate-800 text-slate-100">
                            <tr>
                                <th class="border border-slate-700 p-2 text-left">Perusahaan</th>
                                <th class="border border-slate-700 p-2 text-left">Jabatan</th>
                                <th class="border border-slate-700 p-2 text-center">Tahun</th>
                                <th class="border border-slate-700 p-2 text-center">Surat Pengalaman</th>
                                <th class="border border-slate-700 p-2 text-center w-32">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            <?php $__empty_1 = true; $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-t border-slate-800">
                                    <td class="border border-slate-800 p-2"><?php echo e($work->nama_perusahaan); ?></td>
                                    <td class="border border-slate-800 p-2"><?php echo e($work->jabatan); ?></td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php echo e($work->tahun_mulai); ?> - <?php echo e($work->tahun_selesai ?? 'Sekarang'); ?>

                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <?php if($work->surat_pengalaman): ?>
                                            <a href="<?php echo e(asset('storage/'.$work->surat_pengalaman)); ?>" target="_blank"
                                               class="text-blue-400 hover:underline">
                                                Lihat
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border border-slate-800 p-2 text-center">
                                        <button type="button" title="Edit"
                                                data-modal-open="modalWorkEdit<?php echo e($work->id); ?>"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-yellow-400 hover:bg-slate-700 mr-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <button type="button" title="Hapus"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-800 text-red-500 hover:bg-slate-700"
                                                data-delete-modal="modalWorkDelete"
                                                data-action="<?php echo e(route('pencaker.work.destroy', $work)); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M9 3.75A1.5 1.5 0 0110.5 2.25h3A1.5 1.5 0 0115 3.75V4.5h4.5a.75.75 0 010 1.5H4.5a.75.75 0 010-1.5H9V3.75zM6.75 7.5A.75.75 0 017.5 6.75h9a.75.75 0 01.75.75v10.5A3.75 3.75 0 0113.5 21.75h-3A3.75 3.75 0 016.75 18V7.5z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-slate-400">
                                        Belum ada riwayat kerja.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minat Kerja -->
        <div class="bg-slate-900 rounded-xl shadow">
            <button @click="open === 5 ? open = null : open = 5"
                    class="w-full text-left px-5 py-4 font-semibold text-slate-300 flex justify-between items-center">
                <span>Referensi & Minat Kerja</span>
                <svg :class="{'rotate-180': open === 5}"
                     class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open === 5"
                 x-collapse
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="px-6 pt-6 pb-8 space-y-4 text-slate-300">
                <button data-modal-open="modalPreferenceForm"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Tambah / Ubah Minat Kerja
                </button>

                <div class="bg-slate-950/40 rounded-xl p-6 shadow-inner">
                    <h3 class="text-lg font-semibold mb-4">Data Minat Kerja</h3>
                    <?php if($preference): ?>
                        <div class="space-y-3">
                            <p><strong>Minat Lokasi:</strong>
                                <?php echo e(implode(', ', $preference->minat_lokasi ?? []) ?: '-'); ?></p>
                            <p><strong>Bidang Usaha:</strong>
                                <?php echo e(implode(', ', $preference->minat_bidang ?? []) ?: '-'); ?></p>
                            <p><strong>Gaji Harapan:</strong>
                                <?php echo e($preference->gaji_harapan ?: '-'); ?></p>
                            <p><strong>Deskripsi Diri:</strong>
                                <?php echo e($preference->deskripsi_diri ?: '-'); ?></p>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-400 italic">Belum ada data minat kerja yang diisi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalProfileEdit','title' => 'Edit Data Diri','action' => ''.e(route('pencaker.profile.update')).'','method' => 'PUT','submitLabel' => 'Simpan','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalProfileEdit','title' => 'Edit Data Diri','action' => ''.e(route('pencaker.profile.update')).'','method' => 'PUT','submitLabel' => 'Simpan','cancelLabel' => 'Batal']); ?>
    <input type="hidden" name="__accordion" value="profile">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm text-slate-400">Nama Lengkap</label>
            <input type="text" name="nama_lengkap"
                   value="<?php echo e(old('nama_lengkap', $profile->nama_lengkap ?? '')); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        <div>
            <label class="block text-sm text-slate-400">NIK</label>
            <input type="text" name="nik"
                   value="<?php echo e(old('nik', $profile->nik ?? '')); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500"
                   required>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Tempat Lahir</label>
            <input type="text" name="tempat_lahir"
                   value="<?php echo e(old('tempat_lahir', $profile->tempat_lahir ?? '')); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm text-slate-400">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir"
                   value="<?php echo e(old('tanggal_lahir', $profile->tanggal_lahir ?? '')); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm text-slate-400">Jenis Kelamin</label>
            <select name="jenis_kelamin"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih</option>
                <option value="Laki-laki" <?php if(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki'): echo 'selected'; endif; ?>>Laki-laki</option>
                <option value="Perempuan" <?php if(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan'): echo 'selected'; endif; ?>>Perempuan</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Agama</label>
            <select name="agama"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih</option>
                <?php $__currentLoopData = ['Islam','Kristen','Katolik','Hindu','Budha','Konghucu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($agama); ?>" <?php if(old('agama', $profile->agama ?? '') == $agama): echo 'selected'; endif; ?>><?php echo e($agama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Status Perkawinan</label>
            <select name="status_perkawinan"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih</option>
                <?php $__currentLoopData = ['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>" <?php if(old('status_perkawinan', $profile->status_perkawinan ?? '') == $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Pendidikan Terakhir</label>
            <select name="pendidikan_terakhir"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <?php ($listPendidikan = ['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3']); ?>
                <option value="">Pilih</option>
                <?php $__currentLoopData = $listPendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pd); ?>" <?php if(old('pendidikan_terakhir', $profile->pendidikan_terakhir ?? '') === $pd): echo 'selected'; endif; ?>><?php echo e($pd); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Kecamatan Domisili</label>
            <select name="domisili_kecamatan"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih Kecamatan</option>
                <?php $__currentLoopData = $kecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kec); ?>" <?php if(old('domisili_kecamatan', $profile->domisili_kecamatan ?? '') == $kec): echo 'selected'; endif; ?>><?php echo e($kec); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">No. Telepon</label>
            <input type="text" name="no_telepon"
                   value="<?php echo e(old('no_telepon', $profile->no_telepon ?? '')); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm text-slate-400">Alamat Lengkap</label>
            <textarea name="alamat_lengkap" rows="2"
                      class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500"><?php echo e(old('alamat_lengkap', $profile->alamat_lengkap ?? '')); ?></textarea>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>



<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalEducationCreate','title' => 'Tambah Riwayat Pendidikan','action' => ''.e(route('pencaker.education.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalEducationCreate','title' => 'Tambah Riwayat Pendidikan','action' => ''.e(route('pencaker.education.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']); ?>
    <input type="hidden" name="__accordion" value="education">
    <div>
        <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
        <select name="tingkat"
                class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500" required>
            <option value="">- Pilih -</option>
            <?php $__currentLoopData = ['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tingkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($tingkat); ?>"><?php echo e($tingkat); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div>
        <label class="block text-sm text-slate-400">Nama Institusi / Sekolah</label>
        <input type="text" name="nama_institusi"
               class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm text-slate-400">Jurusan</label>
        <input type="text" name="jurusan"
               class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm text-slate-400">Tahun Mulai</label>
            <input type="number" name="tahun_mulai" placeholder="contoh: 2018"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm text-slate-400">Tahun Selesai</label>
            <input type="number" name="tahun_selesai" placeholder="contoh: 2022"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php $__currentLoopData = $educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalEducationEdit'.e($edu->id).'','title' => 'Edit Riwayat Pendidikan','action' => ''.e(route('pencaker.education.update', $edu->id)).'','method' => 'POST','submitLabel' => 'Update','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalEducationEdit'.e($edu->id).'','title' => 'Edit Riwayat Pendidikan','action' => ''.e(route('pencaker.education.update', $edu->id)).'','method' => 'POST','submitLabel' => 'Update','cancelLabel' => 'Batal']); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="__accordion" value="education">

        <div>
            <label class="block text-sm text-slate-400">Tingkat Pendidikan</label>
            <select name="tingkat"
                    class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 focus:ring-2 focus:ring-indigo-500" required>
                <?php $__currentLoopData = ['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tingkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tingkat); ?>" <?php if($edu->tingkat == $tingkat): echo 'selected'; endif; ?>><?php echo e($tingkat); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Nama Institusi / Sekolah</label>
            <input type="text" name="nama_institusi" value="<?php echo e(old('nama_institusi', $edu->nama_institusi)); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500" required>
        </div>

        <div>
            <label class="block text-sm text-slate-400">Jurusan</label>
            <input type="text" name="jurusan" value="<?php echo e(old('jurusan', $edu->jurusan)); ?>"
                   class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-slate-400">Tahun Mulai</label>
                <input type="number" name="tahun_mulai" value="<?php echo e(old('tahun_mulai', $edu->tahun_mulai)); ?>"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Tahun Selesai</label>
                <input type="number" name="tahun_selesai" value="<?php echo e(old('tahun_selesai', $edu->tahun_selesai)); ?>"
                       class="mt-1 w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500" required>
            </div>
        </div>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalEducationDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalEducationDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']); ?>
    <?php echo method_field('DELETE'); ?>
    <input type="hidden" name="__accordion" value="education">
    <p class="text-slate-400">Apakah Anda yakin ingin menghapus data pendidikan ini?</p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalTrainingCreate','title' => 'Tambah Riwayat Pelatihan','action' => ''.e(route('pencaker.training.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalTrainingCreate','title' => 'Tambah Riwayat Pelatihan','action' => ''.e(route('pencaker.training.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']); ?>
    <input type="hidden" name="__accordion" value="training">
    <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Jenis Pelatihan','name' => 'jenis_pelatihan','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jenis Pelatihan','name' => 'jenis_pelatihan','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Lembaga Pelatihan','name' => 'lembaga_pelatihan','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Lembaga Pelatihan','name' => 'lembaga_pelatihan','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun','name' => 'tahun','type' => 'number','placeholder' => 'contoh: 2024','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun','name' => 'tahun','type' => 'number','placeholder' => 'contoh: 2024','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginale632e4e6446a156ddec0f062c71453c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale632e4e6446a156ddec0f062c71453c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-file','data' => ['label' => 'Upload Sertifikat (PDF/JPG/PNG)','name' => 'sertifikat_file','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Upload Sertifikat (PDF/JPG/PNG)','name' => 'sertifikat_file','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $attributes = $__attributesOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__attributesOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $component = $__componentOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__componentOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $train): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalTrainingEdit'.e($train->id).'','title' => 'Edit Riwayat Pelatihan','action' => ''.e(route('pencaker.training.update', $train->id)).'','method' => 'POST','submitLabel' => 'Perbarui','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalTrainingEdit'.e($train->id).'','title' => 'Edit Riwayat Pelatihan','action' => ''.e(route('pencaker.training.update', $train->id)).'','method' => 'POST','submitLabel' => 'Perbarui','cancelLabel' => 'Batal']); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="__accordion" value="training">
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Jenis Pelatihan','name' => 'jenis_pelatihan','value' => $train->jenis_pelatihan,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jenis Pelatihan','name' => 'jenis_pelatihan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($train->jenis_pelatihan),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Lembaga Pelatihan','name' => 'lembaga_pelatihan','value' => $train->lembaga_pelatihan,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Lembaga Pelatihan','name' => 'lembaga_pelatihan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($train->lembaga_pelatihan),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun','name' => 'tahun','type' => 'number','value' => $train->tahun,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun','name' => 'tahun','type' => 'number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($train->tahun),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginale632e4e6446a156ddec0f062c71453c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale632e4e6446a156ddec0f062c71453c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-file','data' => ['label' => 'Upload Sertifikat Baru (opsional)','name' => 'sertifikat_file']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Upload Sertifikat Baru (opsional)','name' => 'sertifikat_file']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $attributes = $__attributesOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__attributesOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $component = $__componentOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__componentOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalTrainingDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalTrainingDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']); ?>
    <?php echo method_field('DELETE'); ?>
    <input type="hidden" name="__accordion" value="training">
    <p class="text-slate-400">Apakah Anda yakin ingin menghapus data pelatihan ini?</p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalWorkCreate','title' => 'Tambah Riwayat Kerja','action' => ''.e(route('pencaker.work.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalWorkCreate','title' => 'Tambah Riwayat Kerja','action' => ''.e(route('pencaker.work.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']); ?>
    <input type="hidden" name="__accordion" value="work">
    <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Nama Perusahaan','name' => 'nama_perusahaan','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Perusahaan','name' => 'nama_perusahaan','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Jabatan','name' => 'jabatan','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jabatan','name' => 'jabatan','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    <div class="grid grid-cols-2 gap-3">
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun Mulai','name' => 'tahun_mulai','type' => 'number','placeholder' => '2020','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun Mulai','name' => 'tahun_mulai','type' => 'number','placeholder' => '2020','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun Selesai','name' => 'tahun_selesai','type' => 'number','placeholder' => '2024']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun Selesai','name' => 'tahun_selesai','type' => 'number','placeholder' => '2024']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
    </div>
    <?php if (isset($component)) { $__componentOriginale632e4e6446a156ddec0f062c71453c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale632e4e6446a156ddec0f062c71453c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-file','data' => ['label' => 'Upload Surat Pengalaman (Opsional)','name' => 'surat_pengalaman']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Upload Surat Pengalaman (Opsional)','name' => 'surat_pengalaman']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $attributes = $__attributesOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__attributesOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $component = $__componentOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__componentOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalWorkEdit'.e($work->id).'','title' => 'Edit Riwayat Kerja','action' => ''.e(route('pencaker.work.update', $work->id)).'','method' => 'POST','submitLabel' => 'Perbarui','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalWorkEdit'.e($work->id).'','title' => 'Edit Riwayat Kerja','action' => ''.e(route('pencaker.work.update', $work->id)).'','method' => 'POST','submitLabel' => 'Perbarui','cancelLabel' => 'Batal']); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="__accordion" value="work">
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Nama Perusahaan','name' => 'nama_perusahaan','value' => $work->nama_perusahaan,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Perusahaan','name' => 'nama_perusahaan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($work->nama_perusahaan),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Jabatan','name' => 'jabatan','value' => $work->jabatan,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jabatan','name' => 'jabatan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($work->jabatan),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        <div class="grid grid-cols-2 gap-3">
            <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun Mulai','name' => 'tahun_mulai','type' => 'number','value' => $work->tahun_mulai,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun Mulai','name' => 'tahun_mulai','type' => 'number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($work->tahun_mulai),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Tahun Selesai','name' => 'tahun_selesai','type' => 'number','value' => $work->tahun_selesai]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun Selesai','name' => 'tahun_selesai','type' => 'number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($work->tahun_selesai)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
        </div>
        <?php if (isset($component)) { $__componentOriginale632e4e6446a156ddec0f062c71453c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale632e4e6446a156ddec0f062c71453c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-file','data' => ['label' => 'Upload Surat Pengalaman Baru (Opsional)','name' => 'surat_pengalaman']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Upload Surat Pengalaman Baru (Opsional)','name' => 'surat_pengalaman']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $attributes = $__attributesOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__attributesOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale632e4e6446a156ddec0f062c71453c0)): ?>
<?php $component = $__componentOriginale632e4e6446a156ddec0f062c71453c0; ?>
<?php unset($__componentOriginale632e4e6446a156ddec0f062c71453c0); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalWorkDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalWorkDelete','title' => 'Konfirmasi Hapus','action' => '','method' => 'POST','submitLabel' => 'Ya, Hapus','cancelLabel' => 'Batal']); ?>
    <?php echo method_field('DELETE'); ?>
    <input type="hidden" name="__accordion" value="work">
    <p class="text-slate-400">Apakah Anda yakin ingin menghapus data riwayat kerja ini?</p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>


<?php if (isset($component)) { $__componentOriginalf008eb3693fba0afad2cb07bb34bba19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-form','data' => ['id' => 'modalPreferenceForm','title' => 'Isi Minat Kerja','action' => ''.e(route('pencaker.preferences.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalPreferenceForm','title' => 'Isi Minat Kerja','action' => ''.e(route('pencaker.preferences.store')).'','method' => 'POST','submitLabel' => 'Simpan','cancelLabel' => 'Batal']); ?>
    <input type="hidden" name="__accordion" value="preference">

    <div class="space-y-4">
        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Minat Lokasi Kerja (boleh lebih dari satu)
            </label>
            <div class="flex flex-wrap gap-3">
                <?php $__currentLoopData = ['Kabupaten Lebak', 'Luar Kabupaten Lebak', 'Luar Negeri']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_lokasi[]" value="<?php echo e($lokasi); ?>"
                               class="rounded border-slate-600 dark:border-slate-700"
                               <?php echo e(in_array($lokasi, old('minat_lokasi', $preference->minat_lokasi ?? [])) ? 'checked' : ''); ?>>
                        <span><?php echo e($lokasi); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Minat Bidang Usaha (boleh lebih dari satu)
            </label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                <?php $__currentLoopData = ['IT','Jasa','Pertambangan','Kelautan','Pertanian','Pendidikan','Kesehatan','Konstruksi','Transportasi','Administrasi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bidang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="minat_bidang[]" value="<?php echo e($bidang); ?>"
                               class="rounded border-slate-600 dark:border-slate-700"
                               <?php echo e(in_array($bidang, old('minat_bidang', $preference->minat_bidang ?? [])) ? 'checked' : ''); ?>>
                        <span><?php echo e($bidang); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginal262894a2c291df91ae9f7b925bf8a923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262894a2c291df91ae9f7b925bf8a923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-text','data' => ['label' => 'Gaji yang Diharapkan (contoh: 3–5 juta)','name' => 'gaji_harapan','value' => old('gaji_harapan', $preference->gaji_harapan ?? '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Gaji yang Diharapkan (contoh: 3–5 juta)','name' => 'gaji_harapan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('gaji_harapan', $preference->gaji_harapan ?? ''))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $attributes = $__attributesOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__attributesOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262894a2c291df91ae9f7b925bf8a923)): ?>
<?php $component = $__componentOriginal262894a2c291df91ae9f7b925bf8a923; ?>
<?php unset($__componentOriginal262894a2c291df91ae9f7b925bf8a923); ?>
<?php endif; ?>

        <div>
            <label class="block text-sm text-slate-400 dark:text-slate-400 mb-1">
                Deskripsi Singkat Tentang Diri Anda
            </label>
            <textarea name="deskripsi_diri" rows="4"
                      class="w-full rounded-lg border-slate-700 bg-slate-900 text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500"><?php echo e(old('deskripsi_diri', $preference->deskripsi_diri ?? '')); ?></textarea>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $attributes = $__attributesOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__attributesOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19)): ?>
<?php $component = $__componentOriginalf008eb3693fba0afad2cb07bb34bba19; ?>
<?php unset($__componentOriginalf008eb3693fba0afad2cb07bb34bba19); ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const registerDeleteModal = (selector) => {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const modalId = button.getAttribute('data-delete-modal');
                const action = button.getAttribute('data-action');
                const modal = document.getElementById(modalId);
                if (!modal) return;

                const form = modal.querySelector('form');
                if (form && action) {
                    form.setAttribute('action', action);
                }

                // Buka modal via komponen (tanpa duplikasi logika open)
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        });
    };

    registerDeleteModal('[data-delete-modal="modalEducationDelete"]');
    registerDeleteModal('[data-delete-modal="modalTrainingDelete"]');
    registerDeleteModal('[data-delete-modal="modalWorkDelete"]');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pencaker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/pencaker/profile.blade.php ENDPATH**/ ?>