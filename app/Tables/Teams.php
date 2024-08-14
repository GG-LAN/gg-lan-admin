<?php

namespace App\Tables;

use App\Models\Team;
use App\Helpers\Table\Table;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\TextColumn;
use App\Helpers\Table\BadgeColumn;

class Teams extends Table {

    protected $model = Team::class;

    public function columns(): array {
        return [
            TextColumn::add("name", "Nom")->searchable(true)->sortable(true),
            TextColumn::add("description", "Description")->searchable(true),
            DateColumn::add("created_at", "Créée le", format: "d/m/Y")->searchable(true)->sortable(true),
            BadgeColumn::add("registration_state", "Statut", [
                BadgeColumn::Badge("not_full", "Incomplète", "red"),
                BadgeColumn::Badge("pending", "En Attente", "orange"),
                BadgeColumn::Badge("registered", "Inscrite", "green")
            ])->sortable(true),   
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
            "show" => "teams.show",
        ];
    }
}