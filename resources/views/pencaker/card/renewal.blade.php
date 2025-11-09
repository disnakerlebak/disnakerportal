@extends('layouts.pencaker')
@section('title', 'Perpanjangan AK1')

@section('content')
<div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-8 text-slate-100"
     x-data="{ modalOpen: false, showForm: {{ old('mode') === 'update' ? 'true' : 'false' }} }">
    <h2 class="text-2xl font-semibold text-white mb-4">Perpanjangan Kartu AK1</h2>

    @if (session('success'))
        <div class="mb-4 bg-green-800 border border-green-600 text-green-100 px-4 py-3 rounded">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-600/20 border border-red-600 text-red-200 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8 rounded-2xl border border-indigo-800/60 bg-indigo-900/40 px-6 py-5 text-slate-100 shadow-lg">
        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-200">Ringkasan Kartu AK1</p>
        <div class="mt-3 grid gap-4 text-sm md:grid-cols-2">
            <div>
                <p>Nomor AK1</p>
                <p class="text-lg font-semibold text-white">{{ $application->nomor_ak1 ?? '-' }}</p>
            </div>
            <div>
                <p>Status Pengajuan</p>
                <p class="text-lg font-semibold text-white">{{ $application->status }}</p>
            </div>
            <div>
                <p>Tanggal Disetujui</p>
                <p class="text-base text-slate-200">{{ $approvedAt ? indoDateOnly($approvedAt) : '-' }}</p>
            </div>
            <div>
                <p>Masa Berlaku Hingga</p>
                <p class="text-base text-slate-200">{{ $expiresAt ? indoDateOnly($expiresAt) : '-' }}</p>
                @if($expiresAt)
                    <span class="text-xs font-semibold {{ $isExpired ? 'text-red-400' : 'text-green-400' }}">
                        {{ $isExpired ? 'Kartu sudah melewati masa berlaku' : 'Kartu masih aktif' }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if ($hasPendingRenewal)
        <div class="mb-6 rounded-lg border border-yellow-600 bg-yellow-900/40 px-4 py-3 text-sm text-yellow-100">
            ⏳ Pengajuan perpanjangan sebelumnya masih diproses admin. Harap tunggu hingga selesai sebelum mengirim pengajuan baru.
        </div>
    @endif

    @if (!$isExpired)
        <div class="mb-6 rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-3 text-sm text-slate-200">
            Kartu AK1 Anda masih berlaku hingga {{ $expiresAt ? indoDateOnly($expiresAt) : '-' }}. Tombol perpanjangan akan aktif setelah masa berlaku berakhir.
        </div>
    @endif

    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <p class="text-sm text-slate-300 uppercase tracking-wide">Preview Kartu Aktif</p>
                <p class="text-lg font-semibold text-white">Kartu Pencari Kerja Terbaru</p>
            </div>
            @if ($previewCard)
                <span class="text-xs px-2 py-1 rounded-full {{ $previewCard->is_active ? 'bg-green-700/30 text-green-200' : 'bg-slate-700 text-slate-200' }}">
                    {{ $previewCard->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            @endif
        </div>
        <div class="px-6 py-4">
            @if ($previewCard && $previewCard->is_active)
                <iframe src="{{ route('pencaker.card.cetak', $previewCard->id) }}" class="w-full h-[550px] rounded-xl border border-slate-800 bg-white" title="Preview Kartu AK1"></iframe>
            @else
                <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 px-6 py-8 text-center text-slate-300">
                    Belum ada kartu AK1 aktif yang dapat ditampilkan. Jika kartu Anda sudah kedaluwarsa, segera ajukan perpanjangan.
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-4">
        <button type="button"
                class="rounded-xl px-5 py-3 text-base font-semibold transition {{ $canApply ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-slate-700 text-slate-400 cursor-not-allowed' }}"
                @click="modalOpen = true"
                {{ $canApply ? '' : 'disabled' }}>
            Ajukan Perpanjangan AK1
        </button>
        @if(!$canApply)
            <p class="text-sm text-slate-400">Tombol akan aktif setelah kartu kedaluwarsa dan tidak ada pengajuan perpanjangan yang berjalan.</p>
        @endif
    </div>

    <form id="renewalQuickForm" action="{{ route('pencaker.card.renewal.submit') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="mode" value="quick">
    </form>

    {{-- Modal konfirmasi opsi perpanjangan --}}
    <div x-show="modalOpen"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4"
         @keydown.escape.window="modalOpen = false">
        <div class="w-full max-w-lg rounded-2xl border border-slate-800 bg-slate-950 p-6 text-slate-100">
            <h3 class="text-xl font-semibold">Bagaimana Anda ingin memperpanjang?</h3>
            <p class="mt-2 text-sm text-slate-300">Silakan pilih apakah data Anda masih sama atau ingin diperbarui terlebih dahulu.</p>
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <button type="button"
                        class="rounded-xl border border-green-600/60 bg-green-800/30 px-4 py-3 text-sm font-semibold text-green-100 hover:bg-green-700/50"
                        @click="modalOpen = false; document.getElementById('renewalQuickForm').submit();">
                    Data Saya Masih Sama
                    <span class="block text-xs font-normal text-green-200">Langsung ajukan perpanjangan</span>
                </button>
                <button type="button"
                        class="rounded-xl border border-blue-600/60 bg-blue-900/30 px-4 py-3 text-sm font-semibold text-blue-100 hover:bg-blue-800/50"
                        @click="modalOpen = false; showForm = true; $nextTick(() => document.getElementById('renewalUpdateForm')?.scrollIntoView({behavior: 'smooth'}));">
                    Saya Ingin Perbarui Data
                    <span class="block text-xs font-normal text-blue-200">Tampilkan form pembaruan</span>
                </button>
            </div>
            <div class="mt-4 text-right">
                <button type="button" class="text-sm text-slate-400 hover:text-white" @click="modalOpen = false">Batalkan</button>
            </div>
        </div>
    </div>

    @php
        $snapshotChanged = $snapshotChanged ?? false;
    @endphp

    <div x-show="showForm" x-cloak x-transition>
        <div class="mt-10 mb-6 rounded-2xl border border-indigo-800/60 bg-indigo-900/40 px-6 py-5 text-sm leading-relaxed text-slate-200">
            <p class="font-semibold text-indigo-200 uppercase tracking-wide">Form Pembaruan Data</p>
            <p class="mt-2">Silakan perbarui data diri, pendidikan, pelatihan, atau dokumen jika ada perubahan sebelum diajukan ke admin untuk perpanjangan.</p>
        </div>

        <form id="renewalUpdateForm" action="{{ route('pencaker.card.renewal.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-snapshot-changed="{{ $snapshotChanged ? 'true' : 'false' }}">
            @csrf
            <input type="hidden" name="mode" value="update">

            {{-- Status ringkas --}}
            <div class="rounded-2xl bg-slate-900 shadow-lg">
                <div class="mx-auto max-w-6xl px-4 py-6 sm:px-8 lg:px-10">
                    <h3 class="text-lg font-semibold text-white mb-2">Status Kartu Saat Ini</h3>
                    <div class="text-sm text-slate-300 space-y-1">
                        <p>Nomor AK1: <span class="font-semibold text-white">{{ $application->nomor_ak1 ?? '-' }}</span></p>
                        <p>Status Saat Ini: <span class="font-semibold text-white">{{ $application->status }}</span></p>
                        <p>Tanggal Persetujuan: {{ $approvedAt ? indoDateOnly($approvedAt) : '-' }}</p>
                        <p>Masa Berlaku: {{ $expiresAt ? indoDateOnly($expiresAt) : '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Foto & data diri --}}
            <div class="rounded-2xl bg-slate-900 shadow-lg">
                <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-10 lg:px-10">
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
                        <div class="flex flex-col items-center justify-start gap-3 text-sm text-slate-400 lg:items-start">
                            <div class="relative h-52 w-44 overflow-hidden rounded-xl border border-slate-800/60 bg-slate-900 shadow-md sm:h-56 sm:w-48">
                                <img id="fotoPreview" src="{{ optional($application->documents->firstWhere('type', 'foto_closeup'))->file_path ? asset('storage/' . $application->documents->firstWhere('type', 'foto_closeup')->file_path) : asset('images/placeholder-avatar.png') }}" alt="Foto Close-Up" class="h-full w-full object-cover" />
                                <label for="fotoCloseup" class="absolute inset-x-0 bottom-0 bg-black/60 py-2 text-center text-xs text-slate-100 transition hover:bg-black/75">
                                    Ganti Foto
                                </label>
                            </div>
                            <p class="text-center text-xs text-slate-400 sm:text-left">Format JPG/PNG &bull; Maks 2 MB</p>
                            <input id="fotoCloseup" name="foto_closeup" type="file" accept="image/*" class="hidden" onchange="previewImage(event); enableRenewalButton();">
                            <button type="button" class="text-xs text-blue-400 hover:text-blue-300" data-modal-open="modalRepairProfile">Ubah Data Diri</button>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-white sm:text-xl">Data Diri</h3>
                                    <p class="mt-1 text-sm text-slate-400">Pastikan informasi sesuai dengan dokumen kependudukan.</p>
                                </div>
                                <button type="button" data-modal-open="modalRepairProfile" class="text-xs bg-blue-600/20 text-blue-300 px-2 py-1 rounded hover:bg-blue-600/30">Ubah Data</button>
                            </div>

                            <dl class="mt-6 grid grid-cols-1 gap-4 text-sm text-slate-200 sm:grid-cols-2">
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">NIK</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nik ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Status</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->status_perkawinan ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Lengkap</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->nama_lengkap ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Agama</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->agama ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tempat Lahir</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->tempat_lahir ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tanggal Lahir</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->tanggal_lahir ? indoDateOnly($profile->tanggal_lahir) : '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Jenis Kelamin</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->jenis_kelamin ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Pendidikan Terakhir</dt>
                                    <dd class="mt-1 text-base font-semibold text-white">{{ $profile->pendidikan_terakhir ?? '-' }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-800/60 bg-slate-800/60 px-4 py-3 shadow-sm sm:col-span-2">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat Domisili</dt>
                                    <dd class="mt-1 text-base font-semibold text-white leading-relaxed">{{ $profile->alamat_lengkap ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pendidikan, Pelatihan, Dokumen, Submit button, modals, scripts --}}
            @include('pencaker.card.partials.education-training-documents', ['educations' => $educations, 'trainings' => $trainings, 'application' => $application])

            <div class="flex justify-end">
                <button type="submit"
                        id="renewalSubmitBtn"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white transition hover:bg-indigo-500">
                    <span>Ajukan Perpanjangan</span>
                    <svg id="submitSpinner" class="hidden h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

@include('pencaker.card.partials.modals', ['educations' => $educations, 'trainings' => $trainings, 'kecamatanList' => $kecamatanList, 'profile' => $profile])

<div id="confirmOverlay" class="hidden fixed inset-0 z-40 bg-black/70 flex items-center justify-center">
    <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 max-w-md w-full text-slate-100">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Pengajuan Perpanjangan</h3>
        <p class="text-sm text-slate-300">Apakah Anda yakin data sudah benar dan ingin mengirim perpanjangan AK1?</p>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-700" onclick="toggleRenewalConfirm(false)">Batal</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700" onclick="submitRenewalForm()">Kirim</button>
        </div>
    </div>
</div>

<div id="deleteConfirmOverlay" class="hidden fixed inset-0 z-40 bg-black/70 flex items-center justify-center">
    <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 max-w-md w-full text-slate-100">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Hapus Data</h3>
        <p class="text-sm text-slate-300" id="deleteConfirmMessage">Apakah Anda yakin?</p>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" class="px-4 py-2 rounded bg-slate-800 hover:bg-slate-700" onclick="toggleDeleteConfirm(false)">Batal</button>
            <button type="button" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700" onclick="submitDeleteForm()">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteTargetUrl = null;

    function toggleRenewalConfirm(show) {
        document.getElementById('confirmOverlay').classList.toggle('hidden', !show);
    }

    function submitRenewalForm() {
        const form = document.getElementById('renewalUpdateForm');
        const spinner = document.getElementById('submitSpinner');
        toggleRenewalConfirm(false);
        if (spinner) spinner.classList.remove('hidden');
        form.dataset.confirmed = 'true';
        form.submit();
    }

    function enableRenewalButton() {
        const btn = document.getElementById('renewalSubmitBtn');
        if (btn) btn.disabled = false;
    }

    const renewalForm = document.getElementById('renewalUpdateForm');
    if (renewalForm) {
        renewalForm.addEventListener('submit', function (e) {
            if (renewalForm.dataset.confirmed === 'true') {
                return;
            }
            e.preventDefault();
            toggleRenewalConfirm(true);
        });
    }

    function openDeleteConfirm(targetUrl, message) {
        deleteTargetUrl = targetUrl;
        document.getElementById('deleteConfirmMessage').textContent = message || 'Apakah Anda yakin?';
        toggleDeleteConfirm(true);
    }

    function toggleDeleteConfirm(show) {
        document.getElementById('deleteConfirmOverlay').classList.toggle('hidden', !show);
    }

    function submitDeleteForm() {
        if (!deleteTargetUrl) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteTargetUrl;
        form.classList.add('hidden');

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
        form.remove();

        toggleDeleteConfirm(false);
        deleteTargetUrl = null;
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('fotoPreview');
            if (preview) preview.src = reader.result;
        };
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush
