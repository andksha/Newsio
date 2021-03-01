<?php

namespace App\Http\API;

use Illuminate\Http\JsonResponse;

final class APIResponse
{
    public static function ok(array $payload, int $status, array $headers = []): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'payload' => $payload
        ], $status, $headers);
    }

    public static function error(string $errorMessage, ?array $errorData, int $status, array $headers = []): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'error_message' => $errorMessage,
            'error_data' => $errorData
        ], $status, $headers);
    }
}