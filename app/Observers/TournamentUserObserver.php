<?php
namespace App\Observers;

use App\Models\PurchasedPlace;
use App\Models\TournamentUser;
use App\Notifications\PlayerRegistered;

class TournamentUserObserver
{
    public function created(TournamentUser $tournamentUser): void
    {
        $player     = $tournamentUser->user;
        $tournament = $tournamentUser->tournament;

        PurchasedPlace::register($player, $tournament);
    }

    public function updated(TournamentUser $tournamentUser): void
    {
        //
    }

    public function deleted(TournamentUser $tournamentUser): void
    {
        $player     = $tournamentUser->user;
        $tournament = $tournamentUser->tournament;

        PurchasedPlace::unregister($player, $tournament);
    }
}
