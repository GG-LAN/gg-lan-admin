<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\VolunteerFormLink as VolunteerFormLinkModel;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class VolunteerFormLink extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'fas-handshake-angle';

    protected static string $view = 'filament.clusters.settings.pages.volunteer-form-link';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 5;

    public array $data = [];

    public function getTitle(): string | Htmlable
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Volunteer Form Link");
    }

    public function mount(): void
    {
        $this->form->fill([
            "link" => VolunteerFormLinkModel::firstOrCreate()->link,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('link')
                            ->translateLabel()
                            ->columnSpanFull()
                            ->required()
                            ->url(),
                    ]),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        VolunteerFormLinkModel::first()
            ->update($this->form->getState());

        Notification::make()
            ->title(__("responses.setting.updated"))
            ->success()
            ->send();
    }
}
