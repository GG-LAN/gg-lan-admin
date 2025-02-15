<?php
namespace App\Filament\Resources\TournamentResource\Widgets;

use App\Models\Tournament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

class TournamentFilling extends BaseWidget
{
    public Tournament $tournament;

    protected function getStats(): array
    {
        return [
            $this->tournamentFilling(),

            $this->tournament->type == "team" ? $this->teamsRegistered() : $this->playersRegistered(),

            $this->tournament->type == "team" ? $this->teamsNotFull() : null,

            Stat::make(__("Online Payments"), $this->onlinePayments())
                ->icon('fas-money-bill')
                ->chart($this->chartPayments())
                ->color("success")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-success-400 dark:to-success-900 fi-wi-stats-icon-success fi-wi-stats-dark-text-white",
                ]),
        ];
    }

    private function tournamentFilling(): Stat
    {
        $percentage = "";
        $trend;

        if ($this->tournament->type == "team") {
            $trend = Trend::query($this->tournament->teamsQuery("registered"))
                ->dateColumn("teams.registration_state_updated_at");

            $teamsRegistered = $this->tournament->teams()->where('registration_state', 'registered')->count();

            $percentage = (round(($teamsRegistered / $this->tournament->places) * 100)) . " %";
        } else {
            $trend = Trend::query($this->tournament->playersQuery())
                ->dateColumn("tournament_user.created_at");

            $playersRegistered = $this->tournament->players()->count();

            $percentage = (round(($playersRegistered / $this->tournament->places) * 100)) . " %";
        }

        $weeks = $trend
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

        return Stat::make(__("Tournmanent Filling"), $percentage)
            ->icon("fas-trophy")
            ->color("danger")
            ->chart($chart)
            ->extraAttributes([
                "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-danger-400 dark:to-danger-900 fi-wi-stats-icon-danger fi-wi-stats-dark-text-white",
            ]);
    }

    private function teamsRegistered(): Stat
    {
        $countRegistered = $this->tournament
            ->teams()
            ->where('registration_state', 'registered')
            ->count();

        return Stat::make(__("Registered Teams"), $countRegistered)
            ->icon("fas-users")
            ->extraAttributes([
                "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-primary-400 dark:to-primary-900 fi-wi-stats-icon-primary fi-wi-stats-dark-text-white",
            ]);
    }

    private function teamsNotFull(): Stat
    {
        $count = $this->tournament
            ->teams()
            ->where('registration_state', 'not_full')
            ->count();

        return Stat::make(__("Not complete Teams"), $count)
            ->icon("fas-users")
            ->extraAttributes([
                "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-warning-400 dark:to-warning-900 fi-wi-stats-icon-warning fi-wi-stats-dark-text-white",
            ]);
    }

    private function playersRegistered(): Stat
    {
        $count = $this->tournament->players()->count();

        return Stat::make(__("Registered Players"), $count)
            ->icon("fas-users")
            ->extraAttributes([
                "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-primary-400 dark:to-primary-900 fi-wi-stats-icon-primary fi-wi-stats-dark-text-white",
            ]);
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
}
