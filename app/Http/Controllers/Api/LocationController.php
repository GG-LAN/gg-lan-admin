<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\Location;

class LocationController extends Controller
{
    public function location()
    {
        return ApiResponse::success("", json_decode(Location::firstOrCreate()));
    }
}
