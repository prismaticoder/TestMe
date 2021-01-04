<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendSuccessResponse(string $message, $data = [], int $status = 200): JsonResponse
    {
        return response()->json(array(
            'success' => true,
            'message' => $message,
            'data' => $data
        ), $status);
    }

    protected function sendErrorResponse(string $message, int $status = 500): JsonResponse
    {
        return response()->json(array(
            'success' => false,
            'message' => $message,
        ), $status);
    }
}
