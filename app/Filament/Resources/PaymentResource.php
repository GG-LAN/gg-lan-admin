<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\PurchasedPlace;
use App\Models\Tournament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = PurchasedPlace::class;

    protected static ?string $navigationIcon = 'fas-money-bill';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __("Payment");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Payments");
    }

    public static function getNavigationGroup(): string
    {
        return __('Tournaments');
    }

    public static function getNavigationBadge(): ?string
    {
        return PurchasedPlace::query()
            ->where("paid", true)
            ->where("tournaments.status", "open")
            ->join("tournament_prices", "tournament_prices.id", "=", "purchased_places.tournament_price_id")
            ->join("tournaments", "tournaments.id", "=", "tournament_prices.tournament_id")
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return "success";
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
            ->query(Tournament::allPaymentsQuery())
            ->columns([
                TextColumn::make("user.pseudo")
                    ->label(__("Player"))
                    ->searchable()
                    ->url(function (Model $record): ?string {
                        return route(
                            'filament.admin.resources.players.view',
                            ['record' => $record->user_id]
                        );
                    }),
                TextColumn::make("tournamentPrice.tournament.name")
                    ->translateLabel()
                    ->url(function (Model $record): ?string {
                        return route(
                            'filament.admin.resources.tournaments.view',
                            ['record' => $record->tournament->id]
                        );
                    }),
                TextColumn::make("paid")
                    ->label(__("Status"))
                    ->badge()
                    ->color(fn(bool $state): string => match ($state) {
                        true                            => "success",
                        false                           => "danger",
                    })
                    ->formatStateUsing(fn(string $state): string => $state ? __("Payment validated") : __("Waiting for payment")),
            ])
            ->filters([
                TernaryFilter::make("paid")
                    ->label(__("Status"))
                    ->trueLabel(__("Payment validated"))
                    ->falseLabel(__("Waiting for payment"))
                    ->default(true),
                SelectFilter::make('tournament')
                    ->translateLabel()
                    ->multiple()
                    ->relationship('tournamentPrice.tournament', 'name', fn(Builder $query) => $query->where('status', 'open'))
                    ->preload(),
            ])
            ->actions([
                //
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
            'index' => Pages\ListPayments::route('/'),
        ];
    }
}
