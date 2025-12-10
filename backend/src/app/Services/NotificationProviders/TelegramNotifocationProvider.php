<?php

namespace App\Services\NotificationProviders;

use App\Contracts\NotificationSenderInterface;

class TelegramNotifocationProvider implements NotificationSenderInterface{
    public function sendNotification($msg): void
    {
        // telegram.send()...
    }
}