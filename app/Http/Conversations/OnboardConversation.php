<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class OnboardConversation extends Conversation
{
    const VALUE_IMPORT = 'import';
    const VALUE_MANUALLY = 'manually';

    /**
     * @inheritDoc
     */
    public function run()
    {
        $question = Question::create(__('onboardConversation.question'))
            ->addButtons([
                Button::create(__('onboardConversation.buttons.import'))->value(self::VALUE_IMPORT),
                Button::create(__('onboardConversation.buttons.manually'))->value(self::VALUE_MANUALLY),
            ]);

        $this->ask($question, function (Answer $answer) {
		   if ($answer->getValue() === self::VALUE_IMPORT) {
		       $this->getBot()->startConversation(new SyncMasternodeMonitorConversation());
           } elseif ($answer->getValue() === self::VALUE_MANUALLY) {
               $this->getBot()->startConversation(new LinkMasternodeConversation($this->getBot()->getUser()));
           }
        });
    }
}
