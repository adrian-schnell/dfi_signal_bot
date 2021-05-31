<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class HelpConversation extends Conversation
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->say(__('helpConversation.masternode'));
    }
}
