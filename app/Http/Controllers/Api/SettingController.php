<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Location;

class SettingController extends Controller
{
    public function location()
    {
        $location = Location::firstOrCreate();

        return ApiResponse::success("", [
            "address"   => $location->address,
            "longitude" => $location->longitude,
            "latitude"  => $location->latitude,
        ]);
    }
}
