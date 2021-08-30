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

            $this->say($this->generateMessage($masternode), ['parse_mode' => 'Markdown']);

            $this->say(sprintf('⏫⏫⏫⏫ `%s` ⏫⏫⏫⏫', $masternode->name), [
                'parse_mode' => 'Markdown',
            ]);
        });
    }

    protected function generateMessage(UserMasternode $masternode): string
    {
        $questionString = (string)__('MasternodeStatConversation.name', ['name' => $masternode->name]);

        $questionString .= str_replace('_', ' ',
            __('MasternodeStatConversation.state', ['state' => $masternode->masternode->state]));

        $mintedBlockCount = $masternode->mintedBlocks->count();
        try {
            $ageInDays = app(MasternodeService::class)->calculateMasternodeAge($masternode, 'days');
        } catch (DefichainApiException $e) {
            $ageInDays = -1;
            $questionString .= __('errors.api_not_available');
        }

        $averageBlock = $mintedBlockCount > 0 ? round($ageInDays / $mintedBlockCount, 2) : 0;

        $questionString .= __('MasternodeStatConversation.block_minted_count', ['count' => $mintedBlockCount]);
        $questionString .= trans_choice('MasternodeStatConversation.age',
            $ageInDays,
            ['age' => $ageInDays]);
        $questionString .= __('MasternodeStatConversation.average_block', ['average' => $averageBlock]);

        $latestMintedBlock = $masternode->mintedBlocks()->orderBy('block_time', 'DESC')->first();
        if (isset($latestMintedBlock)) {
            $questionString .= __('MasternodeStatConversation.last_block',
                [
                    'blockHeight' => $latestMintedBlock->mintBlockHeight,
                    'hours'       => time_diff_humanreadable(
                        now(),
                        $latestMintedBlock->block_time, $masternode->user
                    ),
                ]);
            $questionString .= __('MasternodeStatConversation.tx_link',
                [
                    'txid'           => $masternode->mintedBlocks->sortByDesc('id')->first()->mint_txid,
                    'txid_truncated' => str_truncate_middle($masternode->mintedBlocks()->latest()->first()
                        ->mint_txid, 30),
                ]);
        }
        if (count($masternode->masternode->target_multipliers) > 0) {
            $questionString .= __('MasternodeStatConversation.target_multiplier',
                [
                    'multiplier' => implode(', ', $masternode->masternode->target_multipliers),
                ]);
        }

        if ($masternode->masternode->timelock) {
            $questionString .= __('MasternodeStatConversation.timelock',
                [
                    'timelock' => $masternode->masternode->timelock,
                ]);
        }

        return $questionString;
    }
}
