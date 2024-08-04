<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class StatController extends Controller {
    public function players() {
        return ApiResponse::success("", User::all()->count());
    }
}
