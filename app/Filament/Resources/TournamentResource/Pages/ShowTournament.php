<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use App\Filament\Resources\TournamentResource\Widgets\TournamentFilling;
use App\Models\Game;
use App\Models\Tournament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ShowTournament extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TournamentResource::class;

    protected static string $view = 'filament.resources.tournament-resource.pages.show-tournament';

    public Tournament $record;

    public ?array $dataInfos = [];
    public ?array $dataDates = [];

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentFilling::make(["tournament" => $this->record]),
        ];
    }

    public function mount(): void
    {
        $this->formInfos->fill([
            "name"        => $this->record->name,
            "cashprize"   => $this->record->cashprize,
            "description" => $this->record->description,
            "game"        => $this->record->game->id,
            "places"      => $this->record->places,
        ]);

        $this->formDates->fill([
            "start_date" => $this->record->start_date,
            "end_date"   => $this->record->end_date,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'formInfos',
            'formDates',
        ];
    }

    public function formInfos(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make("name")
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->placeholder("GG-LAN #18 CS2")
                            ->columnspan(1),
                        TextInput::make("cashprize")
                            ->translateLabel()
                            ->maxLength(255)
                            ->placeholder("XXX €")
                            ->columnspan(1),
                        Textarea::make("description")
                            ->translateLabel()
                            ->required()
                            ->placeholder(__("Short description of the tournament"))
                            ->autosize()
                            ->columnSpanFull(),
                        Select::make("game")
                            ->translateLabel()
                            ->required()
                            ->options([
                                __("By Team") => Game::teamGame()
                                    ->orderBy("name")
                                    ->get()
                                    ->pluck("name", "id")
                                    ->toArray(),
                                __("Solo")    => Game::soloGame()
                                    ->orderBy("name")
                                    ->get()
                                    ->pluck("name", "id")
                                    ->toArray(),
                            ])
                            ->searchable()
                            ->columnspan(1),
                        TextInput::make("places")
                            ->translateLabel()
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->inputMode("decimal")
                            ->columnspan(1),
                    ]),
            ])
            ->statePath('dataInfos');
    }

    public function formDates(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make("start_date")
                    ->translateLabel()
                    ->required(),
                DatePicker::make("end_date")
                    ->translateLabel()
                    ->required(),
            ])
            ->statePath('dataDates');
    }

    public function updateInfos(): void
    {
        $data = $this->formInfos->getState();

        $this->record->update([
            "name"        => $data["name"],
            "cashprize"   => $data["cashprize"],
            "description" => $data["description"],
            "game_id"     => $data["game"],
            "places"      => $data["places"],
        ]);

        Notification::make()
            ->title(__("responses.tournament.updated"))
            ->success()
            ->send();
    }

    public function updateDates(): void
    {
        $data = $this->formDates->getState();

        $this->record->update($data);

        Notification::make()
            ->title(__("responses.tournament.updated"))
            ->success()
            ->send();
    }
}
