@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('content')
  @php $editModalIds = []; @endphp
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold text-gray-100">Kelola Admin Disnaker Portal</h1>
      <button
        type="button"
        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-admin' }))"
        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white"
      >+ Tambah Admin</button>
    </div>

    @if(session('success'))
      <div class="mb-4 p-3 rounded bg-green-600/20 text-green-300 border border-green-600/40">
        {{ session('success') }}
      </div>
    @endif

    <div class="rounded-xl border border-slate-800 bg-slate-900/70 shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-slate-200">
          <thead class="bg-slate-800 text-slate-200 uppercase text-xs sticky top-0 z-20 border-b border-slate-700 shadow-md shadow-slate-900/30">
            <tr>
              <th class="px-4 py-3 text-left">Nama</th>
              <th class="px-4 py-3 text-left">Kontak</th>
              <th class="px-4 py-3 text-left">Role</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Login Terakhir</th>
              <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @forelse($admins as $admin)
              @if($admin->role === 'superadmin')
                @continue
              @endif
              <tr class="hover:bg-slate-800/50 transition">
                <td class="px-4 py-3">{{ $admin->name }}</td>
                <td class="px-4 py-3">
                  <div class="flex flex-col gap-1">
                    <span>{{ $admin->email }}</span>
                    <span class="text-[11px] text-slate-400">Dibuat {{ $admin->created_at->format('d M Y H:i') }}</span>
                  </div>
                </td>
                @php
                  $roleLabel = $admin->role === 'admin_laporan' ? 'Admin Laporan' : ($admin->role === 'admin_ak1' ? 'Admin AK1' : ucfirst($admin->role ?? '-'));
                  $roleDot = $admin->role === 'admin_laporan' ? 'bg-amber-400' : 'bg-emerald-400';
                @endphp
                <td class="px-4 py-3">
                  <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs text-slate-100">
                    <span class="h-2 w-2 rounded-full {{ $roleDot }}"></span>
                    {{ $roleLabel }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  @if($admin->status === 'active')
                    <span class="px-2 py-1 text-[11px] rounded-full bg-green-700/30 text-green-200 border border-green-600/40">Aktif</span>
                  @else
                    <span class="px-2 py-1 text-[11px] rounded-full bg-red-700/30 text-red-200 border border-red-600/40">Tidak Aktif</span>
                  @endif
                </td>
                @php
                  $lastLogin = $lastActivities[$admin->id] ?? null;
                @endphp
                <td class="px-4 py-3">
                  @if($lastLogin)
                    <span class="text-sm text-slate-200">{{ $lastLogin->format('d M Y H:i') }}</span>
                  @else
                    <span class="text-sm text-slate-400">Belum ada aktivitas</span>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <x-admin-action-dropdown :admin="$admin" />
                </td>
              </tr>

              @php $editModalIds[] = 'edit-admin-' . $admin->id; @endphp

              <!-- Edit Modal for this admin -->
              <x-modal id="edit-admin-{{ $admin->id }}" title="Edit Admin" size="md">
                @include('admin.manage.partials.form', [
                    'modalId' => 'edit-admin-' . $admin->id,
                    'action' => route('admin.manage.update', $admin->id),
                    'method' => 'POST',
                    'admin' => $admin,
                ])
              </x-modal>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada admin.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Modal -->
    <x-modal id="create-admin" title="Tambah Admin" size="md">
      @include('admin.manage.partials.form', [
          'modalId' => 'create-admin',
          'action' => route('admin.manage.store'),
          'method' => 'POST',
          'admin' => null,
      ])
    </x-modal>

    @if ($errors->any() && old('modal_id'))
      <script>
        window.addEventListener('DOMContentLoaded', () => {
          const target = @json(old('modal_id'));
          if (target) {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: target }));
          }
        });
      </script>
    @endif

    @if (session('success'))
      @php $modalIds = array_merge(['create-admin'], $editModalIds); @endphp
      <script>
        window.addEventListener('DOMContentLoaded', () => {
          const ids = @json($modalIds);
          ids.forEach(id => {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: id }));
          });
        });
      </script>
    @endif
  </div>
@endsection
