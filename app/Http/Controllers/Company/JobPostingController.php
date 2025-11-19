<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index()
    {
        return view('company.jobs.index');
    }

    public function create(Request $request)
    {
        $company = CompanyProfile::where('user_id', $request->user()->id)->first();

        if (!$company || $company->verification_status !== 'approved') {
            return redirect()
                ->route('company.dashboard')
                ->with('error', 'Perusahaan Anda belum diverifikasi oleh admin. Hubungi Disnaker untuk melanjutkan proses verifikasi sebelum membuat lowongan.');
        }

        return view('company.jobs.create');
    }
}
