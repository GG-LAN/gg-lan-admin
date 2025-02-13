<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use Filament\Pages\Page;

class Faq extends Page
{
    protected static ?string $navigationIcon = 'fas-question';

    protected static string $view = 'filament.clusters.settings.pages.faq';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 3;
}
