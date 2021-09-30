<?php

namespace App\Jobs;

use App\Http\Service\MasternodeHealthWebhookService;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WebhookAnalyzerJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected array $analysis;
    protected TelegramUser $telegramUser;
    protected Carbon $latestUpdate;

    public function __construct(array $data, array $analysis, Carbon $latestUpdate, TelegramUser $telegramUser)
    {
        $this->data = $data;
        $this->analysis = $analysis;
        $this->telegramUser = $telegramUser;
        $this->latestUpdate = $latestUpdate;
    }

	public function handle(MasternodeHealthWebhookService $service)
	{
        $service->initWithData($this->telegramUser, $this->data,$this->analysis, $this->latestUpdate);

		ray($this->data, $this->analysis, $this->telegramUser);
	}
}
