<?php

namespace App\Http\Conversations;

use App\Exceptions\MasternodeHealthApiException;
use App\Http\Service\MasternodeHealthApiService;
use App\Http\Service\MasternodeMonitorService;
use App\Models\DEXPrice;
use App\Models\Repository\MintedBlockRepository;
use App\Models\Service\MasternodeService;
use App\Models\TelegramUser;
use App\Models\Transformer\NodeInfoTransformer;
use App\Models\Transformer\ServerStatTransformer;
use App\Models\UserMasternode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class ServerHealthResetConversation extends Conversation
{
    protected TelegramUser $telegramUser;
    const VALUE_YES = 'yes';
    const VALUE_NO = 'no';

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (is_null($this->telegramUser->server_health_api_key)) {
            $this->say(__('serverHealthResetConversation.not_setup'));
            return;
        }
        $question = Question::create(__('resetConversation.question'))->addButtons([
            Button::create(__('resetConversation.buttons.yes'))->value(self::VALUE_YES),
            Button::create(__('resetConversation.buttons.no'))->value(self::VALUE_NO),
        ]);
        $user = $this->telegramUser;
        $this->ask($question, function (Answer $answer) use ($user) {
            if (!$answer->isInteractiveMessageReply()) {
                $this->repeat();

                return;
            }
            if ($answer->getValue() === self::VALUE_YES) {
                try {
                    (new MasternodeHealthApiService($this->telegramUser))->deleteWebhook();
                    $user->update([
                        'server_health_api_key' => null,
                    ]);
                    $this->say(__('resetConversation.success'));
                } catch (MasternodeHealthApiException $e) {
                    $this->say(__('errors.api_not_available'));

                    return;
                }
            }
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }
}
