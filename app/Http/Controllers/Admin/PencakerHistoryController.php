<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CardApplicationLog;
use App\Models\User;
use Illuminate\Http\Request;

class PencakerHistoryController extends Controller
{
    public function history($id)
    {
        $user = User::findOrFail($id);

        // Ambil log aktivitas umum user (activity_logs)
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil log pengajuan AK1 (card_application_logs) yang terkait user
        $ak1Logs = CardApplicationLog::whereHas('application', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['application', 'actor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Gabungkan keduanya ke satu koleksi seragam
        $histories = collect();

        foreach ($activityLogs as $log) {
            $histories->push((object) [
                'type'       => 'activity',
                'status'     => $log->action ?? 'activity',
                'keterangan' => $log->description ?? $log->meta['description'] ?? '',
                'created_at' => $log->created_at,
            ]);
        }

        $formatRole = function (?string $role) {
            return match ($role) {
                'superadmin', 'admin_ak1', 'admin_laporan', 'admin_verifikator', 'admin_loker', 'admin_statistik' => 'Admin',
                'pencaker' => 'Pemohon',
                'perusahaan' => 'Perusahaan',
                default => $role ? ucfirst(str_replace('_', ' ', $role)) : null,
            };
        };

        foreach ($ak1Logs as $log) {
            $mappedStatus = match ($log->action) {
                'approve'   => 'approved',
                'reject'    => 'rejected',
                'revision'  => 'resubmit',
                'submitted' => 'pending',
                'printed'   => 'printed',
                'picked_up' => 'picked_up',
                'unapprove' => 'unapproved',
                default     => strtolower($log->action ?? 'unknown'),
            };

            $extra = [];
            if ($log->application?->nomor_ak1) {
                $extra[] = 'No. AK1: '.$log->application->nomor_ak1;
            }
            if ($log->actor?->name) {
                $roleLabel = $formatRole($log->actor->role ?? null);
                $name = $log->actor->name;
                if ($roleLabel) {
                    $name .= " ({$roleLabel})";
                }
                $extra[] = 'Oleh: '.$name;
            }

            $histories->push((object) [
                'type'       => 'ak1',
                'status'     => $mappedStatus,
                'keterangan' => trim(($log->notes ?? '').(count($extra) ? "\n".implode(' · ', $extra) : '')),
                'created_at' => $log->created_at,
            ]);
        }

        // Urutkan gabungan berdasarkan waktu terbaru
        $histories = $histories->sortByDesc('created_at')->values();

        return view('admin.pencaker.partials.history-timeline', compact('histories'));
    }
}
