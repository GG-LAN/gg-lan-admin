<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Tournament;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

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
}
