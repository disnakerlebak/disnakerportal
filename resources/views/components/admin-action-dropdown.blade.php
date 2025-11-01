@props(['admin'])

<div x-data="{ open:false, confirmOpen:false }" class="relative inline-block text-left">
  <button @click="open = !open" type="button"
          class="inline-flex justify-center w-10 h-9 items-center rounded-md bg-gray-700 text-white hover:bg-gray-600 focus:outline-none"
          aria-haspopup="true" aria-expanded="true">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm2 2a2 2 0 100-4 2 2 0 000 4z" />
    </svg>
  </button>

  <div x-cloak x-show="open" @click.outside="open=false"
       x-transition
       class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
    <div class="py-1 text-sm text-gray-200">
      <button type="button" @click="$dispatch('open-modal-edit-admin-{{ $admin->id }}'); open=false"
              class="w-full text-left px-4 py-2 hover:bg-gray-700">Edit</button>

      <form action="{{ route('admin.manage.toggle', $admin->id) }}" method="POST">
        @csrf
            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">
          {{ $admin->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>
      </form>

      <button type="button" @click="confirmOpen=true; open=false" class="w-full text-left px-4 py-2 text-red-300 hover:bg-gray-700">Hapus</button>
    </div>
  </div>

  <!-- Confirm Delete Modal -->
  <div x-cloak x-show="confirmOpen" x-transition.opacity class="fixed inset-0 z-40 flex items-center justify-center bg-black/60">
    <div @click.outside="confirmOpen=false" class="bg-gray-900 rounded-lg shadow-xl w-full max-w-md p-6 border border-gray-700">
      <h3 class="text-lg font-semibold text-gray-100 mb-2">Konfirmasi Hapus</h3>
      <p class="text-gray-300 mb-4">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="flex justify-end gap-2">
        <button @click="confirmOpen=false" class="px-4 py-2 rounded bg-gray-700 text-gray-100 hover:bg-gray-600">Batal</button>
        <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
