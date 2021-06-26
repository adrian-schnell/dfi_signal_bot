<?php

namespace App\Api\v1_0\Requests;

use App\Models\Server;
use Illuminate\Foundation\Http\FormRequest;

class ServerStatsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cpu'       => 'required',
            'hdd_used'  => 'required',
            'hdd_total' => 'required',
            'ram_used'  => 'required',
            'ram_total' => 'required',
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

    public function cpu(): string
    {
        return $this->input('cpu');
    }

    public function hddUsed(): string
    {
        return $this->input('hdd_used');
    }

    public function hddTotal(): string
    {
        return $this->input('hdd_total');
    }

    public function ramUsed(): string
    {
        return $this->input('ram_used');
    }

    public function ramTotal(): string
    {
        return $this->input('ram_total');
    }
}
