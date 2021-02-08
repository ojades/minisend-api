<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendSuccess($data): JsonResponse
    {
        return response()->json([
            'status' => Constants::SUCCESS,
            'data' => $data
        ], 200);
    }

    protected function sendError($message, $error_code, $status_code = 500): JsonResponse
    {
        return response()->json([
            'status' => Constants::ERROR,
            'message' => $message,
            'code' => $error_code,
        ], $status_code);
    }

    protected function validate(Request $request)
    {
        $response = $request->validate([
            'sender_name' => 'required|string',
            'sender_email' => 'required|email',
            'subject' => 'required|string',
            'template' => 'required|string|exists:App\Models\Templates,slug'
        ]);
    }
}
