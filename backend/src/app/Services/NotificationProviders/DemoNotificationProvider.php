<?php

namespace App\Services\NotificationProviders;

use App\Contracts\NotificationSenderInterface;

class DemoNotificationProvider implements NotificationSenderInterface {
    public function sendNotification($msg): void
    {
        \Log::info($msg);
    }
}