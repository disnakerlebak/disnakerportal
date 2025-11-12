@extends('layouts.admin')
@section('title', 'Alasan Penolakan')

@section('content')
<div 
    x-data="{
        selectedId: null,
        edit: { id: null, title: '', description: '', action: '' },
        openAddModal(){ window.dispatchEvent(new CustomEvent('open-modal', { detail: 'rr-add' })); },
        openEditModal(id, title, description){
            this.edit = {
                id,
                title,
                description,
                action: '{{ url('admin/rejection-reasons') }}/' + id
            };
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'rr-edit' }));
        },
        openDeleteModal(id){ this.selectedId = id; window.dispatchEvent(new CustomEvent('open-modal', { detail: 'rr-delete' })); }
    }"
    class="space-y-6"
>

    {{-- ===== HEADER ===== --}}
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-100">Daftar Alasan Penolakan</h2>
        <button 
            @click="openAddModal()"
            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium">
            + Tambah Alasan
        </button>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900/70 overflow-hidden shadow">
        <table class="min-w-full text-sm text-slate-200">
            <thead class="bg-slate-800 text-slate-200 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 text-left w-1/4">Judul</th>
                    <th class="px-4 py-2 text-left">Deskripsi</th>
                    <th class="px-4 py-2 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse ($reasons as $reason)
                    <tr class="hover:bg-slate-800/50 transition">
                        <td class="px-4 py-2">{{ $reason->title }}</td>
                        <td class="px-4 py-2">{{ $reason->description ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="inline-flex items-center gap-2">
                                <button 
                                    @click="openEditModal({{ $reason->id }}, '{{ addslashes($reason->title) }}', '{{ addslashes($reason->description ?? '') }}')"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-md border border-slate-700 bg-slate-800 hover:bg-slate-700 text-blue-400"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712Z" />
                                        <path d="M19.513 8.199 15.8 4.487l-12.21 12.21a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                    </svg>
                                </button>
                                <button 
                                    @click="openDeleteModal({{ $reason->id }})"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-md border border-slate-700 bg-slate-800 hover:bg-slate-700 text-red-400"
                                    title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M16.5 4.478V4.125c0-1.035-.84-1.875-1.875-1.875h-5.25A1.875 1.875 0 0 0 7.5 4.125v.353c-1.09.06-2.185.176-3.27.349a.75.75 0 1 0 .24 1.482l.27-.043.8 12.043A3 3 0 0 0 8.532 21h6.936a3 3 0 0 0 2.992-2.69l.8-12.043.27.043a.75.75 0 1 0 .24-1.482 41.03 41.03 0 0 0-3.27-.349ZM10.5 8.25a.75.75 0 0 0-1.5 0v8.25a.75.75 0 0 0 1.5 0V8.25Zm4.5 0a.75.75 0 0 0-1.5 0v8.25a.75.75 0 0 0 1.5 0V8.25Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-slate-400 py-4">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== Modal Tambah (x-modal) ===== --}}
    <x-modal name="rr-add" :show="false" maxWidth="md" animation="slide-up">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Tambah Alasan Penolakan</h3>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-add'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.rejection-reasons.store') }}" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Judul</label>
                <input type="text" name="title" class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-add'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition text-sm font-semibold text-white">Simpan</button>
            </div>
        </form>
    </x-modal>

    {{-- ===== Modal Edit (x-modal) ===== --}}
    <x-modal name="rr-edit" :show="false" maxWidth="md" animation="slide-up">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Edit Alasan Penolakan</h3>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-edit'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <form method="POST" :action="edit.action" class="px-6 py-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Judul</label>
                <input type="text" name="title" x-model="edit.title" class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" x-model="edit.description" class="w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-edit'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition text-sm font-semibold text-white">Perbarui</button>
            </div>
        </form>
    </x-modal>

    {{-- ===== Konfirmasi Hapus (x-modal) ===== --}}
    <x-modal name="rr-delete" :show="false" maxWidth="sm" animation="zoom">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Hapus Alasan?</h3>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-delete'}))" class="text-slate-300 hover:text-white">✕</button>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-300">Data ini akan dihapus secara permanen.</p>
            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', {detail: 'rr-delete'}))" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition text-sm">Batal</button>
                <form :action="'{{ url('admin/rejection-reasons') }}/' + selectedId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 text-sm">Hapus</button>
                </form>
            </div>
        </div>
    </x-modal>

</div>
@endsection
