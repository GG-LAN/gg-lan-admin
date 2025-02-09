<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class DiscordChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toDiscord($notifiable);

        if (config("app.env") == "testing") {
            return;
        }

        DiscordAlert::message("", [
            [
                "timestamp"   => now(),
                'title'       => $message->title,
                'description' => $message->description,
                'color'       => $message->color,
                'author'      => $message->author,
                "fields"      => $message->fields,
            ],
        ]);
    }
}
