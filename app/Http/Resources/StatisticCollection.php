<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Statistic */
class StatisticCollection extends ResourceCollection
{
    /**
     * Transform the resource into a JSON array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
