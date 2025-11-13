<?php

namespace App\Livewire\Admin;

use App\Models\CardApplication;
use App\Models\CardApplicationLog;
use App\Models\JobseekerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;

class DashboardStats extends Component
{
    // KPI & summary
    public int $pending = 0;
    public int $approvedActive = 0;
    public int $rejectedThisMonth = 0;

    public int $totalPencaker = 0;
    public int $lengkapProfil = 0;
    public int $belumLengkap = 0;

    public int $activeSeekers = 0;
    public int $withTraining = 0;
    public int $withWork = 0;

    // charts raw
    public array $genderApproved = [];
    public array $educationApproved = [];
    public array $districtApproved = [];
    public array $typeDist = [];
    public array $statusDist = [];
    public array $monthlyLabels = [];
    public array $monthlyCounts = [];

    public array $recentLogs = [];
    public string $lastUpdated = '';

    // normalized chart payloads
    public array $genderChart = ['labels' => [], 'data' => []];
    public array $educationChart = ['labels' => [], 'data' => []];
    public array $districtChart = ['labels' => [], 'data' => []];

    public function mount(): void
    {
        $this->loadStats();
    }

    #[On('refresh-dashboard')]
    public function loadStats(): void
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        // KPI
        $this->pending = CardApplication::whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])->count();
        $this->approvedActive = CardApplication::where('status', 'Disetujui')->where('is_active', true)->count();
        $this->rejectedThisMonth = CardApplication::where('status', 'Ditolak')
            ->whereBetween('created_at', [$monthStart, now()])->count();

        // Summary pencaker
        $this->totalPencaker = User::where('role', 'pencaker')->count();
        $this->lengkapProfil = JobseekerProfile::whereNotNull('nik')->count();
        $this->belumLengkap = max(0, $this->totalPencaker - $this->lengkapProfil);

        $this->activeSeekers = User::whereHas('cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))->count();
        $this->withTraining = User::whereHas('cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))
            ->whereHas('jobseekerProfile.trainings')->count();
        $this->withWork = User::whereHas('cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))
            ->whereHas('jobseekerProfile.workExperiences')->count();

        // Distros approved & active
        $this->genderApproved = JobseekerProfile::selectRaw('UPPER(COALESCE(jenis_kelamin, "TIDAK DIKETAHUI")) as label, COUNT(*) total')
            ->whereHas('user.cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))
            ->groupBy('label')->pluck('total','label')->toArray();

        $this->educationApproved = JobseekerProfile::selectRaw('COALESCE(pendidikan_terakhir, "-") as label, COUNT(*) total')
            ->whereHas('user.cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))
            ->groupBy('label')->orderByDesc('total')->get()->map(fn($r)=>['label'=>$r->label,'total'=>(int)$r->total])->toArray();

        $this->districtApproved = JobseekerProfile::selectRaw('COALESCE(domisili_kecamatan, "-") as label, COUNT(*) total')
            ->whereHas('user.cardApplications', fn($q)=>$q->where('status','Disetujui')->where('is_active',true))
            ->groupBy('label')->orderByDesc('total')->limit(25)->get()->map(fn($r)=>['label'=>$r->label,'total'=>(int)$r->total])->toArray();

        // Komposisi bulan berjalan
        $this->typeDist = CardApplication::whereBetween('created_at', [$monthStart, now()])
            ->selectRaw('LOWER(type) as type, COUNT(*) as total')->groupBy('type')->pluck('total','type')->toArray();
        $this->statusDist = CardApplication::whereBetween('created_at', [$monthStart, now()])
            ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total','status')->toArray();

        // Rekap bulanan 12 bulan terakhir
        $start12 = now()->startOfMonth()->subMonths(11);
        $monthlyQuery = CardApplication::where('status','Disetujui');
        if (Schema::hasColumn('card_applications','approved_at')) {
            $monthlyQuery = $monthlyQuery->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$start12, now()])
                ->selectRaw("DATE_FORMAT(approved_at, '%Y-%m') ym, COUNT(*) total");
        } else {
            $monthlyQuery = $monthlyQuery->whereBetween('created_at', [$start12, now()])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') ym, COUNT(*) total");
        }
        $raw = $monthlyQuery->groupBy('ym')->orderBy('ym')->pluck('total','ym');
        $months = [];$counts=[];
        for($i=0;$i<12;$i++){
            $m = $start12->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $months[] = $m->translatedFormat('F Y');
            $counts[] = (int)($raw[$key] ?? 0);
        }
        $this->monthlyLabels = $months;
        $this->monthlyCounts = $counts;

        // Recent logs
        $this->recentLogs = CardApplicationLog::with('actor:id,name')->latest()->limit(10)
            ->get(['id','actor_id','action','to_status','created_at'])
            ->map(fn($l)=>[
                'time'=>$l->created_at?->format('d M H:i'),
                'actor'=>$l->actor?->name,
                'action'=>$l->action,
                'to'=>$l->to_status,
            ])->toArray();

        $this->lastUpdated = now()->format('d M Y H:i');

        // Prepare normalized dataset payloads for charts
        $this->genderChart = [
            'labels' => array_keys($this->genderApproved),
            'data'   => array_values($this->genderApproved),
        ];
        $this->educationChart = [
            'labels' => array_map(fn($r)=>$r['label'],$this->educationApproved),
            'data'   => array_map(fn($r)=>$r['total'],$this->educationApproved),
        ];
        $this->districtChart = [
            'labels' => array_map(fn($r)=>$r['label'],$this->districtApproved),
            'data'   => array_map(fn($r)=>$r['total'],$this->districtApproved),
        ];

        // Dispatch events so Alpine chart can update without re-instantiating
        $this->dispatch('updateChart', type: 'gender', data: $this->genderChart);
        $this->dispatch('updateChart', type: 'education', data: $this->educationChart);
        $this->dispatch('updateChart', type: 'district', data: $this->districtChart);
        $this->dispatch('updateChart', type: 'monthly', data: [
            'labels'=>$this->monthlyLabels,
            'data'=>$this->monthlyCounts,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}

