<?php

namespace App\Http\Requests;

use App\Models\TelegramUser;
use Carbon\Carbon;
use Exception;

class MnHealthWebhookRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'data'                     => ['required', 'array'],
            'analysis'                 => ['required', 'array'],
            'analysis.analysis_result' => ['required', 'array', 'min:3'],
            'analysis.warnings'        => ['sometimes', 'array'],
            'analysis.critical'        => ['sometimes', 'array'],
            'latest_update'            => ['required', 'date'],
            'reference'                => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function data(): array
    {
        return $this->input('data');
    }

    public function analysis(): array
    {
        return $this->input('analysis');
    }

    public function latest_update(): Carbon
    {
        return Carbon::parse($this->input('latest_update'));
    }

    public function telegramUser(): ?TelegramUser
    {
        try {
            return TelegramUser::find(decrypt($this->input('reference')));
        } catch (Exception$e) {
            return null;
        }
    }
}
