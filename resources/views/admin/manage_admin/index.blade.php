@extends('layouts.admin')

@section('title', '')

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

    <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-gray-900">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nama</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Email</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Dibuat</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            @forelse($admins as $admin)
              <tr class="hover:bg-gray-700/30">
                <td class="px-4 py-3">{{ $admin->name }}</td>
                <td class="px-4 py-3">{{ $admin->email }}</td>
                <td class="px-4 py-3">
                  @if($admin->status === 'active')
                    <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-300 border border-green-500/30">Active</span>
                  @else
                    <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-300 border border-red-500/30">Inactive</span>
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
                <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada admin.</td>
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
