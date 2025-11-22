<?php

namespace App\Livewire\Admin;

use App\Models\CompanyProfile;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCompaniesTable extends Component
{
    use WithPagination;

    public string $q = '';
    public string $verificationStatus = '';
    public int $perPage = 10;
    public array $selected = [];
    public string $newCompanyName = '';
    public string $newCompanyEmail = '';

    protected $queryString = [
        'q' => ['except' => ''],
        'verificationStatus' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'newCompanyName'  => ['required', 'string', 'max:255'],
        'newCompanyEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
    ];

    protected $paginationTheme = 'tailwind';

    public function approve(int $companyId): void
    {
        $company = CompanyProfile::findOrFail($companyId);
        $company->verification_status = 'approved';
        $company->verified_at = now();
        $company->save();

        session()->flash('success', 'Perusahaan berhasil disetujui.');
        $this->dispatch('toast', message: 'Perusahaan disetujui.', type: 'success');
    }

    public function unapprove(int $companyId): void
    {
        $company = CompanyProfile::findOrFail($companyId);
        $company->verification_status = 'pending';
        $company->verified_at = null;
        $company->save();

        session()->flash('success', 'Status verifikasi perusahaan dikembalikan menjadi pending.');
        $this->dispatch('toast', message: 'Verifikasi perusahaan dibatalkan.', type: 'success');
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->status = ($user->status ?? 'active') === 'active' ? 'inactive' : 'active';
        $user->save();

        $label = $user->status === 'active' ? 'Akun perusahaan diaktifkan.' : 'Akun perusahaan dinonaktifkan.';
        session()->flash('success', 'Status akun perusahaan berhasil diperbarui.');
        $this->dispatch('toast', message: $label, type: 'success');
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();

        session()->flash('success', 'Akun perusahaan berhasil dihapus.');
        $this->dispatch('toast', message: 'Akun perusahaan dihapus.', type: 'success');
    }

    public function bulkApprove(): void
    {
        if (empty($this->selected)) return;

        CompanyProfile::whereIn('id', $this->selected)->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
        ]);

        $this->selected = [];
        session()->flash('success', 'Perusahaan terpilih berhasil disetujui.');
    }

    public function bulkActivateUsers(): void
    {
        $companyIds = $this->selected;
        if (empty($companyIds)) return;

        $userIds = CompanyProfile::whereIn('id', $companyIds)->pluck('user_id')->filter();
        if ($userIds->isEmpty()) return;

        User::whereIn('id', $userIds)->update(['status' => 'active']);
        $this->selected = [];
        session()->flash('success', 'Akun perusahaan terpilih berhasil diaktifkan.');
    }

    public function bulkDeactivateUsers(): void
    {
        $companyIds = $this->selected;
        if (empty($companyIds)) return;

        $userIds = CompanyProfile::whereIn('id', $companyIds)->pluck('user_id')->filter();
        if ($userIds->isEmpty()) return;

        User::whereIn('id', $userIds)->update(['status' => 'inactive']);
        $this->selected = [];
        session()->flash('success', 'Akun perusahaan terpilih berhasil dinonaktifkan.');
    }

    public function bulkDelete(): void
    {
        $companyIds = $this->selected;
        if (empty($companyIds)) return;

        $userIds = CompanyProfile::whereIn('id', $companyIds)->pluck('user_id')->filter();
        if ($userIds->isNotEmpty()) {
            User::whereIn('id', $userIds)->delete();
        }

        $this->selected = [];
        session()->flash('success', 'Perusahaan terpilih berhasil dihapus.');
    }

    public function createCompanyAdmin(): void
    {
        $data = $this->validate();
        $password = 'p@ssword123';

        $user = User::create([
            'name'     => $data['newCompanyName'],
            'email'    => strtolower($data['newCompanyEmail']),
            'password' => Hash::make($password),
            'role'     => 'perusahaan',
            'status'   => 'active',
        ]);

        CompanyProfile::create([
            'user_id'         => $user->id,
            'nama_perusahaan' => $data['newCompanyName'],
            'alamat_lengkap'  => '-',
            'email'           => $user->email,
            'verification_status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'created',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => 'Akun perusahaan dibuat oleh admin',
        ]);

        session()->flash('success', 'Akun perusahaan berhasil dibuat. Password awal: ' . $password);
        $this->dispatch('toast', message: 'Akun perusahaan berhasil dibuat. Password: ' . $password, type: 'success');
        $this->reset(['newCompanyName', 'newCompanyEmail']);
        $this->dispatch('close-modal', id: 'add-company-admin');
        $this->resetPage();
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

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['q', 'verificationStatus', 'selected']);
        $this->resetPage();
    }
}
