<?php

namespace App\Jobs;

use App\Http\Service\TelegramMessageService;
use App\Models\TelegramUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TelegramUser $user;
    protected string $message;
    protected string $type;
    const MESSAGE_UPDATE = 'update';
    const MESSAGE_MAINTENANCE = 'maintenance';
    const MESSAGE_INFO = 'info';
    const MESSAGE_TYPES = [
        self::MESSAGE_UPDATE      => 'Update',
        self::MESSAGE_MAINTENANCE => 'Wartung',
        self::MESSAGE_INFO        => 'Info',
        'none'                    => 'Kein Banner',
    ];

    public function __construct(TelegramUser $user, string $message, string $type)
    {
        $this->user = $user;
        $this->message = $message;
        $this->type = $type;
    }

    public function handle(TelegramMessageService $service): void
    {
        if ($this->type === self::MESSAGE_UPDATE) {
            $service->sendMessage($this->user, '⚙️⚙️⚙️ *Update Notice* ⚙️⚙️⚙️', ['parse_mode' => 'Markdown']);
        } elseif ($this->type === self::MESSAGE_MAINTENANCE) {
            $service->sendMessage($this->user, '🛠🛠🛠 *Maintenance* 🛠🛠🛠', ['parse_mode' => 'Markdown']);
        } elseif ($this->type === self::MESSAGE_INFO) {
            $service->sendMessage($this->user, 'ℹ️ℹ️ℹ️ *Information* ℹ️ℹ️ℹ️', ['parse_mode' => 'Markdown']);
        }

        $service->sendMessage($this->user, $this->message, ['parse_mode' => 'Markdown']);
    }
}
