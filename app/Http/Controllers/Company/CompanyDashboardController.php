<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $company = CompanyProfile::where('user_id', $user->id)->first();

        $verificationStatus = $company ? 'Terverifikasi' : 'Belum Diverifikasi';

        $activeJobsCount = 0;
        $totalApplicants = 0;
        $monthlyApplicants = 0;

        if ($company) {
            $activeJobsCount = JobPosting::where('company_id', $company->id)
                ->where('status', 'aktif')
                ->count();

            $totalApplicants = JobApplication::where('company_id', $company->id)->count();

            $monthlyApplicants = JobApplication::where('company_id', $company->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return view('company.dashboard', [
            'verificationStatus' => $verificationStatus,
            'activeJobsCount'    => $activeJobsCount,
            'totalApplicants'    => $totalApplicants,
            'monthlyApplicants'  => $monthlyApplicants,
        ]);
    }
}

