<?php
namespace App\Filament\Resources\TournamentResource\Widgets;

use App\Models\Tournament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TournamentFilling extends BaseWidget
{
    public Tournament $tournament;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Tournmanent Filling"), $this->tournamentFilling())
                ->icon("fas-trophy")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-danger-400 dark:to-danger-900 fi-wi-stats-icon-danger fi-wi-stats-dark-text-white",
                ]),

            $this->tournament->type == "team" ? $this->teamsRegistered() : $this->playersRegistered(),

            $this->tournament->type == "team" ? $this->teamsNotFull() : null,

            Stat::make(__("Online Payments"), $this->onlinePayments())
                ->icon('fas-money-bill')
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-success-400 dark:to-success-900 fi-wi-stats-icon-success fi-wi-stats-dark-text-white",
                ]),
        ];
    }

    private function tournamentFilling(): string
    {
        if ($this->tournament->type == "team") {
            $teamsRegistered = $this->tournament->teams()->where('registration_state', 'registered')->count();
            return (round(($teamsRegistered / $this->tournament->places) * 100)) . " %";
        }

        $playersRegistered = $this->tournament->players()->count();

        return (round(($playersRegistered / $this->tournament->places) * 100)) . " %";
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
}
