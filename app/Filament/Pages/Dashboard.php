<?php
namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'fas-chart-column';

    protected static string $view = 'filament.pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::pages/dashboard.title');
    }

    public function getHeaderWidgets(): array
    {
        return [
            DashboardOverview::class,
        ];
    }
}
