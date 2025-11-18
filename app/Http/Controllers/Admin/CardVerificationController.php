<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ CardApplication, CardApplicationLog, User };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan sudah install barryvdh/laravel-dompdf
use App\Support\CardApplicationSnapshot;
use App\Notifications\CardApplicationStatusNotification;

class CardVerificationController extends Controller
{
    private function routeForType(?string $type): string
    {
        return match (strtolower($type ?? '')) {
            'perbaikan' => route('pencaker.card.repair'),
            'perpanjangan' => route('pencaker.card.renewal'),
            default => route('pencaker.card.index'),
        };
    }
    public function index()
    {
        return view('admin.ak1.index');
    }

    public function archive()
    {
        return view('admin.ak1.archive');
    }
    public function show(CardApplication $application)
    {
        $application->load(['user','logs.actor']);
        return view('admin.ak1.show', compact('application'));
    }

    public function approve(CardApplication $application, Request $request)
{
    return DB::transaction(function () use ($application, $request) {

        // 🔒 Ambil data terbaru dan kunci agar tidak diproses ganda oleh admin lain
        $app = CardApplication::whereKey($application->id)->lockForUpdate()->with('parent')->first();

        // 🚫 Pastikan belum diproses admin lain
        if (!in_array($app->status, ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])) {
            return back()->with('error', 'Pengajuan sudah diproses admin lain.');
        }

        // ==============================
        // 🔢 Generate Nomor AK1 Unik
        // ==============================
        $nomorAk1 = $app->nomor_ak1;
        if ($app->type === 'perbaikan') {
            $nomorAk1 = $app->parent?->nomor_ak1 ?: $nomorAk1;
        } else {
            $prefix = 'DTK-AK1';
            $monthYear = now()->format('my'); // contoh: 1025

            $latestAk1 = CardApplication::whereYear('created_at', now()->year)
                ->where('status', 'Disetujui')
                ->whereNotNull('nomor_ak1')
                ->orderBy('id', 'desc')
                ->first();

            if ($latestAk1 && preg_match('/DTK-AK1-(\d+)-/', $latestAk1->nomor_ak1, $m)) {
                $nextNumber = str_pad(((int) $m[1]) + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '0001';
            }

            $nomorAk1 = "{$prefix}-{$nextNumber}-{$monthYear}";
        }

        // ==============================
        // 💾 Update Data Pengajuan
        // ==============================
        $app->update([
            'status'      => 'Disetujui',
            'nomor_ak1'   => $nomorAk1,
            'assigned_to' => $request->user()->id,
            'approved_at' => now(),
            'is_active'   => true,
        ]);

        if ($app->type === 'perbaikan' && $app->parent) {
            $app->parent->update(['is_active' => false]);
        }

        $app->loadMissing('documents', 'user.jobseekerProfile.educations', 'user.jobseekerProfile.trainings');
        $app->snapshot_after = CardApplicationSnapshot::capture($app);
        $app->save();

        // ==============================
        // 🧠 Catat Aktivitas ke Log
        // ==============================
        \App\Models\CardApplicationLog::create([
            'card_application_id' => $app->id,
            'actor_id'    => $request->user()->id,
            'action'      => 'approve',
            'from_status' => $application->status,
            'to_status'   => 'Disetujui',
            'notes'       => $request->input('notes'),
            'ip'          => $request->ip(),
            'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
        ]);

        // 🔔 Notifikasi ke pemohon
        try {
            $url = $this->routeForType($app->type);
            $title = 'Pengajuan AK1 Disetujui';
            $msg = $app->type === 'perbaikan'
                ? 'Perbaikan AK1 Anda disetujui. Nomor AK1 tetap: ' . ($nomorAk1 ?? '-')
                : 'Pengajuan AK1 Anda disetujui. Nomor AK1: ' . ($nomorAk1 ?? '-');
            $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
        } catch (\Throwable $e) {
            \Log::warning('notify_approve_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
        }

        // ==============================
        // 🔠 Konversi Data Profil ke Huruf Besar
        // ==============================
        $profile = $app->user->jobseekerProfile;
        if ($profile) {
            $profile->update([
                'nama_lengkap'       => strtoupper($profile->nama_lengkap ?? ''),
                'tempat_lahir'       => strtoupper($profile->tempat_lahir ?? ''),
                'alamat_lengkap'     => strtoupper($profile->alamat_lengkap ?? ''),
                'agama'              => strtoupper($profile->agama ?? ''),
                'jenis_kelamin'      => strtoupper($profile->jenis_kelamin ?? ''),
                'status_perkawinan'  => strtoupper($profile->status_perkawinan ?? ''),
            ]);
        }

        // ==============================
        // ✅ Selesai
        // ==============================
        $message = $app->type === 'perbaikan'
            ? "Perbaikan disetujui. Nomor AK1 tetap: {$nomorAk1}"
            : "Pengajuan disetujui. Nomor AK1: {$nomorAk1}";

        return back()->with('success', $message);
    });
}


    public function reject(CardApplication $application, Request $request)
{
    $validated = $request->validate([
        'reason_id' => 'required|exists:rejection_reasons,id',
        'notes'     => 'nullable|string|max:1000',
    ]);

    return DB::transaction(function () use ($application, $request, $validated) {
        $app = CardApplication::whereKey($application->id)->lockForUpdate()->first();

        if (!in_array($app->status, ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])) {
            return back()->with('error', 'Pengajuan sudah diproses admin lain.');
        }      

        $from = $app->status;

        // ambil alasan dari tabel master alasan penolakan
        $reason = \App\Models\RejectionReason::find($validated['reason_id']);
        $reasonText = $reason ? $reason->title : 'Tanpa alasan';

        // gabungkan dengan catatan tambahan (jika ada)
        $fullNotes = $reasonText;
        if (!empty($validated['notes'])) {
            $fullNotes .= ' — ' . trim($validated['notes']);
        }

        // update status utama
        $app->update([
            'status' => 'Ditolak',
            'rejected_at' => now(),
        ]);

        // simpan ke log aktivitas admin
        \App\Models\CardApplicationLog::create([
            'card_application_id' => $app->id,
            'actor_id'    => $request->user()->id,
            'action'      => 'reject',
            'from_status' => $from,
            'to_status'   => 'Ditolak',
            'notes'       => $fullNotes,
            'ip'          => $request->ip(),
            'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
        ]);

        // 🔔 Notifikasi ke pemohon
        try {
            $url = $this->routeForType($app->type);
            $title = 'Pengajuan AK1 Ditolak';
            $msg = 'Pengajuan AK1 belum dapat disetujui: ' . $fullNotes;
            $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
        } catch (\Throwable $e) {
            \Log::warning('notify_reject_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'Pengajuan telah ditolak dengan alasan: ' . $reasonText);
    });
}

    public function requestRevision(CardApplication $application, Request $request)
    {
        $request->validate(['notes' => 'required|string|max:1000']);

        return DB::transaction(function () use ($application, $request) {
            $app = CardApplication::whereKey($application->id)->lockForUpdate()->first();

            if (!in_array($app->status, ['Menunggu Verifikasi', 'Menunggu Revisi Verifikasi'])) {
                return back()->with('error', 'Pengajuan sudah diproses admin lain.');
            }         
            $from = $app->status;
            $app->update(['status' => 'Revisi Diminta']);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => $request->user()->id,
                'action'      => 'revision',
                'from_status' => $from,
                'to_status'   => 'Revisi Diminta',
                'notes'       => $request->input('notes'),
                'ip'          => $request->ip(),
                'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
            ]);

        // 🔔 Notifikasi revisi
        try {
            $url = $this->routeForType($app->type);
            $title = 'Revisi Diminta';
            $msg = 'Admin meminta revisi pada pengajuan AK1 Anda: ' . ($request->input('notes') ?? '');
            $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
        } catch (\Throwable $e) {
            \Log::warning('notify_revision_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'Permintaan revisi terkirim ke pemohon.');
    });
    }

    public function unapprove(CardApplication $application, Request $request)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($application, $request, $validated) {
            $app = CardApplication::whereKey($application->id)->lockForUpdate()->first();

            if ($app->status !== 'Disetujui') {
                return back()->with('error', 'Pengajuan belum berstatus Disetujui.');
            }

            $from = $app->status;
            // Ubah menjadi Batal dan kosongkan nomor AK1
            $app->update([
                'status' => 'Batal',
                'assigned_to' => null,
                'is_active' => false,
                'nomor_ak1' => null,
            ]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => $request->user()->id,
                'action'      => 'unapprove',
                'from_status' => $from,
                'to_status'   => 'Batal',
                'notes'       => $validated['notes'] ?? null,
                'ip'          => $request->ip(),
                'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
            ]);

            // 🔔 Notifikasi dibatalkan
            try {
                $url = $this->routeForType($app->type);
                $title = 'Persetujuan Dibatalkan';
                $msg = 'Pengajuan AK1 Anda dibatalkan oleh admin. Silakan perbarui data/dokumen dan ajukan ulang.';
                $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
            } catch (\Throwable $e) {
                \Log::warning('notify_unapprove_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
            }

            return back()->with('success', 'Persetujuan dibatalkan. Status diubah menjadi Batal dan nomor AK1 dinonaktifkan.');
        });
    }

    public function markPrinted(CardApplication $application, Request $request)
    {
        return DB::transaction(function () use ($application, $request) {
            $app = CardApplication::whereKey($application->id)->lockForUpdate()->first();

            if ($app->status !== 'Disetujui') {
                return back()->with('error', 'Status harus Disetujui sebelum dicetak.');
            }

            $from = $app->status;
            $app->update([
                'status' => 'Dicetak',
                'printed_at' => now(),
            ]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => $request->user()->id,
                'action'      => 'printed',
                'from_status' => $from,
                'to_status'   => 'Dicetak',
                'ip'          => $request->ip(),
                'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
            ]);

            // 🔔 Notifikasi dicetak
            try {
                $url = $this->routeForType($app->type);
                $title = 'Kartu AK1 Dicetak';
                $msg = 'Kartu AK1 Anda sudah dicetak. Silakan ikuti prosedur pengambilan.';
                $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
            } catch (\Throwable $e) {
                \Log::warning('notify_printed_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
            }

            return back()->with('success', 'Status diubah menjadi Dicetak.');
        });
    }

    public function markPickedUp(CardApplication $application, Request $request)
    {
        return DB::transaction(function () use ($application, $request) {
            $app = CardApplication::whereKey($application->id)->lockForUpdate()->first();

            if (!in_array($app->status, ['Dicetak', 'Disetujui'])) {
                return back()->with('error', 'Status harus Dicetak/Disetujui sebelum ditandai diambil.');
            }

            $from = $app->status;
            $app->update([
                'status' => 'Diambil',
                'picked_up_at' => now(),
            ]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => $request->user()->id,
                'action'      => 'picked_up',
                'from_status' => $from,
                'to_status'   => 'Diambil',
                'ip'          => $request->ip(),
                'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
            ]);

            // 🔔 Notifikasi diambil
            try {
                $url = $this->routeForType($app->type);
                $title = 'Kartu AK1 Diambil';
                $msg = 'Kartu AK1 Anda telah ditandai diambil.';
                $app->user?->notify(new CardApplicationStatusNotification($title, $msg, $url));
            } catch (\Throwable $e) {
                \Log::warning('notify_picked_failed', ['app_id' => $app->id, 'error' => $e->getMessage()]);
            }

            return back()->with('success', 'Kartu ditandai sudah diambil.');
        });
    }


    public function ajaxDetail($applicationId)
    {
        $app = \App\Models\CardApplication::with([
            'user.jobseekerProfile.educations',
            'user.jobseekerProfile.trainings',
            'documents' // tambahkan relasi baru ini
        ])->findOrFail($applicationId);
    
        $profile = optional($app->user)->jobseekerProfile;
    
        // Ambil dokumen sesuai tipe
        $foto = $app->documents->firstWhere('type', 'foto_closeup')?->file_path;
        $ktp = $app->documents->firstWhere('type', 'ktp_file')?->file_path;
        $ijazah = $app->documents->firstWhere('type', 'ijazah_file')?->file_path;
    
        // Map riwayat pendidikan
        $educations = $profile
            ? $profile->educations->map(fn($e) => [
                'tingkat'        => $e->tingkat,
                'nama_institusi' => $e->nama_institusi,
                'jurusan'        => $e->jurusan,
                'tahun_mulai'    => $e->tahun_mulai,
                'tahun_selesai'  => $e->tahun_selesai,
            ])
            : collect();
    
        // Map riwayat pelatihan
        $trainings = $profile
            ? $profile->trainings->map(fn($t) => [
                'jenis_pelatihan'   => $t->jenis_pelatihan,
                'lembaga_pelatihan' => $t->lembaga_pelatihan,
                'tahun'             => $t->tahun,
                'sertifikat_file'   => $t->sertifikat_file,
            ])
            : collect();

        // Sertifikat keahlian (ambil satu contoh sertifikat jika ada)
        $firstCertificate = $profile
            ? $profile->trainings()->whereNotNull('sertifikat_file')->latest('tahun')->value('sertifikat_file')
            : null;
    
        return response()->json([
            'application' => [
                'id'        => $app->id,
                'status'    => $app->status,
                'type'      => $app->type,
                'nomor_ak1' => $app->nomor_ak1,
                'tanggal'   => indoDateOnly($app->created_at),
                'foto_closeup' => $foto,
                'ktp_file'     => $ktp,
                'ijazah_file'  => $ijazah,
                'sertifikat_keahlian' => $firstCertificate,
                'snapshot_before' => $app->snapshot_before,
                'snapshot_after'  => $app->snapshot_after,
                'parent_id'       => $app->parent_id,
            ],
            'profile' => [
                'nama_lengkap'      => $profile->nama_lengkap ?? '-',
                'nik'               => $profile->nik ?? '-',
                'tempat_lahir'      => $profile->tempat_lahir ?? '-',
                'tanggal_lahir'     => $profile->tanggal_lahir
                    ? \Carbon\Carbon::parse($profile->tanggal_lahir)->translatedFormat('d F Y')
                    : '-',
                'jenis_kelamin'     => $profile->jenis_kelamin ?? '-',
                'status_perkawinan' => $profile->status_perkawinan ?? '-',
                'agama'             => $profile->agama ?? '-',
                'alamat_lengkap'    => $profile->alamat_lengkap ?? '-',
                'status_disabilitas'=> $profile->status_disabilitas ?? '-',
                'domisili_kecamatan'=> $profile->domisili_kecamatan ?? '-',
                'email_cache'       => $profile->email_cache ?? ($app->user->email ?? '-'),
                'no_telepon'        => $profile->no_telepon ?? '-',
                'akun_media_sosial' => $profile->akun_media_sosial ?? '-',
            ],
            'educations' => $educations,
            'trainings'  => $trainings,
        ]);
    }

    public function userLogs(User $user)
    {
        $logs = CardApplicationLog::whereHas('application', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['application', 'actor'])
            ->orderBy('created_at')
            ->get();

        $payload = $logs->map(function (CardApplicationLog $log) {
            return [
                'id'          => $log->id,
                'action'      => $log->action,
                'from_status' => $log->from_status,
                'to_status'   => $log->to_status,
                'notes'       => $log->notes,
                'actor'       => $log->actor?->name,
                'created_at'  => optional($log->created_at)->format('d M Y H:i'),
                'timestamp'   => optional($log->created_at)?->timestamp,
                'nomor_ak1'   => $log->application?->nomor_ak1,
                'type'        => $log->application?->type,
            ];
        });

        return response()->json([
            'logs' => $payload,
        ]);
    }

//cetak kartu pencaker
public function cetakPdf($id)
{
    $application = \App\Models\CardApplication::with([
        'user.jobseekerProfile.educations',
        'user.jobseekerProfile.trainings',
        'documents'
    ])->findOrFail($id);

    $snapshot = $application->snapshot_after ?? null;

    if ($snapshot) {
        $profileArray = array_merge([
            'nama_lengkap' => '-',
            'nik' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => null,
            'jenis_kelamin' => '-',
            'agama' => '-',
            'status_perkawinan' => '-',
            'pendidikan_terakhir' => '-',
            'alamat_lengkap' => '-',
            'domisili_kecamatan' => '-',
            'no_telepon' => '-',
        ], $snapshot['profile'] ?? []);

        $profile = (object) $profileArray;
        $educations = collect($snapshot['educations'] ?? [])->map(fn ($item) => (object) $item);
        $trainings = collect($snapshot['trainings'] ?? [])->map(fn ($item) => (object) $item);

        $fotoDoc = collect($snapshot['documents'] ?? [])->firstWhere('type', 'foto_closeup');
        $fotoPath = $fotoDoc && !empty($fotoDoc['file_path'])
            ? storage_path('app/public/' . $fotoDoc['file_path'])
            : null;
    } else {
        $profile = optional($application->user)->jobseekerProfile;
        $educations = $profile ? $profile->educations : collect();
        $trainings = $profile ? $profile->trainings : collect();

        $fotoDoc = $application->documents->firstWhere('type', 'foto_closeup');
        $fotoPath = $fotoDoc && $fotoDoc->file_path
            ? storage_path('app/public/' . $fotoDoc->file_path)
            : null;
    }

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

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ak1_card', $data)
        ->setPaper('legal', 'portrait');

    $filename = 'AK1-' . ($application->nomor_ak1 ?? 'Belum-Ditetapkan') . '.pdf';
    return $pdf->stream($filename);
}

  
}
