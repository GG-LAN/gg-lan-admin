<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use Filament\Pages\Page;

class Rules extends Page
{
    protected static ?string $navigationIcon = 'fas-align-left';

    protected static string $view = 'filament.clusters.settings.pages.rules';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 1;
}
