<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TournamentResource\Pages;
use App\Models\Game;
use App\Models\Tournament;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationIcon = 'fas-trophy';

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
        return __('Tournaments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\TextInput::make('places')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('image')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__("Tournament"))
                    ->searchable(),
                TextColumn::make('game.name')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('start_end_date')
                    ->label(__("Dates Start | End"))
                    ->state(function (Tournament $tournament) {
                        return (new Carbon($tournament->start_date))->format("d/m/Y") . " | " . (new Carbon($tournament->end_date))->format("d/m/Y");
                    }),
                TextColumn::make('type')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => __(Str::ucFirst($state)))
                    ->searchable(),
                TextColumn::make('places')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cashprize')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('status')
                    ->translateLabel()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open'                            => 'success',
                        'finished'                        => 'warning',
                        'closed'                          => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => __(Str::ucfirst($state)))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open'     => __("Open"),
                        'finished' => __("Finished"),
                        'closed'   => __("Closed"),
                    ])
                    ->default('open'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('fas-trophy')
            ->emptyStateHeading(function (Table $table) {
                $status = __($table->getFilter("status")->getState()["value"]);

                return __("responses.tournament.no_tournament_found", ["status" => $status]);
            })
            ->emptyStateActions([
                self::createTournamentAction("table"),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTournaments::route('/'),
            // 'create' => Pages\CreateTournament::route('/create'),
            // 'edit' => Pages\EditTournament::route('/{record}/edit'),
        ];
    }

    public static function createTournamentAction(?string $type = null)
    {
        $action = Action::make("create");

        if ($type == "table") {
            $action = ActionTable::make("create");
        }

        return $action
            ->label(__("Create Tournament"))
            ->icon('fas-plus')
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
                        TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->placeholder("GG-LAN #18 CS2")
                            ->columnspan(1),
                        TextInput::make('cashprize')
                            ->translateLabel()
                            ->maxLength(255)
                            ->placeholder("XXX €")
                            ->columnspan(1),
                        Select::make('game')
                            ->translateLabel()
                            ->required()
                            ->options([
                                __("By Team") => Game::teamGame()
                                    ->orderBy("name")
                                    ->get()
                                    ->pluck('name', 'id')
                                    ->toArray(),
                                __("Solo")    => Game::soloGame()
                                    ->orderBy("name")
                                    ->get()
                                    ->pluck('name', 'id')
                                    ->toArray(),
                            ])
                            ->searchable()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->translateLabel()
                            ->required()
                            ->placeholder(__("Short description of the tournament"))
                            ->autosize()
                            ->columnSpanFull(),
                    ]),
                Step::make(__("Dates"))
                    ->columns(2)
                    ->description("")
                    ->icon("fas-calendar"),
                Step::make(__("Payment"))
                    ->columns(2)
                    ->description("")
                    ->icon("fas-money-bill"),
            ])
            ->action(function (array $data): void {
                //
            });
    }
}
