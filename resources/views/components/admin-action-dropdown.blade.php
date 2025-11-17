@props(['admin'])

<x-dropdown :id="'admin-actions-'.$admin->id">
    <x-dropdown-item modal="edit-admin-{{ $admin->id }}" class="text-blue-300 hover:text-blue-100">
        Edit
    </x-dropdown-item>

    <x-dropdown-item modal="confirm-toggle-{{ $admin->id }}"
        class="{{ $admin->status === 'active' ? 'text-orange-300 hover:text-orange-100' : 'text-green-300 hover:text-green-100' }}">
        {{ $admin->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
    </x-dropdown-item>

    <x-dropdown-item modal="confirm-delete-{{ $admin->id }}" class="text-red-300 hover:text-red-100">
        Hapus
    </x-dropdown-item>
</x-dropdown>

  <!-- Confirm Delete Modal menggunakan komponen -->
  <x-modal id="confirm-delete-{{ $admin->id }}" size="md" title="Konfirmasi Hapus">
      <div class="px-6 py-5">
          <p class="text-slate-300 mb-4">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
          <div class="flex justify-end gap-2 pt-3">
              <button data-modal-hide="confirm-delete-{{ $admin->id }}" class="px-4 py-2 rounded border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">Batal</button>
              <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
              </form>
          </div>
      </div>
  </x-modal>

  <!-- Confirm Toggle Modal -->
  <x-modal id="confirm-toggle-{{ $admin->id }}" size="md" title="Konfirmasi">
      <div class="px-6 py-5">
          @php $aktif = $admin->status === 'active'; @endphp
          <p class="text-slate-300 mb-4">Anda akan {{ $aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} admin <b>{{ $admin->name }}</b>. Lanjutkan?</p>
          <div class="flex justify-end gap-2 pt-3">
              <button data-modal-hide="confirm-toggle-{{ $admin->id }}" class="px-4 py-2 rounded border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">Batal</button>
              <form action="{{ route('admin.manage.toggle', $admin->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="px-4 py-2 rounded {{ $aktif ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white">{{ $aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
              </form>
          </div>
      </div>
  </x-modal>
</div>
