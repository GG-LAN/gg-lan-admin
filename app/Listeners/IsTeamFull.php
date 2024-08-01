<?php

namespace App\Listeners;

use App\Events\TeamUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IsTeamFull implements ShouldQueue
{   
    /**
     * Indicates that this queued listener should be exectued after database transaction
     *
     * @var boolean
     */
    public $afterCommit = true;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TeamUpdated  $event
     * @return void
     */
    public function handle(TeamUpdated $event)
    {
        $team = $event->team;
        $tournament = $team->tournament;

        $team->registration_state_updated_at = now()->toDateTimeString();

        // If the team is full
        if ($team->isFull) {
            if ($tournament->isFull) {
                $team->registration_state = $team::PENDING;
                
            }
            else {
                $team->registration_state = $team::REGISTERED;
            }

            $team->save();
            
            // Return false because we don't need to continue to the next listener
            return false;
            
        }
        else {
            $team->registration_state = $team::NOT_FULL;
            $team->save();
        }
    }
}
