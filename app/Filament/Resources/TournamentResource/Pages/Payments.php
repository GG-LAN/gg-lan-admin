<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource\Widgets\TournamentPayments;

class Payments extends TournamentPage
{
    protected static ?string $navigationIcon = 'fas-money-bill';

    protected static string $view = 'filament.resources.tournament-resource.pages.payments';

    public static function getNavigationLabel(): string
    {
        return __("Payments");
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentPayments::make(["tournament" => $this->record]),
        ];
    }
}
