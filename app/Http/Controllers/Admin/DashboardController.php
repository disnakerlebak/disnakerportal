<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardApplication;
use App\Models\CardApplicationLog;
use App\Models\JobseekerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPencaker = User::where('role', 'pencaker')->count();

        $lengkapProfil = JobseekerProfile::whereNotNull('nik')->count();
        $belumLengkap = max(0, $totalPencaker - $lengkapProfil);

        $users = User::where('role', 'pencaker')
            ->latest()
            ->take(5)
            ->with('jobseekerProfile')
            ->get();

        // AK1 pipeline KPI
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $pending = CardApplication::whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])->count();
        $approvedActive = CardApplication::where('status', 'Disetujui')->where('is_active', true)->count();
        $rejectedThisMonth = CardApplication::where('status', 'Ditolak')
            ->whereBetween('created_at', [$monthStart, now()])->count();

        // Komposisi bulan berjalan
        $typeDist = CardApplication::whereBetween('created_at', [$monthStart, now()])
            ->selectRaw('LOWER(type) as type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $statusDist = CardApplication::whereBetween('created_at', [$monthStart, now()])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Aktivitas terbaru
        $recentLogs = CardApplicationLog::with('actor:id,name')
            ->latest()
            ->limit(10)
            ->get();

        // Pencaker aktif & kualitas profil
        $activeSeekers = User::whereHas('cardApplications', function ($q) {
            $q->where('status', 'Disetujui')->where('is_active', true);
        })->count();

        $withTraining = User::whereHas('cardApplications', function ($q) {
            $q->where('status', 'Disetujui')->where('is_active', true);
        })->whereHas('jobseekerProfile.trainings')->count();

        $withWork = User::whereHas('cardApplications', function ($q) {
            $q->where('status', 'Disetujui')->where('is_active', true);
        })->whereHas('jobseekerProfile.workExperiences')->count();

        // Statistik untuk pencaker Disetujui & aktif
        $genderApproved = JobseekerProfile::selectRaw('UPPER(COALESCE(jenis_kelamin, "TIDAK DIKETAHUI")) as label, COUNT(*) as total')
            ->whereHas('user.cardApplications', function ($q) { $q->where('status', 'Disetujui')->where('is_active', true); })
            ->groupBy('label')
            ->pluck('total', 'label');

        $educationApproved = JobseekerProfile::selectRaw('COALESCE(pendidikan_terakhir, "-") as label, COUNT(*) as total')
            ->whereHas('user.cardApplications', function ($q) { $q->where('status', 'Disetujui')->where('is_active', true); })
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $districtApproved = JobseekerProfile::selectRaw('COALESCE(domisili_kecamatan, "-") as label, COUNT(*) as total')
            ->whereHas('user.cardApplications', function ($q) { $q->where('status', 'Disetujui')->where('is_active', true); })
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(25)
            ->get();

        // Tren 30 hari
        $dailyTrend = CardApplication::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        // Rekap per bulan (12 bulan terakhir) berdasarkan approved_at
        $start12 = now()->startOfMonth()->subMonths(11);
        $monthlyQuery = CardApplication::where('status', 'Disetujui');
        if (Schema::hasColumn('card_applications', 'approved_at')) {
            $monthlyQuery = $monthlyQuery
                ->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$start12, now()])
                ->selectRaw("DATE_FORMAT(approved_at, '%Y-%m') as ym, COUNT(*) as total");
        } else {
            // Fallback: gunakan created_at jika approved_at belum ada di DB
            $monthlyQuery = $monthlyQuery
                ->whereBetween('created_at', [$start12, now()])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total");
        }
        $monthlyRaw = $monthlyQuery
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $months = [];
        $counts = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $start12->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $months[] = $m->translatedFormat('F Y');
            $counts[] = (int) ($monthlyRaw[$key] ?? 0);
        }

        $lastUpdated = now();

        return view('admin.dashboard', [
            'totalPencaker' => $totalPencaker,
            'lengkapProfil' => $lengkapProfil,
            'belumLengkap' => $belumLengkap,
            'users' => $users,

            'pending' => $pending,
            'approvedActive' => $approvedActive,
            'rejectedThisMonth' => $rejectedThisMonth,
            // fitur dicetak/diambil tidak digunakan lagi

            'typeDist' => $typeDist,
            'statusDist' => $statusDist,
            'recentLogs' => $recentLogs,

            'activeSeekers' => $activeSeekers,
            'withTraining' => $withTraining,
            'withWork' => $withWork,
            'genderApproved' => $genderApproved,
            'educationApproved' => $educationApproved,
            'districtApproved' => $districtApproved,
            'dailyTrend' => $dailyTrend,
            'monthlyLabels' => $months,
            'monthlyCounts' => $counts,
            'lastUpdated' => $lastUpdated,
        ]);
    }
}
