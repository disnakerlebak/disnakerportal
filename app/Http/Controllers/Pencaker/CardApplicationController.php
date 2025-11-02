<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

        $application = CardApplication::with([
                'documents',
                'logs' => fn($q) => $q->latest(),
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $lastApp = CardApplication::with('documents')
            ->where('user_id', $user->id)
            ->latest()
            ->skip(1)
            ->first();

        if ($application && $application->status === 'Menunggu Verifikasi' && !$application->documents->count()) {
            $application = $lastApp ?: $application;
        }

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

        $lastApp = CardApplication::with('documents')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        // 🚧 Cegah duplikasi pengajuan jika masih aktif
        if ($lastApp && !in_array($lastApp->status, ['Ditolak', 'Revisi Diminta'])) {
            return back()->with('error', 'Pengajuan Anda sebelumnya masih diproses atau sudah disetujui. Tidak dapat mengajukan ulang saat ini.');
        }

        $requiredDocs = [
            'foto_closeup' => [
                'label' => 'Foto close-up',
                'rule'  => 'image|max:2048',
                'folder'=> 'ak1/foto',
            ],
            'ktp_file' => [
                'label' => 'Scan KTP',
                'rule'  => 'mimes:jpg,jpeg,png,pdf|max:2048',
                'folder'=> 'ak1/ktp',
            ],
            'ijazah_file' => [
                'label' => 'Scan Ijazah Terakhir',
                'rule'  => 'mimes:jpg,jpeg,png,pdf|max:2048',
                'folder'=> 'ak1/ijazah',
            ],
        ];

        $isResubmission = $request->has('is_resubmission') && $lastApp && in_array($lastApp->status, ['Ditolak', 'Revisi Diminta']);

        $existingDocs = $isResubmission
            ? $lastApp->documents->keyBy('type')
            : collect();

        $baseRules = [];
        foreach ($requiredDocs as $field => $meta) {
            if ($request->hasFile($field)) {
                $baseRules[$field] = 'required|' . $meta['rule'];
            } elseif (!$isResubmission) {
                // Pengajuan pertama harus mengunggah semua berkas wajib
                $baseRules[$field] = 'required|' . $meta['rule'];
            }
        }

        $validator = Validator::make($request->all(), $baseRules);

        $validator->after(function ($validator) use ($requiredDocs, $request, $existingDocs, $isResubmission) {
            foreach ($requiredDocs as $field => $meta) {
                $hasNew = $request->hasFile($field);
                $hasOld = $existingDocs->has($field);
                if (!$hasNew && (!$isResubmission || !$hasOld)) {
                    $validator->errors()->add($field, "Dokumen {$meta['label']} wajib diunggah.");
                }
            }
        });

        $validator->validate();

        try {
            DB::beginTransaction();

            if ($isResubmission) {
                $lastApp->update([
                    'status' => 'Menunggu Revisi Verifikasi',
                ]);

                $application = $lastApp->fresh('documents');
            } else {
                $application = CardApplication::create([
                    'user_id'           => $user->id,
                    'status'            => 'Menunggu Verifikasi',
                ]);
                $application->load('documents');
            }

            foreach ($requiredDocs as $input => $meta) {
                $folder = $meta['folder'];
                if ($request->hasFile($input)) {
                    $storedPath = $request->file($input)->store($folder, 'public');
                    if ($existingDocs->has($input)) {
                        Storage::disk('public')->delete($existingDocs[$input]->file_path);
                    }

                    $application->documents()->updateOrCreate(
                        ['type' => $input],
                        ['file_path' => $storedPath]
                    );
                } elseif (!$isResubmission) {
                    // seharusnya tidak terjadi karena validasi, tapi sebagai pengaman
                    throw new \RuntimeException("Dokumen {$input} belum diunggah");
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
