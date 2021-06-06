<?php

namespace App\Nova;

use App\Models\DEXPrice;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class DexPriceResource extends Resource
{
	public static $model = DEXPrice::class;
	public static $title = 'name';
	public static $search = [
		'name'
	];

    public static function group(): string
    {
        return 'Admin';
    }

    public static function label(): string
    {
        return 'DEX Preise';
    }

	public function fields(Request $request): array
	{
		return [
			ID::make()->sortable(),

			Number::make('Dex Id')
				->sortable(),

			Text::make('Name')
				->sortable(),

			Number::make('Price To Dfi')
				->sortable(),

			Number::make('Price From Dfi')
				->sortable(),

            Number::make('Reihenfolge', 'order')
                ->sortable(),
		];
	}
}
