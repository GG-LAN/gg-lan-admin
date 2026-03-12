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

        $breadcrumbs[] = __("Payments");

        return $breadcrumbs;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentPayments::make(["tournament" => $this->record]),
        ];
    }
}
