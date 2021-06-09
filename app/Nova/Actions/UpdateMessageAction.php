<?php

namespace App\Nova\Actions;

use App\Enum\QueueNames;
use App\Jobs\SendTelegramMessageJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;

class UpdateMessageAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Nachricht an Nutzer';

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($fields->language === 'all') {
            $telegramUsers = $models;
        } else {
            $telegramUsers = $models->where('language', $fields->language);
        }

        foreach ($telegramUsers as $telegramUser) {
            dispatch(new SendTelegramMessageJob($telegramUser, $fields->text, $fields->type))
                ->onQueue(QueueNames::TELEGRAM_MESSAGE_OUTGOING);
        }

        return Action::message(sprintf('%s Nachrichten werden nun verschickt', $telegramUsers->count()));
    }

    public function fields(): array
    {
        return [
            Select::make('Typ der Nachricht', 'type')
                ->options(SendTelegramMessageJob::MESSAGE_TYPES)->rules(['required']),
            Select::make('Sprache', 'language')
                ->options([
                    'en'  => 'Englisch',
                    'de'  => 'Deutsch',
                    'all' => 'Alle!'
                ])->rules(['required']),
            Textarea::make('Text', 'text')
                ->rules(['required']),
        ];
    }
}
