(() => {
    console.info("[GlobalModalManager] Modern modal manager initialized");

    /* ============================================================
     *  UTILITAS GLOBAL UNTUK <x-modal>
     * ============================================================ */

    const resolveId = (detail) => {
        if (!detail) return null;
        if (typeof detail === "string") return detail;
        if (typeof detail === "object") {
            return detail.id || detail.modalId || null;
        }
        return null;
    };

    const getModal = (id) => document.getElementById(id);
    const getPanel = (modal) => modal?.querySelector(".modal-panel");
    const isGlobalModal = (el) => !!el?.hasAttribute("data-global-modal");

    const openModal = (id) => {
        const modal = getModal(id);
        if (!modal) return;

        const panel = getPanel(modal);
        modal.classList.remove("hidden");

        if (panel) {
            panel.classList.add("opacity-0", "scale-95");
            setTimeout(() => {
                panel.classList.remove("opacity-0", "scale-95");
                panel.classList.add("opacity-100", "scale-100");
            }, 10);
        }
    };

    // Helper global untuk dipakai di mana saja
    window.modalService = {
        open(id) {
            if (!id) return;
            window.dispatchEvent(new CustomEvent("modal:open", { detail: { id } }));
        },
        close(id) {
            if (!id) return;
            window.dispatchEvent(new CustomEvent("modal:close", { detail: { id } }));
        },
    };
    window.openModal = window.modalService.open;
    window.closeModal = window.modalService.close;

    const closeModal = (id) => {
        const modal = getModal(id);
        if (!modal) return;

        const panel = getPanel(modal);

        if (panel) {
            panel.classList.add("opacity-0", "scale-95");
            panel.classList.remove("opacity-100", "scale-100");

            setTimeout(() => modal.classList.add("hidden"), 200);
        } else {
            modal.classList.add("hidden");
        }
    };

    const closeAllModals = () => {
        document
            .querySelectorAll("[data-global-modal]")
            .forEach((m) => m.classList.add("hidden"));
    };

    /* ============================================================
     *  EVENT GLOBAL UNTUK <x-modal>
     * ============================================================ */

    const openEvents = ["open-modal", "modal:open"];
    const closeEvents = ["close-modal", "modal:close"];

    openEvents.forEach((ev) => {
        window.addEventListener(ev, (e) => {
            const id = resolveId(e.detail);
            if (!id) return;
            openModal(id);
        });
    });

    closeEvents.forEach((ev) => {
        window.addEventListener(ev, (e) => {
            const id = resolveId(e.detail);
            if (!id) return;
            closeModal(id);
        });
    });

    /* ============================================================
     *  CLICK BACKDROP
     * ============================================================ */

    document.addEventListener("click", (e) => {
        // Hanya tutup jika klik tepat pada backdrop milik <x-modal> global
        const backdrop = e.target.closest(".modal-backdrop");
        if (!backdrop || !isGlobalModal(backdrop)) return;
        if (e.target !== backdrop) return;

        const modalId = backdrop.id;
        if (!modalId) return;

        closeModal(modalId);
    });

    /* ============================================================
     *  BUTTON: data-close-modal="id"
     * ============================================================ */

    document.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-close-modal]");
        if (!btn) return;

        const id = btn.getAttribute("data-close-modal");
        if (!id) return;

        closeModal(id);
    });

    /* ============================================================
     *  ESC → TUTUP SEMUA MODAL
     * ============================================================ */

    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeAllModals();
        }
    });

    /* ============================================================
     *  TIMELINE CONTROLLER (TIDAK DIUBAH SAMA SEKALI)
     * ============================================================ */

    const actionColors = {
        submit: "bg-blue-400",
        repair_submit: "bg-yellow-400",
        extend_submit: "bg-purple-400",
        approve: "bg-green-400",
        reject: "bg-red-400",
        revision: "bg-amber-400",
        printed: "bg-sky-400",
        picked_up: "bg-indigo-400",
        archived: "bg-gray-500",
    };

    const timelineColor = (action) => actionColors[action] || "bg-gray-500";

    const timelineIcon = (action) => {
        const icon = {
            submit: "M4 4v6h6M20 20v-6h-6",
            repair_submit: "M3 12h18M12 3v18",
            extend_submit: "M6 9V3h12v6",
            approve: "M5 13l4 4L19 7",
            reject: "M6 6l12 12M6 18L18 6",
            revision: "M4 4h16v16H4z",
            printed: "M6 9V3h12v6",
            picked_up: "M3 12h18",
            archived: "M4 4h16v4H4z",
        }[action];

        if (!icon) {
            return `<path d="M12 4v16m8-8H4" />`;
        }

        return `<path d="${icon}" />`;
    };

    const timelineBadge = (action) => {
        if (action === "submit") return "BARU";
        if (action === "repair_submit") return "PERBAIKAN";
        if (action === "extend_submit") return "PERPANJANGAN";
        return "";
    };

    window.timelineModal = function (id) {
        return {
            html: "",
            loading: false,
            headerTitle: "",
            headerSubtitle: "",
            name: "",
            email: "",
            nomorAk1: "",
            open: false,
            items: [],

            load(detail) {
                const baseTitle = detail?.title ?? "Riwayat AK1";
                this.name = detail?.name ?? "";
                this.email = detail?.email ?? "";
                this.nomorAk1 = detail?.nomor_ak1 ?? detail?.nomor ?? "";

                this.headerTitle = this.name
                    ? `${baseTitle} — ${this.name}`
                    : baseTitle;

                const subtitleParts = [];
                if (this.email) subtitleParts.push(this.email);
                if (this.nomorAk1) subtitleParts.push(`No. AK1: ${this.nomorAk1}`);
                this.headerSubtitle = subtitleParts.join(" · ");

                this.open = true;

                const url = detail?.url;
                if (!url) {
                    this.items = [];
                    this.html = "<p class='text-red-400'>URL tidak tersedia.</p>";
                    return;
                }

                this.loading = true;
                this.items = [];
                this.html = "";

                fetch(url, { headers: { Accept: "application/json" } })
                    .then((res) => res.json())
                    .then((data) => {
                        const logs = Array.isArray(data?.logs) ? data.logs : [];
                        logs.sort((a, b) => {
                            const da = a.timestamp ?? Date.parse(a.created_at);
                            const db = b.timestamp ?? Date.parse(b.created_at);
                            return da - db;
                        });

                        this.items = logs.map((log) => {
                            const badge = timelineBadge(log.action);
                            return {
                                title: log.action_label || log.action || "Aktivitas",
                                badge: badge || null,
                                color: timelineColor(log.action),
                                icon: timelineIcon(log.action),
                                date: log.created_at || "",
                                from: log.from_status || "—",
                                to: log.to_status || "—",
                                actor: log.actor || "Sistem",
                                role: log.actor_role || "",
                                nomor_ak1: log.nomor_ak1 || "",
                                notes: log.notes || "",
                            };
                        });

                        this.html = this.items.length
                            ? ""
                            : "<p class='text-gray-400'>Belum ada riwayat.</p>";
                    })
                    .catch((err) => {
                        this.items = [];
                        this.html = `<p class='text-red-400'>Gagal memuat riwayat: ${
                            err?.message || "Error"
                        }</p>`;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            close() {
                this.open = false;
            },
        };
    };
})();
