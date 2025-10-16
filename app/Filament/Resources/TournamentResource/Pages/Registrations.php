<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource\Widgets\TournamentRegistrations;

class Registrations extends TournamentPage
{

    protected static ?string $navigationIcon = 'fas-users';

    protected static string $view = 'filament.resources.tournament-resource.pages.teams';

    public static function getNavigationLabel(): string
    {
        return __("Registrations");
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentRegistrations::make(["tournament" => $this->record]),
        ];
    }
}
