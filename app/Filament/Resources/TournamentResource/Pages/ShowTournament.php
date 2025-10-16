<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource\Widgets\TournamentFilling;
use App\Models\Game;
use App\Models\Tournament;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class ShowTournament extends TournamentPage implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-trophy';

    protected static string $view = 'filament.resources.tournament-resource.pages.show-tournament';

    public ?array $dataInfos = [];
    public ?array $dataDates = [];

    public static function getNavigationLabel(): string
    {
        return __("Tournament");
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentFilling::make(["tournament" => $this->record]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make(__("Open Tournament"))
                ->translateLabel()
                ->icon("fas-check")
                ->color("success")
                ->hidden(fn(): bool => ($this->record->status != "closed"))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = "open";
                    $this->record->save();

                    Notification::make()
                        ->title(__("responses.tournament.opened"))
                        ->success()
                        ->send();
                }),
            ActionGroup::make([
                Action::make(__("Parental permission"))
                    ->action(fn() => redirect()->route("download.parental-permission", ["tournament" => $this->record->id]))
                    ->icon("fas-download"),
            ])
                ->icon("fas-ellipsis-vertical"),
            Action::make("delete")
                ->translateLabel()
                ->icon("fas-trash-can")
                ->color("danger")
                ->modalHeading(__("Delete Tournament"))
                ->action(function (Tournament $record) {
                    $record->delete();

                    Notification::make()
                        ->title(__("responses.tournament.deleted"))
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(),
        ];
    }

    public function mount(): void
    {
        $this->formInfos->fill([
            "name"         => $this->record->name,
            "cashprize"    => $this->record->cashprize,
            "start_date"   => $this->record->start_date,
            "end_date"     => $this->record->end_date,
            "description"  => $this->record->description,
            "game"         => $this->record->game->id,
            "places"       => $this->record->places,
            "external_url" => $this->record->external_url,
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
                        TextInput::make("external_url")
                            ->translateLabel()
                            ->url()
                            ->required()
                            ->columnSpanFull()
                            ->hidden($this->record->type != "external"),
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
                    ->required()
                    ->columnspan(1),
                DatePicker::make("end_date")
                    ->translateLabel()
                    ->required()
                    ->columnspan(1),
            ])
            ->statePath('dataDates');
    }

    public function updateInfos(): void
    {
        $updateArray = [
            "name"        => $this->dataInfos["name"],
            "cashprize"   => $this->dataInfos["cashprize"],
            "description" => $this->dataInfos["description"],
            "game_id"     => $this->dataInfos["game"],
            "places"      => $this->dataInfos["places"],
        ];

        if ($this->record->type == "external") {
            $updateArray["external_url"] = $this->dataInfos["external_url"];
        }

        $this->record->update($updateArray);

        Notification::make()
            ->title(__("responses.tournament.updated"))
            ->success()
            ->send();
    }

    public function updateDates(): void
    {
        $this->record->update($this->dataDates);

        Notification::make()
            ->title(__("responses.tournament.updated"))
            ->success()
            ->send();
    }
}
