import "./bootstrap";
import Alpine from "alpinejs";
import "flowbite";
import { initFlowbite, Modal, Dropdown } from "flowbite";

// Init Alpine (needed untuk komponen x-*). Pastikan tidak double start saat Livewire ikut mem-boot.
window.Alpine = Alpine;
const startAlpine = () => {
    if (!window.Alpine) return;
    if (window.Alpine.__started) return;
    window.Alpine.start();
    window.Alpine.__started = true;
};

// Izinkan Livewire mengambil alih start Alpine, tapi tetap fallback jika halaman non-Livewire.
window.deferLoadingAlpine = (callback) => {
    window.addEventListener("livewire:load", () => callback());
};
window.deferLoadingAlpine(startAlpine);

// Bridge event modal Livewire -> window

// Modal manager (timeline + global modal events)
import "./modal-manager";

document.addEventListener("DOMContentLoaded", () => {
    // Fallback: jika Livewire tidak ada, tetap start Alpine.
    startAlpine();
    initFlowbite();
});

// ===== Custom Dropdown (fixed positioning untuk tabel) =====
(function () {
    const margin = 6;

    const closeAll = () => {
        document
            .querySelectorAll(".dropdown-menu")
            .forEach((menu) => menu.classList.add("hidden"));
    };

    document.addEventListener("click", (ev) => {
        const trigger = ev.target.closest("[data-dropdown-trigger]");
        const item = ev.target.closest("[data-trigger='dropdown-modal']");

        // Klik item -> tutup semua, lanjutkan ke modal Flowbite (data-modal-toggle)
        if (item) {
            closeAll();
            return;
        }

        // Klik di luar dropdown/trigger -> tutup
        if (!trigger) {
            if (!ev.target.closest(".dropdown-menu")) {
                closeAll();
            }
            return;
        }

        const id = trigger.getAttribute("data-dropdown-id");
        if (!id) return;
        const menu = document.getElementById(id);
        if (!menu) return;

        // Ukur ukuran asli meski menu masih tersembunyi
        const measure = () => {
            const wasHidden = menu.classList.contains("hidden");
            const previousVisibility = menu.style.visibility;
            if (wasHidden) {
                menu.classList.remove("hidden");
                menu.style.visibility = "hidden";
            }
            const width = menu.offsetWidth || menu.scrollWidth || 208;
            const height = menu.offsetHeight || menu.scrollHeight || 200;
            if (wasHidden) {
                menu.classList.add("hidden");
                menu.style.visibility = previousVisibility || "";
            }
            return { width, height };
        };

        const isOpen = !menu.classList.contains("hidden");
        closeAll();
        if (isOpen) return; // jika sudah terbuka, tinggal tutup

        // posisi fixed relative viewport + scroll
        const rect = trigger.getBoundingClientRect();
        const { width: menuWidth, height: menuHeight } = measure();
        let top = rect.bottom + window.scrollY + margin;
        let left = rect.right + window.scrollX - menuWidth;

        const maxLeft = window.scrollX + window.innerWidth - menuWidth - margin;
        if (left > maxLeft) left = maxLeft;
        const minLeft = window.scrollX + margin;
        if (left < minLeft) left = minLeft;

        // Jika terlalu bawah, up
        const viewportBottom = window.scrollY + window.innerHeight;
        if (top + menuHeight > viewportBottom) {
            top = rect.top + window.scrollY - menuHeight - margin;
        }

        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;
        menu.style.minWidth = `${Math.max(menuWidth, rect.width)}px`;
        menu.classList.remove("hidden");
    });

    window.addEventListener("scroll", closeAll, true);
    window.addEventListener("resize", closeAll);
})();

// Matikan modal engine bawaan Flowbite agar tidak override komponen modal kustom
document.addEventListener("DOMContentLoaded", () => {
    window.FlowbiteModal = Modal;
    window.FlowbiteDropdown = Dropdown;

    // Disable auto init modal dari Flowbite
    const disableAutoInit = () => {
        const modalToggles = document.querySelectorAll(
            "[data-modal-target], [data-modal-toggle], [data-modal-hide]"
        );
        modalToggles.forEach((el) => {
            el.removeAttribute("data-modal-target");
            el.removeAttribute("data-modal-toggle");
            el.removeAttribute("data-modal-hide");
        });
    };

    disableAutoInit();
});
