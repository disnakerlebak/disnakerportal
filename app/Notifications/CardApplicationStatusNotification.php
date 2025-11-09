<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CardApplicationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $url
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}

