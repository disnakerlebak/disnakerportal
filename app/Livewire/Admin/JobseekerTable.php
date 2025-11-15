<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class JobseekerTable extends Component
{
    use WithPagination;

    public $q = '';
    public $hasTraining = false;
    public $hasWork = false;
    public $perPage = 20;

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
            }], 'created_at');

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

        $users = $query->orderByDesc('last_ak1_created_at')->paginate($this->perPage);

        return view('livewire.admin.jobseeker-table', [
            'users' => $users,
        ]);
    }
}
