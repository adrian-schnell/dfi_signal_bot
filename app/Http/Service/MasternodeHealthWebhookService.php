<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use Arr;
use Carbon\Carbon;

class MasternodeHealthWebhookService
{
    protected TelegramUser $telegramUser;
    protected array $data;
    protected array $analysis;
    protected Carbon $latestUpdate;

    public function initWithData(
        TelegramUser $telegramUser,
        array $data,
        array $analysis,
        Carbon $latestUpdate
    ): void {

        $this->telegramUser = $telegramUser;
        $this->data = $data;
        $this->analysis = $analysis;
        $this->latestUpdate = $latestUpdate;
    }

    public function run(TelegramMessageService $messageService): void
    {
        if ($this->hasWarnings()) {
            $messageService->sendMessage(
                $this->telegramUser,
                __('mn_health_webhook.headline.warnings'),
                ['parse_mode' => 'Markdown']
            );
            $this->sendWarnings();
        }

        if ($this->hasCriticalError()) {
            $messageService->sendMessage(
                $this->telegramUser,
                __('mn_health_webhook.headline.critical_errors'),
                ['parse_mode' => 'Markdown']
            );
            $this->sendCriticalErrors();
        }
    }

    protected function hasWarnings(): bool
    {
        return count($this->analysis['warnings'] ?? []) > 0;
    }

    protected function hasCriticalError(): bool
    {
        return count($this->analysis['critical'] ?? []) > 0;
    }

    protected function sendWarnings(): void
    {

    }

    protected function sendCriticalErrors(): void
    {

    }

    public function searchInArray(array $array, string $needle): array
    {
        return collect(Arr::where($array, function($value, $key) use ($needle){
            return $value['type'] === $needle;
        }))->first() ?? [];
    }
}
