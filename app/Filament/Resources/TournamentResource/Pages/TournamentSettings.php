<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ComponentAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class TournamentSettings extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected static ?string $navigationIcon = 'fas-cogs';

    public static function getNavigationLabel(): string
    {
        return __("Settings");
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return static::getResource()::getSubheading($this->record);
    }

    public function getBreadcrumb(): string
    {
        return $this->record->name;
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            ...(filled($breadcrumb = $this->getBreadcrumb()) ? [$breadcrumb] : []),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        $breadcrumbs[] = __("Settings");

        return $breadcrumbs;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['discord_notif'] = $this->record->discord_notif;

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->discordNotificationsSection(),
                $this->parentalPermissionSection(),
            ]);

    }

    private function discordNotificationsSection(): Section
    {
        return Section::make(__("Discord Notifications"))
            ->icon("fab-discord")
            ->description(__("If we want that a registration to the tournament send a discord notification"))
            ->schema([
                Toggle::make("discord_notif")
                    ->translateLabel()
                    ->onIcon("fas-check")
                    ->offIcon("fas-xmark")
                    ->live()
                    ->afterStateUpdated(function (bool $state) {
                        $this->record->update([
                            "discord_notif" => $state,
                        ]);

                        Notification::make()
                            ->success()
                            ->title(__("Saved"))
                            ->body(__("responses.tournament.updated"))
                            ->send();
                    }),
            ])
            ->collapsible()
            ->persistCollapsed();
    }

    private function parentalPermissionSection(): Section
    {
        return Section::make(__("Parental permission"))
            ->description(__("Download the parental permission for this tournament."))
            ->icon("fas-hand")
            ->schema([
                Actions::make([
                    ComponentAction::make(__("Download"))
                        ->action(fn() => redirect()->route("download.parental-permission", ["tournament" => $this->record->id]))
                        ->icon("fas-download"),
                ]),
            ])
            ->collapsible()
            ->persistCollapsed();
    }

}
