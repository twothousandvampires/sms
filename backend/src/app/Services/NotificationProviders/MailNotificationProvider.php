<?php

namespace App\Services\NotificationProviders;

use App\Contracts\NotificationSenderInterface;

class MailNotificationProvider implements NotificationSenderInterface{
    public function sendNotification($msg): void
    {
        // Mail::to...
    }
}