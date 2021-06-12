<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\Drivers\Telegram\TelegramDriver;

class TelegramMessageService
{
    protected BotMan $botman;

    public function __construct(BotMan $bot)
    {
        $this->botman = $bot;
    }

    public function sendMessage(TelegramUser $user, string $message, array $param = []): bool
    {
        try {
            $this->botman->say(
                $message,
                $user->telegramId,
                TelegramDriver::class,
                $param
            );

            return true;
        } catch (BotManException $e) {
            return false;
        }
    }
}
