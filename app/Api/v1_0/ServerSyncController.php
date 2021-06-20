<?php

namespace App\Api\v1_0;

use App\Api\v1_0\Requests\ServerStatsRequest;
use \Illuminate\Http\JsonResponse;
use ServerStatService;

class ServerSyncController
{
    public function ping(): JsonResponse
    {
        return response()->json(['message' => 'pong'], JsonResponse::HTTP_OK);
    }

    public function blockInfo()
    {

    }

    public function serverStats(ServerStatsRequest $request, ServerStatService $service): JsonResponse
    {
        $service->store($request);

        return response()->json([
            'message' => 'ok',
        ]);
    }
}
