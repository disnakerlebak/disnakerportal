<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\JobseekerProfile;
use App\Models\CardApplication;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $repairMode = $request->session()->get('ak1_repair_mode', false);

        if ($repairMode) {
            return redirect()->route('pencaker.card.repair');
        }

        return redirect()
            ->route('pencaker.profile')
            ->with('accordion', 'education');
    }

    public function create()
    {
        return view('pencaker.education.form', ['education' => new Education()]);
    }

    public function store(Request $request)
    {
        $repairMode = $request->session()->get('ak1_repair_mode', false) || $request->boolean('repair_mode');

        if ($this->isEditingLocked($request->user()->id) && !$repairMode) {
            return back()->with('error', 'Menambah pendidikan dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }
        $validated = $request->validate([
            'tingkat' => 'required|string|max:50',
            'nama_institusi' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4',
            'tahun_selesai' => 'nullable|digits:4|gte:tahun_mulai',
        ]);

        $profile = JobseekerProfile::firstOrCreate(['user_id' => $request->user()->id]);

        $profile->educations()->create($validated);

        if ($repairMode) {
            return redirect()->route('pencaker.card.repair')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
        }

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Riwayat pendidikan berhasil ditambahkan.')
            ->with('accordion', 'education');
    }

    public function edit(Education $education)
    {
        $this->authorizeEducation($education);
        return view('pencaker.education.form', compact('education'));
    }

    public function update(Request $request, Education $education)
    {
        $this->authorizeEducation($education);
        $repairMode = $request->session()->get('ak1_repair_mode', false) || $request->boolean('repair_mode');

        if ($this->isEditingLocked($request->user()->id) && !$repairMode) {
            return back()->with('error', 'Mengubah pendidikan dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }

        $validated = $request->validate([
            'tingkat' => 'required|string|max:50',
            'nama_institusi' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4',
            'tahun_selesai' => 'nullable|digits:4|gte:tahun_mulai',
        ]);

        $education->update($validated);

        if ($repairMode) {
            return redirect()->route('pencaker.card.repair')->with('success', 'Riwayat pendidikan berhasil diperbarui.');
        }

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Riwayat pendidikan berhasil diperbarui.')
            ->with('accordion', 'education');
    }
    public function show(Education $education)
    {   
        return redirect()
            ->route('pencaker.profile')
            ->with('accordion', 'education');
    }
    public function destroy(Request $request, Education $education)
    {
        $this->authorizeEducation($education);
        $repairMode = $request->session()->get('ak1_repair_mode', false);

        if ($this->isEditingLocked($request->user()->id) && !$repairMode) {
            return back()->with('error', 'Menghapus pendidikan dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }
        $education->delete();
        if ($repairMode) {
            return redirect()->route('pencaker.card.repair')->with('success', 'Riwayat pendidikan dihapus.');
        }

        return redirect()
            ->route('pencaker.profile')
            ->with('success', 'Riwayat pendidikan dihapus.')
            ->with('accordion', 'education');
    }

    private function authorizeEducation(Education $education)
    {
        if ($education->profile->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function isEditingLocked(int $userId): bool
    {
        $last = CardApplication::where('user_id', $userId)->latest()->first();
        return $last && in_array($last->status, ['Menunggu Verifikasi', 'Disetujui']);
    }
}
