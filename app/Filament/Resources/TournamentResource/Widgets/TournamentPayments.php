<?php
namespace App\Filament\Resources\TournamentResource\Widgets;

use App\Models\Tournament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

class TournamentPayments extends BaseWidget
{
    public Tournament $tournament;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Online Payments"), $this->onlinePayments())
                ->icon('fas-money-bill')
                ->chart($this->chartPayments())
                ->color("success")
                ->extraAttributes([
                    "class"      => "cursor-pointer bg-gradient-to-tr from-transparent dark:from-gray-900 to-success-400 dark:to-success-900 fi-wi-stats-icon-success fi-wi-stats-dark-text-white",
                    "wire:click" => "redirectToPayments()",
                ]),
        ];
    }

    private function onlinePayments(): string
    {
        return $this->tournament->paymentsQuery()->where("paid", true)->get()->count();
    }

    private function chartPayments(): array
    {
        $chart = [];

        $weeks = Trend::query($this->tournament->paymentsQuery())
            ->dateColumn("purchased_places.created_at")
            ->between(
                start: $this->tournament->created_at,
                end: now()
            )
            ->perWeek()
            ->count();

        foreach ($weeks as $key => $week) {
            if (! isset($chart[$key])) {
                $chart[$key] = 0;
            }

            $chart[$key] += $week->aggregate;
        }

        return $chart;
    }

    public function redirectToPayments()
    {
        $route = route("filament.admin.resources.payments.index") . "?tableFilters[paid][value]=1&tableFilters[tournament][values][0]={$this->tournament->id}";

        return redirect($route);
    }
}
