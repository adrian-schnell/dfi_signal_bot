<?php

namespace App\Console\Commands;

use App\Enum\QueueNames;
use App\Jobs\StoreMintedBlocksJob;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class MintedBlockSignal extends Command
{
    protected $signature = 'signal:update-masternode-minted';

    protected $description = 'Send signal to user, if masternode has new block minted';

    public function handle()
    {
        $users = TelegramUser::all();
        $users->each(function (TelegramUser $user) {
            dispatch(new StoreMintedBlocksJob($user))->onQueue(QueueNames::MINTED_BLOCK_QUEUE);
        });
    }
}
