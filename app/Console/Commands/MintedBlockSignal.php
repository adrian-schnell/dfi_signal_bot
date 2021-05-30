<?php

namespace App\Console\Commands;

use App\Models\UserMasternode;
use Illuminate\Console\Command;

class MintedBlockSignal extends Command
{
	protected $signature = 'signal:masternode-minted';

	protected $description = 'Send signal to user, if masternode has new block minted';

	public function handle()
	{
		$masternodes = UserMasternode::where('alarm_on', true)->get();
	}
}
