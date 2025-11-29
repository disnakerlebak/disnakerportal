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
        @php
            $confirmClass = match($action) {
                'publish' => 'bg-blue-600 hover:bg-blue-700',
                'close'   => 'bg-amber-600 hover:bg-amber-700',
                'reopen'  => 'bg-emerald-600 hover:bg-emerald-700',
                'delete'  => 'bg-rose-600 hover:bg-rose-700',
                default   => 'bg-indigo-600 hover:bg-indigo-700',
            };
        @endphp
        <button type="button"
                wire:click="confirm"
                class="rounded-md px-4 py-2 text-sm font-semibold text-white {{ $confirmClass }}">
            Ya, lanjutkan
        </button>
    </div>
</div>
