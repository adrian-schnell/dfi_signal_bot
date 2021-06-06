<?php

namespace App\Nova;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class TelegramUserResource extends Resource
{
	public static $model = TelegramUser::class;
	public static $search = [
		'telegramId',
		'firstName',
		'lastName',
		'username',
		'language',
		'status',
		'id'
	];

    public function title(): string
    {
        return sprintf('%s %s (ID: %s)', $this->firstName, $this->lastName, $this->telegramId);
	}

    public static function group(): string
    {
        return 'Benutzer';
	}

    public static function label(): string
    {
        return 'Telegram Nutzer';
    }

	public function fields(Request $request): array
	{
		return [
			ID::make()->sortable(),
			Text::make('Telegram ID', 'telegramId')
				->sortable()
				->exceptOnForms(),
			Text::make('Vorname', 'firstName')
				->sortable()
				->rules('required'),
			Text::make('Nachname', 'lastName')
				->sortable()
				->rules('required'),
			Text::make('Username', 'username')
				->sortable()
				->rules('required'),
			Text::make('Sprache', 'language')
				->sortable()
				->rules('required'),
			Text::make('Status', 'status')
				->sortable()
				->rules('required'),
            HasMany::make('Masternodes', 'masternodes', UserMasternodeResource::class),
		];
	}
}
