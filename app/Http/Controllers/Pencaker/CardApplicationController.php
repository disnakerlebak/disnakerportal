<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{
    JobseekerProfile,
    Education,
    Training,
    CardApplication,
    CardApplicationDocument
};

class CardApplicationController extends Controller
{
    /**
     * Halaman pengajuan AK1
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        $educations = Education::whereHas('profile', fn($q) => $q->where('user_id', $user->id))->get();
        $trainings = Training::whereHas('profile', fn($q) => $q->where('user_id', $user->id))->get();

        $application = CardApplication::with('documents')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('pencaker.card.index', compact(
            'profile', 'educations', 'trainings', 'application'
        ));
    }

    /**
     * Simpan pengajuan AK1 (baru atau revisi)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $lastApp = CardApplication::where('user_id', $user->id)
            ->latest()
            ->first();

        // 🚧 Cegah duplikasi pengajuan jika masih aktif
        if ($lastApp && !in_array($lastApp->status, ['Ditolak', 'Revisi Diminta'])) {
            return back()->with('error', 'Pengajuan Anda sebelumnya masih diproses atau sudah disetujui. Tidak dapat mengajukan ulang saat ini.');
        }

        // 🧩 Validasi hanya untuk file yang benar-benar diunggah
        $validated = $request->validate([
            'foto_closeup' => $request->hasFile('foto_closeup') ? 'image|max:2048' : 'nullable',
            'ktp_file'     => $request->hasFile('ktp_file') ? 'mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable',
            'ijazah_file'  => $request->hasFile('ijazah_file') ? 'mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // 🧱 Buat atau perbarui pengajuan baru
            $application = CardApplication::create([
                'user_id' => $user->id,
                // Jika sebelumnya revisi → ubah ke status menunggu verifikasi ulang
                'status' => ($lastApp && $lastApp->status === 'Revisi Diminta')
                    ? 'Menunggu Revisi Verifikasi'
                    : 'Menunggu Verifikasi',
                'tanggal_pengajuan' => now(),
            ]);

            // 📦 Daftar dokumen
            $dokumenList = [
                'foto_closeup' => 'ak1/foto',
                'ktp_file'     => 'ak1/ktp',
                'ijazah_file'  => 'ak1/ijazah',
            ];

            foreach ($dokumenList as $input => $folder) {
                // Kalau file baru diunggah → simpan baru
                if ($request->hasFile($input)) {
                    $storedPath = $request->file($input)->store($folder, 'public');
                    CardApplicationDocument::create([
                        'card_application_id' => $application->id,
                        'type' => $input,
                        'file_path' => $storedPath,
                    ]);
                }
                // Kalau file tidak diunggah ulang, tapi ada dari revisi sebelumnya → duplikasi path lama
                elseif ($lastApp) {
                    $oldDoc = $lastApp->documents()->where('type', $input)->first();
                    if ($oldDoc) {
                        CardApplicationDocument::create([
                            'card_application_id' => $application->id,
                            'type' => $oldDoc->type,
                            'file_path' => $oldDoc->file_path,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('pencaker.card.index')
                ->with('success', 'Pengajuan kartu pencari kerja berhasil dikirim. Menunggu verifikasi Disnaker.');

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('AK1 submit error', [
                'user_id' => $user->id,
                'msg' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Pengajuan gagal dikirim. ' . $e->getMessage());
        }
    }

    /**
     * Cetak/unduh kartu AK1 untuk pencaker (hanya jika Disetujui dan milik sendiri)
     */
    public function cetakPdf(CardApplication $application, Request $request)
    {
        // Pastikan milik user yang login
        if ($application->user_id !== $request->user()->id) {
            abort(403);
        }

        // Hanya boleh diunduh jika sudah disetujui
        if ($application->status !== 'Disetujui') {
            return back()->with('error', 'Kartu hanya dapat diunduh jika pengajuan sudah disetujui.');
        }

        // Muat relasi yang dibutuhkan
        $application->load([
            'user.jobseekerProfile.educations',
            'user.jobseekerProfile.trainings',
            'documents',
        ]);

        $profile    = optional($application->user)->jobseekerProfile;
        $educations = $profile ? $profile->educations : collect();
        $trainings  = $profile ? $profile->trainings  : collect();

        $fotoDoc  = $application->documents->firstWhere('type', 'foto_closeup');
        $fotoPath = $fotoDoc && $fotoDoc->file_path
            ? storage_path('app/public/' . $fotoDoc->file_path)
            : null;
        if ($fotoPath && !file_exists($fotoPath)) {
            $fotoPath = null;
        }

        $data = [
            'application' => $application,
            'profile'     => $profile,
            'educations'  => $educations,
            'trainings'   => $trainings,
            'fotoPath'    => $fotoPath,
        ];

        $pdf = Pdf::loadView('pdf.ak1_card', $data)->setPaper('legal', 'portrait');
        $filename = 'AK1-' . ($application->nomor_ak1 ?? 'Belum-Ditetapkan') . '.pdf';
        return $pdf->stream($filename);
    }
}
