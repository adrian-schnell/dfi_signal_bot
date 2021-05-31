<?php

namespace App\Http\Conversations;

use App\Coinpaprika\CoinpaprikaApi;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class MasternodeStatsConversation extends Conversation
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
        $this->masternodes->each(function (UserMasternode $masternode) {
            $this->say(sprintf('⏬⏬⏬⏬ `%s` ⏬⏬⏬⏬', $masternode->name), [
                'parse_mode' => 'Markdown',

            ]);

            $questionGeneral = Question::create($this->generateMessage($masternode));
            $this->ask($questionGeneral, function () {
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));
            $questionRewards = Question::create($this->generateRewardMessage($masternode));
            $this->ask($questionRewards, function () {
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));

            $this->say(sprintf('⏫⏫⏫⏫ `%s` ⏫⏫⏫⏫', $masternode->name), [
                'parse_mode' => 'Markdown',

            ]);
        });
    }

    protected function generateMessage(UserMasternode $masternode): string
    {
        $questionString = (string)__('MasternodeStatConversation.name', ['name' => $masternode->name]);

        $questionString .= '
' . (string)__('MasternodeStatConversation.state',
                ['state' => $masternode->masternode->state]);

        $mintedBlockCount = $masternode->mintedBlocks->count();
        if ($mintedBlockCount > 0) {
            $ageInDays = app(MasternodeService::class)->calculateMasternodeAge($masternode, 'days');
            $averageBlock = round($ageInDays / $mintedBlockCount, 2);

            $questionString .= '
' . (string)__('MasternodeStatConversation.block_minted_count',
                    ['count' => $mintedBlockCount]);
            $questionString .= '
' . (string)trans_choice('MasternodeStatConversation.age',
                    $ageInDays,
                    ['age' => $ageInDays]);
            $questionString .= '
' . (string)__('MasternodeStatConversation.average_block',
                    ['average' => $averageBlock]);

            $questionString .= '

' . (string)__('MasternodeStatConversation.last_block',
                    [
                        'blockHeight' => $masternode->mintedBlocks()->latest()->first()->mintBlockHeight,
                        'hours'       => now()->diffInHours($masternode->mintedBlocks()->latest()->first()->block_time),
                    ]);
            $questionString .= '
' . (string)__('MasternodeStatConversation.tx_link',
                    ['txid' => $masternode->mintedBlocks()->latest()->first()->mint_txid]);
        }

        return $questionString;
    }

    protected function generateRewardMessage(UserMasternode $masternode): string
    {
        $currencyRates = app(CoinpaprikaApi::class)->getDfiRates();
        $btcRate = $currencyRates['BTC']['price'];
        $ethRate = $currencyRates['ETH']['price'];
        $usdRate = $currencyRates['USD']['price'];
        $eurRate = $currencyRates['EUR']['price'];

        $dfiRewardSum = app(MintedBlockRepository::class)->calculateRewardsForMasternode($masternode);
        $questionString = (string)__('MasternodeStatConversation.rewards.dfi',
                ['dfi' => $dfiRewardSum]);
        $questionString .= '
'.(string)__('MasternodeStatConversation.rewards.btc',
            ['btc' => $dfiRewardSum * $btcRate]);
        $questionString .= '
'.(string)__('MasternodeStatConversation.rewards.eth',
                ['eth' => $dfiRewardSum * $ethRate]);
        $questionString .= '
'.(string)__('MasternodeStatConversation.rewards.usd',
                ['usd' => round($dfiRewardSum * $usdRate, 2)]);
        $questionString .= '
'.(string)__('MasternodeStatConversation.rewards.eur',
                ['eur' => round($dfiRewardSum * $eurRate, 2)]);

        return $questionString;
    }
}
