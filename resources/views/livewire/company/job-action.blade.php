<div class="space-y-4">
    <div class="space-y-3 text-sm text-slate-200">
        <p>{{ $message }}</p>
    </div>

    <div class="flex items-center justify-end gap-2 w-full">
        <button type="button"
                wire:click="$dispatch('modal:close', { id: 'job-action-modal' })"
                class="rounded-md border border-slate-700 px-4 py-2 text-sm text-slate-100 hover:bg-slate-800">
            Batal
        </button>
        <button type="button"
                wire:click="confirm"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            Ya, lanjutkan
        </button>
    </div>
</div>
