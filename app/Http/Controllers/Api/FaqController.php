<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use Inertia\Inertia;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Faqs\StoreFaqRequest;
use App\Http\Requests\Faqs\UpdateFaqRequest;

class FaqController extends Controller
{
    public function index() {
        return ApiResponse::success("", Faq::all());
    }
}
