<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Minat Kerja
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        {{-- tombol tambah --}}
        <button data-modal-open="modalTambahPreference"
                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Tambah / Ubah Minat Kerja
        </button>

        {{-- ================= MODAL TAMBAH / EDIT ================= --}}
        <x-modal-form id="modalTambahPreference"
                      title="Isi Minat Kerja"
                      :action="route('pencaker.preferences.store')"
                      method="POST"
                      submitLabel="Simpan" cancelLabel="Batal">
            <div class="space-y-4">

                {{-- Minat Lokasi --}}
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                        Minat Lokasi Kerja (boleh lebih dari satu)
                    </label>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $lokasiList = ['Kabupaten Lebak', 'Luar Kabupaten Lebak', 'Luar Negeri'];
                            $selectedLokasi = $preference->minat_lokasi ?? [];
                        @endphp
                        @foreach($lokasiList as $lokasi)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="minat_lokasi[]" value="{{ $lokasi }}"
                                       class="rounded border-gray-400 dark:border-gray-600"
                                       {{ in_array($lokasi, $selectedLokasi ?? []) ? 'checked' : '' }}>
                                <span>{{ $lokasi }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Minat Bidang --}}
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                        Minat Bidang Usaha (boleh lebih dari satu)
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @php
                            $bidangList = ['IT', 'Jasa', 'Pertambangan', 'Kelautan', 'Pertanian', 'Pendidikan', 'Kesehatan', 'Konstruksi', 'Transportasi', 'Administrasi'];
                            $selectedBidang = $preference->minat_bidang ?? [];
                        @endphp
                        @foreach($bidangList as $bidang)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="minat_bidang[]" value="{{ $bidang }}"
                                       class="rounded border-gray-400 dark:border-gray-600"
                                       {{ in_array($bidang, $selectedBidang ?? []) ? 'checked' : '' }}>
                                <span>{{ $bidang }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Gaji Harapan --}}
                <x-input-text label="Gaji yang Diharapkan (contoh: 3–5 juta)" name="gaji_harapan"
                              :value="$preference->gaji_harapan ?? ''" />

                {{-- Deskripsi Diri --}}
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-300 mb-1">
                        Deskripsi Singkat Tentang Diri Anda
                    </label>
                    <textarea name="deskripsi_diri" rows="4"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500">{{ $preference->deskripsi_diri ?? '' }}</textarea>
                </div>

            </div>
        </x-modal-form>

        {{-- ================= RINGKASAN DATA ================= --}}
        <div class="bg-gray-800/40 rounded-lg p-6 text-gray-200">
            <h3 class="text-lg font-semibold mb-4">Data Minat Kerja</h3>

            @if($preference)
                <div class="space-y-3">
                    <p><strong>Minat Lokasi:</strong>
                        {{ implode(', ', $preference->minat_lokasi ?? []) ?: '-' }}</p>
                    <p><strong>Bidang Usaha:</strong>
                        {{ implode(', ', $preference->minat_bidang ?? []) ?: '-' }}</p>
                    <p><strong>Gaji Harapan:</strong>
                        {{ $preference->gaji_harapan ?: '-' }}</p>
                    <p><strong>Deskripsi Diri:</strong>
                        {{ $preference->deskripsi_diri ?: '-' }}</p>
                </div>
            @else
                <p class="text-gray-400 italic">Belum ada data minat kerja yang diisi.</p>
            @endif
        </div>

    </div>

</x-app-layout>
