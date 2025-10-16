<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TournamentResource\Pages;
use App\Filament\Resources\TournamentResource\Pages\Payments;
use App\Filament\Resources\TournamentResource\Pages\Registrations;
use App\Filament\Resources\TournamentResource\Pages\ShowTournament;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationIcon = "fas-trophy";

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getModelLabel(): string
    {
        return __("Tournament");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Tournaments");
    }

    public static function getNavigationGroup(): string
    {
        return __("Tournaments");
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->defaultSort("id", "desc")
            ->columns([
                TextColumn::make("name")
                    ->label(__("Tournament"))
                    ->searchable(),
                TextColumn::make("game.name")
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make("start_end_date")
                    ->label(__("Dates Start | End"))
                    ->state(function (Tournament $tournament) {
                        return (new Carbon($tournament->start_date))->format("d/m/Y") . " | " . (new Carbon($tournament->end_date))->format("d/m/Y");
                    }),
                TextColumn::make("type")
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => __(Str::ucFirst($state)))
                    ->searchable(),
                TextColumn::make("places")
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                TextColumn::make("cashprize")
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make("status")
                    ->translateLabel()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        "open"     => "success",
                        "finished" => "warning",
                        "closed"   => "danger",
                    })
                    ->formatStateUsing(fn(string $state): string => __(Str::ucfirst($state)))
                    ->sortable()
                    ->searchable(),
                TextColumn::make("created_at")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make("status")
                    ->options([
                        "open"     => __("Open"),
                        "finished" => __("Finished"),
                        "closed"   => __("Closed"),
                    ])
                    ->multiple()
                    ->default(["open", "closed"]),
            ])
            ->actions([
                ActionGroup::make([
                    ActionTable::make(__("Parental permission"))
                        ->action(fn(Tournament $record) => redirect()->route("download.parental-permission", ["tournament" => $record->id]))
                        ->icon("fas-download"),
                    ActionTable::make("delete")
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
                ])
                    ->size(ActionSize::Medium)
                    ->icon('fas-ellipsis-vertical')
                    ->color("gray"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon("fas-trophy")
            ->emptyStateHeading(function (Table $table) {
                $statusFilter = $table->getFilter("status")->getState();

                $statusFilterValue = isset($statusFilter["value"]) ? $statusFilter["value"] : "open";

                $status = __($statusFilterValue);

                return __("responses.tournament.no_tournament_found", ["status" => $status]);
            })
            ->emptyStateActions([
                self::createTournamentAction("table"),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            "index"         => Pages\ListTournaments::route("/"),
            "view"          => ShowTournament::route("/{record}"),
            "registrations" => Registrations::route("/{record}/registrations"),
            "payments"      => Payments::route("/{record}/payments"),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ShowTournament::class,
            Registrations::class,
            Payments::class,
        ]);
    }

    public static function createTournamentAction(?string $type = null)
    {
        $action = Action::make("create");

        if ($type == "table") {
            $action = ActionTable::make("create");
        }

        return $action
            ->label(__("Create Tournament"))
            ->icon("fas-plus")
            ->color("success")
            ->button()
            ->modal()
            ->modalWidth(MaxWidth::FiveExtraLarge)
            ->steps([
                Step::make(__("Tournament"))
                    ->columns(2)
                    ->description("")
                    ->icon("fas-trophy")
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
                        Textarea::make("description")
                            ->translateLabel()
                            ->required()
                            ->placeholder(__("Short description of the tournament"))
                            ->autosize()
                            ->columnSpanFull(),
                        Toggle::make("is_external")
                            ->translateLabel()
                            ->default(false)
                            ->live()
                            ->columnspan(2),
                        TextInput::make("external_url")
                            ->translateLabel()
                            ->url()
                            ->placeholder("https://...")
                            ->prefixIcon("fas-globe")
                            ->live(onBlur: true)
                            ->required()
                            ->hidden(fn(Get $get) => ! $get("is_external"))
                            ->columnspan(1),
                    ]),
                Step::make(__("Dates"))
                    ->columns(2)
                    ->description("")
                    ->icon("fas-calendar-days")
                    ->schema([
                        DatePicker::make("start_date")
                            ->translateLabel()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(?string $state, Set $set) => $set("end_date", (new Carbon($state))->addDay()->format("Y-m-d"))),
                        DatePicker::make("end_date")
                            ->translateLabel()
                            ->required(),
                    ]),
                Step::make(__("Payment"))
                    ->description("")
                    ->icon("fas-money-bill")
                    ->schema([
                        Toggle::make("has_price")
                            ->translateLabel()
                            ->default(false)
                            ->live(),
                        TextInput::make("normal_price")
                            ->translateLabel()
                            ->numeric()
                            ->minValue(1)
                            ->inputMode("decimal")
                            ->prefixIcon("fab-stripe-s")
                            ->suffixIcon("fas-euro-sign")
                            ->live(onBlur: true)
                            ->hidden(fn(Get $get) => ! $get("has_price"))
                            ->afterStateUpdated(fn(?string $state, Set $set) => $set("last_week_price", $state)),
                        Toggle::make("has_last_week_price")
                            ->translateLabel()
                            ->default(false)
                            ->hidden(fn(Get $get) => ! $get("has_price"))
                            ->live(),
                        TextInput::make("last_week_price")
                            ->translateLabel()
                            ->numeric()
                            ->minValue(1)
                            ->inputMode("decimal")
                            ->prefixIcon("fab-stripe-s")
                            ->suffixIcon("fas-euro-sign")
                            ->hidden(fn(Get $get) => ! $get("has_last_week_price") || ! $get("has_price")),
                    ]),
            ])
            ->action(function (array $data): void {
                $game = Game::find($data["game"]);

                $type = "";

                if ($data["is_external"]) {
                    $type = "external";
                } else {
                    $type = $game->places > 1 ? "team" : "solo";
                }

                $tournament = Tournament::create([
                    "name"         => $data["name"],
                    "description"  => $data["description"],
                    "game_id"      => $game->id,
                    "start_date"   => $data["start_date"],
                    "end_date"     => $data["end_date"],
                    "places"       => $data["places"],
                    "cashprize"    => $data["cashprize"],
                    "status"       => "closed",
                    "type"         => $type,
                    "external_url" => $data["external_url"],
                ]);

                // Create Stripe Product if wanted
                if ($data["has_price"]) {
                    $product = TournamentPrice::createProduct([
                        "name" => $tournament->name,
                    ]);

                    $normalPrice   = $data["normal_price"];
                    $lastWeekPrice = $data["normal_price"];

                    if ($data["has_last_week_price"]) {
                        $lastWeekPrice = $data["last_week_price"];
                    }

                    // Normal price
                    TournamentPrice::create([
                        "name"          => $tournament->name,
                        "tournament_id" => $tournament->id,
                        "type"          => "normal",
                        "currency"      => "eur",
                        "unit_amount"   => $normalPrice,
                        "product"       => $product->id,
                        "active"        => true,
                    ]);

                    // Last week price
                    TournamentPrice::create([
                        "name"          => $tournament->name . " Last Week",
                        "tournament_id" => $tournament->id,
                        "type"          => "last_week",
                        "currency"      => "eur",
                        "unit_amount"   => $lastWeekPrice,
                        "product"       => $product->id,
                    ]);
                }

                Notification::make()
                    ->title(__("responses.tournament.created"))
                    ->success()
                    ->send();
            });
    }
}
