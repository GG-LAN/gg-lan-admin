<?php

namespace App\Tables;

use App\Helpers\Table\BoolColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\User;

class Players extends Table
{

    protected $model = User::class;

    public function columns(): array
    {
        return [
            TextColumn::add("pseudo", "Pseudo")->searchable(true)->sortable(true),
            TextColumn::add("name", "Name")->searchable(true)->sortable(true),
            TextColumn::add("email", "Email")->searchable(true)->sortable(true),
            BoolColumn::add("admin", "Role", labelTrue: "Admin", labelFalse: "Player")->sortable(true),
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
            "show" => "players.show",
        ];
    }
}
