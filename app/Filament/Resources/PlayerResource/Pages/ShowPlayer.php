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

class ShowPlayer extends Page implements HasForms
{
    use InteractsWithForms;
    

    protected static string $resource = PlayerResource::class;

    protected static ?string $navigationIcon = 'fas-user';

    protected static string $view = 'filament.resources.player-resource.pages.show-player';

    public User $record;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __("Player");
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

    

}
