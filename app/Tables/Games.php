<?php

namespace App\Tables;

use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Game;

class Games extends Table
{

    protected $model = Game::class;

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Game")->searchable(true)->sortable(true),
            TextColumn::add("places", "Player / Team")->searchable(true)->sortable(true),
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
            "search" => true,
            "create" => true,
            "update" => true,
            "delete" => true,
        ];
    }
}
