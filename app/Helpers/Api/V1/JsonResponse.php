<?php

namespace App\Helpers\Api\V1;

class JsonResponse
{
    public static function successResponse(array $data,string $message, int $statusCode)
    {
     return response()->json([
         'message' => $message,
         'data' => $data,
         'success' => true
     ],$statusCode);
    }

    public static function failedResponse(array $errors,string $message, int $statusCode)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'success' => false
        ],$statusCode);
    }
}
