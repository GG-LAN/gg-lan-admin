<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function location()
    {
        $location = Setting::get("lan_location");

        return ApiResponse::success("", json_decode($location));
    }
}
