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

class JobseekerController extends Controller
{
    public function index()
    {
        // Hanya user yang punya CardApplication berstatus "Disetujui"
        $users = User::query()
            ->whereHas('cardApplications', fn($q) => $q->where('status', 'Disetujui'))
            ->with([
                'jobseekerProfile',
                // ambil 1 aplikasi AK1 yang disetujui paling terbaru (untuk foto/berkas)
                'cardApplications' => fn($q) => $q->where('status', 'Disetujui')->latest()->limit(1),
            ])
            ->latest()
            ->paginate(20);

        return view('admin.pencaker.index', compact('users'));
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
}
