<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RejectionReason;
use Illuminate\Http\Request;

class RejectionReasonController extends Controller
{
    public function index()
    {
        $reasons = RejectionReason::latest()->get();
        return view('admin.rejection_reasons.index', compact('reasons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        RejectionReason::create($validated);
        return redirect()->route('admin.rejection-reasons.index')->with('success', 'Alasan penolakan berhasil ditambahkan.');
    }

    public function update(Request $request, RejectionReason $rejectionReason)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $rejectionReason->update($validated);
        return redirect()->route('admin.rejection-reasons.index')->with('success', 'Alasan penolakan berhasil diperbarui.');
    }

    public function destroy(RejectionReason $rejectionReason)
    {
        $rejectionReason->delete();
        return redirect()->route('admin.rejection-reasons.index')->with('success', 'Alasan penolakan berhasil dihapus.');
    }
}
