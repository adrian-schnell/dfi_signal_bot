<?php

namespace App\Api\v1_0\Requests;

use App\Models\Server;
use Illuminate\Foundation\Http\FormRequest;

class BlockInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'connectioncount'       => ['required', 'integer', 'min:0'],
            'block_diff'            => ['required', 'integer'],
            'block_height_local'    => ['required', 'integer'],
            'main_net_block_height' => ['required', 'integer'],
            'local_hash'            => ['required', 'string', 'min:60'],
            'main_net_block_hash'   => ['required', 'string', 'min:60'],
            'split_found'           => ['required', 'boolean'],
            'logsize'               => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function userServer(): Server
    {
        return $this->signal_server;
    }

    public function connectioncount(): string
    {
        return $this->input('connectioncount');
    }

    public function blockDiff(): string
    {
        return $this->input('block_diff');
    }

    public function blockHeightLocal(): string
    {
        return $this->input('block_height_local');
    }

    public function mainNetBlockHeight(): string
    {
        return $this->input('main_net_block_height');
    }

    public function localHash(): string
    {
        return $this->input('local_hash');
    }

    public function mainNetBlockHash(): string
    {
        return $this->input('main_net_block_hash');
    }

    public function splitFound(): string
    {
        return $this->input('split_found');
    }

    public function logsize(): string
    {
        return $this->input('logsize');
    }
}
