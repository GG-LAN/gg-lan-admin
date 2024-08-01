<?php

namespace App\Listeners;

use App\Models\Team;
use App\Events\TeamUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ARegisterOldestTeamPending implements ShouldQueue
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
     * @return void|bool
     */
    public function handle(TeamUpdated $event)
    {
        $tournament = $event->team->tournament;

        if (!$tournament->isFull) {
            // Retrieve the oldest pending team 
            $team = Team::where("registration_state", Team::PENDING)
                        ->where('tournament_id', $tournament->id)
                        ->orderBy('updated_at', 'asc')
                        ->first();
            
            // If we find a team, update the registration_state of this team to Team::REGISTERED
            if ($team) {
                $team->registration_state_updated_at = now()->toDateTimeString();
                $team->registration_state = Team::REGISTERED;
                $team->save();
            }
        }
    }
}
