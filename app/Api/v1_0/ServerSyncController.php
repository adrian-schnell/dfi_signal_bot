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

    public function blockInfo(BlockInfoRequest $request, BlockInfoService $service): JsonResponse
    {
        $service->store($request);

        if ($request->splitFound() && $request->userServer()->user->cooldown(Cooldown::SERVER_SPLIT_NOTIFICATION)
                ->passed()) {
            $service->sendSplitNotification($request);
        }

        return response()->json([
            'message' => 'ok',
        ]);
    }

    public function serverStats(ServerStatsRequest $request, ServerStatService $service): JsonResponse
    {
        $service->store($request);

        return response()->json([
            'message' => 'ok',
        ]);
    }
}
