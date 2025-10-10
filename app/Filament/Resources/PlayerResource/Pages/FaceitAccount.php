<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Models\User;
use App\Services\Faceit;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class FaceitAccount extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PlayerResource::class;

    protected static ?string $navigationIcon = 'fas-gamepad';

    protected static string $view = 'filament.resources.player-resource.pages.faceit-account';

    public User $record;

    public array $data = [];

    public function getTitle(): string | Htmlable
    {
        return $this->record->pseudo;
    }
    public static function getNavigationLabel(): string
    {
        return __("Faceit Account");
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->record,
        ];
    }

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
    }

    public function mount(): void
    {
        $this->form->fill([
            "nickname"    => optional($this->record->faceitAccount)->nickname,
            "player_id"   => optional($this->record->faceitAccount)->player_id,
            "elo_cs2"     => optional($this->record->faceitAccount)->elo_cs2,
            "steam_id_64" => optional($this->record->faceitAccount)->steam_id_64,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('nickname')
                            ->translateLabel()
                            ->columnspan(2),
                        TextInput::make('player_id')
                            ->translateLabel()
                            ->readonly()
                            ->disabled()
                            ->columnspan(1),
                        TextInput::make('elo_cs2')
                            ->translateLabel()
                            ->readonly()
                            ->disabled()
                            ->columnspan(1),
                        TextInput::make('steam_id_64')
                            ->translateLabel()
                            ->readonly()
                            ->disabled()
                            ->columnspan(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $data = $this->form->getState();

        $faceitPlayer = Faceit::getPlayerByNickname($data["nickname"]);

        if (! $faceitPlayer) {
            Notification::make()
                ->title(__("responses.faceit.user_not_found"))
                ->danger()
                ->send();

            return;
        }

        $this->record->linkFaceitAccount($faceitPlayer->only([
            "player_id",
            "nickname",
            "steam_id_64",
            "games",
        ]));

        // Refresh form data
        $this->mount();

        Notification::make()
            ->title(__("responses.player.updated"))
            ->success()
            ->send();
    }
}
