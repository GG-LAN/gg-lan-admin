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

    public function getBreadcrumb(): string
    {
        return $this->record->name;
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            ...(filled($breadcrumb = $this->getBreadcrumb()) ? [$breadcrumb] : []),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        $breadcrumbs[] = __("Registrations");

        return $breadcrumbs;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentRegistrations::make(["tournament" => $this->record]),
        ];
    }
}
