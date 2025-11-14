<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\JobseekerProfile;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Education;
use App\Models\Training;
use App\Models\WorkExperience;
use App\Models\JobPreference;
use App\Models\CardApplication;
use App\Models\CardApplicationLog;

class JobseekerController extends Controller
{
    public function index(Request $request)
    {
        // Hanya user yang punya CardApplication berstatus "Disetujui"
        $query = User::query()
            ->whereHas('cardApplications', fn($q) => $q->where('status', 'Disetujui')->where('is_active', true))
            ->with([
                // ambil profil + hitung jumlah pelatihan/pengalaman kerja (efisien)
                'jobseekerProfile' => fn($q) => $q->withCount(['trainings', 'workExperiences']),
                // ambil 1 aplikasi AK1 yang disetujui paling terbaru (untuk foto/berkas)
                'cardApplications' => fn($q) => $q->where('status', 'Disetujui')->where('is_active', true)->latest()->limit(1),
            ]);

        // Filter: kata kunci (nama)
        if ($request->filled('q')) {
            $keyword = trim($request->string('q'));
            $query->where(function ($w) use ($keyword) {
                $w->where('name', 'like', "%{$keyword}%")
                  ->orWhereHas('jobseekerProfile', function ($p) use ($keyword) {
                      $p->where('nama_lengkap', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter: memiliki pelatihan / pengalaman
        if ($request->boolean('has_training')) {
            $query->whereHas('jobseekerProfile.trainings');
        }
        if ($request->boolean('has_work')) {
            $query->whereHas('jobseekerProfile.workExperiences');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.pencaker.index', [
            'users' => $users,
            'filters' => [
                'has_training' => $request->boolean('has_training'),
                'has_work' => $request->boolean('has_work'),
            ],
        ]);
    }

    public function show(User $user)
    {
        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        $logs    = ActivityLog::where('user_id', $user->id)->latest()->limit(50)->get();

        return view('admin.pencaker.show', compact('user','profile','logs'));
    }

    public function ajaxDetail(User $user)
    {
        $profile     = JobseekerProfile::where('user_id', $user->id)->first();
        $educations  = $profile ? Education::where('jobseeker_profile_id', $profile->id)->orderByDesc('tahun_selesai')->get() : collect();
        $trainings   = $profile ? Training::where('jobseeker_profile_id', $profile->id)->orderByDesc('tahun')->get() : collect();
        $works       = $profile ? WorkExperience::where('jobseeker_profile_id', $profile->id)->orderByDesc('tahun_selesai')->get() : collect();
        $preference  = JobPreference::where('user_id', $user->id)->first();
        $latestApp   = CardApplication::where('user_id', $user->id)
                            ->where('status', 'Disetujui')
                            ->where('is_active', true)
                            ->latest()
                            ->first();

        $fotoPath = null;
        if ($latestApp) {
            $fotoDoc = $latestApp->documents()->where('type', 'foto_closeup')->latest()->first();
            $fotoPath = $fotoDoc?->file_path;
        }

        return view('admin.pencaker.partials.detail', compact(
            'user','profile','educations','trainings','works','preference','latestApp','fotoPath'
        ));
    }

    public function history(User $user)
    {
        // Ambil riwayat aktivitas dari activity_logs
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat pengajuan AK1 dari card_application_logs
        $cardApplicationLogs = CardApplicationLog::whereHas('application', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['application', 'actor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Gabungkan dan urutkan berdasarkan waktu
        $allLogs = collect();
        
        // Tambahkan activity logs
        foreach ($activityLogs as $log) {
            $allLogs->push([
                'type' => 'activity',
                'created_at' => $log->created_at,
                'data' => $log,
            ]);
        }

        // Tambahkan card application logs
        foreach ($cardApplicationLogs as $log) {
            $allLogs->push([
                'type' => 'ak1',
                'created_at' => $log->created_at,
                'data' => $log,
            ]);
        }

        // Urutkan berdasarkan waktu (terbaru dulu)
        $allLogs = $allLogs->sortByDesc('created_at')->values();

        $profile = JobseekerProfile::where('user_id', $user->id)->first();

        return view('admin.pencaker.history', compact('user', 'profile', 'allLogs'));
    }
}
