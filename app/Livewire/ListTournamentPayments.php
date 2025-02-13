<?php
namespace App\Livewire;

use App\Models\Tournament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Livewire\Component;

class ListTournamentPayments extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Tournament $tournament;

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->selectable()
            ->query($this->tournament->paymentsQuery())
            ->columns([
                TextColumn::make('user.pseudo')
                    ->label(__("Player"))
                    ->sortable(),
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
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-tournament-payments');
    }
}
