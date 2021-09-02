<?php

namespace App\Models\Repository;

use App\Exceptions\StatisticException;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StatisticRepository
{
    public static function allActive(): Collection
    {
        return Statistic::whereDate('date', '<', today())->get();
    }

    /**
     * @throws StatisticException
     */
    public static function yesterday(): Statistic
    {
        return self::forDate(today()->subDay());
    }

    /**
     * @throws StatisticException
     */
    public static function today(): Statistic
    {
        return self::forDate(today());
    }

    /**
     * @throws StatisticException
     */
    public static function forDate(Carbon $date): Statistic
    {
        /** @var Statistic $statistic */
        $statistic = Statistic::whereDate('date', $date)->first();

        throw_if(is_null($statistic), StatisticException::notFound('yesterday'));

        return $statistic;
    }
}
