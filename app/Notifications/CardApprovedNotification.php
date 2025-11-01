<?php

// app/Notifications/CardApprovedNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CardApprovedNotification extends Notification
{
    use Queueable;

    public function via($notifiable) { return ['database']; }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pengajuan AK1 Disetujui',
            'message' => 'Pengajuan kartu pencari kerja (AK1) Anda telah disetujui. Silakan unduh kartu pada halaman AK1.',
            'url' => route('pencaker.card.index'), // halaman pengajuan kamu
        ];
    }
}
