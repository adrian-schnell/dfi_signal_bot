<?php

namespace App\Api\v1_0;

use \Illuminate\Http\JsonResponse;

class ServerSyncController
{
    public function cpu(): JsonResponse
    {
        return response()->json([], 200);
    }
}
