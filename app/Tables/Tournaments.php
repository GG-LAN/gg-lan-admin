<?php

namespace App\Tables;

use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\CompactColumn;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\EnumColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Tournament;

class Tournaments extends Table
{

    protected $model = Tournament::class;

    protected string $defaultSort = "created_at,desc";

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Tournament")->searchable(true)->sortable(true),
            TextColumn::add("game.name", "Game"),

            CompactColumn::add("date", "Dates Start | End", columns: [
                DateColumn::add("start_date", format: "d/m/Y"),
                DateColumn::add("end_date", format: "d/m/Y"),
            ]),

            EnumColumn::add("type", "Type", [
                EnumColumn::Enum("team", "Team"),
                EnumColumn::Enum("solo", "Solo"),
            ])->sortable(true),

            TextColumn::add("places", "Places")->searchable(true)->sortable(true),
            TextColumn::add("cashprize", "Cashprice (€)")->searchable(true)->sortable(true),

            BadgeColumn::add("status", "Status", [
                BadgeColumn::Badge("closed", "Closed", "red"),
                BadgeColumn::Badge("finished", "Finished", "orange"),
                BadgeColumn::Badge("open", "Open", "green"),
            ])->sortable(true),
        ];
    }

    public function filters(): array
    {
        return [
            // "status" => [
            //     ["key" => "open", "value" => "Ouvert"],
            //     ["key" => "closed", "value" => "Fermé"],
            //     ["key" => "finished", "value" => "Terminé"],
            // ]
        ];
    }

    public function actions(): array
    {
        return [
            "search" => true,
            "create" => true,
            "show" => "tournaments.show",
        ];
    }
}
