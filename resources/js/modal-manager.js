(() => {
    console.info("[GlobalModalManager] initialized");

    // Fungsi buka/tutup modal
    const toggleModal = (id, show) => {
        if (!id) return;
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.toggle("hidden", !show);
    };

    const resolveId = (detail) => {
        if (!detail) return null;
        if (typeof detail === "string") return detail;
        return detail.id || null;
    };

    // =========================
    // EVENT: modal:open (global)
    // =========================
    window.addEventListener("modal:open", (event) => {
        const id = resolveId(event.detail);
        if (!id)
            return console.warn(
                "[GlobalModalManager] Missing ID in modal:open"
            );
        toggleModal(id, true);
    });

    // =========================
    // EVENT: modal:close (global)
    // =========================
    window.addEventListener("modal:close", (event) => {
        const id = resolveId(event.detail);
        if (!id)
            return console.warn(
                "[GlobalModalManager] Missing ID in modal:close"
            );
        toggleModal(id, false);
    });

    // =========================
    // ESC to close active modals
    // =========================
    window.addEventListener("keydown", (e) => {
        if (e.key !== "Escape") return;

        document
            .querySelectorAll("[data-global-modal]")
            .forEach((el) => el.classList.add("hidden"));
    });
})();
