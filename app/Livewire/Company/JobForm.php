<?php

namespace App\Livewire\Company;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Livewire\Attributes\On;
use Livewire\Component;

class JobForm extends Component
{
    public bool $isEdit = false;
    public ?int $editingId = null;
    public bool $useModal = true;
    public ?string $redirectTo = null;

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
    public string $lokasi_mode = 'domestic'; // domestic | foreign
    public ?int $province_id = null;
    public ?int $regency_id = null;
    public ?int $district_id = null;
    public ?string $country = null;
    public array $provinces = [];
    public array $regencies = [];
    public array $districts = [];

    public function mount(bool $useModal = true, ?string $redirectTo = null, ?int $jobId = null): void
    {
        $this->useModal = $useModal;
        $this->redirectTo = $redirectTo;
        $this->loadProvinces();

        if ($jobId) {
            $this->loadJob($jobId);
        }
    }

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
            $this->loadJob($jobId);
        }

        if (empty($this->provinces)) {
            $this->loadProvinces();
        }
    }

    public function save()
    {
        if (!$this->useModal) {
            if ($this->lokasi_mode === 'domestic') {
                if ($this->province_id && $this->regency_id && $this->district_id) {
                    $this->lokasi_kerja = $this->buildDomesticLocation();
                } elseif (!$this->isEdit || trim((string) $this->lokasi_kerja) === '') {
                    $this->addError('lokasi_kerja', 'Pilih provinsi, kabupaten/kota, dan kecamatan.');
                    return;
                }
            } else {
                if (trim((string) $this->country) !== '') {
                    $this->lokasi_kerja = trim((string) $this->country);
                } elseif (!$this->isEdit || trim((string) $this->lokasi_kerja) === '') {
                    $this->addError('lokasi_kerja', 'Isi negara penempatan.');
                    return;
                }
            }
        }

        $data = $this->validate();
        $successMessage = $this->editingId ? 'Lowongan diperbarui.' : 'Lowongan disimpan sebagai draft.';

        $companyId = Auth::user()?->companyProfile?->id;
        if (!$companyId) {
            session()->flash('error', 'Profil perusahaan tidak ditemukan.');
            return;
        }

        if ($this->editingId) {
            $job = JobPosting::where('company_id', $companyId)->findOrFail($this->editingId);
            $job->update($data);
            $this->logActivity($job, 'updated', "Perbarui lowongan \"{$job->judul}\"");
            $this->dispatch('toast', message: $successMessage, type: 'success');
        } else {
            $data['company_id'] = $companyId;
            $data['status'] = JobPosting::STATUS_DRAFT;
            $job = JobPosting::create($data);
            $this->logActivity($job, 'created', "Buat lowongan \"{$job->judul}\" (draft)");
            $this->dispatch('toast', message: $successMessage, type: 'success');
        }

        $this->dispatch('job-updated');
        $this->resetForm();

        if ($this->useModal) {
            $this->dispatch('modal:close', id: 'job-form-modal');
            return;
        }

        session()->flash('success', $successMessage);
        $redirectUrl = $this->redirectTo ?? route('company.jobs.index');
        return redirect()->to($redirectUrl);
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
            'lokasi_mode',
            'province_id',
            'regency_id',
            'district_id',
            'country',
        ]);
        $this->menerima_disabilitas = true;
        $this->isEdit = false;
        $this->editingId = null;
        $this->tanggal_expired = null;
        $this->lokasi_mode = 'domestic';
        $this->regencies = [];
        $this->districts = [];
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
        if ($this->province_id && empty($this->regencies)) {
            $this->loadRegencies();
        }
        if ($this->regency_id && empty($this->districts)) {
            $this->loadDistricts();
        }

        return view('livewire.company.job-form');
    }

    private function loadProvinces(): void
    {
        $this->provinces = DB::table('reg_provinces')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($row) => ['id' => (int) $row->id, 'name' => $row->name])
            ->all();
    }

    public function loadRegencies(): void
    {
        if (!$this->province_id) {
            $this->regencies = [];
            return;
        }

        $this->regencies = DB::table('reg_regencies')
            ->where('province_id', $this->province_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($row) => ['id' => (int) $row->id, 'name' => $row->name])
            ->all();
    }

    public function loadDistricts(): void
    {
        if (!$this->regency_id) {
            $this->districts = [];
            return;
        }

        $this->districts = DB::table('reg_districts')
            ->where('regency_id', $this->regency_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($row) => ['id' => (int) $row->id, 'name' => $row->name])
            ->all();
    }

    public function updatedLokasiMode($value): void
    {
        if ($value === 'domestic') {
            $this->country = null;
        } else {
            $this->province_id = null;
            $this->regency_id = null;
            $this->district_id = null;
            $this->regencies = [];
            $this->districts = [];
        }
    }

    private function buildDomesticLocation(): string
    {
        $provinceName = $this->findName($this->provinces, $this->province_id);
        $regencyName = $this->findName($this->regencies, $this->regency_id);
        $districtName = $this->findName($this->districts, $this->district_id);

        return collect([$districtName, $regencyName, $provinceName])
            ->filter()
            ->implode(' - ');
    }

    public function onProvinceChange($value): void
    {
        $this->province_id = $value ? (int) $value : null;
        $this->regency_id = null;
        $this->district_id = null;
        $this->loadRegencies();
        $this->districts = [];
    }

    public function onRegencyChange($value): void
    {
        $this->regency_id = $value ? (int) $value : null;
        $this->district_id = null;
        $this->loadDistricts();
    }

    private function loadJob(int $jobId): void
    {
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

    private function findName(array $items, ?int $id): ?string
    {
        if (!$id) return null;
        foreach ($items as $item) {
            if ((int) ($item['id'] ?? 0) === (int) $id) {
                return $item['name'] ?? null;
            }
        }
        return null;
    }
}
