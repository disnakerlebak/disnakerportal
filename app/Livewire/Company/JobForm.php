<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use Livewire\Attributes\On;
use Livewire\Component;

class JobForm extends Component
{
    public bool $isEdit = false;
    public ?int $editingId = null;

    public string $judul = '';
    public string $posisi = '';
    public string $lokasi_kerja = '';
    public ?string $pendidikan_minimal = null;
    public ?string $jenis_kelamin = null;
    public ?int $usia_min = null;
    public ?int $usia_max = null;
    public ?int $gaji_min = null;
    public ?int $gaji_max = null;
    public ?string $tanggal_expired = null;
    public ?string $tipe_pekerjaan = null;
    public ?string $model_kerja = null;
    public bool $menerima_disabilitas = true;
    public ?string $deskripsi = null;
    public ?string $kualifikasi = null;

    protected function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'posisi' => ['nullable', 'string', 'max:255'],
            'lokasi_kerja' => ['required', 'string', 'max:255'],
            'pendidikan_minimal' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'string', 'max:10'],
            'usia_min' => ['nullable', 'integer', 'min:0', 'max:100'],
            'usia_max' => ['nullable', 'integer', 'min:0', 'max:100'],
            'gaji_min' => ['nullable', 'integer', 'min:0'],
            'gaji_max' => ['nullable', 'integer', 'min:0'],
            'tanggal_expired' => ['nullable', 'date'],
            'tipe_pekerjaan' => ['nullable', 'string', 'max:50'],
            'model_kerja' => ['nullable', 'string', 'max:50'],
            'menerima_disabilitas' => ['boolean'],
            'deskripsi' => ['nullable', 'string'],
            'kualifikasi' => ['nullable', 'string'],
        ];
    }

    #[On('job-form:open')]
    public function open(?int $jobId = null): void
    {
        $this->resetForm();

        if ($jobId) {
            $companyId = Auth::user()?->companyProfile?->id;
            $job = JobPosting::where('company_id', $companyId)->findOrFail($jobId);

            $this->isEdit = true;
            $this->editingId = $job->id;
            $this->judul = $job->judul ?? '';
            $this->posisi = $job->posisi ?? '';
            $this->lokasi_kerja = $job->lokasi_kerja ?? '';
            $this->pendidikan_minimal = $job->pendidikan_minimal;
            $this->jenis_kelamin = $job->jenis_kelamin;
            $this->usia_min = $job->usia_min;
            $this->usia_max = $job->usia_max;
            $this->gaji_min = $job->gaji_min;
            $this->gaji_max = $job->gaji_max;
            $this->tanggal_expired = $job->tanggal_expired?->format('Y-m-d');
            $this->tipe_pekerjaan = $job->tipe_pekerjaan;
            $this->model_kerja = $job->model_kerja;
            $this->menerima_disabilitas = (bool) $job->menerima_disabilitas;
            $this->deskripsi = $job->deskripsi;
            $this->kualifikasi = $job->kualifikasi;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $companyId = Auth::user()?->companyProfile?->id;
        if (!$companyId) {
            session()->flash('error', 'Profil perusahaan tidak ditemukan.');
            return;
        }

        if ($this->editingId) {
            $job = JobPosting::where('company_id', $companyId)->findOrFail($this->editingId);
            $job->update($data);
            $this->logActivity($job, 'updated', "Perbarui lowongan \"{$job->judul}\"");
            $this->dispatch('toast', message: 'Lowongan diperbarui.', type: 'success');
        } else {
            $data['company_id'] = $companyId;
            $data['status'] = JobPosting::STATUS_DRAFT;
            $job = JobPosting::create($data);
            $this->logActivity($job, 'created', "Buat lowongan \"{$job->judul}\" (draft)");
            $this->dispatch('toast', message: 'Lowongan disimpan sebagai draft.', type: 'success');
        }

        $this->dispatch('job-updated');
        $this->dispatch('modal:close', id: 'job-form-modal');
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'judul',
            'posisi',
            'lokasi_kerja',
            'pendidikan_minimal',
            'jenis_kelamin',
            'usia_min',
            'usia_max',
            'gaji_min',
            'gaji_max',
            'tanggal_expired',
            'tipe_pekerjaan',
            'model_kerja',
            'menerima_disabilitas',
            'deskripsi',
            'kualifikasi',
        ]);
        $this->menerima_disabilitas = true;
        $this->isEdit = false;
        $this->editingId = null;
        $this->tanggal_expired = null;
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
        return view('livewire.company.job-form');
    }
}
