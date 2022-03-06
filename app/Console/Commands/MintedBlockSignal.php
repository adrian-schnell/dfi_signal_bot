<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class MintedBlockSignal extends Command
{
	protected $signature = 'signal:update-masternode-minted {user?}';
	protected $description = 'Load minted block for a user manually';

	public function handle()
	{
		$user = TelegramUser::find($this->argument('user'));

		if (isset($user)) {
			$this->updateMintedBlocks($user);

			return;
		}

		$choice = $this->choice('Do you want to update the blocks for all users?', [
			'Yes',
			'no',
		]);

		if ($choice !== 'Yes') {
			$this->info('cancel command now...');

			return;
		}

		$users = TelegramUser::all();
		$users->each(function (TelegramUser $user) {
			$this->updateMintedBlocks($user);
		});
	}

	protected function updateMintedBlocks(TelegramUser $user): void
	{
		$this->info(sprintf('updating minted blocks for user %s %s (ID: %s)', $user->firstName, $user->lastName,
			$user->id));
		app(DefichainApiService::class)->storeMintedBlockForTelegramUser($user);
	}
}
