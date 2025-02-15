<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Filament\Resources\PlayerResource\Pages\ShowPlayer;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlayerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-user';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __("Player");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Players");
    }

    public static function getNavigationGroup(): string
    {
        return __('Players');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('pseudo')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->required()
                    ->maxLength(255),
                DatePicker::make('birth_date')
                    ->translateLabel()
                    ->required(),
                Toggle::make('admin')
                    ->translateLabel()
                    ->required(),
                TextInput::make('password')
                    ->translateLabel()
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('pseudo')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make("age")
                    ->translateLabel()
                    ->state(function (User $record) {
                        return (int) (new Carbon($record->birth_date))->diffInYears(now()) . " " . __("years");
                    })
                    ->toggleable(),
                IconColumn::make('admin')
                    ->label(__("Role"))
                    ->boolean()
                    ->trueIcon('far-circle-check')
                    ->falseIcon('far-circle-xmark')
                    ->sortable(),

                TextColumn::make('email_verified_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Filter::make("is_admin")
                    ->translateLabel()
                    ->query(fn(Builder $query): Builder => $query->where("admin", true)),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("edit")
                        ->fillForm(fn(User $player): array=> [
                            'pseudo' => $player->pseudo,
                            'admin'  => $player->admin,
                        ])
                        ->form([
                            TextInput::make('pseudo')
                                ->translateLabel()
                                ->required()
                                ->maxLength(255),
                            Toggle::make('admin')
                                ->translateLabel(),
                        ])
                        ->action(function (User $player, array $data) {
                            $player->pseudo = $data["pseudo"];
                            $player->admin  = $data["admin"];

                            $player->save();

                            Notification::make()
                                ->title(__("responses.player.updated"))
                                ->success()
                                ->send();
                        })
                        ->icon("fas-pen-to-square")
                        ->color("primary")
                        ->translateLabel(),
                    DeleteAction::make()
                        ->modalHeading(__("Delete Player")),
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
            'index' => Pages\ListPlayers::route('/'),
            'view'  => ShowPlayer::route('/{record}'),
        ];
    }
}
