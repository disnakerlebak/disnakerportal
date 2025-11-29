<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class JobsTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    protected function companyId(): ?int
    {
        return Auth::user()?->companyProfile?->id;
    }

    protected function baseQuery(): Builder
    {
        $companyId = $this->companyId();

        return JobPosting::query()
            ->where('company_id', $companyId)
            ->withCount('applications');
    }

    public function preview(int $jobId): void
    {
        $this->dispatch('job-preview:open', jobId: $jobId);
        $this->dispatch('modal:open', id: 'job-preview-modal');
    }

    public function create(): void
    {
        $this->dispatch('job-form:open');
        $this->dispatch('modal:open', id: 'job-form-modal');
    }

    public function edit(int $jobId): void
    {
        $job = JobPosting::where('company_id', $this->companyId())->find($jobId);
        if (!$job) {
            session()->flash('error', 'Lowongan tidak ditemukan.');
            return;
        }
        if ($job->status === JobPosting::STATUS_ACTIVE) {
            session()->flash('error', 'Lowongan aktif tidak dapat diedit.');
            return;
        }
        $this->dispatch('job-form:open', jobId: $jobId);
        $this->dispatch('modal:open', id: 'job-form-modal');
    }

    public function confirmAction(string $action, int $jobId): void
    {
        // Buka modal lebih awal agar respons cepat, isi detail akan diisi oleh Livewire
        $this->dispatch('modal:open', id: 'job-action-modal');
        $this->dispatch('job-action:open', action: $action, jobId: $jobId);
    }

    #[On('job-updated')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return view('livewire.company.jobs-table', [
                'jobs' => collect(),
                'statuses' => JobPosting::statuses(),
            ]);
        }

        $query = $this->baseQuery()
            ->orderByRaw('CASE WHEN tanggal_expired IS NULL THEN 1 ELSE 0 END')
            ->orderBy('tanggal_expired')
            ->orderByDesc('created_at');

        if ($this->status !== 'all' && in_array($this->status, JobPosting::statuses(), true)) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $keyword = trim($this->search);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('posisi', 'like', "%{$keyword}%")
                  ->orWhere('lokasi_kerja', 'like', "%{$keyword}%");
            });
        }

        $jobs = $query->paginate($this->perPage);

        return view('livewire.company.jobs-table', [
            'jobs' => $jobs,
            'statuses' => JobPosting::statuses(),
        ]);
    }
}
