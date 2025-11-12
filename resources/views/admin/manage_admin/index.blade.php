@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('content')
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold text-gray-100">Kelola Admin Disnaker Portal</h1>
      <button
        @click="$dispatch('open-modal-create-admin')"
        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white"
      >+ Tambah Admin</button>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 rounded bg-green-600/20 text-green-300 border border-green-600/40">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-3 rounded bg-red-600/20 text-red-300 border border-red-600/40">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-slate-200">
          <thead class="bg-slate-800 text-slate-200 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">Nama</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Dibuat</th>
              <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($admins as $admin)
              <tr class="hover:bg-slate-800/50 transition">
                <td class="px-4 py-3">{{ $admin->name }}</td>
                <td class="px-4 py-3">{{ $admin->email }}</td>
                <td class="px-4 py-3">
                  @if($admin->status === 'active')
                    <span class="px-2 py-1 text-[11px] rounded-full bg-green-700/30 text-green-200 border border-green-600/40">Active</span>
                  @else
                    <span class="px-2 py-1 text-[11px] rounded-full bg-red-700/30 text-red-200 border border-red-600/40">Inactive</span>
                  @endif
                </td>
                <td class="px-4 py-3">{{ $admin->created_at->format('d M Y H:i') }}</td>
                <td class="px-4 py-3">
                  <x-admin-action-dropdown :admin="$admin" />
                </td>
              </tr>

              <!-- Edit Modal for this admin -->
              <x-modal-admin-form
                :modal-id="'edit-admin-' . $admin->id"
                :title="'Edit Admin'"
                :action="route('admin.manage.update', $admin->id)"
                method="POST"
                :admin="$admin"
              />
            @empty
              <tr>
                <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada admin.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Modal -->
    <x-modal-admin-form
      modal-id="create-admin"
      title="Tambah Admin"
      :action="route('admin.manage.store')"
      method="POST"
    />
  </div>
@endsection
