<?php
namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'fas-gamepad';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __("Game");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Games");
    }

    public static function getNavigationGroup(): string
    {
        return __('Tournaments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make("name")
                            ->translateLabel()
                            ->placeholder("CS2")
                            ->required()
                            ->maxLength(255),
                        TextInput::make("places")
                            ->label(__("Player / Team"))
                            ->placeholder("1 = Jeu solo | 2+ = Jeu par équipe")
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make("name")
                    ->label(__("Game"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("players_by_team")
                    ->label(__("Player / Team"))
                    ->state(fn(Game $game): string => $game->places),
            ])
            ->filters([
                //
            ])
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color("primary")
                        ->icon("fas-pen-to-square"),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->size(ActionSize::Medium)
                    ->icon('fas-ellipsis-vertical')
                    ->color("gray"),
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
            'index' => Pages\ListGames::route('/'),
        ];
    }
}
