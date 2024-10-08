<?php

namespace App\Observers;

use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Tournament;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class TeamUserObserver implements ShouldHandleEventsAfterCommit
{
    public function created(TeamUser $teamUser): void
    {
        $user = $teamUser->user;
        $team = $teamUser->team;
        $tournament = $team->tournament;

        // Register a not_paid purchased_place after attaching a player to a team
        PurchasedPlace::register($user, $tournament);

        // Updates the registration_state accordingly to if the team is full and if the tournament is full.
        $this->updateTeamRegistrationState($team);

        // If the tournament is not full, it will search the oldest pending team and register it to the tournament.
        $this->registerOldestPendingTeam($tournament);
    }

    public function deleted(TeamUser $teamUser): void
    {
        $user = $teamUser->user;
        $team = $teamUser->team;
        $tournament = $team->tournament;

        // Remove the purchased_place after detaching(removing) a player to a team
        PurchasedPlace::unregister($user, $tournament);

        // Updates the registration_state accordingly to if the team is full and if the tournament is full.
        $needToRegisterOldestPendingTeam = $this->updateTeamRegistrationState($team);

        // If the tournament is not full, it will search the oldest pending team and register it to the tournament.
        if ($needToRegisterOldestPendingTeam) {
            $this->registerOldestPendingTeam($tournament);
        }
    }

    private function updateTeamRegistrationState(Team $team): bool
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

            $team->save();

            return true;
        }

        return false;
    }

    private function registerOldestPendingTeam(Tournament $tournament): void
    {
        if ($tournament->isFull) {
            return;
        }

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
