<?php

namespace App\Tables;

use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlayerTeams extends Table
{
    protected $model = User::class;

    protected bool $paginate = false;

    public function resource(): BelongsToMany
    {
        return $this->player->teams();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Team"),
            TextColumn::add("tournament.name", "Tournament"),
            BadgeColumn::add("registration_state", "Statut", [
                BadgeColumn::Badge("not_full", "Incomplete", "red"),
                BadgeColumn::Badge("pending", "Pending", "orange"),
                BadgeColumn::Badge("registered", "Registered", "green"),
            ]),
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
