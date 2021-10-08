<?php

namespace App\Models\Repository;

use app\Http\Service\DefichainApiService;
use App\Models\IndexMemory;
use App\Models\MintedBlock;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use App\SignalService\SignalService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class IndexMemoryRepository
{
    const LATEST_BLOCK_HEIGHT = 'latest_block_height';

    public function getLatestBlockIndex(): IndexMemory
    {
        try {
            $index = IndexMemory::where('type', self::LATEST_BLOCK_HEIGHT)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $index = IndexMemory::create([
                'type'  => self::LATEST_BLOCK_HEIGHT,
                'value' => MintedBlock::orderByDesc('mintBlockHeight')->first()->mintBlockHeight ?? 0,
            ]);
        }

        return $index;
    }

    public function incrementBlockHeightIndex(): void
    {
        $this->getLatestBlockIndex()->increment('value');
    }
}
