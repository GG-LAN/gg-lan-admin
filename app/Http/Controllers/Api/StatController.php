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

    public function teams() {
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

        $totalPlaces = 0;
        $totalRegistration = 0;
        
        $percentage = "0%";

        if (!$tournaments->count()) {
            return ApiResponse::success("", $percentage);
        }
        
        foreach ($tournaments as $tournament) {
            if ($tournament->type == "team") {
                $totalRegistration += $tournament->teams()->where('registration_state', 'registered')->count();
            }
            else {
                $totalRegistration += $tournament->players()->count();
            }

            $totalPlaces += $tournament->places;
        }

        $percentage = Number::percentage($totalRegistration / $totalPlaces * 100);

        return ApiResponse::success("", $percentage);
    }
}
