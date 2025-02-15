<?php
namespace App\Filament\Widgets;

use App\Models\Tournament;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Collection;

class DashboardOverview extends BaseWidget
{
    private Collection $tournaments;

    public function __construct()
    {
        $this->tournaments = Tournament::getOpenTournaments();
    }
    protected function getStats(): array
    {
        return [
            Stat::make(__("Players"), $this->countPlayers())
                ->icon("fas-user")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-primary-400 dark:to-primary-900 fi-wi-stats-icon-primary fi-wi-stats-dark-text-white",
                ]),

            Stat::make(__("Registered Teams"), $this->countRegisteredTeams())
                ->icon("fas-users")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-warning-400 dark:to-warning-900 fi-wi-stats-icon-warning fi-wi-stats-dark-text-white",
                ]),

            Stat::make(__("Not complete Teams"), $this->countNotFullTeams())
                ->icon("fas-users")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-danger-400 dark:to-danger-900 fi-wi-stats-icon-danger fi-wi-stats-dark-text-white",
                ]),

            Stat::make(__("Online Payments"), $this->countPayments())
                ->icon("fas-money-bill")
                ->chart($this->chartPayments())
                ->color("success")
                ->extraAttributes([
                    "class" => "bg-gradient-to-tr from-transparent dark:from-gray-900 to-success-400 dark:to-success-900 fi-wi-stats-icon-success fi-wi-stats-dark-text-white",
                ]),

        ];
    }

    private function countPlayers(): string
    {
        return User::all()->count();
    }

    private function countRegisteredTeams(): string
    {
        $count = 0;

        foreach ($this->tournaments as $tournament) {
            $count += $tournament->teams()->registered()->count();
        }

        return $count;
    }

    private function countNotFullTeams(): string
    {
        $count = 0;

        foreach ($this->tournaments as $tournament) {
            $count += $tournament->teams()->notFull()->count();
        }

        return $count;
    }

    private function countPayments(): string
    {
        $count = 0;

        foreach ($this->tournaments as $tournament) {
            $count += $tournament->purchasedPlaces()->where("paid", true)->count();
        }

        return $count;
    }

    private function chartPayments(): array
    {
        $chart = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
        ];

        foreach ($this->tournaments as $tournament) {
            $startDate = now()->startOfDay()->subWeek();
            $endDate   = now();

            for ($i = 0; $i < 4; $i++) {
                $purchasedPlaces = $tournament->purchasedPlaces()->where("paid", true);

                if ($i != 0) {
                    $endDate   = $startDate;
                    $startDate = $startDate->copy()->subWeek();
                }

                $chart[$i] += $purchasedPlaces->whereBetween("purchased_places.updated_at", [$startDate, $endDate])->count();
            }
        }

        return $chart;
    }
}
