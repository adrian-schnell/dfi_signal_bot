<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerboseCommand extends Command
{
    protected $signature = 'command:verbose';

    protected function sendMessageIfVerbose(string $message): void
    {
        if ($this->getOutput()->isVerbose()) {
            $this->info($message);
        }
    }
}
