<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\VolunteerFormLink;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get the current tournament location
     *
     * @unauthenticated
     */
    public function location(): JsonResponse
    {
        $location = Location::firstOrCreate();

        return ApiResponse::success("", [
            "address"   => $location->address,
            "longitude" => $location->longitude,
            "latitude"  => $location->latitude,
        ]);
    }

    /**
     * Get the volunteer form link
     *
     * @unauthenticated
     */
    public function volunteerFormLink(): JsonResponse
    {
        return ApiResponse::success("", [
            "link" => VolunteerFormLink::firstOrCreate()->link,
        ]);
    }
}
