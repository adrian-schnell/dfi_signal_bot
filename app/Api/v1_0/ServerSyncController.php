<?php

namespace App\Api\v1_0;

use \Illuminate\Http\JsonResponse;

class ServerSyncController
{
    public function ping(): JsonResponse
    {
        return response()->json(['message' => 'pong'], 200);
    }

    public function cpu(): JsonResponse
    {
        return response()->json([], 200);
    }
}
