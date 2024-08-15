<?php

namespace App\Tables;

use App\Models\Test;
use App\Helpers\Table\Table;

class Tests extends Table
{
    protected $model = Test::class;

    public function columns(): array
    {
        return [
            //
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