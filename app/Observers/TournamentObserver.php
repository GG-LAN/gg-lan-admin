<?php
namespace App\Observers;

use App\Models\Tournament;
use App\Models\TournamentPrice;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class TournamentObserver implements ShouldHandleEventsAfterCommit
{
    public function updated(Tournament $tournament): void
    {
        if ($tournament->status != "finished") {
            return;
        }

        TournamentPrice::archiveTournamentProduct($tournament);
    }
}
