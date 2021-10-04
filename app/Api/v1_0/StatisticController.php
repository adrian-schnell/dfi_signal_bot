<?php

namespace App\Api\v1_0;

use App\Http\Resources\StatisticCollection;
use App\Models\Repository\StatisticRepository;

class StatisticController
{
    public function getAll(StatisticRepository $statisticRepository): StatisticCollection
    {
        return new StatisticCollection($statisticRepository::getAll());
    }

    public function getLastWeek(StatisticRepository $statisticRepository): StatisticCollection
    {
        return new StatisticCollection($statisticRepository::lastWeek());
    }
}
