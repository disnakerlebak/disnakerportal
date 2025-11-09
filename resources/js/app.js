import './bootstrap';

// Integrasi Livewire v3 via ESM agar tidak ada duplikasi Alpine
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;

Livewire.start();
