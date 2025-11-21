(() => {
    console.info("[GlobalModalManager] initialized");

    /* ============================================================
     *  UTILITAS GLOBAL
     * ============================================================ */
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

    /* ============================================================
     *  EVENT: modal:open
     * ============================================================ */
    window.addEventListener("modal:open", (event) => {
        const id = resolveId(event.detail);
        if (!id) {
            console.warn("[GlobalModalManager] Missing ID in modal:open");
            return;
        }
        toggleModal(id, true);
    });

    /* ============================================================
     *  EVENT: modal:close
     * ============================================================ */
    window.addEventListener("modal:close", (event) => {
        const id = resolveId(event.detail);
        if (!id) {
            console.warn("[GlobalModalManager] Missing ID in modal:close");
            return;
        }
        toggleModal(id, false);
    });

    /* ============================================================
     *  EVENT: timeline:open  (VERSI BARU)
     * ============================================================ */
    window.addEventListener("timeline:open", (event) => {
        const detail = event.detail;
        if (!detail?.id) return;

        const modal = document.getElementById(detail.id);
        if (!modal) return;

        const alpine = Alpine?.$data ? Alpine.$data(modal) : null;
        if (alpine?.load) alpine.load(detail);
    });

    /* ============================================================
     *  ESC → TUTUP SEMUA GLOBAL MODAL
     * ============================================================ */
    window.addEventListener("keydown", (e) => {
        if (e.key !== "Escape") return;

        document
            .querySelectorAll("[data-global-modal]")
            .forEach((el) => el.classList.add("hidden"));
    });

    /* ============================================================
     *  TIMELINE CONTROLLER (BARU & CLEAN)
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

            /* ---------- LOAD DATA TIMELINE DARI SERVER ---------- */
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

                        if (!this.items.length) {
                            this.html = "<p class='text-gray-400'>Belum ada riwayat.</p>";
                        } else {
                            this.html = "";
                        }
                    })
                    .catch((err) => {
                        this.items = [];
                        this.html = `<p class='text-red-400'>Gagal memuat riwayat: ${err?.message || 'Error'}</p>`;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            /* ---------- TUTUP MODAL ---------- */
            close() {
                this.open = false;
            },
        };
    };
})();
