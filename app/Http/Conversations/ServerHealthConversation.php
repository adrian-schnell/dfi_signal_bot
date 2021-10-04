<?php

namespace App\Http\Conversations;

use App\Exceptions\MasternodeHealthApiException;
use App\Http\Service\MasternodeHealthApiService;
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

class ServerHealthConversation extends Conversation
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
        $masternodeHealthService = new MasternodeHealthApiService($this->telegramUser);

        try {
            $serverStats = $masternodeHealthService->getServerStats();
            $nodeInfo = $masternodeHealthService->getNodeInfo();
        } catch (MasternodeHealthApiException $e) {
            $this->say(__('errors.api_not_available'));

            return;
        }

        if (empty($serverStats['data']) && empty($nodeInfo['data'])) {
            $this->say(__('serverHealthConversation.no_data'), [
                'parse_mode' => 'Markdown',
            ]);

            return;
        }

        if (!empty($nodeInfo['data'])) {
            $this->sayNodeInfo(new NodeInfoTransformer($nodeInfo, $this->telegramUser->language));
        }
        if (!empty($serverStats['data'])) {
            $this->sayServerStats(new ServerStatTransformer($serverStats));
        }
    }

    protected function sayNodeInfo(NodeInfoTransformer $nodeInfo): void
    {
        $operatorStatus = $nodeInfo->operatorStatus();
        $message = __('serverHealthConversation.nodeInfo.block_height', ['value' => $nodeInfo->blockHeight()]);
        $message .= __('serverHealthConversation.nodeInfo.block_hash', [
            'value' => str_truncate_middle($nodeInfo->blockHash(), 25),
            'link'  => sprintf('https://mainnet.defichain.io/#/DFI/mainnet/block/%s', $nodeInfo->blockHash()),
        ]);
        $message .= __('serverHealthConversation.nodeInfo.node_uptime', ['value' => $nodeInfo->nodeUptime()]);
        $message .= __('serverHealthConversation.nodeInfo.log_size', ['value' => $nodeInfo->logSize()]);
        $message .= __('serverHealthConversation.nodeInfo.connection_count', ['value' => $nodeInfo->connectionCount()]);
        $message .= __('serverHealthConversation.nodeInfo.node_version', ['value' => $nodeInfo->nodeVersion()]);
        $message .= __('serverHealthConversation.nodeInfo.operator_status');
        foreach ($operatorStatus as $operator) {
            $message .= sprintf("\r\n%s *%s* (%s)",
                $operator['online'] ? '✅' : '❌',
                $operator['masternode']->name,
                str_truncate_middle($operator['masternode']->masternode->masternode_id, 10),
            );
        }
        $message .= __('serverHealthConversation.latest_update', [
            'time' => $nodeInfo->lastUpdateHumanReadable($this->telegramUser->language),
        ]);

        $this->say($message, [
            'parse_mode' => 'Markdown',
        ]);
    }

    protected function sayServerStats(ServerStatTransformer $serverStats): void
    {
        $hddUsed = $serverStats->hddUsed();
        $hddTotal = $serverStats->hddTotal();
        $ramUsed = $serverStats->ramUsed();
        $ramTotal = $serverStats->ramTotal();
        $loadAvg = $serverStats->loadAvg();
        $maxLoad = $serverStats->numCores() * 1.5;

        $message = __('serverHealthConversation.server_stats.disk_usage', [
            'progress' => progress_bar($hddUsed / $hddTotal * 100),
            'used'     => $hddUsed,
            'total'    => $hddTotal,
        ]);
        $message .= __('serverHealthConversation.server_stats.ram_usage', [
            'progress' => progress_bar($ramUsed / $ramTotal * 100),
            'used'     => $ramUsed,
            'total'    => $ramTotal,
        ]);
        $message .= __('serverHealthConversation.server_stats.system_load', [
            'progress' => progress_bar($loadAvg / $maxLoad * 100),
        ]);
        $message .= __('serverHealthConversation.latest_update', [
            'time' => $serverStats->lastUpdateHumanReadable($this->telegramUser->language),
        ]);

        $this->say($message, [
            'parse_mode' => 'Markdown',
        ]);
    }
}
