<?php

namespace App\Notifications;

// app/Notifications/CardRejectedNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CardRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public string $reason) {}

    public function via($notifiable) { return ['database']; }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pengajuan AK1 Ditolak',
            'message' => 'Pengajuan AK1 belum dapat disetujui: '.$this->reason,
            'url' => route('pencaker.card.index'),
        ];
    }
}
