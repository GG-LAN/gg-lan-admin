<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use Filament\Pages\Page;

class CoverImage extends Page
{
    protected static ?string $navigationIcon = 'fas-panorama';

    protected static string $view = 'filament.clusters.settings.pages.cover-image';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 4;
}
