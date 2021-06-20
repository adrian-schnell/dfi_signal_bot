<?php

namespace App\Api\v1_0\Service;

use App\Api\v1_0\Requests\BlockInfoRequest;
use App\Enum\Cooldown;
use App\Enum\ServerStatTypes;
use App\Http\Conversations\BlockSplitConversation;
use App\Http\Service\TelegramMessageService;
use App\Models\ServerStat;

class BlockInfoService
{
    public function store(BlockInfoRequest $request): void
    {
        $data = collect([
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::CONNECTIONCOUNT,
                'value'     => $request->connectioncount(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::BLOCK_DIFF,
                'value'     => $request->blockDiff(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::BLOCK_HEIGHT,
                'value'     => $request->blockHeightLocal(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::LOCAL_HASH,
                'value'     => $request->localHash(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::LOGSIZE,
                'value'     => $request->logsize(),
            ],
        ]);
        $data->each(function (array $item) {
            ServerStat::updateOrCreate([
                'server_id' => $item['server_id'],
                'type'      => $item['type'],
            ], $item);
        });
    }

    public function sendSplitNotification(BlockInfoRequest $request): void
    {
        $conversation = new BlockSplitConversation($request);

        app(TelegramMessageService::class)->startConversation($request->userServer()->user, $conversation);
        $request->userServer()->user->cooldown(Cooldown::SERVER_SPLIT_NOTIFICATION)->until(now()->addHours(2));
    }
}
