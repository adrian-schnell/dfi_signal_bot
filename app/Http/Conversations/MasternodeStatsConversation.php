<?php

namespace App\Http\Conversations;

use App\Models\Repository\MintedBlockRepository;
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
            $question = Question::create($this->generateMessage($masternode));
            $this->ask($question, function () {
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));
        });
    }

    protected function generateMessage(UserMasternode $masternode): string
    {
        $questionString = (string)__('MasternodeStatConversation.name', ['name' => $masternode->name]);
        $questionString .= '
' . (string)__('MasternodeStatConversation.rewards',
                ['value' => app(MintedBlockRepository::class)->calculateRewardsForMasternode($masternode)]);

        if ($masternode->mintedBlocks->count() > 0) {
            $questionString .= '
' . (string)__('MasternodeStatConversation.block_minted_count',
                    ['count' => $masternode->mintedBlocks->count()]);
            $questionString .= '
' . (string)__('MasternodeStatConversation.last_block',
                    ['blockHeight' => $masternode->mintedBlocks()->latest()->first()->mintBlockHeight]);
            $questionString .= '
' . (string)__('MasternodeStatConversation.tx_link',
                    ['txid' => $masternode->mintedBlocks()->latest()->first()->mint_txid]);
        }

        return $questionString;
    }
}
