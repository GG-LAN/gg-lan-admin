<?php
namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ShowPlayer extends Page implements HasForms
{
    use InteractsWithForms;

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

    public function update()
    {
        $this->record->update($this->form->getState());

        Notification::make()
            ->title(__("responses.player.updated"))
            ->success()
            ->send();

    }

    protected static string $resource = PlayerResource::class;

    protected static string $view = 'filament.resources.player-resource.pages.show-player';
}
