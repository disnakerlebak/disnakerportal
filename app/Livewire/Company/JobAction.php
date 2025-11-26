<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class JobAction extends Component
{
    public ?int $jobId = null;
    public string $action = '';
    public string $title = 'Konfirmasi Aksi';
    public string $message = 'Memuat konfirmasi...';

    protected function companyId(): ?int
    {
        return Auth::user()?->companyProfile?->id;
    }

    #[On('job-action:open')]
    public function open(string $action, int $jobId): void
    {
        $companyId = $this->companyId();
        $job = JobPosting::where('company_id', $companyId)->findOrFail($jobId);

        $this->jobId = $job->id;
        $this->action = $action;
        $this->title = match ($action) {
            'publish' => 'Publikasikan Lowongan',
            'close' => 'Tutup Lowongan',
            'reopen' => 'Buka Kembali Lowongan',
            'delete' => 'Hapus Lowongan',
            default => 'Konfirmasi Aksi',
        };
        $this->message = match ($action) {
            'publish' => "Publikasikan \"{$job->judul}\"? Kandidat akan bisa melihat lowongan ini.",
            'close' => "Tutup lowongan \"{$job->judul}\"? Pelamar baru tidak bisa masuk.",
            'reopen' => "Buka kembali lowongan \"{$job->judul}\" sebagai aktif?",
            'delete' => "Hapus lowongan \"{$job->judul}\" secara permanen?",
            default => "Lanjutkan aksi untuk \"{$job->judul}\"?",
        };

    }

    public function confirm(): void
    {
        if (!$this->jobId || !$this->action) {
            return;
        }

        $companyId = $this->companyId();
        $job = JobPosting::where('company_id', $companyId)->findOrFail($this->jobId);

        match ($this->action) {
            'publish' => $this->publish($job),
            'close' => $this->close($job),
            'reopen' => $this->reopen($job),
            'delete' => $this->delete($job),
            default => null,
        };

        $this->dispatch('job-updated');
        $this->dispatch('modal:close', id: 'job-action-modal');
        $this->resetState();
    }

    protected function publish(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_ACTIVE;
        $job->tanggal_posting = now();
        $job->save();
        session()->flash('success', 'Lowongan dipublikasikan.');
    }

    protected function close(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_CLOSED;
        $job->save();
        session()->flash('success', 'Lowongan ditutup.');
    }

    protected function reopen(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_ACTIVE;
        $job->tanggal_posting = now();
        $job->save();
        session()->flash('success', 'Lowongan dibuka kembali.');
    }

    protected function delete(JobPosting $job): void
    {
        $job->delete();
        session()->flash('success', 'Lowongan dihapus.');
    }

    protected function resetState(): void
    {
        $this->jobId = null;
        $this->action = '';
        $this->title = 'Konfirmasi Aksi';
        $this->message = 'Lanjutkan aksi ini?';
    }

    public function render()
    {
        return view('livewire.company.job-action');
    }
}
