<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\Drivers\Telegram\TelegramDriver;
use Log;

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
            Log::error('sending botman message failed', [
                'message'          => $e->getMessage(),
                'line'             => $e->getLine(),
                'telegram_user_id' => $user->id,
                'message_to_user'  => $message,
            ]);

            return false;
        }
    }
}
