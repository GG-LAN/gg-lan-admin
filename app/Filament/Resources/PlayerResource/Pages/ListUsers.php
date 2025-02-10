<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
