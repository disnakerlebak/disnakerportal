<?php

namespace App\Livewire\Admin;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCompaniesTable extends Component
{
    use WithPagination;

    public string $q = '';
    public string $verificationStatus = '';
    public int $perPage = 10;

    protected $queryString = [
        'q' => ['except' => ''],
        'verificationStatus' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $paginationTheme = 'tailwind';

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function updatingVerificationStatus()
    {
        $this->resetPage();
    }

    public function approve(int $companyId): void
    {
        $company = CompanyProfile::findOrFail($companyId);
        $company->verification_status = 'approved';
        $company->verified_at = now();
        $company->save();

        session()->flash('success', 'Perusahaan berhasil disetujui.');
    }

    public function unapprove(int $companyId): void
    {
        $company = CompanyProfile::findOrFail($companyId);
        $company->verification_status = 'pending';
        $company->verified_at = null;
        $company->save();

        session()->flash('success', 'Status verifikasi perusahaan dikembalikan menjadi pending.');
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->status = ($user->status ?? 'active') === 'active' ? 'inactive' : 'active';
        $user->save();

        session()->flash('success', 'Status akun perusahaan berhasil diperbarui.');
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();

        session()->flash('success', 'Akun perusahaan berhasil dihapus.');
    }

    public function render()
    {
        $query = CompanyProfile::query()
            ->with('user')
            ->when($this->q !== '', function (Builder $q) {
                $search = '%' . $this->q . '%';
                $q->where(function (Builder $sub) use ($search) {
                    $sub->where('nama_perusahaan', 'like', $search)
                        ->orWhere('jenis_usaha', 'like', $search)
                        ->orWhereHas('user', function (Builder $u) use ($search) {
                            $u->where('email', 'like', $search);
                        });
                });
            })
            ->when($this->verificationStatus !== '', function (Builder $q) {
                $q->where('verification_status', $this->verificationStatus);
            })
            ->orderByDesc('created_at');

        $companies = $query->paginate($this->perPage);

        return view('livewire.admin.manage-companies-table', [
            'companies' => $companies,
        ]);
    }
}

