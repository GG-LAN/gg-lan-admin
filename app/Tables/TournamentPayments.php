<?php

namespace App\Tables;

use App\Helpers\Table\BoolColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\PurchasedPlace;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TournamentPayments extends Table
{
    protected $model = PurchasedPlace::class;

    protected string $defaultSort = "paid, desc";

    public function resource(): HasManyThrough
    {
        return $this->tournament->purchasedPlaces();
    }

    public function columns(): array
    {
        return [
            TextColumn::add("user.pseudo", "Player"),
            BoolColumn::add("paid", "Status",
                labelTrue: "Payment validated",
                labelFalse: "Waiting for payment"
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
            "customActions" => [
                [
                    "type" => "success",
                    "icon" => "money-bill",
                    "route" => "payments.store",
                    "condition" => "props.row.paid == false",
                ],
            ],
        ];
    }
}
