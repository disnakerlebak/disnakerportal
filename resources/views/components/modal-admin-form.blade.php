@props([
  'modalId',
  'title' => 'Tambah Admin',
  'action' => '#',
  'method' => 'POST',
  'admin' => null,
])

<div
  x-data="{ open: false }"
  x-on:open-modal-{{ $modalId }}.window="open = true"
  x-on:close-modal-{{ $modalId }}.window="open = false"
  x-show="open"
  x-cloak
  class="fixed inset-0 z-50 flex items-center justify-center"
  x-transition:enter="ease-out duration-200"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="ease-in duration-150"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
>
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/50" @click="$dispatch('close-modal-{{ $modalId }}')"></div>

  <!-- Panel -->
  <div class="relative w-full max-w-lg mx-4 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 text-slate-100 shadow-xl"
       x-transition:enter="ease-out duration-200"
       x-transition:enter-start="translate-y-4 sm:translate-y-0 sm:scale-95"
       x-transition:enter-end="translate-y-0 sm:scale-100"
       x-transition:leave="ease-in duration-150"
       x-transition:leave-start="translate-y-0 sm:scale-100"
       x-transition:leave-end="translate-y-4 sm:translate-y-0 sm:scale-95">
    <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-slate-100">{{ $title }}</h3>
      <button class="text-slate-400 hover:text-slate-200" @click="$dispatch('close-modal-{{ $modalId }}')">&times;</button>
    </div>

    <form method="POST" action="{{ $action }}" class="px-8 py-6 flex flex-col gap-5">
  @csrf
  @if (strtoupper($method) !== 'POST')
    @method($method)
  @endif
  <div class="flex flex-col gap-5"> 
  {{-- Role --}}
<x-input-select
    name="role"
    label="Role"
    :options="[
        'superadmin' => 'Super Admin',
        'admin_ak1' => 'Admin AK1',
        'perusahaan' => 'Perusahaan'
    ]"
    :selected="old('role', $admin->role ?? 'admin_ak1')"
    required
/>

  {{-- Nama --}}
  <div>
    <x-input-label for="name" :value="__('Nama')" />
    <x-text-input id="name" name="name" type="text"
                  class="mt-1 block w-full"
                  :value="old('name', $admin->name ?? '')"
                  required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
  </div>

  {{-- Email --}}
  <div>
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" name="email" type="email"
                  class="mt-1 block w-full"
                  :value="old('email', $admin->email ?? '')"
                  required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
  </div>

  {{-- Password --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input-password
        name="password"
        label="Password {{ $admin ? '(opsional)' : '' }}"
        :required="!$admin"
    />
    <x-input-password
        name="password_confirmation"
        label="Konfirmasi Password {{ $admin ? '(opsional)' : '' }}"
        :required="!$admin"
    />
</div>
  {{-- Tombol Aksi --}}
  <div class="flex justify-end gap-2 pt-4 mt-2 border-t border-slate-800">
    <button type="button" @click="$dispatch('close-modal-{{ $modalId }}')"
            class="px-4 py-2 rounded-md border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
      {{ __('Batal') }}
    </button>
    <x-primary-button>{{ __('Simpan') }}</x-primary-button>
  </div>
  </div>
</form>

  </div>
</div>
