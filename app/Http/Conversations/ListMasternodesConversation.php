<?php

namespace App\Http\Conversations;

use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;
use Str;

class ListMasternodesConversation extends Conversation
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
            $question = Question::create($this->generateQuestionString($masternode));
            $this->ask($question, function () {
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));
        });
    }

    protected function generateQuestionString(UserMasternode $masternode): string
    {
        $state = Str::replace('_', ' ', $masternode->masternode->state);

        $questionString = (string)__('listMasternodeConversation.name', ['name' => $masternode->name]);
        if ($masternode->owner_address) {
            $questionString .= __('listMasternodeConversation.owner', ['owner' => $masternode->owner_address]);
        }
        if ($masternode->operator_address) {
            $questionString .= __('listMasternodeConversation.operator', ['operator' => $masternode->operator_address]);
        }
        if ($masternode->masternode_id) {
            $questionString .= __('listMasternodeConversation.masternode_id', [
                'masternode_id'           => $masternode->masternode->masternode_id,
                'masternode_id_truncated' => str_truncate_middle($masternode->masternode->masternode_id),
            ]);
        }
        $questionString .= __('listMasternodeConversation.state', ['state' => $state]);
        $questionString .= __('listMasternodeConversation.alarm_on',
            ['icon' => $masternode->alarm_on && $masternode->is_active ? '✅' : '❌']);
        $questionString .= __('listMasternodeConversation.synced',
            ['icon' => $masternode->synced_masternode_monitor ? '✅' : '❌']);

        return $questionString;
    }
}
