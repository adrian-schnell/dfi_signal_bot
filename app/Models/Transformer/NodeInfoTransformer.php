<?php

namespace App\Models\Transformer;

use App\Enum\ServerStatTypes;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use Illuminate\Database\Eloquent\Builder;

class NodeInfoTransformer
{
    protected array $rawData;
    protected string $userLanguage;

    public function __construct(array $rawData, string $userLanguage)
    {
        $this->rawData = $rawData;
        $this->userLanguage = $userLanguage;
    }

    public function nodeUptime(): string
    {
        $data = $this->getValuePairForKey(ServerStatTypes::NODE_UPTIME);

        return $data['value'] ? time_diff_humanreadable(
            now(),
            now()->subSeconds($data['value']),
            $this->userLanguage
        ) : 'n/a';
    }

    public function nodeVersion(): string
    {
        $data = $this->getValuePairForKey(ServerStatTypes::NODE_VERSION);

        return $data['value'] ?? 'n/a';
    }

    public function connectionCount(): int
    {
        $data = $this->getValuePairForKey(ServerStatTypes::CONNECTION_COUNT);

        return $data['value'];
    }

    public function logSize(): float
    {
        $data = $this->getValuePairForKey(ServerStatTypes::LOGSIZE);

        return isset($data['value']) ? round($data['value'], 2) : 0;
    }

    public function blockHeight(): int
    {
        $data = $this->getValuePairForKey(ServerStatTypes::BLOCK_HEIGHT);

        return $data['value'];
    }

    public function blockHash(): string
    {
        $data = $this->getValuePairForKey(ServerStatTypes::LOCAL_HASH);

        return isset($data['value']) ? $data['value'] : 'n/a';
    }

    public function operatorStatus(): array
    {
        $operators = $this->getValuePairForKey(ServerStatTypes::OPERATOR_STATUS)['value'];
        $result = [];

        foreach ($operators as $operator) {
            $result[] = [
                'masternode' => UserMasternode::whereHas('masternode', function (Builder $query) use ($operator) {
                    $query->where('masternode_id', $operator['id']);
                })->with('masternode')->first(),
                'online'     => $operator['online'],
            ];
        }

        return $result;
    }

    protected function getValuePairForKey(string $key): array
    {
        foreach ($this->rawData as $data) {
            if ($data['type'] === $key) {
                return $data;
            }
        }

        return [];
    }
}
