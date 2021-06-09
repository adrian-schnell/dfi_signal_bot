<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;

class TelegramMessageService
{
    protected BotMan $botman;

    public function __construct()
    {
        $this->botman = app('botman');
    }

    public function sendMessage(TelegramUser $user, string $message, array $param = []): void
    {
        $this->botman->say(
            $message,
            $user->telegramId,
            TelegramDriver::class,
            $param
        );
    }
}
