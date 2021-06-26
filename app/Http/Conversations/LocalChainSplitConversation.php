<?php

namespace App\Http\Conversations;

use App\Api\v1_0\Requests\BlockInfoRequest;
use App\Enum\Cooldown;
use BotMan\BotMan\Messages\Conversations\Conversation;

class LocalChainSplitConversation extends Conversation
{
    private BlockInfoRequest $data;

    public function __construct(BlockInfoRequest $request)
    {
        $this->data = $request;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->say('🆘🆘🆘️ *Possible local split found* 🆘🆘🆘', ['parse_mode' => 'Markdown']);
        $message = __('chainSplitConversation.intro_local_split');
        $message .= trans_choice('chainSplitConversation.blockdiff', $this->data->blockDiff(), ['diff' =>
                                                                                                        $this->data->blockDiff()]);
        $message .= __('chainSplitConversation.local_block_height', ['height' => $this->data->blockHeightLocal()]);
        $message .= __('chainSplitConversation.mainnet_block_height', ['height' => $this->data->mainNetBlockHeight()]);
        $message .= __('chainSplitConversation.local_block_hash', ['hash' => $this->data->localHash()]);
        $message .= __('chainSplitConversation.mainnet_block_hash', ['hash' => $this->data->mainNetBlockHash()]);

        $this->say($message, ['parse_mode' => 'Markdown']);
        $this->setCooldown();
    }

    protected function setCooldown(): void
    {
        $cooldownInHours = Cooldown::COOLDOWN_HOURS[Cooldown::LOCAL_SPLIT_NOTIFICATION];
        $this->say(__('chainSplitConversation.cooldown_message', ['hours' => $cooldownInHours]),
            ['parse_mode' => 'Markdown']);

//        $this->data->userServer()->user->cooldown(Cooldown::LOCAL_SPLIT_NOTIFICATION)
//            ->until(now()->addHours($cooldownInHours));
    }
}
