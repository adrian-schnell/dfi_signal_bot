<?php

namespace App\Http\Controllers;

use App\Http\Conversations\HelpConversation;
use App\Http\Conversations\LinkMasternodeConversation;
use App\Http\Conversations\MasternodeStatsConversation;
use App\Http\Conversations\SyncDisableConversation;
use App\Http\Conversations\SyncKeyChangedConversation;
use App\Http\Conversations\UnlinkMasternodeConversation;
use App\Http\Conversations\ListMasternodesConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Conversations\ResetMasternodesConversation;
use App\Http\Conversations\SyncMasternodeMonitorConversation;
use App\Http\Middleware\TelegramBot\SetLanguageReceived;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Models\Service\TelegramUserService;
use Throwable;

class BotController extends Controller
{
    protected BotMan $botMan;

    public function handle(TelegramUserService $telegramUserService): void
    {
        $botMan = app('botman');

        $botMan->hears('/start', function (Botman $botman) use ($telegramUserService) {
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
        $botMan->hears('/sync_key_changed', function (BotMan $botman) {
            $botman->startConversation(new SyncKeyChangedConversation());
        });
        $botMan->hears('/sync_disable', function (BotMan $botman) {
            $botman->startConversation(new SyncDisableConversation());
        });


        $botMan->hears('/link_mn(.*|^)', function (BotMan $botman, string $ownerAddress) {
            $botman->startConversation(new LinkMasternodeConversation($botman->getUser(), trim($ownerAddress)));
        })->skipsConversation();
        $botMan->hears('/unlink_mn(.*|^)', function (BotMan $botman, string $ownerAddress) {
            $botman->startConversation(new UnlinkMasternodeConversation($botman->getUser(), trim($ownerAddress)));
        })->skipsConversation();


        $botMan->hears('/list', function (BotMan $botman) use ($telegramUserService) {
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            $masternodes = $telegramUser->masternodes;
            $botman->startConversation(new ListMasternodesConversation($masternodes));
        })->skipsConversation();

        $botMan->hears('/stats', function (BotMan $botman) use ($telegramUserService) {
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            $masternodes = $telegramUser->masternodes;
            $botman->startConversation(new MasternodeStatsConversation($masternodes));
        })->skipsConversation();

        $botMan->hears('/reset', function (BotMan $botman) use ($telegramUserService) {
            $telegramUser = $telegramUserService->getTelegramUser($botman->getUser());
            $botman->startConversation(new ResetMasternodesConversation($telegramUser));
        })->skipsConversation();

        $botMan->hears('/stop', function (BotMan $botman) {
            $botman->reply('⏹ stopped');
        })->stopsConversation();

        $botMan->fallback(function (BotMan $botman) {
            $botman->startConversation(new HelpConversation());
        });
        $botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
            $bot->reply('An error occured. Try again later...');
        });
        $botMan->middleware->received(new SetLanguageReceived());

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
