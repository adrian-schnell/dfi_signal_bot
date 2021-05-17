<?php

namespace App\Http\Controllers;

use App\Http\Conversations\HelpConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Conversations\SyncMasternodeMonitorConversation;
use App\Http\Middleware\TelegramBot\SetLanguage;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Models\Service\TelegramUserService;
use Throwable;

class BotController extends Controller
{
    protected BotMan $botMan;

    public function clearUpdateQueue()
    {
        $botMan = app('botman');
        $botMan->fallback(function (BotMan $botman) {
            $botman->reply('alles ok');
        });
        $botMan->listen();
    }

    public function handle(TelegramUserService $telegramUserService)
    {
        $botMan = app('botman');

        $botMan->hears('/start', function (Botman $botman) use ($telegramUserService) {
            $telegramUserService->getTelegramUser($botman->getUser());

            $botman->startConversation(new OnboardConversation());
            if ($telegramUserService->isExistingUser($botman->getUser())) {
                $botman->startConversation(new HelpConversation());
            } else {
                $telegramUserService->getTelegramUser($botman->getUser());

                $botman->typesAndWaits(2);
                $botman->startConversation(new OnboardConversation());
            }
        });
        $botMan->hears('/sync', function (BotMan $botman) use ($telegramUserService) {
            ray('sync');
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            ray('tel user', $telegramUser);
            $botman->startConversation(new SyncMasternodeMonitorConversation($telegramUser));
        });

//        $botMan->hears('{message}', function ($botman, $message) {
//            if ($message == 'hi') {
//                $this->askName($botman);
//            } else {
//                $botman->reply("write 'hi' for testing...");
//            }
//        });

        $botMan->fallback(function (BotMan $botman) {
            $botman->startConversation(new HelpConversation());
        });
        $botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
            ray($throwable);
            $bot->reply('An error occured. Try again later...');
        });
//        $botMan->middleware->received(new SetLanguage());

        $botMan->listen();
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
