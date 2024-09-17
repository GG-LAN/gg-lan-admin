<?php

namespace App\Tables;

use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlayerTournaments extends Table
{
    protected $model = User::class;

    protected bool $paginate = false;

    public function resource(): BelongsToMany
    {
        return $this->player->tournaments();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Tournament"),
            TextColumn::add("game.name", "Game"),
            BadgeColumn::add("status", "Statut", [
                BadgeColumn::Badge("closed", "Closed", "red"),
                BadgeColumn::Badge("finished", "Finished", "orange"),
                BadgeColumn::Badge("open", "Open", "green"),
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
            "show" => "tournaments.show",
        ];
    }
}
