<?php

namespace App\Tables;

use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\Faq;

class Faqs extends Table
{

    protected $model = Faq::class;

    public function columns(): array
    {
        return [
            TextColumn::add("question", "Question")->searchable(true)->sortable(true),
            TextColumn::add("response", "Response")->searchable(true)->sortable(true),
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
