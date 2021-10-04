<?php

namespace App\Http\Middleware\TelegramBot;

use App\Models\Service\StatisticService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;

class SentMessages implements Sending
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
        app(StatisticService::class)->messageSent();
        return $next($payload);
    }
}
