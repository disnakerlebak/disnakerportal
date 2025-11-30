<form wire:submit.prevent="save" class="space-y-5">
    <div class="grid md:grid-cols-2 gap-5">
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Judul*</label>
            <input type="text" wire:model.defer="judul" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('judul') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Posisi</label>
            <input type="text" wire:model.defer="posisi" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('posisi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2 space-y-4">
            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 font-semibold">Lokasi</div>
            @if($useModal)
                <div class="space-y-2">
                    <label class="text-[13px] font-medium text-slate-300">Lokasi Kerja*</label>
                    <input type="text" wire:model.defer="lokasi_kerja" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    @error('lokasi_kerja') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            @else
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-4 space-y-3 shadow-sm shadow-black/10">
                    <div class="flex flex-wrap items-center gap-3 justify-between">
                        <div class="inline-flex rounded-lg border border-slate-800 bg-slate-900/70 p-1">
                            @php $isDomestic = $lokasi_mode === 'domestic'; @endphp
                            <button type="button"
                                    wire:click="$set('lokasi_mode', 'domestic')"
                                    class="px-3 py-1.5 rounded-md text-[12px] font-semibold transition {{ $isDomestic ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
                                Dalam negeri
                            </button>
                            <button type="button"
                                    wire:click="$set('lokasi_mode', 'foreign')"
                                    class="px-3 py-1.5 rounded-md text-[12px] font-semibold transition {{ !$isDomestic ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
                                Luar negeri
                            </button>
                        </div>
                        <p class="text-xs text-slate-400">Pilih negara untuk luar negeri, atau isi provinsi, kab/kota, kecamatan untuk dalam negeri.</p>
                    </div>

                    @if($lokasi_mode === 'foreign')
                        <div class="space-y-2 max-w-md">
                            <label class="text-[13px] font-medium text-slate-300">Negara Penempatan</label>
                            <input type="text"
                                   wire:model.defer="country"
                                   placeholder="Contoh: Singapura, Jepang, Australia"
                                   class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    @else
                        <div class="grid md:grid-cols-3 gap-3">
                            <div class="space-y-2">
                                <label class="text-[13px] font-medium text-slate-300">Provinsi</label>
                                <select wire:model="province_id"
                                        wire:change="onProvinceChange($event.target.value)"
                                        class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[13px] font-medium text-slate-300">Kabupaten/Kota</label>
                                <select wire:model="regency_id"
                                        wire:change="onRegencyChange($event.target.value)"
                                        class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        @disabled(empty($regencies))>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    @foreach($regencies as $regency)
                                        <option value="{{ $regency['id'] }}">{{ $regency['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[13px] font-medium text-slate-300">Kecamatan</label>
                                <select wire:model="district_id"
                                        class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        @disabled(empty($districts))>
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @error('lokasi_kerja') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            @endif
        </div>

        <div class="space-y-2">
            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 font-semibold">Kriteria</div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Pendidikan Minimal</label>
                <select wire:model.defer="pendidikan_minimal" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                    <option value="SMA/SMK">SMA/SMK</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
                @error('pendidikan_minimal') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Jenis Kelamin</label>
                <select wire:model.defer="jenis_kelamin" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                    <option value="LP">Laki-laki / Perempuan</option>
                </select>
                @error('jenis_kelamin') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="space-y-2">
            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 font-semibold invisible">.</div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Tipe Pekerjaan</label>
                <select wire:model.defer="tipe_pekerjaan" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="Penuh Waktu (Full Time)">Penuh Waktu (Full Time)</option>
                    <option value="Paruh Waktu (Part Time)">Paruh Waktu (Part Time)</option>
                    <option value="Pekerja Harian">Pekerja Harian</option>
                    <option value="Magang (Internship)">Magang (Internship)</option>
                    <option value="Pekerja Lepas (Freelance)">Pekerja Lepas (Freelance)</option>
                    <option value="Alih Daya (Outsourcing)">Alih Daya (Outsourcing)</option>
                    <option value="Program Trainee">Program Trainee</option>
                </select>
                @error('tipe_pekerjaan') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Model Kerja</label>
                <select wire:model.defer="model_kerja" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih</option>
                    <option value="WFO">WFO</option>
                    <option value="WFH/Remote">WFH/Remote</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
                @error('model_kerja') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Usia Min</label>
                <input type="number" wire:model.defer="usia_min" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('usia_min') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Usia Max</label>
                <input type="number" wire:model.defer="usia_max" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('usia_max') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Gaji Min</label>
                <input type="number" wire:model.defer="gaji_min" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('gaji_min') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[13px] font-medium text-slate-300">Gaji Max</label>
                <input type="number" wire:model.defer="gaji_max" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('gaji_max') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Tanggal Expired</label>
            <input type="date" wire:model.defer="tanggal_expired" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('tanggal_expired') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Disabilitas</label>
            <div class="flex items-center gap-5 h-[42px]">
                <label class="inline-flex items-center gap-2 text-sm text-slate-200">
                    <input type="radio" wire:model.defer="menerima_disabilitas" value="1" class="h-4 w-4 text-indigo-500 border-slate-700 bg-slate-900 focus:ring-indigo-500">
                    <span>Menerima</span>
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-slate-200">
                    <input type="radio" wire:model.defer="menerima_disabilitas" value="0" class="h-4 w-4 text-indigo-500 border-slate-700 bg-slate-900 focus:ring-indigo-500">
                    <span>Tidak menerima</span>
                </label>
            </div>
            @error('menerima_disabilitas') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-4">
        <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 font-semibold">Detail</div>
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Deskripsi</label>
            <textarea wire:model.defer="deskripsi" rows="4" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="Gambarkan peran, tanggung jawab, dan budaya kerja."></textarea>
            @error('deskripsi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-[13px] font-medium text-slate-300">Kualifikasi</label>
            <textarea wire:model.defer="kualifikasi" rows="4" class="w-full rounded-xl border border-slate-800/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: • Min. 2 tahun pengalaman • Menguasai Ms. Office • Komunikatif"></textarea>
            @error('kualifikasi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 w-full pt-1">
        @if($useModal)
            <button type="button"
                    wire:click="$dispatch('modal:close', { id: 'job-form-modal' })"
                    class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 transition">
                Batal
            </button>
        @else
            <a href="{{ $redirectTo ?? route('company.jobs.index') }}"
               class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 transition">
                Kembali
            </a>
        @endif
        <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
            <span wire:loading.remove>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Draft' }}</span>
            <span wire:loading class="inline-flex items-center gap-1">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Menyimpan...
            </span>
        </button>
    </div>
</form>
