@extends('layouts.admin')
@section('title', 'Alasan Penolakan')

@section('content')
<div 
    x-data="{
        openAdd: false,
        openEdit: false,
        openDelete: false,
        selectedId: null,
        editAction: '',
        setEdit(id, title, description) {
            this.openEdit = true
            this.selectedId = id
            this.editAction = '{{ url('admin/rejection-reasons') }}/' + id
            $refs.editTitle.value = title
            $refs.editDescription.value = description
        }
    }"
    class="space-y-6"
>

    {{-- ===== HEADER ===== --}}
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-100">Daftar Alasan Penolakan</h2>
        <button 
            @click="openAdd = true"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-medium">
            + Tambah Alasan
        </button>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-gray-300">
            <thead class="bg-gray-700 text-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 text-left w-1/4">Judul</th>
                    <th class="px-4 py-2 text-left">Deskripsi</th>
                    <th class="px-4 py-2 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reasons as $reason)
                    <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">
                        <td class="px-4 py-2">{{ $reason->title }}</td>
                        <td class="px-4 py-2">{{ $reason->description ?? '-' }}</td>
                        <td class="px-4 py-2 text-center space-x-2">
                            {{-- Tombol Edit --}}
                            <button 
                                @click="setEdit({{ $reason->id }}, '{{ addslashes($reason->title) }}', '{{ addslashes($reason->description ?? '') }}')"
                                class="px-3 py-1 rounded-md text-blue-400 hover:bg-blue-700/20">
                                Edit
                            </button>

                            {{-- Tombol Hapus --}}
                            <button 
                                @click="openDelete = true; selectedId = {{ $reason->id }}"
                                class="px-3 py-1 rounded-md text-red-400 hover:bg-red-700/20">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-4">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH ===== --}}
    <div 
        x-cloak
        x-show="openAdd"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
    >
        <div 
            x-transition.scale.origin.center.duration.150ms
            class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-xl"
        >
            <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-100">Tambah Alasan Penolakan</h3>
                <button @click="openAdd = false" class="text-gray-400 hover:text-gray-200 text-2xl font-bold">×</button>
            </div>

            <form method="POST" action="{{ route('admin.rejection-reasons.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Judul</label>
                    <input type="text" name="title"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Data tidak lengkap" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Opsional: jelaskan alasan penolakan"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                    <button type="button" @click="openAdd = false"
                        class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div 
        x-cloak
        x-show="openEdit"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
    >
        <div 
            x-transition.scale.origin.center.duration.150ms
            class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-xl"
        >
            <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-100">Edit Alasan Penolakan</h3>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-200 text-2xl font-bold">×</button>
            </div>

            <form method="POST" :action="editAction" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Judul</label>
                    <input type="text" name="title" x-ref="editTitle"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" x-ref="editDescription"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                    <button type="button" @click="openEdit = false"
                        class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== KONFIRMASI HAPUS (TAILWIND) ===== --}}
    <div 
        x-cloak
        x-show="openDelete"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
    >
        <div 
            x-transition.scale.origin.center.duration.150ms
            class="bg-gray-800 border border-gray-700 rounded-xl p-6 max-w-sm w-full text-center shadow-2xl"
        >
            <h3 class="text-lg font-semibold text-gray-100 mb-3">Hapus Alasan?</h3>
            <p class="text-sm text-gray-400 mb-6">Data ini akan dihapus secara permanen.</p>
            <div class="flex justify-center gap-3">
                <button @click="openDelete = false"
                    class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-700 transition">
                    Batal
                </button>
                <form :action="'{{ url('admin/rejection-reasons') }}/' + selectedId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
