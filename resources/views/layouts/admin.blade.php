<!DOCTYPE html>
<html lang="id"
      x-data="{
        darkMode: localStorage.getItem('theme') === 'dark',
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true'
      }"
      x-bind:class="darkMode ? 'dark' : ''"
      x-init="
        $watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'));
        $watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val));
      "
      class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Disnaker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="flex h-screen bg-gray-900 text-gray-200">

    <!-- ===== Sidebar ===== -->
 <!-- ===== Sidebar ===== -->
<aside
  x-bind:class="{
      'w-64': !sidebarCollapsed,
      'w-20': sidebarCollapsed,
      '-translate-x-full': !sidebarOpen && window.innerWidth < 768
  }"
  class="fixed z-20 inset-y-0 left-0 transition-all duration-200 bg-gray-800 border-r border-gray-700 overflow-y-auto md:translate-x-0 flex flex-col"
>
  <style>
    [x-cloak]{display:none!important}
    html, body {
      overflow-x: hidden;
  }

  aside {
      overflow-x: hidden;
  }
    /* Tooltip kecil saat collapse */
    .tooltip-text{
      position:absolute;visibility:hidden;background:rgba(30,41,59,.95);color:#fff;
      font-size:11px;border-radius:6px;padding:3px 6px;left:130%;top:50%;transform:translateY(-50%);
      opacity:0;transition:opacity .2s;white-space:nowrap
    }
    .tooltip:hover .tooltip-text{visibility:visible;opacity:1}
  </style>

  <!-- Header -->
  <div class="flex items-center justify-between p-4 border-b border-gray-700">
    <span x-show="!sidebarCollapsed" class="text-base font-semibold text-blue-400 truncate">⚙️ Disnaker Admin</span>
    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-1 text-gray-400 hover:text-white transition">
      <template x-if="!sidebarCollapsed"><span>◀️</span></template>
      <template x-if="sidebarCollapsed"><span>▶️</span></template>
    </button>
  </div>

  <!-- Menu -->
  <nav class="p-3 flex-1 overflow-y-auto" x-bind:class="sidebarCollapsed ? 'space-y-0.5' : 'space-y-1'">

    <!-- ITEM: helper class pattern (Flowbite style) -->
    @php
      $linkBase  = 'group relative flex items-center h-10 px-3 rounded-md transition hover:bg-gray-700';
      $iconBase  = 'w-5 h-5 block flex-shrink-0 text-gray-300 group-hover:text-white';   // <-- block penting
      $labelBase = 'ml-3 text-[13px] leading-none font-medium truncate';
    @endphp

    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
       class="{{ $linkBase }} {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-blue-400' : 'text-gray-300' }} tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
      </svg>
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Dashboard</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Dashboard</span>
    </a>

    <!-- Section: AK1 -->
    <div x-cloak x-show="!sidebarCollapsed"
         class="mt-3 mb-1 text-[10px] font-semibold text-gray-400 tracking-wider uppercase">AK1 Management</div>

    <!-- Verifikasi AK1 -->
    <a href="{{ route('admin.ak1.index') }}"
       class="{{ $linkBase }} {{ request()->routeIs('admin.ak1.*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300' }} tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
      </svg>
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Verifikasi AK1</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Verifikasi AK1</span>
    </a>

    <!-- Alasan Penolakan -->
    <a href="{{ route('admin.rejection-reasons.index') }}"
   class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 transition tooltip
          {{ request()->routeIs('admin.rejection-reasons.*') ? 'bg-gray-700 text-blue-400' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
      </svg>
            <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Alasan Penolakan</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Alasan Penolakan</span>
    </a>

    <!-- Section: USER -->
    <div x-cloak x-show="!sidebarCollapsed"
         class="mt-3 mb-1 text-[10px] font-semibold text-gray-400 tracking-wider uppercase">User Management</div>

    <!-- Kelola Admin -->
    <a href="#"
       class="{{ $linkBase }} text-gray-400 opacity-60 cursor-not-allowed tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
      </svg> 
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Kelola Admin</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Kelola Admin</span>
    </a>

    <!-- Verifikasi Perusahaan -->
    <a href="#"
       class="{{ $linkBase }} text-gray-400 opacity-60 cursor-not-allowed tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819"/>
      </svg>
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Verifikasi Perusahaan</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Verifikasi Perusahaan</span>
    </a>

    <!-- Section: INFO -->
    <div x-cloak x-show="!sidebarCollapsed"
         class="mt-3 mb-1 text-[10px] font-semibold text-gray-400 tracking-wider uppercase">Informasi & Aktivitas</div>

    <!-- Pengumuman -->
    <a href="#"
       class="{{ $linkBase }} text-gray-400 opacity-60 cursor-not-allowed tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/>
      </svg>
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Pengumuman</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Pengumuman</span>
    </a>

    <!-- Log Aktivitas -->
    <a href="#"
       class="{{ $linkBase }} text-gray-4 00 opacity-60 cursor-not-allowed tooltip">
      <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconBase }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span x-cloak x-show="!sidebarCollapsed" class="{{ $labelBase }}">Log Aktivitas</span>
      <span class="tooltip-text" x-show="sidebarCollapsed">Log Aktivitas</span>
    </a>
  </nav>

  <!-- Logout -->
  <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-gray-700">
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


    <!-- ===== Main Content ===== -->
    <div class="flex-1 flex flex-col transition-all duration-200"
         x-bind:class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'">
        <header class="flex justify-between items-center px-6 py-3 border-b border-gray-700 bg-gray-800/50 backdrop-blur">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                    ☰
                </button>
                <h1 class="font-semibold text-lg">@yield('title')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-gray-700">
                    <template x-if="darkMode"><span>☀️</span></template>
                    <template x-if="!darkMode"><span>🌙</span></template>
                </button>
                <div class="text-sm text-gray-400">{{ Auth::user()->name ?? 'Super Admin Disnaker' }}</div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">@yield('content')</main>
    </div>
    @stack('scripts')
    <!-- ===== Toastify (Global Notification) ===== -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    @if (session('success'))
        Toastify({
            text: "{{ session('success') }}",
            duration: 3500,
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#16a34a", // Tailwind green-600
            stopOnFocus: true
        }).showToast();
    @endif

    @if (session('error'))
        Toastify({
            text: "{{ session('error') }}",
            duration: 3500,
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#dc2626", // Tailwind red-600
            stopOnFocus: true
        }).showToast();
    @endif

    @if (session('warning'))
        Toastify({
            text: "{{ session('warning') }}",
            duration: 3500,
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#facc15", // yellow-400
            stopOnFocus: true
        }).showToast();
    @endif

    @if (session('info'))
        Toastify({
            text: "{{ session('info') }}",
            duration: 3500,
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#2563eb", // blue-600
            stopOnFocus: true
        }).showToast();
    @endif
});
</script>


</body>
</html>
