<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      x-bind:class="darkMode ? 'dark' : ''" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Pencaker Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col">
    <header class="bg-gray-800 p-4 flex justify-between items-center border-b border-gray-700">
        <h1 class="text-lg font-semibold">@yield('title')</h1>
        <div class="flex items-center space-x-4">
            <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-gray-700">
                <template x-if="darkMode"><span>☀️</span></template>
                <template x-if="!darkMode"><span>🌙</span></template>
            </button>
            <span class="text-sm text-gray-400">{{ Auth::user()->name ?? 'Pencaker' }}</span>
        </div>
    </header>

    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-500 text-center p-3 border-t border-gray-700 text-sm">
        &copy; {{ date('Y') }} Dinas Tenaga Kerja Kabupaten Lebak
    </footer>
</body>
</html>
