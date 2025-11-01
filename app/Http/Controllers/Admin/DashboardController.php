<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPencaker = \App\Models\User::where('role', 'pencaker')->count();

        $lengkapProfil = \App\Models\JobseekerProfile::whereNotNull('nik')->count();
        $belumLengkap = $totalPencaker - $lengkapProfil;
    
        $users = \App\Models\User::where('role', 'pencaker')
            ->latest()
            ->take(5)
            ->with('jobseekerProfile')
            ->get();
    
        return view('admin.dashboard', compact('totalPencaker', 'lengkapProfil', 'belumLengkap', 'users'));
    }
}
