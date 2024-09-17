<?php

namespace App\Observers;

use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\TeamUser;

class TeamUserObserver
{
    public function created(TeamUser $teamUser): void
    {
        $user = $teamUser->user;
        $team = $teamUser->team;

        // Register a not_paid purchased_place after attaching a player to a team
        PurchasedPlace::register($user, $team->tournament);

        // Updates the registration_state accordingly to if the team is full and if the tournament is full.
        $this->updateTeamRegistrationState($team);
    }

    public function deleted(TeamUser $teamUser): void
    {
        // Remove the purchased_place after detaching(removing) a player to a team
        PurchasedPlace::unregister($teamUser->user, $teamUser->team->tournament);
    }

    private function updateTeamRegistrationState(Team $team): void
    {
        if ($team->isFull) {
            if ($team->tournament->isFull) {
                $team->registration_state = $team::PENDING;

            } else {
                $team->registration_state = $team::REGISTERED;
            }
        } else {
            $team->registration_state = $team::NOT_FULL;
        }

        if ($team->isDirty("registration_state")) {
            $team->registration_state_updated_at = now()->toDateTimeString();
        }

        $team->save();
    }
}
