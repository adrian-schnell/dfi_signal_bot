<?php

namespace App\Models\Service;

use App\Models\MintedBlock;
use App\Models\Statistic;
use App\Models\TelegramUser;
use Carbon\Carbon;
use DB;

class StatisticService
{
    public function messageSent(): void
    {
        Statistic::updateOrCreate([
            'date' => today(),
        ], [
            'messages_sent' => DB::raw('messages_sent + 1'),
        ]);
    }

    public function commandReceived(): void
    {
        Statistic::updateOrCreate([
            'date' => today(),
        ], [
            'commands_received' => DB::raw('commands_received + 1'),
        ]);
    }

    public function updateMintedBlockForDate(Carbon $date = null): self
    {
        $date = $date ?? today();
        Statistic::updateOrCreate([
            'date' => $date,
        ], [
            'minted_blocks' => MintedBlock::whereDate('block_time', $date)->count(),
        ]);

        return $this;
    }

    public function updateUserForDate(Carbon $date = null): self
    {
        $date = $date ?? today();
        Statistic::updateOrCreate([
            'date' => $date,
        ], [
            'user_count' => TelegramUser::all()->count(),
        ]);

        return $this;
    }
}
