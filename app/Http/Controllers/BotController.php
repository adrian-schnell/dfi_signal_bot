<?php

namespace App\Http\Controllers;

use App\Http\Conversations\HelpConversation;
use App\Http\Conversations\LinkMasternodeConversation;
use App\Http\Conversations\ListMasternodesConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Conversations\ResetMasternodesConversation;
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
                $botman->startConversation(new OnboardConversation());
            }
        })->skipsConversation();
        $botMan->hears('/sync', function (BotMan $botman) {
            $botman->startConversation(new SyncMasternodeMonitorConversation());
        })->skipsConversation();
        $botMan->hears('/link {ownerAddress}', function (BotMan $botman, string $ownerAddress) {
            $botman->startConversation(new LinkMasternodeConversation($ownerAddress, $botman->getUser()));
        })->skipsConversation();


        $botMan->hears('/list', function (BotMan $botman) use ($telegramUserService) {
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            $masternodes = $telegramUser->masternodes;
            $botman->startConversation(new ListMasternodesConversation($masternodes));
        })->skipsConversation();

        $botMan->hears('/reset', function (BotMan $botman) use ($telegramUserService) {
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            $botman->startConversation(new ResetMasternodesConversation($telegramUser));
        })->skipsConversation();


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
