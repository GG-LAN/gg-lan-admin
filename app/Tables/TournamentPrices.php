<?php

namespace App\Tables;

use App\Helpers\Table\BoolColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\TournamentPrice;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentPrices extends Table
{
    protected $model = TournamentPrice::class;

    protected bool $paginate = false;

    public function resource(): HasMany
    {
        return $this->tournament->prices();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("name", "Name"),
            TextColumn::add("price", "Price"),
            BoolColumn::add("active", "Status",
                labelTrue: "Active",
                labelFalse: "Inactive"
            ),
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
