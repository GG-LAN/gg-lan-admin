<?php
namespace App\Livewire;

use App\Models\Team;
use App\Models\Tournament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;

class ListTournamentTeams extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Tournament $tournament;

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->defaultSort("registration_state", "desc")
            ->selectable()
            ->query(Team::where("tournament_id", $this->tournament->id))
            ->columns([
                TextColumn::make('name')
                    ->label(__("Team"))
                    ->sortable()
                    ->url(function (Model $record): string {
                        return route(
                            'filament.admin.resources.teams.view',
                            ['record' => $record->id]
                        );
                    }),
                TextColumn::make("captain.pseudo")
                    ->translateLabel()
                    ->url(function (Model $record): string {
                        return route(
                            'filament.admin.resources.players.view',
                            ['record' => $record->captain->id]
                        );
                    }),
                TextColumn::make("registration_state_updated_at")
                    ->label(__("Registered / Pending"))
                    ->since()
                    ->sortable(),
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
        return view('livewire.list-tournament-teams');
    }
}
