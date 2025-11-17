@props(['id'])

<div class="relative">
    <!-- Trigger -->
    <button
        type="button"
        data-dropdown-trigger
        data-dropdown-id="{{ $id }}"
        class="flex items-center justify-center w-9 h-9 rounded-lg 
               bg-slate-800/60 hover:bg-slate-700 text-gray-300 
               focus:ring-2 focus:ring-indigo-500 transition">
        <svg class="w-5 h-5" fill="currentColor"><circle cx="10" cy="4" r="2"/><circle cx="10" cy="10" r="2"/><circle cx="10" cy="16" r="2"/></svg>
    </button>

    <!-- Dropdown menu (teleported via JS positioning) -->
    <div id="{{ $id }}"
         class="dropdown-menu hidden fixed z-[9999] w-52 rounded-xl shadow-xl 
                border border-slate-700 bg-slate-800"
         style="top:0;left:0;">
        <ul class="py-2 text-sm text-gray-200">
            {{ $slot }}
        </ul>
    </div>
</div>
