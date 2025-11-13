@props(['admin'])

<div x-data="dropdownMenu()" x-id="['action-menu']" class="relative inline-block text-left">
  <button @click="toggle($event)" type="button"
          class="inline-flex justify-center w-10 h-9 items-center rounded-md border border-slate-700 bg-slate-800 text-white hover:bg-slate-700 focus:outline-none"
          aria-haspopup="true" aria-expanded="true">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm2 2a2 2 0 100-4 2 2 0 000 4z" />
    </svg>
  </button>

  <template x-teleport="body">
  <div x-cloak x-show="open" @click.away="close()" @keydown.escape.window="close()"
       x-transition:enter="transition ease-out duration-150"
       x-transition:enter-start="opacity-0 transform scale-95"
       x-transition:enter-end="opacity-100 transform scale-100"
       x-transition:leave="transition ease-in duration-100"
       x-transition:leave-start="opacity-100 transform scale-100"
       x-transition:leave-end="opacity-0 transform scale-95"
       :class="dropUp ? 'origin-bottom-right' : 'origin-top-right'"
       class="fixed z-[70] w-56 rounded-lg border border-slate-800 bg-slate-900 shadow-lg ring-1 ring-indigo-500/10 divide-y divide-slate-800"
       :style="style + (dropUp ? ';transform: translateY(-100%)' : '')">
    <div class="py-1 text-sm text-slate-200">
      <button type="button" @click="$dispatch('open-modal-edit-admin-{{ $admin->id }}'); open=false"
              class="w-full text-left px-4 py-2 text-blue-400 hover:bg-blue-700/20 flex items-center gap-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 20h4l10.5-10.5a2.5 2.5 0 00-3.536-3.536L4 16v4z" />
        </svg>
        Edit
      </button>

      <button type="button" @click="$dispatch('open-modal', 'confirm-toggle-{{ $admin->id }}'); open=false"
              class="w-full text-left px-4 py-2 flex items-center gap-2 transition {{ $admin->status === 'active' ? 'text-orange-300 hover:bg-orange-700/20' : 'text-green-400 hover:bg-green-700/20' }}">
        @if($admin->status === 'active')
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/></svg>
          Nonaktifkan
        @else
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
          Aktifkan
        @endif
      </button>

      <button type="button" @click="$dispatch('open-modal', 'confirm-delete-{{ $admin->id }}'); open=false" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-700/20 flex items-center gap-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        Hapus
      </button>
    </div>
  </div>
  </template>

  <!-- Confirm Delete Modal menggunakan komponen -->
  <x-modal name="confirm-delete-{{ $admin->id }}" maxWidth="md">
      <div class="px-6 py-5">
          <h3 class="text-lg font-semibold text-slate-100 mb-2">Konfirmasi Hapus</h3>
          <p class="text-slate-300 mb-4">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
          <div class="flex justify-end gap-2 pt-3">
              <button @click="$dispatch('close-modal', 'confirm-delete-{{ $admin->id }}')" class="px-4 py-2 rounded border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">Batal</button>
              <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
              </form>
          </div>
      </div>
  </x-modal>

  <!-- Confirm Toggle Modal -->
  <x-modal name="confirm-toggle-{{ $admin->id }}" maxWidth="md">
      <div class="px-6 py-5">
          @php $aktif = $admin->status === 'active'; @endphp
          <h3 class="text-lg font-semibold text-slate-100 mb-2">Konfirmasi {{ $aktif ? 'Nonaktifkan' : 'Aktifkan' }}</h3>
          <p class="text-slate-300 mb-4">Anda akan {{ $aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} admin <b>{{ $admin->name }}</b>. Lanjutkan?</p>
          <div class="flex justify-end gap-2 pt-3">
              <button @click="$dispatch('close-modal', 'confirm-toggle-{{ $admin->id }}')" class="px-4 py-2 rounded border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">Batal</button>
              <form action="{{ route('admin.manage.toggle', $admin->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="px-4 py-2 rounded {{ $aktif ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white">{{ $aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
              </form>
          </div>
      </div>
  </x-modal>
</div>
