<?php

namespace App\SignalService;

use App\Http\Service\TelegramMessageService;
use App\Models\MintedBlock;
use App\Models\TelegramUser;
use BotMan\BotMan\BotMan;

class SignalService
{
    protected Botman $bot;
    /**
     * @var \App\Http\Service\TelegramMessageService
     */
    protected TelegramMessageService $messageService;

    public function __construct(BotMan $bot, TelegramMessageService $messageService)
    {
        $this->bot = $bot;
        $this->messageService = $messageService;
    }

    public function tellMintedBlock(TelegramUser $user, MintedBlock $mintedBlock, string $language = 'en'): void
    {
        // don't report one block again
        if ($mintedBlock->is_reported) {
            return;
        }
        set_language($language);

        $messageSent = $this->messageService->sendMessage(
            $user,
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

        if ($messageSent) { // update, so this block will not be reported again!
            $mintedBlock->update([
                'is_reported' => true,
            ]);
        }
    }
}
