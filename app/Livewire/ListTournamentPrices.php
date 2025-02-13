<?php
namespace App\Livewire;

use App\Models\Tournament;
use App\Models\TournamentPrice;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Livewire\Component;

class ListTournamentPrices extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Tournament $tournament;

    public function table(Table $table): Table
    {
        return $table
            ->selectable()
            ->query(TournamentPrice::where("tournament_id", $this->tournament->id))
            ->columns([
                TextColumn::make('name')
                    ->translateLabel(),
                TextColumn::make('price')
                    ->translateLabel(),
                IconColumn::make('active')
                    ->translateLabel()
                    ->boolean()
                    ->trueIcon('far-circle-check')
                    ->falseIcon('far-circle-xmark')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make("active")
                    ->label(__("Status"))
                    ->trueLabel(__("Active"))
                    ->falseLabel(__("Inactive"))
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
        return view('livewire.list-tournament-prices');
    }
}
