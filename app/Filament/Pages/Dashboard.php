<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

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
            AccountWidget::class,
            FilamentInfoWidget::class,
        ];
    }
}
