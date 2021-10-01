<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use Arr;
use Carbon\Carbon;
use Str;

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

            $this->sendWarnings($messageService);
        }

        if ($this->hasCriticalError()) {

            $this->sendCriticalErrors($messageService);
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

    protected function sendWarnings(TelegramMessageService $messageService): void
    {
        $message = '';
        $blockHeight = $this->searchInArray($this->analysis['warnings'], 'block_height');
        $cooldownKeyBlockHeight = $this->generateCooldownKey('warnings', 'block_height');
        if (
            count($blockHeight) > 0
            && $this->telegramUser->cooldown($cooldownKeyBlockHeight)->passed()
        ) {
            $message = __('mn_health_webhook.warnings.block_height', [
                'local_height'  => $blockHeight['value'],
                'remote_height' => $blockHeight['expected'],
                'difference'    => abs($blockHeight['value'] - $blockHeight['expected']),
            ]);
            $this->telegramUser->cooldown($cooldownKeyBlockHeight)->until(now()->addHours(2));
        }

        $connectionCount = $this->searchInArray($this->analysis['warnings'], 'block_height');
        $cooldownKeyConnectionCount = $this->generateCooldownKey('warnings', 'connection_count');
        if (
            count($connectionCount) > 0
            && $this->telegramUser->cooldown($cooldownKeyConnectionCount)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.connection_count', [
                    'count' => $connectionCount['value'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyConnectionCount)->until(now()->addHours(1));
        }

        $logsize = $this->searchInArray($this->analysis['warnings'], 'logsize');
        $cooldownKeyLogSize = $this->generateCooldownKey('warnings', 'logsize');
        if (
            count($logsize) > 0
            && $this->telegramUser->cooldown($cooldownKeyLogSize)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.logsize', [
                    'size' => $logsize['value'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyLogSize)->until(now()->addHours(6));
        }

        $configChecksum = $this->searchInArray($this->analysis['warnings'], 'config_checksum');
        $cooldownKeyConfigChecksum = $this->generateCooldownKey('warnings', 'config_checksum');
        if (
            count($configChecksum) > 0
            && $this->telegramUser->cooldown($cooldownKeyConfigChecksum)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.config_checksum');
            $this->telegramUser->cooldown($cooldownKeyConfigChecksum)->until(now()->addHours(12));
        }

        $nodeVersion = $this->searchInArray($this->analysis['warnings'], 'node_version');
        $cooldownKeyNodeVersion = $this->generateCooldownKey('warnings', 'node_version');
        if (
            count($nodeVersion) > 0
            && $this->telegramUser->cooldown($cooldownKeyNodeVersion)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.node_version', [
                    'active_version' => $nodeVersion['value'],
                    'new_version'    => $nodeVersion['expected'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyNodeVersion)->until(now()->addHours(12));
        }

        $loadAvg = $this->searchInArray($this->analysis['warnings'], 'load_avg');
        $cooldownKeyLoadAvg = $this->generateCooldownKey('warnings', 'load_avg');
        if (
            count($loadAvg) > 0
            && $this->telegramUser->cooldown($cooldownKeyLoadAvg)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.load_avg', [
                    'value' => $loadAvg['value'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyLoadAvg)->until(now()->addHours(1));
        }

        $hdd = $this->searchInArray($this->analysis['warnings'], 'hdd');
        $cooldownKeyHdd = $this->generateCooldownKey('warnings', 'hdd');
        if (
            count($hdd) > 0
            && $this->telegramUser->cooldown($cooldownKeyHdd)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.hdd', [
                    'percent' => $hdd['value'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyHdd)->until(now()->addDay());
        }

        $ram = $this->searchInArray($this->analysis['warnings'], 'ram');
        $cooldownKeyHdd = $this->generateCooldownKey('warnings', 'ram');
        if (
            count($ram) > 0
            && $this->telegramUser->cooldown($cooldownKeyHdd)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.ram', [
                    'percent' => $ram['value'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyHdd)->until(now()->addDay());
        }

        $serverScript = $this->searchInArray($this->analysis['warnings'], 'server_script_version');
        $cooldownKeyHdd = $this->generateCooldownKey('warnings', 'server_script_version');
        if (
            count($serverScript) > 0
            && $this->telegramUser->cooldown($cooldownKeyHdd)->passed()
        ) {
            $message = "\r\n\r\n" . __('mn_health_webhook.warnings.server_script_version', [
                    'installed_version' => $serverScript['value'],
                    'new_version'       => $serverScript['expected'],
                ]);
            $this->telegramUser->cooldown($cooldownKeyHdd)->until(now()->addDay());
        }

        // finally send the message
        if (Str::length($message) > 0) {
            $messageService->sendMessage(
                $this->telegramUser,
                __('mn_health_webhook.headline.warnings'),
                ['parse_mode' => 'Markdown']
            );
            $messageService->sendMessage(
                $this->telegramUser,
                $message,
                ['parse_mode' => 'Markdown']
            );
        }
    }

    protected function sendCriticalErrors(TelegramMessageService $messageService): void
    {
        $messageService->sendMessage(
            $this->telegramUser,
            __('mn_health_webhook.headline.critical_errors'),
            ['parse_mode' => 'Markdown']
        );
    }

    protected function searchInArray(array $array, string $needle): array
    {
        return collect(Arr::where($array, function ($value, $key) use ($needle) {
                return $value['type'] === $needle;
            }))->first() ?? [];
    }

    protected function generateCooldownKey(string $category, string $type): string
    {
        return sprintf('%s_%s_%s', $category, $type, md5($this->telegramUser->id));
    }
}
