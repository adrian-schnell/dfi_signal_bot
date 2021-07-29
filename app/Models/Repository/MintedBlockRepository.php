<?php

namespace App\Models\Repository;

use app\Http\Service\DefichainApiService;
use App\Models\MintedBlock;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use App\SignalService\SignalService;
use Carbon\Carbon;

class MintedBlockRepository
{
    public function storeMintedBlocks(
        DefichainApiService $service,
        UserMasternode $userMasternode,
        array $mintedBlocks,
        bool $initMode = false
    ): void {
        foreach ($mintedBlocks as $mintedBlock) {
            $txInfo         = $service->getTransactionDetails($mintedBlock['mintTxid']);
            $newMintedBlock = MintedBlock::updateOrCreate([
                'user_masternode_id' => $userMasternode->id,
                'mintBlockHeight'    => $mintedBlock['mintHeight'],
                'mint_txid'          => $mintedBlock['mintTxid'],
            ], [
                'user_masternode_id' => $userMasternode->id,
                'mintBlockHeight'    => $mintedBlock['mintHeight'],
                'spentBlockHeight'   => $mintedBlock['spentHeight'],
                'spent_txid'         => $mintedBlock['spentTxid'],
                'mint_txid'          => $mintedBlock['mintTxid'],
                'value'              => $mintedBlock['value'] / 100000000,
                'address'            => $mintedBlock['address'],
                'block_hash'         => $txInfo['blockHash'] ?? null,
                'block_time'         => array_key_exists('blockTime', $txInfo)
                    ? Carbon::parse($txInfo['blockTime'])->addHours(2)
                    : now(),
            ]);

            if (!$initMode && $userMasternode->alarm_on && !$newMintedBlock->is_reported) {
                // calculate diff between last 2 blocks
                $lastTwoBlocks = $userMasternode->mintedBlocks->sortByDesc('id')->take(2);
                if ($lastTwoBlocks->count() == 2) {
                    $lastBlockA = $lastTwoBlocks->first();
                    $lastBlockB = $lastTwoBlocks->last();
                    $timeDiff = $lastBlockA->block_time->diffInHours($lastBlockB->block_time);
                    $blockDiff = abs($lastBlockA->mintBlockHeight - $lastBlockB->mintBlockHeight);
                } else {
                    $timeDiff = '∞';
                    $blockDiff = '∞';
                }

                app(SignalService::class)->tellMintedBlock(
                    $userMasternode->user,
                    $newMintedBlock,
                    $timeDiff,
                    $blockDiff,
                    $userMasternode->user->language
                );
            } else {
                $newMintedBlock->update([
                    'is_reported' => true,
                ]);
            }
        }
    }

    public function calculateRewardsForUser(TelegramUser $user): float
    {
        $rewardsSum = 0;

        $user->masternodes->each(function (UserMasternode $masternode) use (&$rewardsSum) {
            $rewardsSum += $this->calculateRewardsForMasternode($masternode);
        });

        return $rewardsSum;
    }

    public function calculateRewardsForMasternode(UserMasternode $userMasternode): float
    {
        $rewardsSum = 0;
        $userMasternode->mintedBlocks->each(function (MintedBlock $mintedBlock) use (&$rewardsSum) {
            $rewardsSum += $mintedBlock->value;
        });

        return $rewardsSum;
    }
}
