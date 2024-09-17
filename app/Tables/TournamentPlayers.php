<?php

namespace App\Tables;

use App\Helpers\Table\DateColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TournamentPlayers extends Table
{
    protected $model = User::class;

    protected bool $paginate = false;

    public function resource(): BelongsToMany
    {
        return $this->tournament->players();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("pseudo", "Player"),
            DateColumn::add("pivot.created_at", "Registered since", "d/m/Y"),
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
            //
        ];
    }
}
