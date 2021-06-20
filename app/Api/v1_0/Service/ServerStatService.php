<?php

use App\Api\v1_0\Requests\ServerStatsRequest;
use App\Enum\ServerStatTypes;
use App\Models\ServerStat;

class ServerStatService
{
    public function store(ServerStatsRequest $request): void
    {
        $data = [
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::CPU,
                'value'     => $request->cpu(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::HDD_TOTAL,
                'value'     => $request->hddTotal(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::HDD_USED,
                'value'     => $request->hddUsed(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::RAM_TOTAL,
                'value'     => $request->ramTotal(),
            ],
            [
                'server_id' => $request->userServer()->id,
                'type'      => ServerStatTypes::RAM_USED,
                'value'     => $request->ramUsed(),
            ],
        ];
        ServerStat::upsert($data, [
            'server_id',
            'type',
        ]);
    }
}
