<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\JobseekerProfile;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $profile = JobseekerProfile::where('user_id', $request->user()->id)->firstOrFail();
        $works = WorkExperience::where('jobseeker_profile_id', $profile->id)->latest()->get();

        return view('pencaker.work.index', compact('works'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4',
            'tahun_selesai' => 'nullable|digits:4|gte:tahun_mulai',
            'surat_pengalaman' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $profile = JobseekerProfile::where('user_id', $request->user()->id)->firstOrFail();

        if ($request->hasFile('surat_pengalaman')) {
            $validated['surat_pengalaman'] = $request->file('surat_pengalaman')->store('surat_pengalaman', 'public');
        }

        $validated['jobseeker_profile_id'] = $profile->id;

        WorkExperience::create($validated);

        return redirect()->route('pencaker.work.index')->with('success', 'Riwayat kerja berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
{
    $work = \App\Models\WorkExperience::findOrFail($id);

    $validated = $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'jabatan' => 'required|string|max:255',
        'tahun_mulai' => 'required|digits:4',
        'tahun_selesai' => 'nullable|digits:4|gte:tahun_mulai',
        'surat_pengalaman' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Jika upload file baru, hapus yang lama
    if ($request->hasFile('surat_pengalaman')) {
        if ($work->surat_pengalaman && \Storage::disk('public')->exists($work->surat_pengalaman)) {
            \Storage::disk('public')->delete($work->surat_pengalaman);
        }
        $validated['surat_pengalaman'] = $request->file('surat_pengalaman')->store('surat_pengalaman', 'public');
    }

    $work->update($validated);

    return redirect()->route('pencaker.work.index')->with('success', 'Data riwayat kerja berhasil diperbarui.');
}


    public function destroy(WorkExperience $work)
    {
        if ($work->surat_pengalaman) {
            Storage::disk('public')->delete($work->surat_pengalaman);
        }

        $work->delete();

        return redirect()->route('pencaker.work.index')->with('success', 'Data riwayat kerja dihapus.');
    }
}
