<?php

namespace App\Tables;

use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentTeams extends Table
{
    protected $model = Team::class;

    protected bool $paginate = false;

    public function resource(): HasMany
    {
        return $this->tournament->teams();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Team"),
            BadgeColumn::add("registration_state", "Status", [
                BadgeColumn::Badge("not_full", "Incomplete", "red"),
                BadgeColumn::Badge("pending", "Pending", "orange"),
                BadgeColumn::Badge("registered", "Registered", "green"),
            ]),
            DateColumn::add("registration_state_updated_at", "Registered / Pending since", "d/m/Y"),
            TextColumn::add("", ""),
        ];
    }

    public function filters(): array
    {
        return [
            //
        ];
    }

    public function actions(): array
    {
        return [
            "show" => "teams.show",
        ];
    }
}