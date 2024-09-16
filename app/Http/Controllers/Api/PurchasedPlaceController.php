<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\PurchasedPlace;
use App\Models\Tournament;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;

class PurchasedPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", PurchasedPlace::all());
    }

    /**
     * Register the purchase of a user
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function registerPurchase(User $user, Tournament $tournament): JsonResponse
    {
        if (Auth::user()->id != $user->id) {
            return ApiResponse::forbidden(__("responses.purchasedPlaces.cant_register"), []);
        }

        // Update purchased place
        $purchasedPlace = PurchasedPlace::register($user, $tournament, true);

        return ApiResponse::created(__("responses.purchasedPlaces.registered"), $purchasedPlace);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchasedPlace  $purchasedPlace
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasedPlace $purchasedPlace): JsonResponse
    {
        return ApiResponse::success("", $purchasedPlace);
    }
}
