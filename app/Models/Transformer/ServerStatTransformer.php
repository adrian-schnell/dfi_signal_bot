<?php

namespace App\Models\Transformer;

use App\Enum\ServerStatTypes;
use Carbon\Carbon;

class ServerStatTransformer extends BaseTransformer
{
    protected array $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData['data'];
        $this->lastUpdate = Carbon::parse($rawData['latest_update']);
    }

    public function numCores(): int
    {
        $data = $this->getValuePairForKey(ServerStatTypes::NUM_CORES);

        return $data['value'] ?? 'n/a';
    }

    public function loadAvg(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::LOAD_AVG);

        return $data['value'] ?? 'n/a';
    }

    public function hddUsed(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::HDD_USED);

        return isset($data['value']) ? round($data['value'], 2) : 0;
    }

    public function hddTotal(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::HDD_TOTAL);

        return isset($data['value']) ? round($data['value'], 2) : 0;
    }

    public function ramUsed(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::RAM_USED);

        return isset($data['value']) ? round($data['value'], 2) : 0;
    }

    public function ramTotal(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::RAM_TOTAL);

        return isset($data['value']) ? round($data['value'], 2) : 0;
    }
}
