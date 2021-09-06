<?php

namespace App\Models\Service;

use App\Models\TelegramUser;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\Drivers\Telegram\Extensions\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;

class TelegramUserService
{
    public function getTelegramUser(UserInterface $user): TelegramUser
    {
        try {
            return TelegramUser::where('telegramId', $user->getId())->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->storeTelegramUser($user);
        }
    }

    public function getTelegramUserById(string $telegramId): ?TelegramUser
    {
        return TelegramUser::where('telegramId', $telegramId)->first();
    }

    public function isNewUser(User $user): bool
    {
        return !$this->isExistingUser($user);
    }

    public function isExistingUser(User $user): bool
    {
        return TelegramUser::where('telegramId', $user->getId())->count() === 1;
    }

    protected function storeTelegramUser(User $user): TelegramUser
    {
        return TelegramUser::create([
            'telegramId'    => $user->getId(),
            'username'      => $user->getUsername(),
            'firstName'     => $user->getFirstName(),
            'lastName'      => $user->getLastName(),
            'language'      => substr($user->getLanguageCode() ?? 'en', 2),
            'status'        => $user->getStatus(),
            'user_sync_key' => $this->generateUserSyncKey(),
        ]);
    }

    public function generateUserSyncKey(): string
    {
        return Str::uuid();
    }
}
