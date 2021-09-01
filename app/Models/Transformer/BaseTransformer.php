<?php

namespace App\Models\Transformer;

use Carbon\Carbon;

class BaseTransformer
{
    protected Carbon $lastUpdate;

    public function lastUpdate(): Carbon
    {
        return $this->lastUpdate;
    }

    public function lastUpdateHumanReadable(string $lang): string
    {
        return time_diff_humanreadable(now(), $this->lastUpdate(), $lang);
    }

    protected function getValuePairForKey(string $key): array
    {
        foreach ($this->rawData as $data) {
            if ($data['type'] === $key) {
                return $data;
            }
        }

        return [];
    }
}
