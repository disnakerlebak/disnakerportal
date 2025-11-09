<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\CardApplicationSnapshot;
use Carbon\Carbon;
use App\Models\{
    JobseekerProfile,
    Education,
    Training,
    CardApplication,
    CardApplicationDocument,
    CardApplicationLog
};

class CardApplicationController extends Controller
{
    /**
     * Halaman pengajuan AK1
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $request->session()->forget('ak1_repair_mode');

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

    public function repairForm(Request $request)
    {
        $user = $request->user();

        $activeApp = CardApplication::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereIn('status', ['Disetujui', 'Dicetak', 'Diambil'])
            ->orderByDesc('id')
            ->first();

        if (!$activeApp) {
            return redirect()->route('pencaker.card.index')
                ->with('error', 'Tidak ada kartu AK1 aktif yang dapat diperbaiki.');
        }

        $hasPendingRepair = CardApplication::where('user_id', $user->id)
            ->whereIn('type', ['perbaikan'])
            ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi', 'Revisi Diminta'])
            ->exists();

        if ($hasPendingRepair) {
            return redirect()->route('pencaker.card.index')
                ->with('error', 'Pengajuan perbaikan sebelumnya masih diproses.');
        }

        $request->session()->put('ak1_repair_mode', true);

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        $educations = Education::whereHas('profile', fn($q) => $q->where('user_id', $user->id))->get();
        $trainings = Training::whereHas('profile', fn($q) => $q->where('user_id', $user->id))->get();

        $activeApp->loadMissing('documents', 'logs');

        $baselineSnapshot = $activeApp->snapshot_after ?? CardApplicationSnapshot::capture($activeApp);
        if (!$activeApp->snapshot_after) {
            $activeApp->snapshot_after = $baselineSnapshot;
            $activeApp->save();
        }

        $currentSnapshot = CardApplicationSnapshot::captureFromContext($user, $activeApp->documents);
        $snapshotChanged = $this->snapshotHash($baselineSnapshot) !== $this->snapshotHash($currentSnapshot);

        $activeApp->loadMissing('logs');
        $approvedAt = $this->approvedLogAt($activeApp);

        return view('pencaker.card.repair', [
            'profile' => $profile,
            'educations' => $educations,
            'trainings' => $trainings,
            'application' => $activeApp,
            'currentSnapshot' => $currentSnapshot,
            'snapshotChanged' => $snapshotChanged,
            'kecamatanList' => $this->districtOptions(),
            'approvedAt' => $approvedAt,
        ]);
    }

    public function submitRepair(Request $request)
    {
        $user = $request->user();

        $activeApp = CardApplication::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereIn('status', ['Disetujui', 'Dicetak', 'Diambil'])
            ->orderByDesc('id')
            ->first();

        if (!$activeApp) {
            return redirect()->route('pencaker.card.index')->with('error', 'Tidak ada kartu AK1 aktif yang dapat diperbaiki.');
        }

        $hasPendingRepair = CardApplication::where('user_id', $user->id)
            ->where('type', 'perbaikan')
            ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi', 'Revisi Diminta'])
            ->exists();

        if ($hasPendingRepair) {
            return redirect()->route('pencaker.card.index')->with('error', 'Pengajuan perbaikan sebelumnya masih diproses.');
        }

        $activeApp->loadMissing('documents');

        $baselineSnapshot = $activeApp->snapshot_after ?? CardApplicationSnapshot::capture($activeApp);
        if (!$activeApp->snapshot_after) {
            $activeApp->snapshot_after = $baselineSnapshot;
            $activeApp->save();
        }

        $currentSnapshot = CardApplicationSnapshot::captureFromContext($user, $activeApp->documents);
        $hasStructureChange = $this->snapshotHash($baselineSnapshot) !== $this->snapshotHash($currentSnapshot);
        $hasDocumentChange = $request->hasFile('foto_closeup') || $request->hasFile('ktp_file') || $request->hasFile('ijazah_file');

        if (!$hasStructureChange && !$hasDocumentChange) {
            return back()->with('error', 'Belum ada perubahan data atau dokumen yang diajukan untuk perbaikan.');
        }

        $docRules = [
            'foto_closeup' => 'nullable|image|max:2048',
            'ktp_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $request->validate($docRules);

        try {
            DB::beginTransaction();

            $repairApp = CardApplication::create([
                'user_id' => $user->id,
                'status' => 'Menunggu Verifikasi',
                'type' => 'perbaikan',
                'parent_id' => $activeApp->id,
                'nomor_ak1' => $activeApp->nomor_ak1,
                'tanggal_pengajuan' => now(),
                'is_active' => false,
                'snapshot_before' => $baselineSnapshot,
            ]);

            $docTypes = [
                'foto_closeup' => [
                    'folder' => 'ak1/foto',
                ],
                'ktp_file' => [
                    'folder' => 'ak1/ktp',
                ],
                'ijazah_file' => [
                    'folder' => 'ak1/ijazah',
                ],
            ];

            $activeDocs = $activeApp->documents->keyBy('type');

            foreach ($docTypes as $input => $meta) {
                if ($request->hasFile($input)) {
                    $storedPath = $request->file($input)->store($meta['folder'], 'public');

                    $repairApp->documents()->create([
                        'type' => $input,
                        'file_path' => $storedPath,
                    ]);
                } elseif ($activeDocs->has($input)) {
                    $repairApp->documents()->create([
                        'type' => $input,
                        'file_path' => $activeDocs[$input]->file_path,
                    ]);
                }
            }

            $repairApp->load('documents', 'user.jobseekerProfile.educations', 'user.jobseekerProfile.trainings');
            $repairApp->snapshot_after = CardApplicationSnapshot::capture($repairApp);
            $repairApp->save();

            CardApplicationLog::create([
                'card_application_id' => $repairApp->id,
                'actor_id' => $user->id,
                'action' => 'repair_submit',
                'from_status' => $activeApp->status,
                'to_status' => $repairApp->status,
                'notes' => 'Pengajuan perbaikan oleh pemohon.',
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            ]);

            DB::commit();

            $request->session()->forget('ak1_repair_mode');

            return redirect()->route('pencaker.card.index')->with('success', 'Pengajuan perbaikan AK1 berhasil dikirim. Menunggu verifikasi admin.');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('AK1 repair submit error', [
                'user_id' => $user->id,
                'msg' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengajukan perbaikan: ' . $e->getMessage());
        }
    }

    public function renewalForm(Request $request)
    {
        $user = $request->user();

        $activeCard = CardApplication::with(['documents', 'logs' => fn ($q) => $q->latest()])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $latestApproved = CardApplication::with(['documents', 'logs' => fn ($q) => $q->latest()])
            ->where('user_id', $user->id)
            ->whereIn('status', ['Disetujui', 'Dicetak', 'Diambil'])
            ->latest('id')
            ->first();

        if (!$latestApproved) {
            return redirect()->route('pencaker.card.index')
                ->with('error', 'Belum ada kartu AK1 yang dapat diperpanjang.');
        }

        $referenceCard = $activeCard ?? $latestApproved;

        $referenceCard->loadMissing('documents', 'logs');
        $currentSnapshot = CardApplicationSnapshot::captureFromContext($user, $referenceCard->documents);
        $baselineSnapshot = $referenceCard->snapshot_after ?? $currentSnapshot;
        $snapshotChanged = $baselineSnapshot !== $currentSnapshot;
        $approvedAt = $this->approvedLogAt($referenceCard);
        $expiresAt = $approvedAt ? Carbon::parse($approvedAt)->copy()->addYears(2) : null;
        $isExpired = $expiresAt ? Carbon::now()->greaterThanOrEqualTo($expiresAt) : false;

        if ($activeCard && $isExpired && $activeCard->is_active) {
            $activeCard->update(['is_active' => false]);
            $activeCard = null;
        }

        $hasPendingRenewal = CardApplication::where('user_id', $user->id)
            ->where('type', 'perpanjangan')
            ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi', 'Revisi Diminta'])
            ->exists();

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        $educations = Education::whereHas('profile', fn ($q) => $q->where('user_id', $user->id))->get();
        $trainings = Training::whereHas('profile', fn ($q) => $q->where('user_id', $user->id))->get();

        $previewCard = $activeCard ?? $latestApproved;

        return view('pencaker.card.renewal', [
            'profile' => $profile,
            'educations' => $educations,
            'trainings' => $trainings,
            'application' => $referenceCard,
            'currentSnapshot' => $currentSnapshot,
            'snapshotChanged' => $snapshotChanged,
            'kecamatanList' => $this->districtOptions(),
            'approvedAt' => $approvedAt,
            'expiresAt' => $expiresAt,
            'isExpired' => $isExpired,
            'hasPendingRenewal' => $hasPendingRenewal,
            'previewCard' => $previewCard,
            'canApply' => $isExpired && !$hasPendingRenewal,
        ]);
    }

    public function submitRenewal(Request $request)
    {
        $user = $request->user();
        $mode = $request->input('mode', 'quick');

        $baseCard = CardApplication::with(['documents', 'logs' => fn ($q) => $q->latest()])
            ->where('user_id', $user->id)
            ->whereIn('status', ['Disetujui', 'Dicetak', 'Diambil'])
            ->latest('id')
            ->first();

        if (!$baseCard) {
            return redirect()->route('pencaker.card.index')
                ->with('error', 'Belum ada kartu AK1 yang dapat diperpanjang.');
        }

        $baseCard->loadMissing('logs');
        $approvedAt = $this->approvedLogAt($baseCard);
        $expiresAt = $approvedAt ? Carbon::parse($approvedAt)->copy()->addYears(2) : null;

        if (!$expiresAt || Carbon::now()->lt($expiresAt)) {
            return back()->with('error', 'Kartu AK1 Anda masih dalam masa berlaku.');
        }

        $hasPendingRenewal = CardApplication::where('user_id', $user->id)
            ->where('type', 'perpanjangan')
            ->whereIn('status', ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi', 'Revisi Diminta'])
            ->exists();

        if ($hasPendingRenewal) {
            return back()->with('error', 'Masih ada pengajuan perpanjangan yang belum diproses.');
        }

        $docRules = [
            'foto_closeup' => 'nullable|image|max:2048',
            'ktp_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        if ($mode === 'update') {
            $request->validate($docRules);
        } else {
            $request->validate([
                'mode' => 'in,quick',
            ]);
        }

        try {
            DB::beginTransaction();

            $snapshotBefore = $baseCard->snapshot_after ?? CardApplicationSnapshot::capture($baseCard);

            $renewal = CardApplication::create([
                'user_id' => $user->id,
                'status' => 'Menunggu Verifikasi',
                'type' => 'perpanjangan',
                'parent_id' => $baseCard->id,
                'nomor_ak1' => $baseCard->nomor_ak1,
                'tanggal_pengajuan' => now(),
                'is_active' => false,
                'snapshot_before' => $snapshotBefore,
            ]);

            $docTypes = [
                'foto_closeup' => ['folder' => 'ak1/foto'],
                'ktp_file' => ['folder' => 'ak1/ktp'],
                'ijazah_file' => ['folder' => 'ak1/ijazah'],
            ];

            $baseDocs = $baseCard->documents->keyBy('type');

            foreach ($docTypes as $field => $meta) {
                if ($mode === 'update' && $request->hasFile($field)) {
                    $path = $request->file($field)->store($meta['folder'], 'public');
                    $renewal->documents()->create([
                        'type' => $field,
                        'file_path' => $path,
                    ]);
                } elseif ($baseDocs->has($field)) {
                    $renewal->documents()->create([
                        'type' => $field,
                        'file_path' => $baseDocs[$field]->file_path,
                    ]);
                }
            }

            if ($mode === 'update') {
                $renewal->load('documents', 'user.jobseekerProfile.educations', 'user.jobseekerProfile.trainings');
                $renewal->snapshot_after = CardApplicationSnapshot::captureFromContext($user, $renewal->documents);
            } else {
                $renewal->snapshot_after = $snapshotBefore;
            }

            $renewal->save();

            CardApplicationLog::create([
                'card_application_id' => $renewal->id,
                'actor_id' => $user->id,
                'action' => 'renewal_submit',
                'from_status' => $baseCard->status,
                'to_status' => 'Menunggu Verifikasi',
                'notes' => $mode === 'update'
                    ? 'Pengajuan perpanjangan dengan pembaruan data.'
                    : 'Pengajuan perpanjangan tanpa perubahan data.',
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            ]);

            if ($baseCard->is_active) {
                $baseCard->update(['is_active' => false]);
            }

            DB::commit();

            return redirect()->route('pencaker.card.renewal')
                ->with('success', 'Pengajuan perpanjangan AK1 berhasil dikirim. Menunggu verifikasi admin.');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('AK1 renewal submit error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Gagal mengajukan perpanjangan: ' . $e->getMessage());
        }
    }

    protected function approvedLogAt(CardApplication $application)
    {
        $logs = $application->relationLoaded('logs') ? $application->logs : $application->logs()->latest()->get();
        $log = $logs->first(fn ($log) => $log->action === 'approve' && $log->to_status === 'Disetujui');
        return optional($log)->created_at;
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
        // Izinkan ajukan ulang untuk Ditolak, Revisi Diminta, dan Batal
        if ($lastApp && !in_array($lastApp->status, ['Ditolak', 'Revisi Diminta', 'Batal'])) {
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

        $isResubmission = $request->has('is_resubmission') && $lastApp && in_array($lastApp->status, ['Ditolak', 'Revisi Diminta', 'Batal']);

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

            $previousStatus = $lastApp?->status;

            if ($isResubmission) {
                $lastApp->update([
                    'status' => 'Menunggu Revisi Verifikasi',
                ]);

                $application = $lastApp->fresh('documents');
            } else {
                $application = CardApplication::create([
                    'user_id'           => $user->id,
                    'status'            => 'Menunggu Verifikasi',
                    'type'              => 'baru',
                    'tanggal_pengajuan' => now(),
                    'is_active'         => false,
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

            $application->loadMissing('user.jobseekerProfile.educations', 'user.jobseekerProfile.trainings', 'documents');
            $application->snapshot_after = CardApplicationSnapshot::capture($application);
            $application->is_active = false;
            $application->save();

            CardApplicationLog::create([
                'card_application_id' => $application->id,
                'actor_id'           => $user->id,
                'action'             => $isResubmission ? 'resubmit' : 'submit',
                'from_status'        => $isResubmission ? $previousStatus : null,
                'to_status'          => $application->status,
                'notes'              => $isResubmission ? 'Pengajuan ulang oleh pemohon.' : 'Pengajuan baru oleh pemohon.',
                'ip'                 => $request->ip(),
                'user_agent'         => substr($request->userAgent() ?? '', 0, 255),
            ]);

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

    protected function snapshotHash(?array $snapshot): string
    {
        if (!$snapshot) {
            return '';
        }

        return md5(json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function districtOptions(): array
    {
        return [
            'Bayah','Banjarsari','Bojongmanik','Cibadak','Cibeber','Cigemblong','Cihara','Cijaku',
            'Cikulur','Cileles','Cilograng','Cimarga','Cipanas','Cirinten','Curugbitung','Gunungkencana',
            'Kalanganyar','Lebakgedong','Leuwidamar','Maja','Malingping','Muncang','Panggarangan',
            'Rangkasbitung','Sajira','Sobang','Wanasalam','Warunggunung'
        ];
    }
}
