<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Models\Participation;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ShowPlayer extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = PlayerResource::class;

    protected static string $view = 'filament.resources.player-resource.pages.show-player';

    public User $record;

    public ?array $data = [];

    public function getTitle(): string | Htmlable
    {
        return $this->record->pseudo;
    }

    public function mount(): void
    {
        $this->form->fill([
            "name"   => $this->record->name,
            "email"  => $this->record->email,
            "pseudo" => $this->record->pseudo,
            "admin"  => $this->record->admin,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->columnspan(1),
                        TextInput::make('email')
                            ->translateLabel()
                            ->required()
                            ->columnspan(1),
                        TextInput::make('pseudo')
                            ->translateLabel()
                            ->required()
                            ->columnspan(2),
                        Toggle::make('admin')
                            ->translateLabel()
                            ->required()
                            ->columnspan(1),

                    ]),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $this->record->update($this->form->getState());

        Notification::make()
            ->title(__("responses.player.updated"))
            ->success()
            ->send();

    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->record->participationsQuery())
            ->selectable()
            ->columns([
                TextColumn::make("team")
                    ->translateLabel()
                    ->state(function (Participation $participation) {
                        $team = $participation->team;
                        return $team != null ? $team->name : "Solo";
                    })
                    ->icon(function (string $state, Participation $participation) {
                        if ($state == "Solo") {
                            return;
                        }

                        if ($participation->team->captain->id == $this->record->id) {
                            return "fas-star";
                        }

                        return "fas-user";
                    })
                    ->iconColor(function (string $state, Participation $participation) {
                        if ($state == "Solo") {
                            return;
                        }

                        if ($participation->team->captain->id == $this->record->id) {
                            return "success";
                        }

                        return "danger";
                    })
                    ->iconPosition(IconPosition::After)
                    ->url(function (Participation $record): ?string {
                        if (! $record->team_id) {
                            return null;
                        }

                        return route(
                            'filament.admin.resources.teams.view',
                            ['record' => $record->team_id]
                        );
                    }),
                TextColumn::make("tournament.name")
                    ->translateLabel()
                    ->sortable()
                    ->url(function (Participation $record): string {
                        return route(
                            'filament.admin.resources.tournaments.view',
                            ['record' => $record->tournament_id]
                        );
                    }),
                TextColumn::make("status")
                    ->translateLabel()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'registered'                      => 'success',
                        'pending'                         => 'warning',
                        'not_full'                        => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => __(Str::ucfirst($state)))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make("status")
                    ->translateLabel()
                    ->options([
                        'registered' => __("Registered"),
                        'pending'    => __("Pending"),
                        'not_full'   => __("Not_full"),
                    ]),
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->emptyStateHeading(__("No existing participations"));
    }

}
