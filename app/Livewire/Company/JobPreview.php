<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class JobPreview extends Component
{
    public ?JobPosting $job = null;

    protected function companyId(): ?int
    {
        return Auth::user()?->companyProfile?->id;
    }

    #[On('job-preview:open')]
    public function loadJob(int $jobId): void
    {
        $companyId = $this->companyId();
        $this->job = JobPosting::query()
            ->where('company_id', $companyId)
            ->withCount('applications')
            ->findOrFail($jobId);

        $this->dispatch('open-modal', id: 'job-preview-modal');
    }

    public function publish(): void
    {
        if (!$this->job) {
            return;
        }

        $companyId = $this->companyId();
        $job = JobPosting::where('company_id', $companyId)->findOrFail($this->job->id);
        $job->status = JobPosting::STATUS_ACTIVE;
        $job->tanggal_posting = now();
        $job->save();

        $this->job = $job->fresh()->loadCount('applications');
        $this->dispatch('job-updated');
        session()->flash('success', 'Lowongan dipublikasikan.');
    }

    public function closeJob(): void
    {
        if (!$this->job) {
            return;
        }

        $companyId = $this->companyId();
        $job = JobPosting::where('company_id', $companyId)->findOrFail($this->job->id);
        $job->status = JobPosting::STATUS_CLOSED;
        $job->save();

        $this->job = $job->fresh()->loadCount('applications');
        $this->dispatch('job-updated');
        session()->flash('success', 'Lowongan ditutup.');
    }

    public function closeModal(): void
    {
        $this->dispatch('close-modal', id: 'job-preview-modal');
    }

    public function render()
    {
        return view('livewire.company.job-preview');
    }
}
