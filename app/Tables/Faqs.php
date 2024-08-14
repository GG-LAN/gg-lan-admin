<?php

namespace App\Tables;

use App\Models\Faq;
use App\Helpers\Table\Table;
use App\Helpers\Table\DateColumn;
use App\Helpers\Table\TextColumn;
use App\Helpers\Table\BadgeColumn;

class Faqs extends Table {

    protected $model = Faq::class;

    public function columns(): array {
        return [
            TextColumn::add("question", "Question")->searchable(true)->sortable(true),
            TextColumn::add("response", "Réponse")->searchable(true)->sortable(true),
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