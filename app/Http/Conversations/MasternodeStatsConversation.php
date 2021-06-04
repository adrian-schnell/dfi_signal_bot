<?php

namespace App\Http\Conversations;

use App\Exceptions\DefichainApiException;
use App\Models\DFIPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;
use Throwable;

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
        try {
            $ageInDays = app(MasternodeService::class)->calculateMasternodeAge($masternode, 'days');
        } catch (DefichainApiException $e) {
            $ageInDays = -1;
            $questionString .= __('errors.api_not_available');
        }

        if ($mintedBlockCount > 0 && $ageInDays >= 0) {
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

            $latestMintedBlock = $masternode->mintedBlocks()->orderBy('block_time', 'DESC')->first();
            if (isset($latestMintedBlock)) {
                $questionString .= '

' . (string)__('MasternodeStatConversation.last_block',
                        [
                            'blockHeight' => $latestMintedBlock->mintBlockHeight,
                            'hours'       => now()->diffInHours($latestMintedBlock->block_time),
                        ]);
                $questionString .= '
' . (string)__('MasternodeStatConversation.tx_link',
                        ['txid' => $masternode->mintedBlocks()->latest()->first()->mint_txid]);
            }
        }

        return $questionString;
    }

    protected function generateRewardMessage(UserMasternode $masternode): string
    {
        $dfiRewardSum   = app(MintedBlockRepository::class)->calculateRewardsForMasternode($masternode);
        $questionString = (string)__('MasternodeStatConversation.rewards.dfi',
            ['dfi' => $dfiRewardSum]);
        foreach (DFIPrice::all() as $coin) {
            $priceValue     = $dfiRewardSum * $coin->price;
            $questionString .= '
' . (string)__('MasternodeStatConversation.rewards.other_coins',
                    [
                        'value'  => $coin->is_rounded ? round($priceValue, 2) : $priceValue,
                        'ticker' => $coin->symbol ?? $coin->ticker,
                    ]);
        }

        return $questionString;
    }
}
