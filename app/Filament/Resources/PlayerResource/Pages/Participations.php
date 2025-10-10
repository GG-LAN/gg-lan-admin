<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Models\Participation;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class Participations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = PlayerResource::class;

    protected static ?string $navigationIcon = 'fas-trophy';

    protected static string $view = 'filament.resources.player-resource.pages.participations';

    public User $record;

    public function getTitle(): string | Htmlable
    {
        return $this->record->pseudo;
    }
    public static function getNavigationLabel(): string
    {
        return __("Participations");
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->record,
        ];
    }

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
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
                        'registered' => 'success',
                        'pending'    => 'warning',
                        'not_full'   => 'danger',
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
