@props([
    'id' => 'modalDefault',
    'title' => 'Form',
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => 'Simpan',
    'cancelLabel' => 'Batal'
])

{{-- ================= MODAL OVERLAY ================= --}}
<div id="{{ $id }}"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">

    {{-- ================= MODAL WRAPPER ================= --}}
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-700/30
                flex flex-col overflow-hidden min-h-0"
         style="max-height:90vh">

        {{-- ================= HEADER ================= --}}
        <div class="p-4 border-b border-gray-600/20 flex justify-between items-center
                    bg-white dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $title }}</h3>
            <button type="button"
                    class="text-gray-400 hover:text-gray-200 text-2xl font-bold"
                    data-modal-close="{{ $id }}">×</button>
        </div>

        {{-- ================= FORM BODY ================= --}}
        <form method="{{ $method }}" action="{{ $action }}" enctype="multipart/form-data"
              class="p-6 overflow-y-auto grow space-y-4">
            @csrf
            @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                @method($method)
            @endif

            {{-- Slot konten form --}}
            {{ $slot }}

            {{-- ================= FOOTER BUTTONS ================= --}}
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-600/30">
                <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-400 text-gray-700 dark:text-gray-200
                               hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        data-modal-close="{{ $id }}">
                    {{ $cancelLabel }}
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= GLOBAL SCRIPT ================= --}}
@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === Buka Modal ===
            document.querySelectorAll('[data-modal-open]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-modal-open');
                    const modal = document.getElementById(target);
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden'); // 🔒 kunci scroll
                    }
                });
            });

            // === Tutup Modal ===
            document.querySelectorAll('[data-modal-close]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-modal-close');
                    const modal = document.getElementById(target);
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden'); // 🔓 aktifkan scroll
                    }
                });
            });

            // === Tutup Modal jika klik area luar ===
            document.querySelectorAll('[id^="modal"]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            });

            // === Tutup Modal saat tekan ESC ===
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[id^="modal"]').forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                }
            });
        });
    </script>
@endonce
