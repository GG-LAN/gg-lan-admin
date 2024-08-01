<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use App\Models\Tournament;
use App\Helpers\ApiResponse;
use App\Models\PurchasedPlace;
use Illuminate\Http\JsonResponse;

class PurchasedPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse {
        return ApiResponse::success("", PurchasedPlace::all());
    }

    /**
     * Register the purchase of a user
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function registerPurchase(User $user, Tournament $tournament): JsonResponse {
        if (Auth::user()->id != $user->id) {            
            return ApiResponse::forbidden(__("responses.purchasedPlaces.cant_register"), []);
        }

        // Create new purchased place
        if (!PurchasedPlace::checkExist(Auth::user(), $tournament)) {
            $purchasedPlace = PurchasedPlace::create([
                'user_id' => $user->id,
                'tournament_price_id' => $tournament->currentPrice()->id
            ]);
                        
            return ApiResponse::created(__("responses.purchasedPlaces.registered"), $purchasedPlace);
        }
        
        return ApiResponse::forbidden(__("responses.purchasedPlaces.already_registered"), []);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchasedPlace  $purchasedPlace
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasedPlace $purchasedPlace): JsonResponse {
        return ApiResponse::success("", $purchasedPlace);
    }
}
