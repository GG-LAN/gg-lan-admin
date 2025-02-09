<?php
namespace App\Notifications;

use App\Models\Team;
use App\Notifications\Messages\DiscordMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TeamRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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

    public function toDiscord(Team $team): DiscordMessage
    {
        $tournament = $team->tournament;

        $gameEmoji = "";

        switch (Str::lower($tournament->game->name)) {
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

        $players = "";
        $count   = 1;

        $team->users->each(function ($player) use (&$players, &$count) {
            if ($player->pivot->captain) {
                $players .= ":star: ";
            } else {
                $players .= ":number_{$count}: ";
                $count++;
            }

            $players .= $player->pseudo . "\n";
        });

        $availableSlots = $tournament->places - $tournament->register_count;

        return (new DiscordMessage())
            ->title("Une nouvelle équipe s'est inscrite !")
            ->description($team->name)
            ->color("#ff0000")
            ->field([
                "name"   => "Joueurs",
                "inline" => true,
                "value"  => $players,
            ])
            ->emptyField()
            ->field([
                "name"   => "Tournoi",
                "inline" => true,
                "value"  => "
                    {$gameEmoji}{$tournament->name}
                    {$tournament->places} slots
                    {$tournament->register_count} équipes inscrites
                    {$tournament->teamsNotFull()->count()} équipes incomplètes
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
