<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Tournament;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\PurchasedPlace;
use Illuminate\Support\Number;

class StatController extends Controller {
    public function players() {
        return ApiResponse::success("", User::all()->count());
    }

    public function teamsStat() {
        $tournaments = Tournament::getOpenTournaments();
        $teamsCount = 0;

        foreach ($tournaments as $tournament) {
            $teamsCount += $tournament->teams()->count();
        }
        
        return ApiResponse::success("", $teamsCount);
    }

    public function payments() {
        return ApiResponse::success("", PurchasedPlace::all()->count());
    }

    public function tournamentsFilling() {
        $tournaments = Tournament::getOpenTournaments();

        $totalRegistration = 0;
                
        $results = [
            "series" => [],
            "labels" => []
        ];
        
        foreach ($tournaments as $tournament) {
            $totalRegistration = 0;
            
            array_push($results["labels"], $tournament->name);
            
            if ($tournament->type == "team") {
                $totalRegistration += $tournament->teams()->where('registration_state', 'registered')->count();
            }
            else {
                $totalRegistration += $tournament->players()->count();
            }

            array_push($results["series"], Number::format($totalRegistration / $tournament->places * 100, 0));
        }

        return ApiResponse::success("", $results);
    }
}
