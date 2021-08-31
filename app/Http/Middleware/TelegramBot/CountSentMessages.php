<?php

namespace App\Http\Middleware\TelegramBot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;

class CountSentMessages implements Sending
{
    /**
     * Handle an outgoing message payload before/after it
     * hits the message service.
     *
     * @param mixed $payload
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        cache()->increment('message_count');
        ray(sprintf('sending message #%s', cache('message_count')));
        return $next($payload);
    }
}
