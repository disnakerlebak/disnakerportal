<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

class JobPostingController extends Controller
{
    public function index()
    {
        return view('company.jobs.index');
    }

    public function create()
    {
        return view('company.jobs.create');
    }
}

