<?php

namespace App\Tables;

use App\Models\Tournament;
use App\Helpers\Table\Table;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\EnumColumn;
use App\Helpers\Table\TextColumn;
use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\CompactColumn;

class Tournaments extends Table {

    protected $model = Tournament::class;

    protected $defaultSort = "created_at,desc";

    public function columns(): array {
        return [
            TextColumn::add("name", "Nom")->searchable(true)->sortable(true),
            TextColumn::add("game.name", "Jeu"),
            
            CompactColumn::add("date", "Dates Début | Fin", columns: [
                DateColumn::add("start_date", format: "d/m/Y")->sortable(true),
                DateColumn::add("end_date", format: "d/m/Y")->sortable(true),
            ]),

            EnumColumn::add("type", "Type", [
                EnumColumn::Enum("team", "Équipe"),
                EnumColumn::Enum("solo", "Solo"),
            ])->sortable(true),
            
            TextColumn::add("places", "Places")->searchable(true)->sortable(true),
            TextColumn::add("cashprize", "Cashprize (€)")->searchable(true)->sortable(true),
            
            BadgeColumn::add("status", "Statut", [
                BadgeColumn::Badge("closed", "Fermé", "red"),
                BadgeColumn::Badge("finished", "Terminé", "orange"),
                BadgeColumn::Badge("open", "Ouvert", "green")
            ])->sortable(true),            
        ];
    }

    public function filters(): array {
        return [
            // "status" => [
            //     ["key" => "open", "value" => "Ouvert"],
            //     ["key" => "closed", "value" => "Fermé"],
            //     ["key" => "finished", "value" => "Terminé"],
            // ]
        ];
    }

    public function actions(): array {
        return [
            "search" => true,
            "create" => true,
            "show" => "tournaments.show",
        ];
    }
}