<?php

namespace App\Http\Conversations;

use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class EditMasternodeConversation extends Conversation
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
            $buttons = [
                Button::create(__('listMasternodeConversation.buttons.unlink'))
                    ->value('unlink')
            ];

            if ($masternode->alarm_on) {
                $buttons[] = Button::create(__('listMasternodeConversation.buttons.alarm_off'))
                    ->value('alarm_off');
            } else {
                $buttons[] = Button::create(__('listMasternodeConversation.buttons.alarm_on'))
                    ->value('alarm_on');
            }

            $question = Question::create($this->generateQuestionString($masternode))
                ->addButtons($buttons);
            $this->ask($question, function (Answer $answer) use ($masternode) {
                if (!$answer->isInteractiveMessageReply()) {
                    return;
                }

                $answerValue = $answer->getValue();
                if ($answerValue === 'alarm_on' || $answerValue === 'alarm_off') {
                    $masternode->update([
                        'alarm_on' => $answerValue === 'alarm_on',
                    ]);
                } elseif ($answerValue === 'unlink') {
                    $masternode->delete();
                }
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));
        });
    }

    protected function generateQuestionString(UserMasternode $masternode): string
    {
        $questionString = (string)__('listMasternodeConversation.name', ['name' => $masternode->name]);
        if ($masternode->owner_address) {
            $questionString .= '
' . __('listMasternodeConversation.owner', ['owner' => $masternode->owner_address]);
        }
        if ($masternode->operator_address) {
            $questionString .= '
' . __('listMasternodeConversation.operator', ['operator' => $masternode->operator_address]);
        }
        if ($masternode->masternode_id) {
            $questionString .= '
' . __('listMasternodeConversation.masternode_id', ['masternode_id' => $masternode->masternode_id]);
        }
        $questionString .= '
' . __('listMasternodeConversation.alarm_on', ['icon' => $masternode->alarm_on ? '✅' : '❌']);
        $questionString .= '
' . __('listMasternodeConversation.synced', ['icon' => $masternode->synced_masternode_monitor ? '✅' : '❌']);

        return $questionString;
    }
}
