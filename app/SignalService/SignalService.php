<?php

namespace App\SignalService;

use App\Models\MintedBlock;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;

class SignalService
{
    protected Botman $bot;

    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
    }

    public function tellMintedBlock(string $telegramId, MintedBlock $mintedBlock): void
    {
        $this->tellMessage(
            $telegramId,
            __('signals.minted_block', [
                'time'            => $mintedBlock->block_time->format('d.m.Y H:i:s'),
                'name'            => $mintedBlock->userMasternode->name,
                'mintBlockHeight' => $mintedBlock->mintBlockHeight,
                'value'           => $mintedBlock->value,
            ]),
            [
                'parse_mode' => 'Markdown'
            ]
        );
    }

    public function tellMessage(string $telegramId, string $message, array $additionalParam = []): void
    {
        $this->bot->say(
            $message,
            $telegramId,
            TelegramDriver::class,
            $additionalParam
        );
    }
}
