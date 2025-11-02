<!DOCTYPE html>
<html lang="id" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Disnaker Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    use Illuminate\Support\HtmlString;

    $sidebarIcons = [
        'home' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l9-9 9 9m-1.5 0v9.75a1.5 1.5 0 01-1.5 1.5h-3.75v-6a1.5 1.5 0 00-1.5-1.5h-3a1.5 1.5 0 00-1.5 1.5v6H6a1.5 1.5 0 01-1.5-1.5V12"/></svg>'),
        'user' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a8.25 8.25 0 0115 0"/></svg>'),
        'id-card' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><rect width="18" height="12" x="3" y="6" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10.5h3m-3 3h3M13.5 12h3"/></svg>'),
        'briefcase' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5V6a2.25 2.25 0 012.25-2.25h1.5A2.25 2.25 0 0115 6v1.5m-9 0A2.25 2.25 0 003.75 9.75v6.75A2.25 2.25 0 006 18.75h12a2.25 2.25 0 002.25-2.25V9.75A2.25 2.25 0 0018 7.5H6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/></svg>'),
        'history' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75V12a8.25 8.25 0 008.25 8.25c4.56 0 8.25-3.69 8.25-8.25S16.56 3.75 12 3.75A8.22 8.22 0 006.75 5.6"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v4.5l3 1.5"/></svg>'),
        'settings' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.75a1.5 1.5 0 012.812 0l.276.832a1.5 1.5 0 001.423 1.02h.879a1.5 1.5 0 011.341.83l.442.884a1.5 1.5 0 00.982.78l.894.224a1.5 1.5 0 011.092 1.448v1.06a1.5 1.5 0 01-1.092 1.448l-.894.224a1.5 1.5 0 00-.982.78l-.442.884a1.5 1.5 0 01-1.341.83h-.879a1.5 1.5 0 00-1.423 1.02l-.276.832a1.5 1.5 0 01-2.812 0l-.276-.832a1.5 1.5 0 00-1.423-1.02h-.879a1.5 1.5 0 01-1.341-.83l-.442-.884a1.5 1.5 0 00-.982-.78l-.894-.224A1.5 1.5 0 013.75 12.75v-1.06a1.5 1.5 0 011.092-1.448l.894-.224a1.5 1.5 0 00.982-.78l.442-.884a1.5 1.5 0 011.341-.83h.879a1.5 1.5 0 001.423-1.02l.276-.832z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'),
        'logout' => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15"/><path stroke-linecap="round" stroke-linejoin="round" d="M18 12l3-3m0 0l-3-3m3 3h-9"/></svg>'),
    ];
@endphp
<body class="min-h-screen bg-gray-100 text-gray-900 transition-colors duration-300 ease-out dark:bg-gray-950 dark:text-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        <!-- Mobile top bar -->
        <header class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between border-b border-gray-200 bg-white/95 px-4 py-3 shadow-sm dark:border-gray-800 dark:bg-gray-900/95 lg:hidden">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-700 shadow-sm transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6.75h16.5m-16.5 6.75h16.5"/></svg>
                </button>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">Disnaker Portal</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ auth()->user()->name ?? 'Pencaker' }}</p>
                </div>
            </div>
            <button type="button" class="theme-toggle inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800" aria-label="Toggle tema">
                <span class="text-lg">🌙</span>
            </button>
        </header>

        <!-- Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-black/60 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 transform border-r border-gray-200/80 bg-white/95 backdrop-blur transition-transform duration-200 ease-out dark:border-gray-800/70 dark:bg-gray-900/90 lg:translate-x-0" :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-gray-200/70 px-4 py-4 dark:border-gray-800/70 lg:hidden">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Disnaker Portal</p>
                    <button type="button" @click="sidebarOpen = false" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Tutup menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="hidden border-b border-gray-200/70 px-4 py-6 dark:border-gray-800/70 lg:block">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">Disnaker Portal</p>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang,</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Pencaker' }}</p>
                        </div>
                        <button type="button" class="theme-toggle inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800" aria-label="Toggle tema">
                            <span class="text-lg">🌙</span>
                        </button>
                    </div>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6 text-sm font-medium">
                    <x-sidebar-link :icon="$sidebarIcons['home']" label="Beranda" :href="route('pencaker.dashboard')" />
                    <x-sidebar-link :icon="$sidebarIcons['user']" label="Profil Pencaker" :href="route('pencaker.profile')" />
                    <x-sidebar-link :icon="$sidebarIcons['id-card']" label="Kartu AK1" :href="route('pencaker.card.index')" />
                    <x-sidebar-link :icon="$sidebarIcons['briefcase']" label="Lowongan Kerja" href="#" />
                    <x-sidebar-link :icon="$sidebarIcons['history']" label="Riwayat Lamaran" href="#" />
                    <x-sidebar-link :icon="$sidebarIcons['settings']" label="Pengaturan" :href="route('profile.edit')" />
                </nav>

                <div class="border-t border-gray-200/70 px-4 py-6 dark:border-gray-800/70">
                    <x-sidebar-link :icon="$sidebarIcons['logout']" label="Keluar" :href="route('logout')" method="POST" />
                </div>
            </div>
        </aside>

        <!-- Main content wrapper -->
        <div class="flex min-h-screen w-full flex-col bg-gray-100 pt-16 transition-colors duration-300 ease-out dark:bg-gray-950 lg:ml-64 lg:pt-0">
            <main class="flex-1 px-4 pb-10 sm:px-6 lg:px-10">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    const toggleButtons = document.querySelectorAll('.theme-toggle');
    const html = document.documentElement;
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
      html.classList.add(storedTheme);
    }

    const syncIcon = () => {
      toggleButtons.forEach(btn => {
        btn.innerHTML = html.classList.contains('dark')
          ? '<span class="text-lg">☀️</span>'
          : '<span class="text-lg">🌙</span>';
      });
    };

    const toggleTheme = () => {
      if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', '');
      } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      }
      syncIcon();
    };

    syncIcon();
    toggleButtons.forEach(btn => btn.addEventListener('click', toggleTheme));
    </script>
    @stack('scripts')
</body>
</html>
