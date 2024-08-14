<?php

namespace App\Tables;

use App\Models\Game;
use App\Helpers\Table\Table;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\TextColumn;
use App\Helpers\Table\BadgeColumn;

class Games extends Table {

    protected $model = Game::class;

    public function columns(): array {
        return [
            TextColumn::add("name", "Nom")->searchable(true)->sortable(true),
            TextColumn::add("places", "Joueurs / Équipe")->searchable(true)->sortable(true),
        ];
    }

    public function filters(): array {
        return [
            //
        ];
    }

    public function actions(): array {
        return [
            "search" => true,
            "create" => true,
            "update" => true,
            "delete" => true,
        ];
    }
}