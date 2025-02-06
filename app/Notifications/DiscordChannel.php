<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DiscordChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $notification->toDiscord($notifiable);

        // Send notification to the $notifiable instance...
    }
}
