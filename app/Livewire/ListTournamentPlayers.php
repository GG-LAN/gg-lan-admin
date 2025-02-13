<?php
namespace App\Livewire;

use App\Models\Tournament;
use App\Models\TournamentUser;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Component;

class ListTournamentPlayers extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Tournament $tournament;

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->defaultSort("created_at", "desc")
            ->selectable()
            ->query(TournamentUser::where("tournament_id", $this->tournament->id))
            ->columns([
                TextColumn::make('user.pseudo')
                    ->label(__("Player"))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__("Registered"))
                    ->since()
                    ->sortable(),
                TextColumn::make("status")
                    ->label(__("Status"))
                    ->badge()
                    ->color("success")
                    ->state(__(Str::ucfirst("registered"))),
            ])
            ->filters([
                // ...
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
        return view('livewire.list-tournament-players');
    }
}
