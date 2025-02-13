<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Resources\Pages\Page;

class ShowTeam extends Page
{
    protected static string $resource = TeamResource::class;

    protected static string $view = 'filament.resources.team-resource.pages.show-team';
}
