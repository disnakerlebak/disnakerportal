<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> | Disnaker Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<?php
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
?>
<body class="min-h-screen bg-slate-950 text-slate-100 transition-colors duration-300 ease-out">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        <!-- Mobile top bar -->
        <header class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between border-b border-slate-800 bg-slate-900/95 px-4 py-3 shadow-sm backdrop-blur lg:hidden">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-700/70 text-slate-100 shadow-sm transition hover:bg-slate-800" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6.75h16.5m-16.5 6.75h16.5"/></svg>
                </button>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-400">Disnaker Portal</p>
                    <p class="text-sm font-medium text-slate-50"><?php echo e(auth()->user()->name ?? 'Pencaker'); ?></p>
                </div>
            </div>
            <button type="button" class="theme-toggle inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-700/70 text-slate-100 transition hover:bg-slate-800" aria-label="Toggle tema">
                <span class="text-lg">🌙</span>
            </button>
        </header>

        <!-- Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-black/60 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 transform border-r border-slate-800/80 bg-slate-900/95 shadow-xl backdrop-blur transition-transform duration-200 ease-out lg:translate-x-0" :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-slate-800 px-4 py-4 lg:hidden">
                    <p class="text-sm font-semibold text-slate-100">Disnaker Portal</p>
                    <button type="button" @click="sidebarOpen = false" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-300 transition hover:bg-slate-800" aria-label="Tutup menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="hidden border-b border-slate-800 px-4 py-6 lg:block">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-400">Disnaker Portal</p>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-sm text-slate-400">Selamat datang,</p>
                            <p class="text-base font-semibold text-white"><?php echo e(auth()->user()->name ?? 'Pencaker'); ?></p>
                        </div>
                        <button type="button" class="theme-toggle inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-700 text-slate-200 transition hover:bg-slate-800" aria-label="Toggle tema">
                            <span class="text-lg">🌙</span>
                        </button>
                    </div>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6 text-sm font-medium text-slate-300">
                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['home'],'label' => 'Beranda','href' => route('pencaker.dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['home']),'label' => 'Beranda','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('pencaker.dashboard'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['user'],'label' => 'Profil Pencaker','href' => route('pencaker.profile')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['user']),'label' => 'Profil Pencaker','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('pencaker.profile'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>

                    <?php
                        $ak1Expanded = request()->routeIs('pencaker.card.*');
                        $ak1ParentClasses = $ak1Expanded
                            ? 'bg-indigo-600 text-white shadow-sm'
                            : 'text-slate-300 hover:bg-slate-800';
                        $ak1SubLink = function (string $label, string $href, bool $active = false) {
                            $base = 'block rounded-md px-3 py-2 text-sm transition-colors';
                            $state = $active
                                ? 'bg-indigo-500/20 text-indigo-200'
                                : 'text-slate-400 hover:text-white hover:bg-slate-800/70';
                            return "<a href=\"{$href}\" class=\"{$base} {$state}\">{$label}</a>";
                        };
                    ?>

                    <div x-data="{ open: <?php echo e($ak1Expanded ? 'true' : 'false'); ?> }" class="space-y-1">
                        <button type="button"
                                class="flex w-full items-center justify-between gap-3 rounded-md px-3 py-2 transition-colors duration-150 <?php echo e($ak1ParentClasses); ?>"
                                @click="open = !open">
                            <span class="flex items-center gap-3">
                                <span class="flex h-5 w-5 items-center justify-center text-current"><?php echo $sidebarIcons['id-card']; ?></span>
                                <span class="truncate">Kartu AK1</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="space-y-1 pl-11" x-cloak>
                            <?php echo $ak1SubLink('Pengajuan Kartu AK1', route('pencaker.card.index'), request()->routeIs('pencaker.card.index')); ?>

                            <?php echo $ak1SubLink('Perbaikan AK1', route('pencaker.card.repair'), request()->routeIs('pencaker.card.repair')); ?>

                            <?php echo $ak1SubLink('Perpanjangan AK1', route('pencaker.card.renewal'), request()->routeIs('pencaker.card.renewal')); ?>

                        </div>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['briefcase'],'label' => 'Lowongan Kerja','href' => '#']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['briefcase']),'label' => 'Lowongan Kerja','href' => '#']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['history'],'label' => 'Riwayat Lamaran','href' => '#']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['history']),'label' => 'Riwayat Lamaran','href' => '#']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['settings'],'label' => 'Pengaturan','href' => route('profile.edit')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['settings']),'label' => 'Pengaturan','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
                </nav>

                <div class="border-t border-slate-800 px-4 py-6">
                    <?php if (isset($component)) { $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-link','data' => ['icon' => $sidebarIcons['logout'],'label' => 'Keluar','href' => route('logout'),'method' => 'POST']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarIcons['logout']),'label' => 'Keluar','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('logout')),'method' => 'POST']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $attributes = $__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__attributesOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300)): ?>
<?php $component = $__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300; ?>
<?php unset($__componentOriginal3d3185cbc95d2b4d3b41182ae7d7a300); ?>
<?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- Main content wrapper -->
        <div class="flex min-h-screen w-full flex-col bg-slate-950 pt-16 transition-colors duration-300 ease-out lg:ml-64 lg:pt-0">
            <main class="flex-1 px-4 pb-10 sm:px-6 lg:px-10">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        <?php if(session('success')): ?>
            Toastify({
                text: <?php echo json_encode(session('success')); ?>,
                duration: 3500,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#16a34a",
                stopOnFocus: true
            }).showToast();
        <?php endif; ?>

        <?php if(session('error')): ?>
            Toastify({
                text: <?php echo json_encode(session('error')); ?>,
                duration: 3500,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#dc2626",
                stopOnFocus: true
            }).showToast();
        <?php endif; ?>

        <?php if(session('warning')): ?>
            Toastify({
                text: <?php echo json_encode(session('warning')); ?>,
                duration: 3500,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#f59e0b",
                stopOnFocus: true
            }).showToast();
        <?php endif; ?>

        <?php if(session('info')): ?>
            Toastify({
                text: <?php echo json_encode(session('info')); ?>,
                duration: 3500,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#2563eb",
                stopOnFocus: true
            }).showToast();
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                Toastify({
                    text: <?php echo json_encode($message); ?>,
                    duration: 4000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#f97316",
                    stopOnFocus: true
                }).showToast();
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\disnakerportal\resources\views/layouts/pencaker.blade.php ENDPATH**/ ?>