<?php
namespace App\Filament\Widgets;

use App\Models\Tournament;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
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
                ->color("primary")
                ->chart($this->chartPlayers())
                ->extraAttributes([
                    "class"      => "cursor-pointer bg-gradient-to-tr from-transparent dark:from-gray-900 to-primary-400 dark:to-primary-900 fi-wi-stats-icon-primary fi-wi-stats-dark-text-white",
                    "wire:click" => "redirectToPlayers()",
                ]),

            Stat::make(__("Registered Teams"), $this->countRegisteredTeams())
                ->icon("fas-users")
                ->color("warning")
                ->chart($this->chartRegisteredTeams())
                ->extraAttributes([
                    "class"      => "cursor-pointer bg-gradient-to-tr from-transparent dark:from-gray-900 to-warning-400 dark:to-warning-900 fi-wi-stats-icon-warning fi-wi-stats-dark-text-white",
                    "wire:click" => "redirectToRegisteredTeams()",
                ]),

            Stat::make(__("Not complete Teams"), $this->countNotFullTeams())
                ->icon("fas-users")
                ->color("danger")
                ->chart($this->chartNotFullTeams())
                ->extraAttributes([
                    "class"      => "cursor-pointer bg-gradient-to-tr from-transparent dark:from-gray-900 to-danger-400 dark:to-danger-900 fi-wi-stats-icon-danger fi-wi-stats-dark-text-white",
                    "wire:click" => "redirectToNotFullTeams()",
                ]),

            Stat::make(__("Online Payments"), $this->countPayments())
                ->icon("fas-money-bill")
                ->chart($this->chartPayments())
                ->color("success")
                ->extraAttributes([
                    "class"      => "cursor-pointer bg-gradient-to-tr from-transparent dark:from-gray-900 to-success-400 dark:to-success-900 fi-wi-stats-icon-success fi-wi-stats-dark-text-white",
                    "wire:click" => "redirectToPayments()",
                ]),

        ];
    }

    private function countPlayers(): string
    {
        return User::all()->count();
    }

    private function chartPlayers(): array
    {
        $weeks = Trend::query(User::query())
            ->dateColumn("created_at")
            ->between(
                start: now()->startOfDay()->subMonths(2),
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

    private function countRegisteredTeams(): string
    {
        $count = 0;

        foreach ($this->tournaments as $tournament) {
            $count += $tournament->teams()->registered()->count();
        }

        return $count;
    }

    private function chartRegisteredTeams(): array
    {
        $chart = [];

        foreach ($this->tournaments as $tournament) {
            $weeks = Trend::query($tournament->teamsQuery("registered"))
                ->dateColumn("teams.registration_state_updated_at")
                ->between(
                    start: now()->startOfDay()->subMonths(2),
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
        }

        return $chart;
    }

    private function countNotFullTeams(): string
    {
        $count = 0;

        foreach ($this->tournaments as $tournament) {
            $count += $tournament->teams()->notFull()->count();
        }

        return $count;
    }

    private function chartNotFullTeams(): array
    {
        $chart = [];

        foreach ($this->tournaments as $tournament) {
            $weeks = Trend::query($tournament->teamsQuery("not_full"))
                ->dateColumn("teams.created_at")
                ->between(
                    start: now()->startOfDay()->subMonths(2),
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
        }

        return $chart;
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
        $chart = [];

        foreach ($this->tournaments as $tournament) {
            $weeks = Trend::query($tournament->paymentsQuery()->where("paid", true))
                ->dateColumn("purchased_places.updated_at")
                ->between(
                    start: now()->startOfDay()->subMonths(2),
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
        }

        return $chart;
    }

    public function redirectToPlayers()
    {
        $route = route("filament.admin.resources.players.index");

        return redirect($route);
    }

    public function redirectToRegisteredTeams()
    {
        $route = route("filament.admin.resources.teams.index") . "?tableFilters[registration_state][values][0]=registered";

        return redirect($route);
    }

    public function redirectToNotFullTeams()
    {
        $route = route("filament.admin.resources.teams.index") . "?tableFilters[registration_state][values][0]=not_full";

        return redirect($route);
    }

    public function redirectToPayments()
    {
        $route = route("filament.admin.resources.payments.index");

        return redirect($route);
    }
}
