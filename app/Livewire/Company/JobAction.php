<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\ActivityLog;

class JobAction extends Component
{
    public ?int $jobId = null;
    public string $action = '';
    public string $title = 'Konfirmasi Aksi';
    public string $message = '';
    public array $bulkIds = [];

    protected function companyId(): ?int
    {
        return Auth::user()?->companyProfile?->id;
    }

    #[On('job-action:open')]
    public function open(string $action, int $jobId = null, array $ids = []): void
    {
        $companyId = $this->companyId();
        $this->action = $action;
        $this->bulkIds = [];
        $this->jobId = null;

        if ($action === 'bulk-delete') {
            $validIds = JobPosting::where('company_id', $companyId)
                ->whereIn('id', $ids)
                ->where('status', '!=', JobPosting::STATUS_ACTIVE)
                ->pluck('id')
                ->all();

            if (empty($validIds)) {
                session()->flash('error', 'Tidak ada lowongan yang dapat dihapus.');
                $this->dispatch('modal:close', id: 'job-action-modal');
                return;
            }

            $this->bulkIds = $validIds;
            $this->title = 'Hapus Lowongan Terpilih';
            $this->message = 'Anda akan menghapus lowongan non-aktif yang dipilih. Tindakan ini tidak dapat dibatalkan.';
        } else {
            $job = JobPosting::where('company_id', $companyId)->findOrFail($jobId);

            if ($action === 'delete' && $job->status === JobPosting::STATUS_ACTIVE) {
                session()->flash('error', 'Lowongan aktif tidak dapat dihapus.');
                $this->dispatch('modal:close', id: 'job-action-modal');
                return;
            }

            $this->jobId = $job->id;
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

        $this->dispatch('modal:open', id: 'job-action-modal');
    }

    public function confirm(): void
    {
        if (!$this->action) return;

        $companyId = $this->companyId();
        $job = null;
        if ($this->action !== 'bulk-delete') {
            if (!$this->jobId) return;
            $job = JobPosting::where('company_id', $companyId)->findOrFail($this->jobId);
        }

        if ($this->action === 'bulk-delete') {
            $this->bulkDelete();
        } else {
            match ($this->action) {
                'publish' => $this->publish($job),
                'close' => $this->close($job),
                'reopen' => $this->reopen($job),
                'delete' => $this->delete($job),
                default => null,
            };
        }

        $this->dispatch('job-updated');
        $this->dispatch('modal:close', id: 'job-action-modal');
        $this->resetState();
    }

    protected function publish(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_ACTIVE;
        $job->tanggal_posting = now();
        $job->save();
        $this->dispatch('toast', message: 'Lowongan dipublikasikan.', type: 'success');
        $this->logActivity($job, 'published', "Publikasikan lowongan \"{$job->judul}\"");
    }

    protected function close(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_CLOSED;
        $job->save();
        $this->dispatch('toast', message: 'Lowongan ditutup.', type: 'success');
        $this->logActivity($job, 'closed', "Tutup lowongan \"{$job->judul}\"");
    }

    protected function reopen(JobPosting $job): void
    {
        $job->status = JobPosting::STATUS_ACTIVE;
        $job->tanggal_posting = now();
        $job->save();
        $this->dispatch('toast', message: 'Lowongan dibuka kembali.', type: 'success');
        $this->logActivity($job, 'reopened', "Buka kembali lowongan \"{$job->judul}\"");
    }

    protected function delete(JobPosting $job): void
    {
        $title = $job->judul;
        $job->delete();
        $this->dispatch('toast', message: 'Lowongan dihapus.', type: 'success');
        $this->logActivity($job, 'deleted', "Hapus lowongan \"{$title}\"");
    }

    protected function bulkDelete(): void
    {
        if (empty($this->bulkIds)) {
            $this->dispatch('toast', message: 'Tidak ada lowongan yang dapat dihapus.', type: 'error');
            return;
        }

        $companyId = $this->companyId();
        JobPosting::where('company_id', $companyId)
            ->whereIn('id', $this->bulkIds)
            ->where('status', '!=', JobPosting::STATUS_ACTIVE)
            ->delete();

        $this->dispatch('toast', message: 'Lowongan terpilih berhasil dihapus.', type: 'success');
    }

    protected function resetState(): void
    {
        $this->jobId = null;
        $this->action = '';
        $this->title = 'Konfirmasi Aksi';
        $this->message = '';
        $this->bulkIds = [];
    }

    private function logActivity(JobPosting $job, string $action, ?string $description = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => JobPosting::class,
            'model_id' => $job->id,
            'description' => $description,
        ]);
    }

    public function render()
    {
        return view('livewire.company.job-action');
    }
}
