<?php
namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\PlayerResource\Pages\ShowPlayer;
use App\Filament\Resources\TeamResource;
use App\Models\Team;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = TeamResource::class;

    protected static ?string $navigationIcon = 'fas-user';

    protected static string $view = 'filament.resources.team-resource.pages.team-members';

    public Team $record;

    public static function getNavigationLabel(): string
    {
        return __("Team members");
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
            ->query($this->record->playersQuery())
            ->paginated(false)
            ->selectable()
            ->columns([
                TextColumn::make("user.pseudo")
                    ->translateLabel(),
                TextColumn::make("created_at")
                    ->label(__("Member since"))
                    ->since()
                    ->sortable(),
                TextColumn::make("captain")
                    ->label(__("Hierarchy"))
                    ->icon(fn(bool $state): string => $state ? "fas-star" : "fas-user")
                    ->iconColor(fn(bool $state): string => $state ? "success" : "danger")
                    ->formatStateUsing(fn(bool $state): string => $state ? __("Captain") : __("Player"))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionTable::make("promote_player")
                    ->icon("fas-star")
                    ->iconButton()
                    ->color("success")
                    ->requiresConfirmation()
                    ->tooltip(__("Promote player to captain of the team"))
                    ->hidden(fn(Model $record): bool => $record->captain)
                    ->modalHeading(__("Promote player"))
                    ->modalIcon("fas-star")
                    ->action(function (Model $teamUser) {
                        $teamUser->team->users()->where("captain", true)->update([
                            "captain" => false,
                        ]);

                        $teamUser->update([
                            "captain" => true,
                        ]);

                        Notification::make()
                            ->title(__("responses.team.promoted"))
                            ->success()
                            ->send();

                    }),

                ActionTable::make("remove_player")
                    ->icon("fas-user-minus")
                    ->iconButton()
                    ->color("danger")
                    ->requiresConfirmation()
                    ->tooltip(__("Remove player from the team"))
                    ->hidden(fn(Model $record): bool => $record->captain)
                    ->modalHeading(__("Remove player from the team"))
                    ->modalIcon("fas-user-minus")
                    ->action(function (Model $teamUser) {
                        $teamUser->team->users()->detach($teamUser->user);

                        Notification::make()
                            ->title(__("responses.team.player_removed"))
                            ->success()
                            ->send();

                    }),
            ])
            ->bulkActions([
                // ...
            ])
            ->recordUrl(
                fn(Model $teamUser): string => ShowPlayer::getUrl([$teamUser->user->id]),
            );
    }
}
