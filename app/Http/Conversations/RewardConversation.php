<?php

namespace App\Http\Conversations;

use App\Exceptions\DefichainApiException;
use App\Models\DEXPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class RewardConversation extends Conversation
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
        $questionString = (string)__('MasternodeStatConversation.rewards.dfi',
            ['dfi' => $dfiRewardSum]);
        $prices = DEXPrice::orderBy('order')->get();

        foreach ($prices as $price) {
            $questionString .= (string)__('MasternodeStatConversation.rewards.other_coins',
                [
                    'value'  => $dfiRewardSum * $price->price,
                    'ticker' => $price->symbol,
                ]);
        }

        $questionString .= (string)__('MasternodeStatConversation.rewards.legal',
            [
                'date' => $prices->first()->updated_at->format('H:i:s - d.m.Y'),
            ]);

        return $questionString;
    }
}
