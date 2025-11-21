@props(['id'])

<div 
    id="{{ $id }}"
    x-data="timelineModal('{{ $id }}')"
    @timeline:open.window="load($event.detail)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
>
    <div class="modal-panel w-full max-w-4xl shadow-xl overflow-hidden" @click.outside="close()">

        <div class="modal-panel-header flex items-start justify-between px-6 py-4 sticky top-0 z-10">
            <div>
                <h3 class="text-lg font-semibold text-gray-100" x-text="title"></h3>
                <p class="text-sm text-gray-400 mt-1" x-text="subtitle"></p>
            </div>
            <button @click="close()" class="modal-close">✕</button>
        </div>

        <div class="px-6 py-5 max-h-[75vh] overflow-y-auto">
            <template x-if="loading">
                <p class="text-sm text-slate-300">Memuat riwayat...</p>
            </template>

            <template x-if="!loading && html">
                <div x-html="html"></div>
            </template>

            <template x-if="!loading && !html">
                <div class="space-y-6">
                    <template x-if="items.length === 0">
                        <p class="text-sm text-gray-400">Belum ada riwayat.</p>
                    </template>

                    <template x-for="(entry, idx) in items" :key="idx">
                        <x-timeline.item />
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
