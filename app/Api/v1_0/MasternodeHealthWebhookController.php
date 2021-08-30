<?php

namespace App\Api\v1_0;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasternodeHealthWebhookController
{
    public function receiveWebhook(Request $request): JsonResponse
    {
        // @todo implement the notifications on a received webhook
        return response()->json([], JsonResponse::HTTP_OK);
    }
}
