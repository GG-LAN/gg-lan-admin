<?php
namespace App\Filament\Widgets;

use App\Models\Tournament;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class TournamentFillingChart extends ChartWidget
{
    protected static ?string $maxHeight = '350px';

    public Tournament $tournament;

    public function getHeading(): string | Htmlable | null
    {
        return $this->tournament->name;
    }

    protected function getOptions(): array
    {
        return [
            "legend" => [
                "display" => false,
            ],
            "scales" => [
                "y" => [
                    "display" => false,
                ],
                "x" => [
                    "display" => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $registeredLabel = $this->tournament->type == "team" ? __("Registered Teams") : __("Registered Players");

        $countRegistered = $this->tournament->register_count;
        $slotsAvailable  = $this->tournament->places - $countRegistered;

        return [
            "labels"   => [__("Slots Available"), $registeredLabel],
            "datasets" => [
                [
                    "label"           => "dsfdsf",
                    "data"            => [$slotsAvailable, $countRegistered],
                    "backgroundColor" => [
                        "#f05252",
                        "#3f83f8",
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
