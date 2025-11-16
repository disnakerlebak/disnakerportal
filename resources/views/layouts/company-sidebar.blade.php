<!DOCTYPE html>
<html lang="id"
      x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('companySidebarCollapsed') === 'true'
      }"
      x-init="
        $watch('sidebarCollapsed', val => localStorage.setItem('companySidebarCollapsed', val));
      "
      class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Perusahaan - DisnakerPortal</title>
    <script>
      (function () {
        try {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        } catch (e) {}
      })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>

<body class="flex h-screen bg-slate-950 text-slate-100 overflow-x-hidden">
<!-- Overlay mobile -->
<div x-show="sidebarOpen" x-transition.opacity
     class="fixed inset-0 z-30 bg-black/40 md:hidden"
     @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col overflow-y-hidden bg-slate-900/95 shadow-xl transition-[width,transform] duration-200 md:translate-x-0"
    :class="[
      sidebarCollapsed ? 'w-20' : 'w-64',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full'
    ]"
>
    <style>
        [x-cloak]{display:none!important}
        aside{overflow-x:hidden}
        .tooltip-text{
            position:absolute;visibility:hidden;background:rgba(30,41,59,.95);color:#fff;
            font-size:11px;border-radius:6px;padding:3px 6px;left:130%;top:50%;transform:translateY(-50%);
            opacity:0;transition:opacity .2s;white-space:nowrap
        }
        .tooltip:hover .tooltip-text{visibility:visible;opacity:1}
    </style>

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-900 px-5 py-5">
        <span x-show="!sidebarCollapsed" class="text-base font-semibold text-blue-400 truncate">
            Admin Perusahaan
        </span>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-1 text-slate-400 hover:text-white transition">
            <template x-if="!sidebarCollapsed"><span>◀️</span></template>
            <template x-if="sidebarCollapsed"><span>▶️</span></template>
        </button>
    </div>

    <!-- Menu -->
    <nav class="p-3 flex-1" :class="[sidebarCollapsed ? 'space-y-0.5 overflow-y-hidden' : 'space-y-1 overflow-y-auto']">
        @php
          $linkBase  = 'group relative flex items-center h-10 px-3 rounded-md transition-colors hover:bg-slate-800';
          $iconBase  = 'w-5 h-5 block flex-shrink-0 text-slate-300 group-hover:text-white';
          $labelBase = 'ml-3 text-[13px] leading-none font-medium truncate';
        @endphp

        <!-- Dashboard -->
        <a href="{{ route('company.dashboard') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.dashboard') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Dashboard</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Dashboard</span>
        </a>

        <!-- Profil Perusahaan -->
        <a href="{{ route('company.profile.show') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.profile.*') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip mt-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118A7.5 7.5 0 0112 15a7.5 7.5 0 017.5 5.118"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Profil Perusahaan</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Profil Perusahaan</span>
        </a>

        <!-- Section: Lowongan Kerja -->
        <div x-cloak x-show="!sidebarCollapsed"
             class="mt-3 mb-1 text-[10px] font-semibold text-slate-400 tracking-wider uppercase">
            Lowongan Kerja
        </div>

        <!-- Daftar Lowongan -->
        <a href="{{ route('company.jobs.index') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.jobs.index') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 10h16M4 14h10M4 18h6"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Daftar Lowongan</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Daftar Lowongan</span>
        </a>

        <!-- Tambah Lowongan -->
        <a href="{{ route('company.jobs.create') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.jobs.create') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Tambah Lowongan</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Tambah Lowongan</span>
        </a>

        <!-- Section: Pelamar -->
        <div x-cloak x-show="!sidebarCollapsed"
             class="mt-3 mb-1 text-[10px] font-semibold text-slate-400 tracking-wider uppercase">
            Pelamar
        </div>

        <!-- Semua Pelamar -->
        <a href="{{ route('company.applicants.index') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.applicants.index') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-1.5C22 16.57 19.314 15 16 15c-.597 0-1.176.053-1.732.152M9 20H2v-1.5C2 16.57 4.686 15 8 15c.597 0 1.176.053 1.732.152M16 11a4 4 0 10-8 0 4 4 0 008 0z"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Semua Pelamar</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Semua Pelamar</span>
        </a>

        <!-- Pelamar per Lowongan -->
        <a href="#"
           class="{{ $linkBase }} text-slate-300 tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h4l2 3h7a1 1 0 01.894.553l2 4A1 1 0 0120 12H7l-2-3-2 3v7H3"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Pelamar per Lowongan</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Pelamar per Lowongan</span>
        </a>

        <!-- Riwayat Proses Pelamar -->
        <a href="{{ route('company.applicants.history') }}"
           class="{{ $linkBase }} {{ request()->routeIs('company.applicants.history') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Riwayat Proses Pelamar</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Riwayat Proses Pelamar</span>
        </a>

        <!-- Section: Akun -->
        <div x-cloak x-show="!sidebarCollapsed"
             class="mt-3 mb-1 text-[10px] font-semibold text-slate-400 tracking-wider uppercase">
            Akun
        </div>

        <!-- Pengaturan Akun -->
        <a href="{{ route('profile.edit') }}"
           class="{{ $linkBase }} {{ request()->routeIs('profile.edit') ? 'bg-indigo-600 text-white shadow' : 'text-slate-300' }} tooltip">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.594 3.75a1.5 1.5 0 012.812 0l.276.832a1.5 1.5 0 001.423 1.02h.879a1.5 1.5 0 011.341.83l.442.884a1.5 1.5 0 00.982.78l.894.224A1.5 1.5 0 0120.25 12.75v1.06a1.5 1.5 0 01-1.092 1.448l-.894.224a1.5 1.5 0 00-.982.78l-.442.884a1.5 1.5 0 01-1.341.83h-.879a1.5 1.5 0 00-1.423 1.02l-.276.832a1.5 1.5 0 01-2.812 0l-.276-.832a1.5 1.5 0 00-1.423-1.02h-.879a1.5 1.5 0 01-1.341-.83l-.442-.884a1.5 1.5 0 00-.982-.78l-.894-.224A1.5 1.5 0 013.75 13.81v-1.06a1.5 1.5 0 011.092-1.448l.894-.224a1.5 1.5 0 00.982-.78l.442-.884a1.5 1.5 0 011.341-.83h.879a1.5 1.5 0 001.423-1.02l.276-.832z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Pengaturan Akun</span>
            <span class="tooltip-text" x-show="sidebarCollapsed">Pengaturan Akun</span>
        </a>
    </nav>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-slate-800">
        @csrf
        <button type="submit" class="group w-full {{ $linkBase }} bg-red-700/20 text-red-400 hover:bg-red-600/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a1 1 0 001 1h4a1 1 0 001-1v-1m-6-8V7a1 1 0 011-1h4a1 1 0 011 1v1"/>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Logout</span>
        </button>
    </form>
</aside>

<!-- Main content -->
<div class="flex-1 min-w-0 flex flex-col transition-all duration-200 bg-slate-950 border-l border-slate-800"
     x-bind:class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'">
    <header class="flex justify-between items-center px-4 py-4 border-b border-slate-800 bg-slate-900/60 backdrop-blur">
        <div class="flex items-center space-x-3">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg bg-slate-800 hover:bg-slate-700">
                ☰
            </button>
            <h1 class="font-semibold text-lg">@yield('title')</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto">
        @yield('content')
    </main>
</div>

<livewire:scripts />
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js" integrity="sha512-6ZFAHMRqkpS2NMVzYGzYMKA+Hitzzh3u/SYh1GcU0XAiHQmmKAiozoxg2Oe8ZpMazciDJNy8FCWyKPS5YW6A9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
