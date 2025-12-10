<?php

namespace App\Listeners;

use App\Contracts\NotificationSenderInterface;
use App\Events\TaskCreatedEvent;


class TaskCreatedNotification
{
    public function __construct(private NotificationSenderInterface $notifocationSender) {}
    public function handle(TaskCreatedEvent $event): void
    {
        $task = $event->task;

        $this->notifocationSender->sendNotification('Task was created: ' . $task->title);
    }
}