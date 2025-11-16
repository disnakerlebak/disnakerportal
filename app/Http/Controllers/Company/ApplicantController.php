<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

class ApplicantController extends Controller
{
    public function index()
    {
        return view('company.applicants.index');
    }

    public function history()
    {
        return view('company.applicants.history');
    }
}

