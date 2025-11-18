@props([
  'modalId',
  'title' => 'Tambah Admin',
  'action' => '#',
  'method' => 'POST',
  'admin' => null,
])

@php $formId = $modalId . '-form'; @endphp

<x-modal :id="$modalId" size="lg" :title="$title">
  <form id="{{ $formId }}" method="POST" action="{{ $action }}" class="flex flex-col gap-5">
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
              'admin_ak1' => 'Admin AK1',
              'admin_laporan' => 'Admin Laporan',
              'superadmin' => 'Super Admin'
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
    </div>
  </form>

  <x-slot name="footer">
    <button type="button" data-close-modal="{{ $modalId }}"
            class="px-4 py-2 rounded-md border border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700">
      {{ __('Batal') }}
    </button>
    <x-primary-button form="{{ $formId }}">{{ __('Simpan') }}</x-primary-button>
  </x-slot>
</x-modal>
