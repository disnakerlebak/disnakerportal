<?php

namespace App\Livewire\Admin;

use App\Models\CardApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class JobseekerTable extends Component
{
    use WithPagination;

    public $q = '';
    public $hasTraining = false;
    public $hasWork = false;
    public $perPage = 20;
    #[Url(as: 'sort', except: 'last_ak1_created_at')]
    public string $sortField = 'last_ak1_created_at';
    #[Url(as: 'dir', except: 'desc')]
    public string $sortDirection = 'desc';

    protected array $sortableFields = [
        'usia',
        'pendidikan',
        'keahlian',
        'pengalaman',
        'nomor_ak1',
        'last_ak1_created_at',
    ];

    protected $queryString = [
        'q' => ['except' => ''],
        'hasTraining' => ['except' => false],
        'hasWork' => ['except' => false],
        'page' => ['except' => 1],
    ];

    public function updatingQ() { $this->resetPage(); }
    public function updatingHasTraining() { $this->resetPage(); }
    public function updatingHasWork() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->q = '';
        $this->hasTraining = false;
        $this->hasWork = false;
        $this->resetPage();
    }

    public function apply()
    {
        // Terapkan perubahan filter yang di-bind dengan defer
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if (!in_array($field, $this->sortableFields, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    protected function applySorting(Builder $query): Builder
    {
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return match ($this->sortField) {
            'usia' => $query->orderBy('jp.tanggal_lahir', $direction === 'asc' ? 'desc' : 'asc'),
            'pendidikan' => $query->orderBy('jp.pendidikan_terakhir', $direction),
            'keahlian' => $query->orderBy('training_count', $direction),
            'pengalaman' => $query->orderBy('experience_count', $direction),
            'nomor_ak1' => $query->orderBy('latest_nomor_ak1', $direction),
            default => $query->orderBy('last_ak1_created_at', $direction)->orderBy('users.id'),
        };
    }

    public function render()
    {
        $query = User::query()
            ->whereHas('cardApplications', function ($q) {
                $q->where('status', 'Disetujui')->where('is_active', true);
            })
            ->with([
                'jobseekerProfile' => fn($q) => $q->withCount(['trainings', 'workExperiences']),
                'cardApplications' => fn($q) => $q->where('status', 'Disetujui')->where('is_active', true)->latest()->limit(1),
            ])
            ->withMax(['cardApplications as last_ak1_created_at' => function ($q) {
                $q->where('status', 'Disetujui')->where('is_active', true);
            }], 'created_at')
            ->leftJoin('jobseeker_profiles as jp', 'jp.user_id', '=', 'users.id')
            ->addSelect('users.*')
            ->selectSub(
                CardApplication::select('nomor_ak1')
                    ->whereColumn('user_id', 'users.id')
                    ->where('status', 'Disetujui')
                    ->where('is_active', true)
                    ->orderByDesc('created_at')
                    ->limit(1),
                'latest_nomor_ak1'
            )
            ->selectSub(
                DB::table('trainings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('jobseeker_profile_id', 'jp.id'),
                'training_count'
            )
            ->selectSub(
                DB::table('work_experiences')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('jobseeker_profile_id', 'jp.id'),
                'experience_count'
            );

        if (filled($this->q)) {
            $keyword = trim($this->q);
            $query->where(function ($w) use ($keyword) {
                $w->where('name', 'like', "%{$keyword}%")
                  ->orWhereHas('jobseekerProfile', function ($p) use ($keyword) {
                      $p->where('nama_lengkap', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($this->hasTraining) {
            $query->whereHas('jobseekerProfile.trainings');
        }
        if ($this->hasWork) {
            $query->whereHas('jobseekerProfile.workExperiences');
        }

        $query = $this->applySorting($query);

        $users = $query->paginate($this->perPage);

        return view('livewire.admin.jobseeker-table', [
            'users' => $users,
        ]);
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

    // === ACTION: Aktifkan kembali user ===
    public function activateUser(int $userId): void
    {
        $user = User::where('role', 'pencaker')->findOrFail($userId);
        $user->status = 'active';
        $user->save();

        session()->flash('success', 'Pencaker berhasil diaktifkan kembali.');
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
}
