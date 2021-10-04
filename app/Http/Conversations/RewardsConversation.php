<?php

namespace App\Http\Conversations;

use App\Enum\MNStates;
use App\Models\DEXPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Collection;

class RewardsConversation extends Conversation
{
    protected bool $onlyEnabled;
    private ?Collection $masternodes;

    public function __construct(Collection $masternodes, bool $onlyEnabled = false)
    {
        $this->masternodes = $masternodes;
        $this->onlyEnabled = $onlyEnabled;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if ($this->masternodes->count() === 0) {
            $this->say(__('listMasternodeConversation.no_masternodes_available'), array_merge([
                'parse_mode' => 'Markdown',
            ]));

            return;
        }

        if ($this->onlyEnabled) {
            $this->masternodes = $this->masternodes->filter(function (UserMasternode $masternode) {
                return $masternode->masternode->state === MNStates::MN_ENABLED;
            });
        }
        $this->bot->typesAndWaits(1);
        $rewardsInDfi = $this->masternodes->sum(function (UserMasternode $masternode) {
            return app(MintedBlockRepository::class)->calculateRewardsForMasternode($masternode);
        });
        $mintedBlocks = app(MintedBlockRepository::class)->sumMintedBlocks($this->masternodes);
        $this->say($this->generateRewardMessage($rewardsInDfi, $mintedBlocks), ['parse_mode' => 'Markdown']);
    }

    protected function generateRewardMessage(float $rewardsInDfi, int $mintedBlocks): string
    {
        if ($this->onlyEnabled) {
            $message = __('rewardsConversation.commulated.enabled_intro');
        } else {
            $message = __('rewardsConversation.commulated.all_intro');
        }

        $message .= __('rewardsConversation.commulated.sum_minted_blocks', [
            'amount' => $mintedBlocks,
        ]);
        $message .= __('rewardsConversation.commulated.sum_rewards', [
            'rewards' => $rewardsInDfi,
        ]);

        $prices = DEXPrice::whereName('BTC')
            ->orWhere('name', 'USDC')
            ->orderBy('order')
            ->get();

        foreach ($prices as $price) {
            $message .= __('rewardsConversation.rewards.other_coins',
                [
                    'value'  => $rewardsInDfi * $price->price,
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
