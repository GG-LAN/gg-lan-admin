<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Location as LocationModel;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Location extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-location-dot';

    protected static string $view = 'filament.clusters.settings.pages.location';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 2;

    public $latitude;
    public $longitude;
    public $address;
    public $location;

    public function getTitle(): string | Htmlable
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Location");
    }

    public function mount(): void
    {
        $location = LocationModel::firstOrCreate();

        $this->form->fill([
            "latitude"  => $location->latitude,
            "longitude" => $location->longitude,
            "address"   => $location->address,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Hidden::make('latitude'),
                        Hidden::make('longitude'),
                        Geocomplete::make('address')
                            ->translateLabel()
                            ->required()
                            ->columnSpanFull()
                            ->countries(['fr']),

                        Map::make("location")
                            ->translateLabel()
                            ->columnSpanFull()
                            ->defaultLocation(function (Get $get) {
                                if ($get("latitude") != "" && $get("longitude") != "") {
                                    return [$get("latitude"), $get("longitude")];
                                }

                                return [48.38972128484736, -4.483451695592351]; // Brest, France
                            })
                            ->autocomplete('address')
                            ->defaultZoom(12)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            }),
                    ]),
            ]);
    }

    public function update(): void
    {
        $data = $this->form->getState();

        $location = LocationModel::first();

        $location->latitude  = $data["latitude"];
        $location->longitude = $data["longitude"];
        $location->address   = $data["address"];

        $location->save();

        Notification::make()
            ->title(__("responses.setting.updated"))
            ->success()
            ->send();
    }
}
