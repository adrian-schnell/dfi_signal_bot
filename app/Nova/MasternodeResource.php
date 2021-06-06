<?php

namespace App\Nova;

use App\Models\Masternode;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class MasternodeResource extends Resource
{
	public static $model = Masternode::class;
	public static $title = 'owner_address';
	public static $search = [
		'masternode_id',
		'owner_address',
		'operator_address',
		'state'
	];

    public static function group(): string
    {
        return 'Masternode';
    }

    public static function label(): string
    {
        return 'alle Masternodes';
    }

	public function fields(Request $request): array
	{
		return [
			Text::make('Masternode Id')
				->sortable(),

			Text::make('Owner Address')
				->sortable(),

			Text::make('Operator Address')
				->sortable(),

			Number::make('Creation Height')
				->sortable(),

			Text::make('State')
				->sortable(),

			Number::make('Minted Blocks')
				->sortable(),

			Number::make('Target Multiplier')
				->sortable(),
		];
	}
}
