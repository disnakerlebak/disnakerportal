<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ CardApplication, CardApplicationLog };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan sudah install barryvdh/laravel-dompdf
use App\Models\RejectionReason;
use App\Support\CardApplicationSnapshot;

class CardVerificationController extends Controller
{
    public function index(Request $request)
{
    $apps = CardApplication::with(['user', 'lastHandler.actor', 'logs.actor'])
                ->when($request->q, fn($q) =>
                    $q->whereHas('user', fn($u) =>
                        $u->where('name', 'like', "%{$request->q}%")
                          ->orWhere('email', 'like', "%{$request->q}%")
                    )
                )
                ->when($request->status, fn($q) =>
                    $q->where('status', $request->status)
                )
                ->latest('created_at')
                ->paginate(20);

    $rejectionReasons = RejectionReason::orderBy('title')->get();

    return view('admin.ak1.index', compact('apps', 'rejectionReasons'));
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
            $app->update([
                'status' => 'Revisi Diminta',
                'assigned_to' => null,
                'is_active' => false,
            ]);

            CardApplicationLog::create([
                'card_application_id' => $app->id,
                'actor_id'    => $request->user()->id,
                'action'      => 'unapprove',
                'from_status' => $from,
                'to_status'   => 'Revisi Diminta',
                'notes'       => $validated['notes'] ?? null,
                'ip'          => $request->ip(),
                'user_agent'  => substr($request->userAgent() ?? '', 0, 255),
            ]);

            return back()->with('success', 'Persetujuan dibatalkan dan status dikembalikan ke Revisi Diminta.');
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
            ])
            : collect();
    
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
            ],
            'educations' => $educations,
            'trainings'  => $trainings,
        ]);
    }  

//cetak kartu pencaker
public function cetakPdf($id)
{
    // 1. Ambil data lengkap (relasi profil, pendidikan, pelatihan, dan dokumen)
    $application = \App\Models\CardApplication::with([
        'user.jobseekerProfile.educations',
        'user.jobseekerProfile.trainings',
        'documents'
    ])->findOrFail($id);

    // 2. Ambil profil pencaker melalui relasi user
    $profile = optional($application->user)->jobseekerProfile;

    // 3. Ambil pendidikan & pelatihan (jika ada)
    $educations = $profile ? $profile->educations : collect();
    $trainings = $profile ? $profile->trainings : collect();

    // 4. Ambil pas foto dari tabel card_application_documents
    $fotoDoc = $application->documents->firstWhere('type', 'foto_closeup');
    $fotoPath = $fotoDoc && $fotoDoc->file_path
        ? storage_path('app/public/' . $fotoDoc->file_path)
        : null;

    // 5. Pastikan file foto benar-benar ada di storage
    if ($fotoPath && !file_exists($fotoPath)) {
        $fotoPath = null; // fallback jika file tidak ditemukan
    }

    // 6. Data yang dikirim ke view PDF
    $data = [
        'application' => $application,
        'profile'     => $profile,
        'educations'  => $educations,
        'trainings'   => $trainings,
        'fotoPath'    => $fotoPath,
    ];

    // 7. Generate PDF (legal size seperti format Disnaker)
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ak1_card', $data)
        ->setPaper('legal', 'portrait');

    // 8. Stream hasil PDF
    $filename = 'AK1-' . ($application->nomor_ak1 ?? 'Belum-Ditetapkan') . '.pdf';
    return $pdf->stream($filename);
}

  
}
