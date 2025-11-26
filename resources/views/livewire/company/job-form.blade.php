<form wire:submit.prevent="save" class="space-y-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Judul*</label>
            <input type="text" wire:model.defer="judul" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('judul') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Posisi</label>
            <input type="text" wire:model.defer="posisi" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('posisi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Lokasi Kerja*</label>
            <input type="text" wire:model.defer="lokasi_kerja" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('lokasi_kerja') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Pendidikan Minimal</label>
            <input type="text" wire:model.defer="pendidikan_minimal" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('pendidikan_minimal') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Jenis Kelamin</label>
            <select wire:model.defer="jenis_kelamin" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="">Pilih</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
                <option value="LP">Laki-laki / Perempuan</option>
            </select>
            @error('jenis_kelamin') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="space-y-2">
                <label class="text-sm text-slate-300">Usia Min</label>
                <input type="number" wire:model.defer="usia_min" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('usia_min') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-sm text-slate-300">Usia Max</label>
                <input type="number" wire:model.defer="usia_max" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('usia_max') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="space-y-2">
                <label class="text-sm text-slate-300">Gaji Min</label>
                <input type="number" wire:model.defer="gaji_min" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('gaji_min') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-sm text-slate-300">Gaji Max</label>
                <input type="number" wire:model.defer="gaji_max" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                @error('gaji_max') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Tanggal Expired</label>
            <input type="date" wire:model.defer="tanggal_expired" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            @error('tanggal_expired') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm text-slate-300">Disabilitas</label>
            <select wire:model.defer="menerima_disabilitas" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="1">Menerima</option>
                <option value="0">Tidak menerima</option>
            </select>
            @error('menerima_disabilitas') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm text-slate-300">Deskripsi</label>
        <textarea wire:model.defer="deskripsi" rows="3" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"></textarea>
        @error('deskripsi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label class="text-sm text-slate-300">Kualifikasi</label>
        <textarea wire:model.defer="kualifikasi" rows="3" class="w-full rounded-lg border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"></textarea>
        @error('kualifikasi') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-end gap-2 w-full">
        <button type="button"
                wire:click="$dispatch('modal:close', { id: 'job-form-modal' })"
                class="rounded-md border border-slate-700 px-4 py-2 text-sm text-slate-100 hover:bg-slate-800">
            Batal
        </button>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Draft' }}
        </button>
    </div>
</form>
