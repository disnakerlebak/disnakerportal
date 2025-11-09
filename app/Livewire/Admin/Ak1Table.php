<?php

namespace App\Livewire\Admin;

use App\Models\CardApplication;
use App\Models\RejectionReason;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return $this->search !== '' || $this->type !== '' || $this->status !== '';
    }

    public function render(): View
    {
        $apps = CardApplication::with(['user', 'lastHandler.actor', 'logs.actor'])
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
            ->latest('created_at')
            ->paginate(20);

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
}
