<?php

namespace App\Tables;

use App\Helpers\Table\BadgeColumn;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Team;

class Teams extends Table
{

    protected $model = Team::class;

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Nom")->searchable(true)->sortable(true),
            TextColumn::add("description", "Description")->searchable(true),
            DateColumn::add("created_at", "Created at", format: "d/m/Y")->searchable(true)->sortable(true),
            BadgeColumn::add("registration_state", "Status", [
                BadgeColumn::Badge("not_full", "Incomplete", "red"),
                BadgeColumn::Badge("pending", "Pending", "orange"),
                BadgeColumn::Badge("registered", "Registered", "green"),
            ])->sortable(true),
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
            "show" => "teams.show",
        ];
    }
}
