<?php

namespace App\Console\Commands;

use App\Enum\QueueNames;
use App\Jobs\MNMonitorSyncMasternodesForUser;
use App\Models\TelegramUser;
use Illuminate\Console\Command;

class MasternodeMonitorSyncCommand extends Command
{
	protected $signature = 'update:masternode-monitor-sync';
	protected $description = 'Resync users with masternode monitor';

	public function handle()
	{
		$users = TelegramUser::whereNotNull('mn_monitor_sync_key')->get();

		$users->each(function (TelegramUser $user) {
		    dispatch(new MNMonitorSyncMasternodesForUser($user))->onQueue(QueueNames::MASTERNODE_MONITOR_QUEUE);
        });
	}
}
