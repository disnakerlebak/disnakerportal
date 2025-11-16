<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;

class CompanyManagementController extends Controller
{
    public function index()
    {
        return view('admin.company.index');
    }

    public function show(CompanyProfile $company)
    {
        $company->load('user');

        return view('admin.company.show', [
            'company' => $company,
        ]);
    }
}
