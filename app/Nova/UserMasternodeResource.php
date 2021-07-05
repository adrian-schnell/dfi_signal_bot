<?php

namespace App\Nova;

use App\Models\UserMasternode;
use App\Nova\Filters\UserMasternodeSignalFilter;
use App\Nova\Filters\UserMasternodeSyncedFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;

class UserMasternodeResource extends Resource
{
    public static $model = UserMasternode::class;
    public static $title = 'name';
    public static $search = [
        'name'
    ];

    public static function group(): string
    {
        return 'Masternode';
    }

    public static function label(): string
    {
        return 'User Masternodes';
    }

    public function fields(Request $request): array
    {
        return [
            Text::make('Name')
                ->sortable(),
            BelongsTo::make('User', 'user', TelegramUserResource::class),
            BelongsTo::make('Masternode', 'masternode', MasternodeResource::class),
            Boolean::make('DFI Signal', 'alarm_on'),
            Boolean::make('MN Monitor Sync', 'synced_masternode_monitor'),
            Boolean::make('Aktiv', 'is_active'),
            HasMany::make('Minted Blocks', 'mintedBlocks', MintedBlockResource::class),
        ];
    }

    public function filters(Request $request): array
    {
        return [
            new UserMasternodeSyncedFilter(),
            new UserMasternodeSignalFilter(),
        ];
    }
}
