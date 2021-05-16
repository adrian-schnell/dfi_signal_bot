<?php

namespace App\Http\Controllers;

use App\Http\Conversations\OnboardConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Models\Service\TelegramUserService;

class BotController extends Controller
{
    protected BotMan $botMan;

    public function __construct()
    {
        $this->botMan = app('botman');
    }
    public function handle(TelegramUserService $telegramUserService)
    {

        $this->botMan->on('new_chat_members', function ($payload, $bot) {
            $bot->reply('Hello');
        });

        $this->botMan->hears('/start', function (Botman $botman) use ($telegramUserService) {
            if ($telegramUserService->isExistingUser($botman->getUser())) {
                $botman->reply('You\'re already onboarded. Do you want to restart the onboarding process?');
                return;
            }
            $telegramUserService->getTelegramUser($botman->getUser());

            $botman->typesAndWaits(2);
            $botman->startConversation(new OnboardConversation());
        });

        $this->botMan->hears('information', function (BotMan $botman) {
            $user = $botman->getUser();
            $botman->reply('ID: ' . $user->getId());
            $botman->reply('Firstname: ' . $user->getFirstName());
            $botman->reply('Lastname: ' . $user->getLastName());
            $botman->reply('Username: ' . $user->getUsername());
            $botman->reply('Info: ' . json_encode($user->getInfo(), true));
        });
//        $this->botMan->hears('{message}', function ($botman, $message) {
//            if ($message == 'hi') {
//                $this->askName($botman);
//            } else {
//                $botman->reply("write 'hi' for testing...");
//            }
//        });

        $this->botMan->listen();
    }

    /**
     * @param $botman
     */
    public function askName($botman)
    {
        $botman->ask('Hello! What is your Name?', function (Answer $answer) use ($botman) {
            $name = $answer->getText();
            $this->say('Nice to meet you ' . $name);
            $botman->userStorage()->save([
                'name' => $name,
            ]);
        });
    }
}
