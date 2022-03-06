<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class MintedBlockSignal extends Command
{
	protected $signature = 'signal:update-masternode-minted {user}';
	protected $description = 'Load minted block for a user manually';

	public function handle()
	{
		$user = TelegramUser::find($this->argument('user'));

		if (isset($user)) {
			$this->info(sprintf('updating minted blocks for user %s %s (ID: %s)', $user->firstName, $user->lastName,
				$user->id));
			app(DefichainApiService::class)->storeMintedBlockForTelegramUser($user);
		}
	}
}
