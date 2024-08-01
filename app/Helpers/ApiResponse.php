<?php

namespace App\Helpers;

use App\ApiCode;    
use Illuminate\Http\JsonResponse;

class ApiResponse {
    public static function success($message = "null", $data = []): JsonResponse {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data
        ], ApiCode::SUCCESS);
    }

    public static function created($message = null, $data = null): JsonResponse {
        return response()->json([
            "status" => "created",
            "message" => $message,
            "data" => $data
        ], ApiCode::CREATED);
    }

    public static function badRequest($message = null, $data = null): JsonResponse {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], ApiCode::BAD_REQUEST);
    }

    public static function unauthorized($message = null, $data = null): JsonResponse {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], ApiCode::UNAUTHORIZED);
    }

    public static function forbidden($message = null, $data = null): JsonResponse {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], ApiCode::FORBIDDEN);
    }

    public static function unprocessable($message = null, $data = null): JsonResponse {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], ApiCode::UNPROCESSABLE);
    }
}