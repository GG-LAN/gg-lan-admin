<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
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
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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

    public function getSubheading(): string | Htmlable | null
    {
        $statusLabel = __(Str::ucfirst($this->record->status));

        switch ($this->record->status) {
            case 'open':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'closed':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'finished':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-warning'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            default:
                return null;
                break;
        }
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
