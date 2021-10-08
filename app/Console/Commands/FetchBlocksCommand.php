<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\Masternode;
use App\Models\Repository\IndexMemoryRepository;
use App\Models\Repository\MintedBlockRepository;
use App\Models\UserMasternode;
use App\SignalService\SignalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FetchBlocksCommand extends VerboseCommand
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
        $this->sendMessageIfVerbose('finished run');
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
            $this->sendMessageIfVerbose('synced data is up to date');

            return false;
        }

        // check if user has this masternode
        $userMn = $masternode->userMasternodes;
        if (count($userMn) === 0) {
            $this->sendMessageIfVerbose(sprintf('no masternode by users hit block height %s', $blockHeight));
            $this->finish($memoryRepository);

            return true;
        }

        $blockRepository = app(MintedBlockRepository::class);
        $signalService = app(SignalService::class);
        // user masternodes found - create minted block data
        $userMn->each(function (UserMasternode $userMasternode) use (
            $blockData,
            $defichainApi,
            $blockRepository,
            $signalService
        ) {
            $mintedBlock = $blockRepository->storeMintedBlockOceanData(
                $defichainApi,
                $userMasternode,
                $blockData
            );
            if ($mintedBlock->is_reported) {
                $this->warn(sprintf('user mn %s hit block %s - message skipped (was sent before)', $userMasternode->id,
                    $blockData['height']));
                return;
            }

            [$timeDiff, $blockDiff] = $blockRepository->calculateTimeBlockDiff($userMasternode);
            $signalService->tellMintedBlock(
                $userMasternode->user,
                $mintedBlock,
                $timeDiff,
                $blockDiff
            );
            $this->warn(sprintf('user mn %s hit block %s - message sent', $userMasternode->id, $blockData['height']));
        });

        return true;
    }

    protected function finish(IndexMemoryRepository $memoryRepository): void
    {
        $memoryRepository->incrementBlockHeightIndex();
    }
}
