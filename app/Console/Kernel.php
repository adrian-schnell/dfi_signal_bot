<?php

namespace App\Console;

use App\Console\Commands\CreateBackendUserCommand;
use App\Console\Commands\MasternodeMonitorSyncCommand;
use App\Console\Commands\MintedBlockSignal;
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
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('masternode:update-list')->everyFiveMinutes()->withoutOverlapping();
         $schedule->command('update:masternode-monitor-sync')->hourly()->withoutOverlapping();
         $schedule->command('update:dex-prices')->everyFiveMinutes()->withoutOverlapping();
         $schedule->command('signal:update-masternode-minted')->everyFiveMinutes()->withoutOverlapping();

         // telescope prune old data
        $schedule->command('telescope:prune')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
