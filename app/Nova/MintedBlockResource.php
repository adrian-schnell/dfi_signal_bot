<?php

namespace App\Nova;

use App\Models\MintedBlock;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class MintedBlockResource extends Resource
{
    public static $model = MintedBlock::class;
    public static $title = 'id';
    public static $search = [
        'block_hash',
        'spent_txid',
        'mint_txid',
        'address'
    ];

    public static function group(): string
    {
        return 'Masternode';
    }

    public static function label(): string
    {
        return 'Minted Blocks';
    }

    public function fields(Request $request): array
    {
        return [
            Number::make('MintBlockHeight', 'mintBlockHeight')
                ->sortable()
                ->exceptOnForms(),
            Number::make('SpentBlockHeight', 'spentBlockHeight')
                ->sortable()
                ->exceptOnForms(),
            Text::make('Block Hash')
                ->sortable()
                ->hideFromIndex(),
            Text::make('Spent Txid')
                ->sortable()
                ->hideFromIndex(),
            Text::make('Mint Txid')
                ->sortable()
                ->hideFromIndex(),
            Number::make('Value')
                ->sortable(),
            Text::make('Address')
                ->sortable()
                ->hideFromIndex(),
            DateTime::make('Block Time')
                ->sortable(),
            BelongsTo::make('UserMasternode', 'userMasternode', UserMasternodeResource::class),
        ];
    }
}