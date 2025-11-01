<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Riwayat Pelatihan
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto p-6 space-y-6">
        @php $locked = $isLocked ?? false; @endphp
        @if($locked)
            <div class="bg-yellow-600/20 border border-yellow-600 text-yellow-100 px-4 py-3 rounded">
                Perubahan data pelatihan terkunci karena pengajuan AK1 berstatus Menunggu Verifikasi atau Disetujui.
            </div>
        @endif

        {{-- tombol tambah --}}
        @if($locked)
            <button disabled title="Terkunci saat pengajuan AK1 diproses/diterima"
                    class="px-3 py-2 bg-gray-600 text-white rounded-lg cursor-not-allowed">
                + Tambah Pelatihan (Terkunci)
            </button>
        @else
            <button data-modal-open="modalTambahPelatihan"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Tambah Pelatihan
            </button>
        @endif

        {{-- ================= modal tambah ================= --}}
        <x-modal-form id="modalTambahPelatihan"
                      title="Tambah Riwayat Pelatihan"
                      :action="route('pencaker.training.store')"
                      method="POST"
                      submitLabel="Simpan" cancelLabel="Batal">
            <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan" required />
            <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan" required />
            <x-input-text label="Tahun" name="tahun" type="number" placeholder="contoh: 2024" required />
            <x-input-file label="Upload Sertifikat (PDF/JPG/PNG)" name="sertifikat_file" required />
        </x-modal-form>

        {{-- ================= modal konfirmasi hapus ================= --}}
        <x-modal-form id="modalDelete" title="Konfirmasi Hapus"
                      action="" method="POST"
                      submitLabel="Ya, Hapus" cancelLabel="Batal">
            @method('DELETE')
            <p class="text-gray-300">Apakah Anda yakin ingin menghapus data pelatihan ini?</p>
        </x-modal-form>

        {{-- ================= tabel data ================= --}}
        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse border border-gray-400 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                    <tr>
                        <th class="border p-2">Jenis Pelatihan</th>
                        <th class="border p-2">Lembaga</th>
                        <th class="border p-2">Tahun</th>
                        <th class="border p-2">Sertifikat</th>
                        <th class="border p-2 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200">
                    @forelse($trainings as $train)
                        <tr class="border-t border-gray-300 dark:border-gray-700">
                            <td class="border p-2">{{ $train->jenis_pelatihan }}</td>
                            <td class="border p-2">{{ $train->lembaga_pelatihan }}</td>
                            <td class="border p-2 text-center">{{ $train->tahun }}</td>
                            <td class="border p-2 text-center">
                                @if($train->sertifikat_file)
                                    <a href="{{ asset('storage/'.$train->sertifikat_file) }}"
                                       target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="border p-2 text-center">
                                {{-- ===== tombol edit ===== --}}
                                @if($locked)
                                    <span class="text-gray-400">Terkunci</span>
                                @else
                                    <a href="javascript:void(0)"
                                       data-modal-open="modalEdit{{ $train->id }}"
                                       class="text-yellow-400 hover:text-yellow-300">
                                       Edit
                                    </a> |

                                    {{-- ===== tombol hapus ===== --}}
                                    <form action="{{ route('pencaker.training.destroy', $train) }}"
                                          method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                class="text-red-500 hover:text-red-400 btnDelete"
                                                data-action="{{ route('pencaker.training.destroy', $train) }}">
                                            Hapus
                                        </button>
                                    </form>

                                    {{-- ===== modal edit per-record ===== --}}
                                    <x-modal-form id="modalEdit{{ $train->id }}"
                                                  title="Edit Riwayat Pelatihan"
                                                  :action="route('pencaker.training.update', $train)"
                                                  method="POST"
                                                  submitLabel="Perbarui" cancelLabel="Batal">
                                        @method('PUT')
                                        <x-input-text label="Jenis Pelatihan" name="jenis_pelatihan"
                                                      :value="$train->jenis_pelatihan" required />
                                        <x-input-text label="Lembaga Pelatihan" name="lembaga_pelatihan"
                                                      :value="$train->lembaga_pelatihan" required />
                                        <x-input-text label="Tahun" name="tahun"
                                                      type="number" :value="$train->tahun" required />
                                        <x-input-file label="Upload Sertifikat Baru (opsional)" name="sertifikat_file" />
                                    </x-modal-form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Belum ada data pelatihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= script ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- tombol delete ---
            const modalDelete = document.getElementById('modalDelete');
            const formDelete = modalDelete.querySelector('form');
            const deleteButtons = document.querySelectorAll('.btnDelete');

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    const action = btn.getAttribute('data-action');
                    formDelete.action = action;
                    modalDelete.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
        });
    </script>
</x-app-layout>
