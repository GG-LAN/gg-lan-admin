<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\PurchasedPlace;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Number;

class StatController extends Controller
{

    /**
     * Get count of all players
     *
     * @unauthenticated
     */
    public function players(): JsonResponse
    {
        return ApiResponse::success("", User::all()->count());
    }

    /**
     * Get count of all teams in open tournaments
     *
     * @unauthenticated
     */
    public function teamsStat(): JsonResponse
    {
        $tournaments = Tournament::getOpenTournaments();
        $teamsCount  = 0;

        foreach ($tournaments as $tournament) {
            $teamsCount += $tournament->teams()->count();
        }

        return ApiResponse::success("", $teamsCount);
    }

    /**
     * Get count of all purchased places in open tournaments
     *
     * @unauthenticated
     */
    public function payments(): JsonResponse
    {
        return ApiResponse::success("", PurchasedPlace::forOpenTournaments()->where('paid', true)->count());
    }

    /**
     * Get tournaments fillings
     *
     * @unauthenticated
     */
    public function tournamentsFilling(): JsonResponse
    {
        $tournaments = Tournament::getOpenTournaments();

        $totalRegistration = 0;

        $results = [
            "series" => [],
            "labels" => [],
        ];

        foreach ($tournaments as $tournament) {
            $totalRegistration = 0;

            array_push($results["labels"], $tournament->name);

            if ($tournament->type == "team") {
                $totalRegistration += $tournament->teams()->where('registration_state', 'registered')->count();
            } else {
                $totalRegistration += $tournament->players()->count();
            }

            array_push($results["series"], Number::format($totalRegistration / $tournament->places * 100, 0));
        }

        return ApiResponse::success("", $results);
    }
}
