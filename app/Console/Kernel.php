<?php

namespace App\Console;

use App\Console\Commands\MintedBlockSignal;
use App\Console\Commands\UpdateDexPrices;
use App\Console\Commands\UpdateDFIPricesCommand;
use App\Console\Commands\UpdateEnabledMasternodes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        UpdateEnabledMasternodes::class,
        MintedBlockSignal::class,
        UpdateDFIPricesCommand::class,
        UpdateDexPrices::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('update:masternode-list')->hourly()->withoutOverlapping();
//         $schedule->command('update:dfi-prices')->everyThirtyMinutes()->withoutOverlapping();
         $schedule->command('signal:update-masternode-minted')->everyFiveMinutes()->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
