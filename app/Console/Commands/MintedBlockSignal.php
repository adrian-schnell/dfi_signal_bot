<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class MintedBlockSignal extends Command
{
    protected $signature = 'signal:update-masternode-minted';

    protected $description = 'DEPRECATED: Send signal to user, if masternode has new block minted';

    public function handle()
    {
        $users = TelegramUser::all();
        $users->each(function (TelegramUser $user) {
            app(DefichainApiService::class)->storeMintedBlockForTelegramUser($user);
        });
    }
}
