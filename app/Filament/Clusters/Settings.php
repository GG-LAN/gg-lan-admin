<?php
namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'fas-cog';

    public static function getNavigationGroup(): string
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Settings");
    }

    public static function getClusterBreadcrumb(): string
    {
        return __("Settings");
    }
}
