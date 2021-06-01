<?php

namespace App\Jobs;

use App\Http\Service\DefichainApiService;
use App\Models\TelegramUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreMintedBlocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TelegramUser $user;

    public function __construct(TelegramUser $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        app(DefichainApiService::class)->storeMintedBlockForTelegramUser($this->user);
    }
}
