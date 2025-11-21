<div class="relative pl-10">
    <span class="absolute left-2.5 top-4 bottom-0 border-l border-gray-700"></span>

    <span class="absolute left-1 top-1.5 inline-flex h-3 w-3 rounded-full ring-4 ring-gray-900"
          :class="entry.color">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             class="h-3 w-3 text-gray-900">
            <template x-if="entry.icon">
                <path :d="entry.icon"></path>
            </template>
        </svg>
    </span>

    <div class="rounded-xl border border-gray-800 bg-gray-900/60 px-4 py-3 shadow-sm">
        <div class="flex items-center justify-between gap-3 mb-1">
            <div class="flex items-center gap-2">
                <h4 class="text-sm font-semibold text-gray-100" x-text="entry.title"></h4>
                <template x-if="entry.badge">
                    <span class="rounded bg-emerald-700/40 px-2 py-0.5 text-xs text-emerald-300" x-text="entry.badge"></span>
                </template>
            </div>
            <span class="text-xs text-gray-400 shrink-0" x-text="entry.date || '-'"></span>
        </div>

        <p class="text-xs text-gray-400">
            Perubahan:
            <span class="text-gray-300" x-text="`${entry.from || '—'} → ${entry.to || '—'}`"></span>
        </p>

        <p class="mt-2 text-sm text-gray-300 leading-relaxed">
            Oleh:
            <span class="font-medium text-gray-100">
                <span x-text="entry.actor || 'Sistem'"></span>
                <template x-if="entry.role">
                    <span x-text="` (${entry.role})`"></span>
                </template>
            </span>
        </p>

        <template x-if="entry.nomor_ak1">
            <p class="mt-1 text-xs text-gray-400">
                <span class="text-gray-300">No. AK1:</span>
                <span x-text="entry.nomor_ak1"></span>
            </p>
        </template>

        <template x-if="entry.notes">
            <div class="mt-3 rounded-lg bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">Catatan</span>
                <p class="mt-1 leading-relaxed" x-text="entry.notes"></p>
            </div>
        </template>
    </div>
</div>
