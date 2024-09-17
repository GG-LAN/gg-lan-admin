<?php

namespace App\Tables;

use App\Helpers\Table\BoolColumn;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TeamPlayers extends Table
{
    protected $model = Team::class;

    protected string $defaultSort = "created_at,asc";

    protected bool $paginate = false;

    public function resource(): BelongsToMany
    {
        return $this->team->users();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("pseudo", "Player"),
            DateColumn::add("created_at", "Member since", "d/m/Y"),
            BoolColumn::add("pivot.captain", "Hierarchy",
                labelTrue: "Captain",
                labelFalse: "Player"
            ),
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
            "show" => "players.show",
        ];
    }
}
