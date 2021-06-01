<?php

namespace App\Console;

use App\Console\Commands\MintedBlockSignal;
use App\Console\Commands\UpdateEnabledMasternodes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
		UpdateEnabledMasternodes::class,
		MintedBlockSignal::class,
	];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('masternode:list-update')->hourly()->withoutOverlapping();
         $schedule->command('signal:update-masternode-minted')->everyFiveMinutes()->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
