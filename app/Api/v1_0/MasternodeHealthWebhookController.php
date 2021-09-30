<?php

namespace App\Api\v1_0;

use App\Enum\QueueNames;
use App\Http\Requests\MnHealthWebhookRequest;
use App\Jobs\WebhookAnalyzerJob;
use Illuminate\Http\JsonResponse;

class MasternodeHealthWebhookController
{
    public function receiveWebhook(MnHealthWebhookRequest $webhookRequest): JsonResponse
    {
        $analyzerJob = new WebhookAnalyzerJob($webhookRequest->data(),
            $webhookRequest->analysis(),
            $webhookRequest->latest_update(),
            $webhookRequest->telegramUser());
        dispatch_sync($analyzerJob);
//        dispatch($analyzerJob)->onQueue(QueueNames::WEBHOOK_RECEIVED);

        return response()->json(['message' => 'ok'], JsonResponse::HTTP_OK);
    }
}
