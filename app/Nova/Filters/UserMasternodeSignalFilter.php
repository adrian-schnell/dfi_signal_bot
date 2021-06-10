<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class UserMasternodeSignalFilter extends BooleanFilter
{
    public $name = 'MN Alarm';

    public function apply(Request $request, $query, $value): Builder
    {
        if ($value['on']) {
            return $query->where('alarm_on', true);
        }

        if ($value['off']) {
            return $query->where('alarm_on', false);
        }

        return $query;
    }

    public function options(Request $request): array
    {
        return [
            'An'  => 'on',
            'Aus' => 'off',
        ];
    }
}
