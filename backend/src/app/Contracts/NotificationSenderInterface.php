<?php

namespace App\Contracts;

interface NotificationSenderInterface
{
    public function sendNotification($msg): void;
}