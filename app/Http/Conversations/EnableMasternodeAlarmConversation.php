<?php

    namespace App\Http\Conversations;

    use App\Http\Service\MasternodeMonitorService;
    use App\Models\UserMasternode;
    use App\Models\TelegramUser;
    use BotMan\BotMan\Messages\Conversations\Conversation;
    use BotMan\BotMan\Messages\Incoming\Answer;
    use BotMan\BotMan\Messages\Outgoing\Actions\Button;
    use BotMan\BotMan\Messages\Outgoing\Question;

    class EnableMasternodeAlarmConversation extends Conversation
    {
        private ?array $masternodes = null;

        public function __construct(array $masternodes)
        {
            $this->masternodes = $masternodes;
        }

        /**
         * @inheritDoc
         */
        public function run()
        {
            $question = Question::create(__('enableMasternodeAlarmConversation.intro'))
                ->addButtons([
                    Button::create(__('enableMasternodeAlarmConversation.buttons.yes'))->value('yes'),
                    Button::create(__('enableMasternodeAlarmConversation.buttons.no'))->value('no'),
                ]);
            $masternodes = $this->masternodes;
            $this->ask($question, function (Answer $answer) use ($masternodes) {
                $alarmOn = true;
                if ($answer->getValue() === 'no') {
                    $alarmOn = false;
                }

                foreach ($masternodes as $masternode) {
                    $masternode->update([
                        'alarm_on' => $alarmOn,
                    ]);
                }

                if ($alarmOn) {
                    $this->say(__('enableMasternodeAlarmConversation.result'));
                } else {
                    $this->say(__('enableMasternodeAlarmConversation.result_false'));
                }
            });
        }
    }
