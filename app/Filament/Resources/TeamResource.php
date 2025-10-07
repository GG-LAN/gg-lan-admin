<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\Pages\ShowTeam;
use App\Models\Team;
use App\Models\Tournament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'fas-users';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __("Team");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Teams");
    }

    public static function getNavigationGroup(): string
    {
        return __("Players");
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->label(__("Team"))
                    ->searchable(),
                TextColumn::make('description')
                    ->translateLabel()
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime("d/m/Y")
                    ->sortable(),
                TextColumn::make("players_by_team")
                    ->label(__("Player / Team"))
                    ->state(function (Team $team) {
                        $test = $team->users->count() . " / " . $team->tournament->game->places;
                        return $test;
                    }),
                TextColumn::make("tournament.name")
                    ->translateLabel(),
                TextColumn::make("registration_state")
                    ->label(__("Status"))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        "registered"                      => "success",
                        "pending"                         => "warning",
                        "not_full"                        => "danger",
                    })
                    ->formatStateUsing(fn(string $state): string => __(Str::ucfirst($state)))
                    ->sortable(),
                TextColumn::make('average_elo_cs2')
                    ->label(__("Average Elo CS2"))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime("d/m/Y")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make("registration_state")
                    ->label(__("Status"))
                    ->options([
                        "registered" => __("Registered"),
                        "pending"    => __("Pending"),
                        "not_full"   => __("Not_full"),
                    ])
                    ->multiple(),
                SelectFilter::make('tournament')
                    ->translateLabel()
                    ->multiple()
                    ->relationship('tournament', 'name', fn(Builder $query) => $query->orderBy("id", "desc"))
                    ->preload()
                    ->default(function () {
                        return Tournament::getOpenTournaments()->pluck("id")->toArray();
                    }),
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTeams::route('/'),
            'view'  => ShowTeam::route("/{record}"),
        ];
    }
}
