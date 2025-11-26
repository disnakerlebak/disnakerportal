document.addEventListener("livewire:init", () => {
    const normalizeId = (payload) => {
        if (!payload) return null;

        // string langsung → id
        if (typeof payload === "string") return payload;

        // { id: "xxx" }
        if (typeof payload === "object" && payload.id) return payload.id;

        // { detail: { id: "xxx" } }
        if (payload?.detail?.id) return payload.detail.id;

        return null;
    };

    const forward = (type, detail) => {
        const id = normalizeId(detail);
        if (!id) return;
        window.dispatchEvent(new CustomEvent(type, { detail: { id } }));
    };

    /* =======================================================
     *  SUPPORT UNTUK SELURUH VERSI EVENT
     * ======================================================= */

    Livewire.on("modal:open", (payload) => forward("modal:open", payload));
    Livewire.on("modal:close", (payload) => forward("modal:close", payload));

    // VERSI LAIN YANG SERING DIPAKAI CODEx DAN FILE LAMA
    Livewire.on("open-modal", (payload) => forward("modal:open", payload));
    Livewire.on("close-modal", (payload) => forward("modal:close", payload));
});
