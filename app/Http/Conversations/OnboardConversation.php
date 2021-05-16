<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class OnboardConversation extends Conversation
{
	/**
	 * @inheritDoc
	 */
	public function run()
	{
	    $question = Question::create(__('onboardConversation.question'))
            ->addButtons([
                Button::create(__('onboardConversation.buttons.import'))->value('import'),
                Button::create(__('onboardConversation.buttons.manually'))->value('manually'),
            ]);


		$this->ask($question, function (Answer $answer) {
		   $this->say('Lets start ' . $answer->getValue());
        });
	}
}
