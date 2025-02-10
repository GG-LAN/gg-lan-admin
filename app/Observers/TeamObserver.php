<?php
namespace App\Observers;

// use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Notifications\TeamRegistered;
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
    public function updating(Team $team): void
    {
        if ($team->isDirty("registration_state") && $team->registration_state == Team::REGISTERED) {
            $team->notify(new TeamRegistered);
        }
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleting(Team $team): void
    {
        // Manually remove all players of the team to trigger TeamUserObserver
        $team->users()->sync([]);
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
