<?php

namespace App\Models\Service;

use App\Models\TelegramUser;
use BotMan\BotMan\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

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

    public function isNewUser(UserInterface $user): bool
    {
        return !$this->isExistingUser($user);
    }

    public function isExistingUser(UserInterface $user): bool
    {
        return TelegramUser::where('telegramId', $user->getId())->count() === 1;
    }

    protected function storeTelegramUser(UserInterface $user): TelegramUser
    {
        $language = $this->extractLanguage($user);
        $status   = $this->extractStatus($user);

        return TelegramUser::create([
            'telegramId' => $user->getId(),
            'username'   => $user->getUsername(),
            'firstName'  => $user->getFirstName(),
            'lastName'   => $user->getLastName(),
            'language'   => $language,
            'status'     => $status,
        ]);
    }

    public function extractLanguage(UserInterface $user): string
    {
        try {
            return $user->getInfo()['user']['language_code'];
        } catch (Throwable $e) {
            return 'en';
        }
    }

    public function extractStatus(UserInterface $user): string
    {
        try {
            return $user->getInfo()['status'];
        } catch (Throwable $e) {
            return 'member';
        }
    }
}
