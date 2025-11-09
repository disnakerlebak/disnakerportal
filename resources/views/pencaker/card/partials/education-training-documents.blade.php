{{-- Pendidikan --}}
<div class="rounded-2xl bg-slate-900 shadow-lg">
    <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Riwayat Pendidikan</h3>
            <div class="flex gap-2">
                <button type="button" data-modal-open="modalRepairEducationCreate" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Tambah</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm md:text-base border-collapse text-slate-300">
                <thead class="bg-slate-800 text-slate-200">
                    <tr>
                        <th class="p-3 text-left">Tingkat</th>
                        <th class="p-3 text-left">Lembaga / Sekolah</th>
                        <th class="p-3 text-left">Jurusan</th>
                        <th class="p-3 text-left">Tahun</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($educations as $edu)
                        <tr class="border-b border-slate-800">
                            <td class="p-3">{{ $edu->tingkat }}</td>
                            <td class="p-3">{{ $edu->nama_institusi }}</td>
                            <td class="p-3">{{ $edu->jurusan ?: '-' }}</td>
                            <td class="p-3">{{ $edu->tahun_mulai }} - {{ $edu->tahun_selesai }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            data-modal-open="modalRepairEducationEdit{{ $edu->id }}"
                                            class="p-2 rounded-full bg-slate-800 text-blue-300 hover:bg-blue-700/30"
                                            title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13.5V19h5.5L19 9.5l-5.5-5.5L4 13.5z" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                            class="p-2 rounded-full bg-slate-800 text-red-300 hover:bg-red-700/30"
                                            title="Hapus"
                                            onclick="openDeleteConfirm('{{ route('pencaker.education.destroy', $edu->id) }}', 'Hapus riwayat pendidikan ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pelatihan --}}
<div class="rounded-2xl bg-slate-900 shadow-lg">
    <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Riwayat Pelatihan</h3>
            <div class="flex gap-2">
                <button type="button" data-modal-open="modalRepairTrainingCreate" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Tambah</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm md:text-base border-collapse text-slate-300">
                <thead class="bg-slate-800 text-slate-200">
                    <tr>
                        <th class="p-3 text-left">Jenis Pelatihan</th>
                        <th class="p-3 text-left">Lembaga</th>
                        <th class="p-3 text-left">Tahun</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $training)
                        <tr class="border-b border-slate-800">
                            <td class="p-3">{{ $training->jenis_pelatihan }}</td>
                            <td class="p-3">{{ $training->lembaga_pelatihan }}</td>
                            <td class="p-3">{{ $training->tahun }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            data-modal-open="modalRepairTrainingEdit{{ $training->id }}"
                                            class="p-2 rounded-full bg-slate-800 text-blue-300 hover:bg-blue-700/30"
                                            title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13.5V19h5.5L19 9.5l-5.5-5.5L4 13.5z" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                            class="p-2 rounded-full bg-slate-800 text-red-300 hover:bg-red-700/30"
                                            title="Hapus"
                                            onclick="openDeleteConfirm('{{ route('pencaker.training.destroy', $training->id) }}', 'Hapus riwayat pelatihan ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Dokumen --}}
<div class="rounded-2xl bg-slate-900 shadow-lg">
    <div class="max-w-6xl mx-auto p-6 sm:p-8 lg:p-10">
        <h3 class="text-lg font-semibold text-white mb-4">Unggah Dokumen</h3>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block font-medium mb-2">KTP</label>
                @if ($ktp = optional($application->documents->firstWhere('type', 'ktp_file'))->file_path)
                    <a href="{{ asset('storage/'.$ktp) }}" target="_blank" class="text-sm text-blue-300 hover:underline">Lihat Dokumen Saat Ini</a>
                @endif
                <input type="file" name="ktp_file" accept=".jpg,.jpeg,.png,.pdf"
                       class="mt-2 block w-full text-sm text-slate-300"
                       onchange="enableRenewalButton();">
            </div>
            <div>
                <label class="block font-medium mb-2">Ijazah</label>
                @if ($ijazah = optional($application->documents->firstWhere('type', 'ijazah_file'))->file_path)
                    <a href="{{ asset('storage/'.$ijazah) }}" target="_blank" class="text-sm text-blue-300 hover:underline">Lihat Dokumen Saat Ini</a>
                @endif
                <input type="file" name="ijazah_file" accept=".jpg,.jpeg,.png,.pdf"
                       class="mt-2 block w-full text-sm text-slate-300"
                       onchange="enableRenewalButton();">
            </div>
            <div>
                <label class="block font-medium mb-2">Pas Foto (Close-up)</label>
                <input type="file" name="foto_closeup" accept="image/*"
                       class="mt-2 block w-full text-sm text-slate-300"
                       onchange="previewImage(event); enableRenewalButton();">
            </div>
        </div>
        <p class="mt-4 text-xs text-slate-400">Kosongkan jika tidak ada perubahan dokumen.</p>
    </div>
</div>
