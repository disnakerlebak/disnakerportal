<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CardApplicationLog;
use App\Models\User;
use Illuminate\Http\Request;

class PencakerHistoryController extends Controller
{
    public function history($id, Request $request)
    {
        $user = User::findOrFail($id);

        // Ambil log aktivitas umum user (activity_logs)
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        // Ambil log pengajuan AK1 (card_application_logs) yang terkait user
        $ak1Logs = CardApplicationLog::whereHas('application', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['application', 'actor'])
            ->orderBy('created_at')
            ->get();

        $formatRole = function (?string $role) {
            return match ($role) {
                'superadmin', 'admin_ak1', 'admin_laporan', 'admin_verifikator', 'admin_loker', 'admin_statistik' => 'Admin',
                'pencaker' => 'Pemohon',
                'perusahaan' => 'Perusahaan',
                default => $role ? ucfirst(str_replace('_', ' ', $role)) : null,
            };
        };

        // Payload gabungan untuk timeline premium (JSON)
        $payload = [];

        foreach ($activityLogs as $log) {
            $payload[] = [
                'action'       => $log->action ?? 'activity',
                'action_label' => $log->description
                    ?? ucfirst(str_replace('_', ' ', $log->action ?? 'aktivitas')),
                'from_status'  => null,
                'to_status'    => null,
                'notes'        => $log->description ?? ($log->meta['description'] ?? ''),
                'actor'        => $user->name,
                'actor_role'   => $formatRole($user->role ?? 'pencaker') ?? 'Pemohon',
                'created_at'   => optional($log->created_at)->format('d M Y H:i'),
                'timestamp'    => optional($log->created_at)->timestamp,
                'nomor_ak1'    => null,
                'type'         => 'activity',
            ];
        }

        foreach ($ak1Logs as $log) {
            $payload[] = [
                'action'       => $log->action,
                'action_label' => ucfirst(str_replace('_', ' ', $log->action ?? 'AK1')),
                'from_status'  => $log->from_status,
                'to_status'    => $log->to_status,
                'notes'        => $log->notes,
                'actor'        => $log->actor?->name ?? 'Sistem',
                'actor_role'   => $formatRole($log->actor?->role),
                'created_at'   => optional($log->created_at)->format('d M Y H:i'),
                'timestamp'    => optional($log->created_at)->timestamp,
                'nomor_ak1'    => $log->application?->nomor_ak1,
                'type'         => 'ak1',
            ];
        }

        // Urutkan kronologis (paling lama → paling baru)
        usort($payload, function ($a, $b) {
            return ($a['timestamp'] ?? 0) <=> ($b['timestamp'] ?? 0);
        });

        if ($request->wantsJson()) {
            return response()->json(['logs' => $payload]);
        }

        // Fallback lama (jika ada pemanggilan view)
        $histories = collect($payload)
            ->map(function ($item) {
                return (object) [
                    'type'       => $item['type'],
                    'status'     => $item['action'],
                    'keterangan' => $item['notes'],
                    'created_at' => $item['created_at'],
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return view('admin.pencaker.partials.history-timeline', compact('histories'));
    }
}
