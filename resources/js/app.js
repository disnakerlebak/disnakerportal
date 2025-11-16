import "./bootstrap";
import "flowbite";
import { initFlowbite, Modal } from "flowbite";

// Integrasi Livewire v3 via ESM agar tidak ada duplikasi Alpine
import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

window.Alpine = Alpine;
window.FlowbiteModal = Modal;

document.addEventListener("DOMContentLoaded", () => {
    initFlowbite();
});

Livewire.start();
