<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class TournamentSettings extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected static ?string $navigationIcon = 'fas-cogs';

    public static function getNavigationLabel(): string
    {
        return __("Settings");
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['discord_notif'] = $this->record->discord_notif;

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__("Discord Notifications"))
                    ->extraAttributes(['class' => 'rounded-b-lg'])
                    ->description(__("If we want that a registration to the tournament send a discord notification"))
                    ->schema([
                        Toggle::make("discord_notif")
                            ->translateLabel()
                            ->onIcon("fas-check")
                            ->offIcon("fas-xmark"),
                    ])
                    ->icon("fab-discord")
                    ->collapsible()
                    ->persistCollapsed(),
            ]);
    }
}
