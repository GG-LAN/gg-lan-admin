<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\Rule;
use Illuminate\Http\JsonResponse;

class RuleController extends Controller
{
    /**
     * Get the rules
     *
     * @unauthenticated
     */
    public function show(): JsonResponse
    {
        return ApiResponse::success("", Rule::firstOrCreate());
    }
}
