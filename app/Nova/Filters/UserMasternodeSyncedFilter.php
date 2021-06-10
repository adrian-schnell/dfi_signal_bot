<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class UserMasternodeSyncedFilter extends BooleanFilter
{
    public function apply(Request $request, $query, $value): Builder
    {
        if ($value['synced']) {
            return $query->where('synced_masternode_monitor', true);
        }

        if ($value['manual']) {
            return $query->where('synced_masternode_monitor', false);
        }

        return $query;
    }

    public function options(Request $request): array
    {
        return [
            'Synced'  => 'synced',
            'Manuell' => 'manual',
        ];
    }
}
