<?php
namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use App\Models\Team;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ViewTeam extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TeamResource::class;

    protected static ?string $navigationIcon = 'fas-users';

    protected static string $view = 'filament.resources.team-resource.pages.view-team';

    public Team $record;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __("Team");
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

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string | Htmlable | null
    {
        $registrationStateLabel = __(Str::ucfirst($this->record->registration_state));

        switch ($this->record->registration_state) {
            case 'registered':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$registrationStateLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'not_full':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$registrationStateLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'pending':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-warning'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$registrationStateLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            default:
                return null;
                break;
        }
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make("delete")
    //             ->translateLabel()
    //             ->icon("fas-trash-can")
    //             ->color("danger")
    //             ->modalHeading(__("Delete Team"))
    //             ->action(function () {
    //                 $this->record->delete();

    //                 Notification::make()
    //                     ->title(__("responses.team.deleted"))
    //                     ->success()
    //                     ->send();

    //                 return redirect()->route("filament.admin.resources.teams.index");
    //             })
    //             ->requiresConfirmation(),
    //     ];
    // }

    public function mount(): void
    {
        $this->form->fill([
            "name"        => $this->record->name,
            "description" => $this->record->description,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("name")
                    ->translateLabel()
                    ->placeholder("MANGEMORT ;)")
                    ->required(),
                Textarea::make("description")
                    ->translateLabel()
                    ->placeholder(__("Short description of the team"))
                    ->autosize()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $this->record->update($this->form->getState());

        Notification::make()
            ->title(__("responses.team.updated"))
            ->success()
            ->send();
    }
}
