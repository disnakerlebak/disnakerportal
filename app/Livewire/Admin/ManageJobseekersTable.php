<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ManageJobseekersTable extends Component
{
    use WithPagination;

    // Filter
    public string $q = '';
    public ?string $profileStatus = null; // complete / incomplete / null
    public ?string $ak1Status = null;     // never / pending / approved / rejected / expired / null

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'q' => ['except' => ''],
        'profileStatus' => ['except' => null],
        'ak1Status' => ['except' => null],
        'page' => ['except' => 1],
    ];

    // === Lifecycle & helpers ===
    public function updatingQ()            { $this->resetPage(); }
    public function updatingProfileStatus(){ $this->resetPage(); }
    public function updatingAk1Status()    { $this->resetPage(); }

    public function applyFilters()
    {
        // dipanggil saat klik tombol "Terapkan"
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['q', 'profileStatus', 'ak1Status']);
        $this->resetPage();
    }

    // === Query builder utama ===
    public function getRowsQueryProperty()
    {
        $query = User::query()
            ->where('role', 'pencaker')
            ->with([
                'jobseekerProfile',
                'latestCardApplication',
            ]);

        // Cari nama / NIK / email
        if (trim($this->q) !== '') {
            $term = '%' . trim($this->q) . '%';

            $query->where(function ($q) use ($term) {
                $q->whereHas('jobseekerProfile', function ($qq) use ($term) {
                    $qq->where('nama_lengkap', 'like', $term)
                       ->orWhere('nik', 'like', $term);
                })->orWhere('email', 'like', $term);
            });
        }

        // Filter status profil
        if ($this->profileStatus === 'complete') {
            $query->whereHas('jobseekerProfile', function ($q) {
                // patokan sederhananya: punya NIK berarti lengkap
                $q->whereNotNull('nik')->where('nik', '!=', '');
            });
        } elseif ($this->profileStatus === 'incomplete') {
            $query->where(function ($q) {
                $q->doesntHave('jobseekerProfile')
                  ->orWhereHas('jobseekerProfile', function ($qq) {
                      $qq->whereNull('nik')->orWhere('nik', '=', '');
                  });
            });
        }

        // Filter status AK1
        if ($this->ak1Status) {
            $status = $this->ak1Status;

            $query->where(function ($q) use ($status) {
                if ($status === 'never') {
                    $q->doesntHave('cardApplications');
                } else {
                    $q->whereHas('cardApplications', function ($qq) use ($status) {
                        $qq->where('status', $status);
                    });
                }
            });
        }

        return $query->orderByDesc('created_at');
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate(10);
    }

    // === ACTION: Nonaktifkan user ===
    public function deactivateUser(int $userId): void
    {
        $user = User::where('role', 'pencaker')->findOrFail($userId);

        $user->status = 'inactive';
        $user->save();

        session()->flash('success', 'Pencaker berhasil dinonaktifkan.');
        $this->resetPage();
    }

    // === ACTION: Reset profil pencaker (hapus data profil & riwayat) ===
    public function resetProfile(int $userId): void
    {
        $user = User::where('role', 'pencaker')->findOrFail($userId);

        if ($user->jobseekerProfile) {
            $user->jobseekerProfile()->delete();
        }

        if (method_exists($user, 'jobEducations')) {
            $user->jobEducations()->delete();
        }
        if (method_exists($user, 'jobTrainings')) {
            $user->jobTrainings()->delete();
        }
        if (method_exists($user, 'jobExperiences')) {
            $user->jobExperiences()->delete();
        }
        if (method_exists($user, 'jobPreferences')) {
            $user->jobPreferences()->delete();
        }

        // AK1 dibiarkan, supaya histori layanan tetap ada
        session()->flash('success', 'Profil pencaker berhasil direset.');
        $this->resetPage();
    }

    // === ACTION: Hapus pencaker + seluruh relasi (TERMASUK AK1) ===
    public function deleteUser(int $userId): void
    {
        $user = User::where('role', 'pencaker')->findOrFail($userId);

        if ($user->jobseekerProfile) {
            $user->jobseekerProfile()->delete();
        }
        if (method_exists($user, 'jobEducations')) {
            $user->jobEducations()->delete();
        }
        if (method_exists($user, 'jobTrainings')) {
            $user->jobTrainings()->delete();
        }
        if (method_exists($user, 'jobExperiences')) {
            $user->jobExperiences()->delete();
        }
        if (method_exists($user, 'jobPreferences')) {
            $user->jobPreferences()->delete();
        }
        if (method_exists($user, 'cardApplications')) {
            $user->cardApplications()->delete();
        }

        $user->delete();

        session()->flash('success', 'Pencaker beserta seluruh datanya berhasil dihapus.');
        $this->resetPage();
    }

    public function render()
    {
    return view('livewire.admin.manage-jobseekers-table', [
        'users' => $this->rows,
    ]);
    }

}
