<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    /**
     * Get the faqs
     *
     * @unauthenticated
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", Faq::all());
    }
}
