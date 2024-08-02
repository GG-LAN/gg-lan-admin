<?php

namespace App\Http\Controllers\Api;

use App\Models\Rule;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class RuleController extends Controller {
    public function show() {
        return ApiResponse::success("", Rule::firstOrCreate());
    }
}
