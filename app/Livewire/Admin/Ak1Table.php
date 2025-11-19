<?php

namespace App\Livewire\Admin;

use App\Models\CardApplication;
use App\Models\CardApplicationLog;
use App\Models\RejectionReason;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Ak1Table extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $type = '';

    #[Url(as: 'aktif', except: true)]
    public bool $activeOnly = true;

    public bool $archivedOnly = false;

    public array $archiveIds = [];
    public ?string $archiveLabel = null;
    public ?string $bulkErrorMessage = null;

    public function resetArchiveState(): void
    {
        $this->archiveIds = [];
        $this->archiveLabel = null;
    }

    /** @var array<int,int> */
    public array $selected = [];
    public bool $selectAll = false;
    protected array $currentPageIds = [];

    /** @var array<int, array{id:int,title:string}> */
    public array $rejectionReasons = [];

    /** @var array<int, string> */
    public array $statusOptions = [
        'Menunggu Verifikasi',
        'Menunggu Revisi Verifikasi',
        'Revisi Diminta',
        'Batal',
        'Disetujui',
        'Ditolak',
    ];

    /** @var array<string, string> */
    public array $typeTabs = [
        '' => 'Semua Tipe',
        'baru' => 'Pengajuan Baru',
        'perbaikan' => 'Perbaikan',
        'perpanjangan' => 'Perpanjangan',
    ];

    public function mount(): void
    {
        $this->activeOnly = !$this->archivedOnly;
        $this->rejectionReasons = RejectionReason::orderBy('title')
            ->get(['id', 'title'])
            ->map(fn (RejectionReason $reason) => [
                'id' => $reason->id,
                'title' => $reason->title,
            ])
            ->all();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingSelected(): void
    {
        $this->selectAll = false;
    }

    public function updatedSelectAll($value): void
    {
        $ids = $this->currentPageIds ?: [];
        $this->selected = $value ? $ids : [];
    }

    public function setType(?string $type = null): void
    {
        $this->type = $type ?? '';
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->status = '';
        $this->type = '';
        $this->activeOnly = true;
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return $this->search !== '' || $this->type !== '' || $this->status !== '';
    }

    public function render(): View
    {
        $apps = CardApplication::with(['user.jobseekerProfile', 'lastHandler.actor', 'logs.actor'])
            ->when($this->search, function (Builder $query) {
                $query->whereHas('user', function (Builder $userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->type !== '', function (Builder $query) {
                $query->whereRaw('LOWER(type) = ?', [strtolower($this->type)]);
            })
            ->when($this->activeOnly && !$this->archivedOnly, function (Builder $query) {
                $query->where('is_active', true);
            })
            ->when(!$this->archivedOnly, function (Builder $query) {
                $query->whereNull('archived_at');
            }, function (Builder $query) {
                $query->whereNotNull('archived_at');
            })
            ->latest('created_at')
            ->paginate(20);

        $this->currentPageIds = $apps->pluck('id')->all();

        return view('livewire.admin.ak1-table', [
            'apps' => $apps,
        ]);
    }

    public function noop(): void
    {
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        // Dengan wire:model.defer pada form, klik tombol ini akan
        // mengirim perubahan ke server dan memicu re-render + update URL
        $this->resetPage();
    }

    // ===== Bulk Actions =====
    public function bulkApprove(): void
    {
        DB::transaction(function () {
        $ids = array_map('intval', $this->selected);
        if (count($ids) < 2) {
            $this->bulkErrorMessage = 'Pilih lebih dari 1 pengajuan untuk aksi massal.';
            $this->dispatch('open-bulk-error', title: 'Pilih minimal 2 pengajuan', message: 'Pilih lebih dari 1 pengajuan untuk aksi massal.');
            return;
        }

        $allowedStatuses = ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'];
        $apps = CardApplication::with('parent')
            ->whereIn('id', $ids)
            ->orderBy('id') // pastikan urutan deterministik agar nomor berurutan
            ->lockForUpdate()
            ->get();

        if ($apps->count() !== count($ids) || $apps->contains(fn ($a) => !in_array($a->status, $allowedStatuses, true))) {
            $this->bulkErrorMessage = 'Pastikan semua pilihan berstatus Menunggu Verifikasi/Revisi dan belum Disetujui.';
            $this->dispatch('open-bulk-error', title: 'Tidak valid untuk disetujui', message: 'Pastikan semua pilihan berstatus Menunggu Verifikasi/Revisi dan belum Disetujui.');
            return;
        }

        // Ambil nomor terakhir yang sudah dipakai tahun ini, lock untuk hindari tabrakan saat bulk
        $latest = CardApplication::whereYear('created_at', now()->year)
            ->where('status', 'Disetujui')
            ->whereNotNull('nomor_ak1')
            // Abaikan perbaikan karena nomor memakai parent, bukan penerbitan baru
            ->where(function ($q) {
                $q->where('type', '!=', 'perbaikan')->orWhereNull('type');
            })
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $currentNumber = 0;
        if ($latest && preg_match('/DTK-AK1-(\d+)-/', $latest->nomor_ak1, $m)) {
            $currentNumber = (int) $m[1];
        }
        $prefix = 'DTK-AK1';
        $monthYear = now()->format('my');

        foreach ($apps as $app) {
            $nomorAk1 = $app->nomor_ak1;
            if (!$nomorAk1) {
                if ($app->type === 'perbaikan' && $app->parent) {
                    $nomorAk1 = $app->parent->nomor_ak1;
                } else {
                    $currentNumber++;
                    $nextNumber = str_pad($currentNumber, 4, '0', STR_PAD_LEFT);
                    $nomorAk1 = "{$prefix}-{$nextNumber}-{$monthYear}";
                }
            }

            $previousStatus = $app->status;
            $app->update([
                'status'      => 'Disetujui',
                'nomor_ak1'   => $nomorAk1,
                'approved_at' => Carbon::now(),
                'is_active'   => true,
            ]);

            \App\Models\CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => auth()->id(),
                'action'      => 'approve',
                'from_status' => $previousStatus,
                'to_status'   => 'Disetujui',
                'notes'       => null,
            ]);
        }

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Pengajuan terpilih berhasil disetujui.');
        $this->resetPage();
        }); // end transaction
    }

    public function bulkUnapprove(): void
    {
        $ids = array_map('intval', $this->selected);
        if (count($ids) < 2) {
            $this->bulkErrorMessage = 'Pilih lebih dari 1 pengajuan untuk aksi massal.';
            $this->dispatch('open-bulk-error', title: 'Pilih minimal 2 pengajuan', message: 'Pilih lebih dari 1 pengajuan untuk aksi massal.');
            return;
        }

        $apps = CardApplication::whereIn('id', $ids)->get();
        if ($apps->count() !== count($ids) || $apps->contains(fn ($a) => $a->status !== 'Disetujui')) {
            $this->bulkErrorMessage = 'Aksi batal persetujuan hanya untuk pengajuan berstatus Disetujui.';
            $this->dispatch('open-bulk-error', title: 'Tidak valid untuk dibatalkan', message: 'Aksi batal persetujuan hanya untuk pengajuan berstatus Disetujui.');
            return;
        }

        foreach ($apps as $app) {
            $from = $app->status;
            $app->update([
                'status' => 'Batal',
                'is_active' => false,
                'nomor_ak1' => null,
                'assigned_to' => null,
            ]);

            \App\Models\CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => auth()->id(),
                'action'      => 'unapprove',
                'from_status' => $from,
                'to_status'   => 'Batal',
                'notes'       => null,
            ]);
        }

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Persetujuan pengajuan terpilih dibatalkan.');
        $this->resetPage();
    }

    public function bulkArchive(): void
    {
        $ids = array_map('intval', $this->selected);
        if (count($ids) < 2) {
            $this->bulkErrorMessage = 'Pilih lebih dari 1 pengajuan untuk arsip.';
            $this->dispatch('open-bulk-error', title: 'Pilih minimal 2 pengajuan', message: 'Pilih lebih dari 1 pengajuan untuk arsip.');
            return;
        }

        $apps = CardApplication::whereIn('id', $ids)->get();
        if ($apps->count() !== count($ids) || $apps->contains(fn ($a) => $a->is_active)) {
            $this->bulkErrorMessage = 'Pastikan semua pengajuan berstatus non-aktif sebelum diarsipkan.';
            $this->dispatch('open-bulk-error', title: 'Arsip hanya untuk AK1 non-aktif', message: 'Pastikan semua pengajuan berstatus non-aktif sebelum diarsipkan.');
            return;
        }

        CardApplication::whereIn('id', $ids)->update([
            'archived_at' => Carbon::now(),
        ]);

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Pengajuan terpilih dipindahkan ke arsip.');
        $this->resetPage();
    }

    public function bulkRestore(): void
    {
        $ids = array_map('intval', $this->selected);
        if (count($ids) < 2) {
            $this->bulkErrorMessage = 'Pilih lebih dari 1 pengajuan untuk keluar dari arsip.';
            $this->dispatch('open-bulk-error', title: 'Pilih minimal 2 pengajuan', message: 'Pilih lebih dari 1 pengajuan untuk keluar dari arsip.');
            return;
        }

        CardApplication::whereIn('id', $ids)->update([
            'archived_at' => null,
        ]);

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Pengajuan terpilih dikembalikan dari arsip.');
        $this->resetPage();
    }

    public function prepareArchiveSingle($id): void
    {
        $this->archiveIds = [(int)$id];
        $app = CardApplication::find($id);
        $this->archiveLabel = $app?->user?->jobseekerProfile?->nama_lengkap
            ?? $app?->user?->name
            ?? 'Pencaker';

        $this->dispatch('open-modal', 'confirm-archive');
    }

    public function prepareArchiveBulk(): void
    {
        if (count($this->selected) === 0) {
            $this->dispatch('toast', type: 'error', message: 'Tidak ada data yang dipilih.');
            return;
        }

        $this->archiveIds = array_map('intval', $this->selected);
        $this->archiveLabel = count($this->archiveIds) . ' pengajuan terpilih';
        $this->dispatch('open-modal', 'confirm-archive');
    }

    public function performArchive(): void
    {
        if (empty($this->archiveIds)) return;

        foreach ($this->archiveIds as $id) {
            $app = CardApplication::find($id);
            if (!$app) continue;

            $app->update(['archived_at' => now()]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => auth()->id(),
                'action'      => 'archived',
                'from_status' => $app->status,
                'to_status'   => $app->status,
                'notes'       => 'Pengajuan diarsipkan',
            ]);
        }

        $this->resetArchiveState();
        $this->selected = [];
        $this->selectAll = false;
        $this->dispatch('close-modal', 'confirm-archive');
        $this->dispatch('$refresh');
        $this->dispatch('toast', type: 'success', message: 'Berhasil mengarsipkan pengajuan.');
    }

    public function prepareRestoreSingle($id): void
    {
        $this->archiveIds = [(int)$id];
        $this->archiveLabel = null;
        $this->dispatch('open-modal', 'confirm-restore');
    }

    public function doRestoreSingle(): void
    {
        if (empty($this->archiveIds)) return;

        foreach ($this->archiveIds as $id) {
            $app = CardApplication::find($id);
            if (!$app) continue;

            $app->archived_at = null;
            $app->save();
        }

        $this->resetArchiveState();
        $this->dispatch('close-modal', 'confirm-restore');
        $this->dispatch('$refresh');
        $this->dispatch('toast', type: 'success', message: 'Pengajuan dikembalikan dari arsip.');
        $this->resetPage();
    }

    // Arsip bulk langsung tanpa modal (boleh 1 atau lebih, hanya non-aktif)
    public function archiveSelected(): void
    {
        if (empty($this->selected)) {
            $this->dispatch('toast', type: 'error', message: 'Tidak ada yang dipilih.');
            return;
        }

        $ids = array_map('intval', $this->selected);
        $apps = CardApplication::whereIn('id', $ids)->get();
        $eligible = $apps->where('is_active', false);

        if ($eligible->isEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Hanya AK1 non-aktif yang dapat diarsipkan.');
            return;
        }

        $archivedCount = 0;
        foreach ($eligible as $app) {
            $app->update(['archived_at' => now()]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => auth()->id(),
                'action'      => 'archived',
                'from_status' => $app->status,
                'to_status'   => $app->status,
                'notes'       => 'Pengajuan diarsipkan',
            ]);
            $archivedCount++;
        }

        $this->selected = [];
        $this->selectAll = false;
        $this->dispatch('$refresh');
        if ($archivedCount > 0) {
            $this->dispatch('toast', type: 'success', message: 'Berhasil mengarsipkan pengajuan terpilih.');
        }
        $this->resetPage();
    }
}
