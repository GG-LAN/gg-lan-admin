<?php
namespace App\Notifications;

use App\Models\Tournament;
use App\Models\User;
use App\Notifications\Messages\DiscordMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PlayerRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Tournament $tournament)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [DiscordChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDiscord(User $player): DiscordMessage
    {
        $gameEmoji = "";

        switch (Str::lower($this->tournament->game->name)) {
            case "cs2":
                $gameEmoji = "<:CSGO:668530474058252302> ";
                break;

            case "rocket league 2v2":
                $gameEmoji = "<:RL:668529646798897192> ";
                break;

            case "starcraft 2":
                $gameEmoji = "<:SC2:668529646832189441> ";
                break;

            case "trackmania":
                $gameEmoji = "<:TM:668530474926211108> ";
                break;

            default:
                break;
        }

        $availableSlots = $this->tournament->places - $this->tournament->register_count;

        return (new DiscordMessage())
            ->title("Un nouveau joueur s'est inscrit !")
            ->description($player->pseudo)
            ->color("#ff0000")
            ->field([
                "name"   => "Tournoi",
                "inline" => true,
                "value"  => "
                    {$gameEmoji}{$this->tournament->name}
                    {$this->tournament->places} slots
                    {$this->tournament->register_count} équipes inscrites
                    {$availableSlots} slots disponible
                ",
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
