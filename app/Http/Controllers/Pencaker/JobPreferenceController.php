<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\JobPreference;
use Illuminate\Http\Request;

class JobPreferenceController extends Controller
{
    public function index(Request $request)
    {
        return redirect()
            ->route('pencaker.profile')
            ->with('accordion', 'preference');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'minat_lokasi' => 'nullable|array',
            'minat_bidang' => 'nullable|array',
            'gaji_harapan' => 'nullable|string|max:100',
            'deskripsi_diri' => 'nullable|string|max:2000',
        ]);

        $preference = JobPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'minat_lokasi' => $validated['minat_lokasi'] ?? [],
                'minat_bidang' => $validated['minat_bidang'] ?? [],
                'gaji_harapan' => $validated['gaji_harapan'] ?? null,
                'deskripsi_diri' => $validated['deskripsi_diri'] ?? null,
            ]
        );

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Minat kerja berhasil disimpan.')
            ->with('accordion', 'preference');
    }
}
