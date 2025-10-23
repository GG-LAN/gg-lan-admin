<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use App\Filament\Resources\TournamentResource\Widgets\TournamentFilling;
use App\Models\Game;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ComponentAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewTournament extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected static ?string $navigationIcon = 'fas-trophy';

    public static function getNavigationLabel(): string
    {
        return __("Tournament");
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    public function getBreadcrumb(): string
    {
        return $this->record->name;
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            ...(filled($breadcrumb = $this->getBreadcrumb()) ? [$breadcrumb] : []),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        $breadcrumbs[] = __("Edit");

        return $breadcrumbs;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return $this->getResource()::getSubheading($this->record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TournamentFilling::make(["tournament" => $this->record]),
        ];
    }

    public function form(Form $form): Form
    {
        $sections = [];

        if ($this->record->status == "closed") {
            $sections[] = $this->openTournamentSection();
        }

        array_push($sections,
            $this->informationsSection(),
            $this->datesSection()
        );

        return $form
            ->schema($sections)
            ->columns(3);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make("update")
                ->translateLabel()
                ->color("success")
                ->icon("fas-floppy-disk")
                ->action('save'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__("Saved"))
            ->body(__("responses.tournament.updated"));
    }

    private function openTournamentSection(): ?Section
    {

        return Section::make(__("Open Tournament"))
            ->icon("fas-door-open")
            ->description(__("Open the tournament to start the registrations."))
            ->schema([
                Actions::make([
                    ComponentAction::make(__("Open Tournament"))
                        ->icon("fas-check")
                        ->color("success")
                        ->requiresConfirmation()
                        ->action(function () {
                            $this->record->status = "open";
                            $this->record->save();

                            Notification::make()
                                ->title(__("responses.tournament.opened"))
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->columnSpanFull();
    }

    private function informationsSection(): Section
    {
        return Section::make(__("Tournament Info"))
            ->icon("fas-info")
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
                        Select::make("game_id")
                            ->label(__("Game"))
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
                            ->placeholder("https://...")
                            ->prefixIcon("fas-globe")
                            ->required()
                            ->columnSpanFull()
                            ->hidden($this->record->type != "external"),
                    ]),
            ])
            ->columnSpan(2);
    }

    private function datesSection(): Section
    {
        return Section::make(__("Tournament Dates"))
            ->icon("fas-calendar")
            ->schema([
                DatePicker::make("start_date")
                    ->translateLabel()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(?string $state, Set $set) => $set("end_date", (new Carbon($state))->addDay()->format("Y-m-d"))),
                DatePicker::make("end_date")
                    ->translateLabel()
                    ->required(),
            ])
            ->columnSpan(1);
    }
}
