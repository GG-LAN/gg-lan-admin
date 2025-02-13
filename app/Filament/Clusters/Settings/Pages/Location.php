<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use Filament\Pages\Page;

class Location extends Page
{
    protected static ?string $navigationIcon = 'fas-location-dot';

    protected static string $view = 'filament.clusters.settings.pages.location';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 2;
}
