<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\JobseekerProfile;
use App\Models\CardApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        return redirect()
            ->route('pencaker.profile')
            ->with('accordion', 'training');
    }

    public function store(Request $request)
    {
        if ($this->isEditingLocked($request->user()->id)) {
            return back()->with('error', 'Menambah pelatihan dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }
        $validated = $request->validate([
            'jenis_pelatihan' => 'required|string|max:255',
            'lembaga_pelatihan' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'sertifikat_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $profile = JobseekerProfile::where('user_id', $request->user()->id)->firstOrFail();

        // simpan file sertifikat
        if ($request->hasFile('sertifikat_file')) {
            $validated['sertifikat_file'] = $request->file('sertifikat_file')->store('sertifikat', 'public');
        }

        $validated['jobseeker_profile_id'] = $profile->id;

        Training::create($validated);

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Riwayat pelatihan berhasil ditambahkan.')
            ->with('accordion', 'training');
    }

    public function update(Request $request, $id)
{
    $training = \App\Models\Training::findOrFail($id);

    if ($this->isEditingLocked($request->user()->id)) {
        return back()->with('error', 'Mengubah pelatihan dikunci karena pengajuan AK1 sedang diproses/diterima.');
    }

    $validated = $request->validate([
        'jenis_pelatihan' => 'required|string|max:255',
        'lembaga_pelatihan' => 'required|string|max:255',
        'tahun' => 'required|digits:4',
        'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('sertifikat_file')) {
        // hapus file lama jika ada
        if ($training->sertifikat_file && \Storage::disk('public')->exists($training->sertifikat_file)) {
            \Storage::disk('public')->delete($training->sertifikat_file);
        }
        $validated['sertifikat_file'] = $request->file('sertifikat_file')->store('sertifikat', 'public');
    }

    $training->update($validated);

    return redirect()
        ->route('pencaker.profile')
        ->with('success', 'Data pelatihan berhasil diperbarui.')
        ->with('accordion', 'training');
}

    public function destroy(Training $training)
    {
        if ($this->isEditingLocked(auth()->id())) {
            return back()->with('error', 'Menghapus pelatihan dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }
        if ($training->sertifikat_file) {
            Storage::disk('public')->delete($training->sertifikat_file);
        }
        $training->delete();

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Data pelatihan berhasil dihapus.')
            ->with('accordion', 'training');
}

    private function isEditingLocked(int $userId): bool
    {
        $last = CardApplication::where('user_id', $userId)->latest()->first();
        return $last && in_array($last->status, ['Menunggu Verifikasi', 'Disetujui']);
    }
}
