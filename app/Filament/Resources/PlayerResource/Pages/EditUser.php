<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
