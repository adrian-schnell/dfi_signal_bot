<?php

namespace App\Http\Middleware\TelegramBot;

use App\Models\Service\StatisticService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class CommandReceived implements Received
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
        app(StatisticService::class)->commandReceived();

        return $next($message);
    }
}
