<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\VolunteerFormLink;

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

    public function volunteerFormLink()
    {
        return ApiResponse::success("", [
            "link" => VolunteerFormLink::firstOrCreate()->link,
        ]);
    }
}
