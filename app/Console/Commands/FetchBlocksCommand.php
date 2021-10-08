<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\Masternode;
use App\Models\Repository\IndexMemoryRepository;
use App\Models\UserMasternode;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchBlocksCommand extends Command
{
    protected $signature = 'update:blocks';
    protected $description = 'Fetch all new blocks and check, if a signal should be sent to a user';

    public function handle(IndexMemoryRepository $memoryRepository, DefichainApiService $defichainApi): void
    {
        $latestBlockHeight = $memoryRepository->getLatestBlockIndex()->value;

        while (
        $this->runNewBlocks(
            $latestBlockHeight,
            $memoryRepository,
            $defichainApi
        )
        ) {
            $latestBlockHeight++;
        }
        $this->info('finished run');
    }

    protected function runNewBlocks(
        int $blockHeight,
        IndexMemoryRepository $memoryRepository,
        DefichainApiService $defichainApi
    ): bool {
        $blockData = $defichainApi->getBlockData($blockHeight);

        try {
            $masternode = Masternode::where('masternode_id', $blockData['masternode'] ?? 'n/a')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->info('synced data is up to date');

            return false;
        }

        // check if user has this masternode
        $userMn = $masternode->userMasternodes;
        if (count($userMn) === 0) {
            $this->info(sprintf('This MN has no active users atm for block height %s', $blockHeight));
            $this->finish($memoryRepository);

            return true;
        }


        // user masternodes found - create minted block data
        $userMn->each(function (UserMasternode $userMasternode) {

        });

        ray($masternode, $userMn);

        return true;
    }

    protected function finish(IndexMemoryRepository $memoryRepository): void
    {
        $memoryRepository->incrementBlockHeightIndex();
    }
}
