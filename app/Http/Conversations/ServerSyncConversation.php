<?php

namespace App\Http\Conversations;

use App\Http\Service\ServerSyncService;
use App\Models\Server;
use App\Models\Service\TelegramUserService;
use App\Models\TelegramUser;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Str;

class ServerSyncConversation extends Conversation
{
    protected ?TelegramUser $user = null;
    protected ?ServerSyncService $serverSyncService;
    const API_KEY = 'api_key';
    const LIST_SERVER = 'list_server';
    const ADD_SERVER = 'add_server';
    const REMOVE_SERVER = 'remove_server';
    const CANCEL = 'cancel';
    const GET_KEY = 'get_api_key';
    const REGENERATE_KEY = 'regenerate_api_key';

    public function __construct(UserInterface $user)
    {
        $this->user              = app(TelegramUserService::class)->getTelegramUser($user);
        $this->serverSyncService = app(ServerSyncService::class);
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->bot->typesAndWaits(1);
        $question = Question::create(__('serverSyncConversation.intro', [
            'repo' => config('github.server_repo'),
        ]))->addButtons([
            Button::create(__('serverSyncConversation.buttons.api_key'))->value(self::API_KEY),
            Button::create(__('serverSyncConversation.buttons.list_server'))->value(self::LIST_SERVER),
            Button::create(__('serverSyncConversation.buttons.add_server'))->value(self::ADD_SERVER),
            Button::create(__('serverSyncConversation.buttons.remove_server'))->value(self::REMOVE_SERVER),
            Button::create(__('serverSyncConversation.buttons.cancel'))->value(self::CANCEL),
        ]);

        $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return $this->repeat(__('serverSyncConversation.repeat_question'));
            }

            if ($answer->getValue() === self::API_KEY) {
                $this->apiKey();
            }

            if ($answer->getValue() === self::LIST_SERVER) {
                $this->listServers();
            }

            if ($answer->getValue() === self::ADD_SERVER) {
                $this->addServer();
            }

            if ($answer->getValue() === self::REMOVE_SERVER) {
                $this->say('remove');
            }

            if ($answer->getValue() === self::CANCEL) {
                $this->say('stop');
            }
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    protected function apiKey()
    {
        $question = Question::create(__('serverSyncConversation.api_key.question'))
            ->addButtons([
                Button::create(__('serverSyncConversation.api_key.buttons.get_api_key'))->value(self::GET_KEY),
                Button::create(__('serverSyncConversation.api_key.buttons.regenerate_api_key'))->value(self::REGENERATE_KEY),
            ]);

        $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                return $this->repeat(__('serverSyncConversation.repeat_question'));
            }

            if ($answer->getValue() === self::GET_KEY) {
                $this->say(__('serverSyncConversation.api_key.get_api_key', [
                    'api_key' => $this->serverSyncService->generateApiKey($this->user),
                ]), [
                    'parse_mode' => 'Markdown',
                ]);
            }

            if ($answer->getValue() === self::REGENERATE_KEY) {
                $this->say(__('serverSyncConversation.api_key.regenerate_api_key', [
                    'api_key' => $this->serverSyncService->regenerateApiKey($this->user),
                ]), [
                    'parse_mode' => 'Markdown',
                ]);
            }
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    protected function listServers()
    {
        $servers = $this->user->server;

        $servers->sortBy('name')->each(function (Server $server) {
            $this->say(__('serverSyncConversation.add_server.server_id', [
                'name'      => $server->name,
                'server_id' => $server->id,
            ]), [
                'parse_mode' => 'Markdown',
            ]);
        });
    }

    protected function addServer()
    {
        $this->ask(__('serverSyncConversation.add_server.ask_name'), function (Answer $answer) {
            if (Str::length($answer->getText()) < 1) {
                return $this->repeat(__('serverSyncConversation.add_server.ask_name'));
            }
            $this->say(__('serverSyncConversation.add_server.server_id', [
                'name'      => $answer->getText(),
                'server_id' => $this->serverSyncService->addServerForUser($this->user, $answer->getText()),
            ]), [
                'parse_mode' => 'Markdown',
            ]);
        });
    }
}
