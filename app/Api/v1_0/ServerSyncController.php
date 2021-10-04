<?php

namespace App\Api\v1_0;

use App\Api\v1_0\Requests\BlockInfoRequest;
use App\Api\v1_0\Requests\ServerStatsRequest;
use App\Api\v1_0\Service\BlockInfoService;
use App\Api\v1_0\Service\ServerStatService;
use App\Enum\Cooldown;
use \Illuminate\Http\JsonResponse;

class ServerSyncController
{
    public function ping(): JsonResponse
    {
        return response()->json(['message' => 'pong'], JsonResponse::HTTP_OK);
    }
}
