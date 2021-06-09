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
        array $mintedBlocks
    ): void {
        $mintedUserBlocks = $userMasternode->mintedBlocks;
        $initMode = $mintedUserBlocks->count() === 0;

        foreach ($mintedBlocks as $mintedBlock) {
            $filtered = $mintedUserBlocks->where('mintBlockHeight', $mintedBlock['mintHeight'])->all();
            if ($filtered) {
                continue;
            }
            $txInfo = $service->getTransactionDetails($mintedBlock['mintTxid']);
            $newMintedBlock = MintedBlock::updateOrCreate([
                'mintBlockHeight'  => $mintedBlock['mintHeight'],
                'spentBlockHeight' => $mintedBlock['spentHeight'],
                'spent_txid'       => $mintedBlock['spentTxid'],
            ], [
                'user_masternode_id' => $userMasternode->id,
                'mintBlockHeight'    => $mintedBlock['mintHeight'],
                'spentBlockHeight'   => $mintedBlock['spentHeight'],
                'spent_txid'         => $mintedBlock['spentTxid'],
                'mint_txid'          => $mintedBlock['mintTxid'],
                'value'              => $mintedBlock['value'] / 100000000,
                'address'            => $mintedBlock['address'],
                'block_hash'         => $txInfo['blockHash'] ?? null,
                'block_time'         => Carbon::parse($txInfo['blockTime'])->addHours(2),
            ]);

            if (!$initMode && $userMasternode->alarm_on) {
                app(SignalService::class)->tellMintedBlock(
                    $userMasternode->user->telegramId,
                    $newMintedBlock,
                    $userMasternode->user->language
                );
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
