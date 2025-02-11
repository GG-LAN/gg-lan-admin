<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make("add")
                ->form([
                    Grid::make()
                        ->schema([
                            TextInput::make('name')
                                ->translateLabel()
                                ->required()
                                ->maxLength(255)
                                ->placeholder("George Abitbol")
                                ->columnspan(1),
                            TextInput::make('email')
                                ->translateLabel()
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->placeholder("progamer@gglan.fr")
                                ->columnspan(1),
                            TextInput::make('pseudo')
                                ->translateLabel()
                                ->required()
                                ->maxLength(255)
                                ->placeholder("DiGiDiX")
                                ->columnspan(1),
                            DatePicker::make('birth_date')
                                ->translateLabel()
                                ->required()
                                ->columnspan(1),
                            TextInput::make('password')
                                ->translateLabel()
                                ->password()
                                ->required()
                                ->maxLength(255)
                                ->placeholder("••••••••")
                                ->columnspan(2),
                            Toggle::make('admin')
                                ->translateLabel()
                                ->required()
                                ->columnspan(2),
                        ])
                        ->columns(2),
                ])
                ->action(function (array $data) {
                    User::create($data);

                    Notification::make()
                        ->title(__("responses.player.created"))
                        ->success()
                        ->send();
                })
                ->icon("fas-plus")
                ->color("success")
                ->translateLabel()
            ,
        ];
    }
}
