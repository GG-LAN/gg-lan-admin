<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlayerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-user';

    public static function getPluralModelLabel(): string
    {
        return __("Players");
    }

    public static function getNavigationGroup(): string
    {
        return __('Players');
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
                IconColumn::make('admin')
                    ->translateLabel()
                    ->boolean(),

                TextColumn::make('birth_date')
                    ->translateLabel()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
