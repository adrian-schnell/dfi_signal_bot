<?php

namespace App\Http\Middleware\TelegramBot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class SetLanguageReceived implements Received
{
    /**
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param callable                                         $next
     * @param \BotMan\BotMan\BotMan                            $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        app()->setLocale($message->getPayload()['from']['language_code'] ?? 'en');

        return $next($message);
    }
}
