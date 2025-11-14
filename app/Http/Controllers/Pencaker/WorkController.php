<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\JobseekerProfile;
use App\Models\WorkExperience;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        return redirect()
            ->route('pencaker.profile')
            ->with('accordion', 'work');
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

        $work = WorkExperience::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'created',
            'model_type' => WorkExperience::class,
            'model_id' => $work->id,
            'description' => 'Menambah riwayat kerja: ' . $validated['jabatan'] . ' di ' . $validated['nama_perusahaan'],
        ]);

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Riwayat kerja berhasil ditambahkan.')
            ->with('accordion', 'work');
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

    ActivityLog::create([
        'user_id' => $request->user()->id,
        'action' => 'updated',
        'model_type' => WorkExperience::class,
        'model_id' => $work->id,
        'description' => 'Memperbarui riwayat kerja: ' . $validated['jabatan'] . ' di ' . $validated['nama_perusahaan'],
    ]);

    return redirect()
        ->route('pencaker.profile')
        ->with('success', 'Data riwayat kerja berhasil diperbarui.')
        ->with('accordion', 'work');
}


    public function destroy(WorkExperience $work)
    {
        $workId = $work->id;
        $workInfo = $work->jabatan . ' di ' . $work->nama_perusahaan;
        
        if ($work->surat_pengalaman) {
            Storage::disk('public')->delete($work->surat_pengalaman);
        }

        $work->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model_type' => WorkExperience::class,
            'model_id' => $workId,
            'description' => 'Menghapus riwayat kerja: ' . $workInfo,
        ]);

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Data riwayat kerja dihapus.')
            ->with('accordion', 'work');
    }
}
