<?php

namespace App\Observers;

// use App\Models\PurchasedPlace;
use App\Models\Team;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class TeamObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        $captain = auth()->user();

        // Add the current player to the team as captain
        $team->users()->attach($captain, ['captain' => true]);
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}
