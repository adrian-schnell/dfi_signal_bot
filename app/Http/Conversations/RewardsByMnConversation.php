<?php

namespace App\Http\Conversations;

use App\Models\DEXPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Collection;

class RewardsByMnConversation extends Conversation
{
    private ?Collection $masternodes;

    public function __construct(Collection $masternodes)
    {
        $this->masternodes = $masternodes;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->bot->typesAndWaits(1);
        if ($this->masternodes->count() === 0) {
            $this->say(__('listMasternodeConversation.no_masternodes_available'), array_merge([
                'parse_mode' => 'Markdown',
            ]));

            return;
        }

        $this->masternodes->each(function (UserMasternode $masternode) {
            $this->say(sprintf('⏬⏬⏬⏬ `%s` ⏬⏬⏬⏬', $masternode->name), [
                'parse_mode' => 'Markdown',
            ]);

            $this->say($this->generateRewardMessage($masternode), ['parse_mode' => 'Markdown']);

            $this->say(sprintf('⏫⏫⏫⏫ `%s` ⏫⏫⏫⏫', $masternode->name), [
                'parse_mode' => 'Markdown',
            ]);
        });
    }

    protected function generateRewardMessage(UserMasternode $masternode): string
    {
        $dfiRewardSum = app(MintedBlockRepository::class)->calculateRewardsForMasternode($masternode);
        $mintedBlockCount = $masternode->mintedBlocks->count();
        $mnCreatedAt = app(MasternodeService::class)->getCreationDateOfMasternode($masternode);
        $ageInDays = now()->diffInDays($mnCreatedAt);

        $message = __('rewardsConversation.by_node.sum_minted_blocks', [
            'amount' => $mintedBlockCount,
        ]);
        $message .= __('rewardsConversation.by_node.mn_age', [
            'date' => $mnCreatedAt->format('d.m.Y H:i'),
            'days' => $ageInDays,
        ]);
        $message .= __('rewardsConversation.by_node.day_per_block', [
            'value' => $mintedBlockCount > 0 ? round($ageInDays / $mintedBlockCount, 2) : '∞',
        ]);
        $message .= __('rewardsConversation.by_node.block_per_day', [
            'value' => $ageInDays > 0 ? round($mintedBlockCount / $ageInDays, 2) : '∞',
        ]);

        $message .= "\r\n\r\n" . __('rewardsConversation.rewards.dfi',
                ['dfi' => $dfiRewardSum]);
        $prices = DEXPrice::whereName('BTC')
            ->orWhere('name', 'USDC')
            ->orderBy('order')
            ->get();

        foreach ($prices as $price) {
            $message .= __('rewardsConversation.rewards.other_coins',
                [
                    'value'  => $dfiRewardSum * $price->price,
                    'ticker' => $price->symbol,
                ]);
        }

        $message .= __('rewardsConversation.rewards.legal',
            [
                'date' => $prices->first()->updated_at->format('H:i:s - d.m.Y'),
            ]);

        return $message;
    }
}
