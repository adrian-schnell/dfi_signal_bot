<?php

namespace App\Models\Service;

use App\Models\OnetimeNotification;
use Illuminate\Database\Eloquent\Model;

class OnetimeNotificationService
{
    public function notificationSendForModel(Model $model, string $notificationType): void
    {
        OnetimeNotification::create([
            'notifiable_type' => get_class($model),
            'notifiable_id'   => $model->id,
            'name'            => $notificationType,
            'sent_at'         => now(),
        ]);
    }
}
