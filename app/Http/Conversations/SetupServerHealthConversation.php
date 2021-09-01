<?php

namespace App\Http\Conversations;

use App\Exceptions\MasternodeHealthApiException;
use App\Http\Service\MasternodeHealthApiService;
use App\Models\DEXPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class SetupServerHealthConversation extends Conversation
{
    protected TelegramUser $telegramUser;

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->say(__('serverHealthSetupConversation.intro'), [
            'parse_mode' => 'Markdown',
        ]);
        $buttons = Question::create(__('serverHealthSetupConversation.setup_method_selection'))
            ->addButtons([
                Button::create(__('serverHealthSetupConversation.buttons.new_key'))->value('new_key'),
                Button::create(__('serverHealthSetupConversation.buttons.existing_key'))->value('existing_key'),
            ]);
        $user = $this->telegramUser;
        $this->ask($buttons, function (Answer $answer) use ($user) {
            if (!$answer->isInteractiveMessageReply()) {
                return $this->repeat(__('serverHealthSetupConversation.setup_method_selection_repeat'));
            }

            if ($answer->getValue() === 'new_key') {
                try {
                    (new MasternodeHealthApiService($user))->setupApiKey()->setupWebhook();
                    $this->say(__('serverHealthSetupConversation.success'));
                    $this->say(__('serverHealthSetupConversation.api_key', [
                        'api_key' => $user->server_health_api_key,
                    ]), [
                        'parse_mode' => 'Markdown',
                    ]);
                } catch (MasternodeHealthApiException $e) {
                    $this->say(__('serverHealthSetupConversation.api_error'));
                }
            }

            if ($answer->getValue() === 'existing_key') {
                $this->ask(__('serverHealthSetupConversation.buttons.existing_key_question'), function (
                    Answer $answer
                ) use ($user) {
                    if (strlen($answer->getText()) != 36) {
                        return $this->repeat(__('serverHealthSetupConversation.buttons.existing_key_question'));
                    }
                    $user->update([
                        'server_health_api_key' => $answer->getText(),
                    ]);
                    $user->refresh();
                    (new MasternodeHealthApiService($user))->setupWebhook();
                    $this->say(__('serverHealthSetupConversation.success'));
                });
            }
            $this->say(__('serverHealthSetupConversation.setup_instructions'), [
                'parse_mode' => 'Markdown',
            ]);
        });
    }
}
