<?php

namespace App\Console;

use App\Console\Commands\CreateBackendUserCommand;
use App\Console\Commands\FetchBlocksCommand;
use App\Console\Commands\FinalizeStatisticsCommand;
use App\Console\Commands\MasternodeMonitorSyncCommand;
use App\Console\Commands\MintedBlockSignal;
use App\Console\Commands\OnDemandMessageCommand;
use App\Console\Commands\UpdateDexPrices;
use App\Console\Commands\UpdateEnabledMasternodes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
	    UpdateEnabledMasternodes::class,
	    MintedBlockSignal::class,
	    UpdateDexPrices::class,
	    CreateBackendUserCommand::class,
	    MasternodeMonitorSyncCommand::class,
	    FinalizeStatisticsCommand::class,
	    FetchBlocksCommand::class,
	    OnDemandMessageCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('masternode:update-list')->everyFiveMinutes()->withoutOverlapping();
         $schedule->command('update:masternode-monitor-sync')->hourly()->withoutOverlapping();
         $schedule->command('update:dex-prices')->everyFiveMinutes()->withoutOverlapping();
//         $schedule->command('signal:update-masternode-minted')->everyFiveMinutes()->withoutOverlapping();
         $schedule->command('update:blocks')->everyMinute()->withoutOverlapping();

         // create statistics for yesterday
        $schedule->command('statistic:finalize')->dailyAt('0:00');

         // telescope prune old data
        $schedule->command('telescope:prune')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
