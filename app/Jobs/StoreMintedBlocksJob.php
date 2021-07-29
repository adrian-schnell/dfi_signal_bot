<?php

namespace App\Jobs;

use App\Http\Service\DefichainApiService;
use App\Models\Repository\MintedBlockRepository;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class StoreMintedBlocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected UserMasternode $masternode;
    protected array $mintedBlocks = [];
    protected bool $isInit = true;
    protected TelegramUser $user;

    public function __construct(UserMasternode $masternode, TelegramUser $user, bool $isInit)
    {
        $this->masternode = $masternode;
        $this->isInit = $isInit;
        $this->user = $user;
    }

    public function handle(DefichainApiService $apiService): void
    {
        if (!isset($this->masternode->masternode)) {
            Log::info(sprintf('user masternode %s (user %s) does not exist anymore', $this->masternode->id,
                $this->user->id));

            return;
        }

        $this->mintedBlocks = $apiService->mintedBlocksForOwnerAddress($this->masternode->masternode->owner_address);

        app(MintedBlockRepository::class)
            ->storeMintedBlocks(
                $apiService,
                $this->masternode,
                $this->mintedBlocks,
                $this->isInit
            );
    }

    public function tags(): array
    {
        return [
            sprintf('user: %s', $this->user->id),
            sprintf('user masternode: %s', $this->masternode->id),
        ];
    }
}
