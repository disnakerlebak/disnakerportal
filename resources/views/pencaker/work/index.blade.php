<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Riwayat Kerja
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        {{-- Tombol Tambah --}}
        <button data-modal-open="modalTambahKerja"
                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Tambah Riwayat Kerja
        </button>

        {{-- ================= MODAL TAMBAH ================= --}}
        <x-modal-form id="modalTambahKerja"
                      title="Tambah Riwayat Kerja"
                      :action="route('pencaker.work.store')"
                      method="POST"
                      submitLabel="Simpan" cancelLabel="Batal">
            <x-input-text label="Nama Perusahaan" name="nama_perusahaan" required />
            <x-input-text label="Jabatan" name="jabatan" required />
            <div class="grid grid-cols-2 gap-3">
                <x-input-text label="Tahun Mulai" name="tahun_mulai" type="number" placeholder="2020" required />
                <x-input-text label="Tahun Selesai" name="tahun_selesai" type="number" placeholder="2024" />
            </div>
            <x-input-file label="Upload Surat Pengalaman (Opsional)" name="surat_pengalaman" />
        </x-modal-form>

        {{-- ================= MODAL KONFIRMASI HAPUS ================= --}}
        <x-modal-form id="modalDelete" title="Konfirmasi Hapus"
                      action="" method="POST"
                      submitLabel="Ya, Hapus" cancelLabel="Batal">
            @method('DELETE')
            <p class="text-gray-300">Apakah Anda yakin ingin menghapus data riwayat kerja ini?</p>
        </x-modal-form>

        {{-- ================= TABEL DATA ================= --}}
        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse border border-gray-400 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                    <tr>
                        <th class="border p-2">Perusahaan</th>
                        <th class="border p-2">Jabatan</th>
                        <th class="border p-2">Tahun</th>
                        <th class="border p-2">Surat Pengalaman</th>
                        <th class="border p-2 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200">
                    @forelse($works as $work)
                        <tr class="border-t border-gray-300 dark:border-gray-700">
                            <td class="border p-2">{{ $work->nama_perusahaan }}</td>
                            <td class="border p-2">{{ $work->jabatan }}</td>
                            <td class="border p-2 text-center">
                                {{ $work->tahun_mulai }} - {{ $work->tahun_selesai ?? 'Sekarang' }}
                            </td>
                            <td class="border p-2 text-center">
                                @if($work->surat_pengalaman)
                                    <a href="{{ asset('storage/'.$work->surat_pengalaman) }}" target="_blank"
                                       class="text-blue-600 hover:underline">Lihat</a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="border p-2 text-center">
                                <a href="javascript:void(0)"
                                   data-modal-open="modalEdit{{ $work->id }}"
                                   class="text-yellow-400 hover:text-yellow-300">Edit</a> |

                                <form action="{{ route('pencaker.work.destroy', $work) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="text-red-500 hover:text-red-400 btnDelete"
                                            data-action="{{ route('pencaker.work.destroy', $work) }}">
                                        Hapus
                                    </button>
                                </form>

                                {{-- ===== modal edit per-record ===== --}}
                                <x-modal-form id="modalEdit{{ $work->id }}"
                                              title="Edit Riwayat Kerja"
                                              :action="route('pencaker.work.update', $work)"
                                              method="POST"
                                              submitLabel="Perbarui" cancelLabel="Batal">
                                    @method('PUT')
                                    <x-input-text label="Nama Perusahaan" name="nama_perusahaan"
                                                  :value="$work->nama_perusahaan" required />
                                    <x-input-text label="Jabatan" name="jabatan"
                                                  :value="$work->jabatan" required />
                                    <div class="grid grid-cols-2 gap-3">
                                        <x-input-text label="Tahun Mulai" name="tahun_mulai"
                                                      type="number" :value="$work->tahun_mulai" required />
                                        <x-input-text label="Tahun Selesai" name="tahun_selesai"
                                                      type="number" :value="$work->tahun_selesai" />
                                    </div>
                                    <x-input-file label="Upload Surat Pengalaman Baru (Opsional)"
                                                  name="surat_pengalaman" />
                                </x-modal-form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Belum ada riwayat kerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= SCRIPT ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tombol hapus
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
