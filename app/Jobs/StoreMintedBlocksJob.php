<?php

namespace App\Jobs;

use App\Http\Service\DefichainApiService;
use App\Models\Repository\MintedBlockRepository;
use App\Models\UserMasternode;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreMintedBlocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected UserMasternode $masternode;
    protected array $mintedBlocks = [];

    public function __construct(UserMasternode $masternode)
    {
        $this->masternode = $masternode;
    }

    public function handle(DefichainApiService $apiService): void
    {
        $this->mintedBlocks = $apiService->mintedBlocksForOwnerAddress($this->masternode->masternode->owner_address);

        app(MintedBlockRepository::class)
            ->storeMintedBlocks(
                $apiService,
                $this->masternode,
                $this->mintedBlocks
            );
    }
}
