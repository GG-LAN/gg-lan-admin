<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Rule;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Rules extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-align-left';

    protected static string $view = 'filament.clusters.settings.pages.rules';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 1;

    public array $data = [];

    public function getTitle(): string | Htmlable
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Rules");
    }

    public function mount(): void
    {
        $this->form->fill([
            "content" => Rule::firstOrCreate()->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->id("richeditor")
                    ->columns(1)
                    ->schema([
                        RichEditor::make("content")
                            ->label(__("Rules"))
                            ->extraInputAttributes(["style" => "max-height: 30rem;"])
                            ->disableGrammarly(),
                    ]),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        Rule::first()->update($this->form->getState());

        Notification::make()
            ->title(__("responses.rules.updated"))
            ->success()
            ->send();
    }
}
