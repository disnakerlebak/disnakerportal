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
     class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop">

    {{-- ================= MODAL WRAPPER ================= --}}
    <div class="modal-panel w-full max-w-xl flex flex-col overflow-hidden min-h-0"
         style="max-height:90vh">

        {{-- ================= HEADER ================= --}}
        <div class="modal-panel-header p-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-100">{{ $title }}</h3>
            <button type="button"
                    class="modal-close text-2xl font-bold"
                    data-modal-close="{{ $id }}">×</button>
        </div>

        {{-- ================= FORM BODY ================= --}}
        @php
            $httpMethod = strtoupper($method);
            $formMethod = in_array($httpMethod, ['PUT','PATCH','DELETE']) ? 'POST' : $httpMethod;
        @endphp
        <form method="{{ $formMethod }}" action="{{ $action }}" enctype="multipart/form-data"
              class="p-6 overflow-y-auto grow space-y-4">
            @csrf
            @if(in_array($httpMethod, ['PUT', 'PATCH', 'DELETE']))
                @method($httpMethod)
            @endif

            {{-- Slot konten form --}}
            {{ $slot }}

            {{-- ================= FOOTER BUTTONS ================= --}}
            <div class="modal-panel-footer flex justify-end gap-3 mt-6 pt-4">
                <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-100 hover:bg-gray-700 transition"
                        data-modal-close="{{ $id }}">
                    {{ $cancelLabel }}
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:ring-offset-gray-900/60">
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
