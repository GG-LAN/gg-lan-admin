<?php

namespace App\Tables;

use App\Helpers\Table\BoolColumn;
use App\Helpers\Table\Table;
use App\Helpers\Table\TextColumn;
use App\Models\PurchasedPlace;
use Illuminate\Database\Eloquent\Builder;

class PurchasedPlaces extends Table
{
    protected $model = PurchasedPlace::class;

    protected string $defaultSort = "paid, desc";

    public function resource(): Builder
    {
        return PurchasedPlace::whereRelation('tournamentPrice.tournament', 'status', 'open');
    }

    public function columns(): array
    {
        return [
            TextColumn::add("user.pseudo", "Joueur")->searchable(true),
            TextColumn::add("tournamentPrice.tournament.name", "Tournois"),
            BoolColumn::add("paid", "Statut", "Paiement validé", "En attente de paiement")->sortable(true),
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
